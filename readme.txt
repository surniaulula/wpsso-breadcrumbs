=== Schema Breadcrumbs Markup | WPSSO Add-on ===
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
Requires At Least: 4.2
Tested Up To: 5.5
Stable Tag: 2.9.0

Schema BreadcrumbList Markup in JSON-LD for Better Google Rich Results with Structured Data.

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

**Version 3.0.0-b.3 (2020/09/03)**

* **New Features**
	* None.
* **Improvements**
	* Renamed the "Home Page Name" option to "Site Home Page Name" (default value is "Home").
	* Added a new "WordPress Home Page Name" option (default value is "Blog").
	* Added a new "Breadcrumbs by Taxonomy" option.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new lib/filters-messages.php library file.
	* Added support for terms, users, search results, and date archive pages in `WpssoBcFilters->filter_json_data_https_schema_org_breadcrumblist()`.
	* Updated the `WpssoBcBreadcrumb::add_itemlist_data()` method to add the WordPress Address URL (ie. the "WordPress Home Page") to the breadcrumbs list when different to the Site Address URL (ie. the "Site Home Page").
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v8.3.0-b.3

**Version 2.9.0 (2020/08/11)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added support for the new WpssoUtilMetabox class in WPSSO Core v8.0.0.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v8.0.0

== Upgrade Notice ==

= 3.0.0-b.3 =

(2020/09/03) Added new "WordPress Home Page Name" and "Breadcrumbs by Taxonomy" options.

= 2.9.0 =

(2020/08/11) Added support for the new WpssoUtilMetabox class in WPSSO Core v8.0.0.

