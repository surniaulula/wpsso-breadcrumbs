=== WPSSO Schema Breadcrumbs Markup ===
Plugin Name: WPSSO Schema Breadcrumbs Markup
Plugin Slug: wpsso-breadcrumbs
Text Domain: wpsso-breadcrumbs
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-breadcrumbs/assets/
Tags: schema, breadcrumbs, hierarchy, seo, google, categories, tags, search
Contributors: jsmoriss
Requires PHP: 5.6
Requires At Least: 4.5
Tested Up To: 5.6
Stable Tag: 3.2.0

Schema BreadcrumbList markup with JSON-LD structured data for better Google Rich Results.

== Description ==

<p style="margin:0;"><img class="readme-icon" src="https://surniaulula.github.io/wpsso-breadcrumbs/assets/icon-256x256.png"></p>

**Most comprehensive Schema BreadcrumbsList markup of any plugin!**

Add Schema BreadcrumbList markup in JSON-LD format to posts, pages, custom post types, categories, tags, custom taxonomies, search results, and date archive pages.

Select between using ancestors (aka parents) or categories for the breadcrumbs markup of posts, pages, and custom post types.

Uses [Google's preferred Schema JSON-LD format for your breadcrumbs markup](https://developers.google.com/search/docs/data-types/breadcrumb).

<h3>No templates to modify or update!</h3>

Simply activate / deactivate the plugin to enable / disable the addition of Schema BreadcrumbList markup.

<h3>WPSSO Core Plugin Required</h3>

WPSSO Schema Breadcrumbs Markup (aka WPSSO BC) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/).

WPSSO Core and its add-ons make sure your content looks best on social sites and in search results, no matter how your webpages are shared, re-shared, messaged, posted, embedded, or crawled.

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the Schema Breadcrumbs Markup add-on](https://wpsso.com/docs/plugins/wpsso-breadcrumbs/installation/install-the-plugin/).
* [Uninstall the Schema Breadcrumbs Markup add-on](https://wpsso.com/docs/plugins/wpsso-breadcrumbs/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

== Screenshots ==

01. WPSSO BC settings page includes options to select the kind of breadcrumbs for each post type (media, post, page, and custom post types).

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Standard Version Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-breadcrumbs/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-breadcrumbs/)

<h3>Changelog / Release Notes</h3>

**Version 3.3.0 (2020/12/11)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added support for the new `$mod` array elements in WPSSO Core v8.17.0.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.5.
	* WPSSO Core v8.17.0.

**Version 3.2.0 (2020/12/01)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Included the `$addon` argument for library class constructors.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.5.
	* WPSSO Core v8.16.0.

**Version 3.1.1 (2020/10/17)**

* **New Features**
	* None.
* **Improvements**
	* Refactored the add-on class to extend a new WpssoAddOn abstract class.
* **Bugfixes**
	* Fixed backwards compatibility with older 'init_objects' and 'init_plugin' action arguments.
* **Developer Notes**
	* Added a new WpssoAddOn class in lib/abstracts/add-on.php.
	* Added a new SucomAddOn class in lib/abstracts/com/add-on.php.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.4.
	* WPSSO Core v8.13.0.

**Version 3.0.1 (2020/09/15)**

* **New Features**
	* None.
* **Improvements**
	* Updated the French plugin translations.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Moved extracted translation strings from lib/gettext-*.php files to a new gettext/ folder.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.4.
	* WPSSO Core v8.5.0.

**Version 3.0.0 (2020/09/05)**

* **New Features**
	* None.
* **Improvements**
	* Added Schema cleanup for Rank Math to remove its Schema BreadcrumbList markup.
	* Renamed the "Home Page Name" option to "Site Home Page Name" (default value is "Home").
	* Added a new "WordPress Home Page Name" option (default value is "Blog").
	* Added a new "Breadcrumbs by Taxonomy" option.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new lib/filters-messages.php library file.
	* Added support for terms, users, search results, and date archive pages in `WpssoBcFilters->filter_json_data_https_schema_org_breadcrumblist()`.
	* Updated the `WpssoBcBreadcrumb::add_itemlist_data()` method to add the WordPress Address URL (ie. the "WordPress Home Page") to the breadcrumbs list when different to the Site Address URL (ie. the "Site Home Page").
	* Added a lib/compat.php library file for 3rd party plugin and theme compatibility actions and filters.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v8.4.1.

== Upgrade Notice ==

= 3.3.0 =

(2020/12/11) Added support for the new `$mod` array elements in WPSSO Core v8.17.0.

= 3.2.0 =

(2020/12/01) Included the `$addon` argument for library class constructors.

= 3.1.1 =

(2020/10/17) Refactored the add-on class to extend a new WpssoAddOn abstract class.

