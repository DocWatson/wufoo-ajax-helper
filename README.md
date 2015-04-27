Wufoo AJAX Helper
==================

A plugin that makes it easier to negotiate AJAX submissions to your Wufoo forms.

## Installation
1. Clone or download the repository
2. Move the folder wufoo-ajax-helper into your `/wp-content/plugins` directory (or equivalent)
3. Activate the plugin

## Configuration

Like most services with an API, you need to specify some kind of API key. Wufoo is no exception.
You can read a little more about Wufoo's API on their [official docs](http://help.wufoo.com/articles/en_US/SurveyMonkeyArticleType/Wufoo-REST-API-V3).

This plugin will allow you to save your API key, Wufoo ID, and form hashes through an admin page available in the admin sidebar. Read the Wufoo documentation link above for information on how to find these things.

#### Form setup

If you're using this plugin, it's assumed that you want to build and submit your own forms to Wufoo via AJAX. If you're using Wufoo's code, you probably won't need this plugin.

The plugin injects some javascript that relies on WordPress's built in jQuery to submit the forms over AJAX. Each form you want to submit to Wufoo needs to have:

1. A class of `'wufoo-form'` (targeted for the submit event)
2. A data-form-type attribute that corresponds with a hash/label combination entered on the plugin's admin page. For example, if you called your form "Subscribe" when you provided a hash key, your data attribute would be `data-form-type="subscribe"`.

## Caveats
Keeping your API keys private is usually a good idea to prevent abuse. Using this plugin means you'll be saving your API information to a database. Be mindful of this, and try to use a secure connection whenever possible.

For those of you that are keen on keeping your WordPress installs light on plugins, most of the functionality has been encasuplated within a single class. You could very easily include the class within, say, your  `functions.php` file and get most, if not all, of the plugin's features.

This is an early version of the plugin that has been tested on a single machine in a narrow set of use-cases. I wouldn't recommend using it in a production environment just yet, but if you choose to, you are doing so at your own risk.

There are bound to be bugs. Feel free to leave me a note on the issue tracker and describe your issue and I'll see what I can do to help.

## Special Thanks
This plugin was inspired by this [excellent article](http://phuse.ca/integrating-wufoo-with-wordpress-and-ajax/) by Phuse. Many of the concepts and lines of code are lifted directly from their workflow. Thanks a lot, guys!
