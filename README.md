# Directus Router Plugin

**This extension reads routing data from a directus backend and reroutes deprecated urls**

The **Directus Router** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav). redirects expired urls to new routes configured in directus

## Installation

Installing the Directus Router plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](http://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install directus-router

This will install the Directus Router plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/directus-router`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `directus-router`. You can find these files on [GitHub](https://github.com//grav-plugin-directus-router) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/directus-router

> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com//grav-plugin-directus-router/blob/master/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/directus-router/directus-router.yaml` to `user/config/plugins/directus-router.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
track_unknown: false
mapping:
  table: routing_table
  request_field: field_for_deprecated_url
  target_field: field_for_target_site
  status_field: field_for_status_code
additionalFilters:
  some_field.id:
    operator: _eq
    value: 1
```

| Parameter | Description |
| --- | --- |
| track_unknown | add unknown routes that not match existing pages or routes as drafts (data graveyard ahead!) |
| table | the table name of the routing data |
| request_field | the route of the deprecated url |
| target_field | the new url for the redirect |
| status_field | the status code field. (example value is 301 for permanently moved) |
| additionalFilters | here you can specify some more filter options. The syntax is the same as in the directus plugin |

Note that if you use the Admin Plugin, a file with your configuration named directus-router.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

**If this plugin is correctly configured, it will work out of the box.**


