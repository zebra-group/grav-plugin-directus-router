<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use Grav\Plugin\Directus\Utility\DirectusUtility;
use mysql_xdevapi\Exception;

/**
 * Class DirectusRouterPlugin
 * @package Grav\Plugin
 */
class DirectusRouterPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                ['autoload', 100000], // TODO: Remove when plugin requires Grav >=1.7
                ['onPluginsInitialized', 0]
            ],
            'onPageNotFound' => [
                ['onPageNotFound', 0]
            ],
        ];
    }

    /**
    * Composer autoload.
    *is
    * @return ClassLoader
    */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            //
        ]);
    }

    public function onPageNotFound() {
        $directusUtility = new DirectusUtility(
            $this->config["plugins.directus"]['directus']['directusAPIUrl'],
            $this->grav,
            $this->config["plugins.directus"]['directus']['email'],
            $this->config["plugins.directus"]['directus']['password'],
            $this->config["plugins.directus"]['directus']['token'],
            isset($this->config["plugins.directus"]['disableCors']) && $this->config["plugins.directus"]['disableCors']
        );
        $requestedUri = $this->grav['uri']->path();

        $filter = [
            $this->config()['mapping']['request_field'] => [
                'operator' => '_eq',
                'value' => $requestedUri
            ]
        ];
        foreach ($this->config()['additionalFilters'] as $field => $filterItem) {
          $filter[$field] = $filterItem;
        }

        $requestURL = $directusUtility->generateRequestUrl($this->config()['mapping']['table'], 0, 2, $filter);
        $redirectData = $directusUtility->get($requestURL)->toArray();
        $redirectUrl = '';
        $redirectStatusCode = false;

        if(isset($redirectData['data']['0'])) {
            $redirectUrl = $redirectData['data']['0'][$this->config()['mapping']['target_field']];
            $redirectStatusCode = $redirectData['data']['0'][$this->config()['mapping']['status_field']];
        } elseif ($this->config()['track_unknown']) {
            $postObj = [
                'status' => 'draft',
                $this->config()['mapping']['page_instance_field'] => $this->config()['additionalFilters'][$this->config()['mapping']['page_instance_field'] . '.id']['value'],
                $this->config()['mapping']['request_field'] => $requestedUri
            ];
            try {
                $response = $directusUtility->insert($this->config()['mapping']['table'], $postObj)->toArray();
            } catch (\Error $e) {
                $response = 'something went wrong';
            }
        }


        if ($redirectUrl && $redirectStatusCode) {
            $this->redirect($redirectUrl, $redirectStatusCode);
        }
    }

    private function redirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
        die();
    }
}
