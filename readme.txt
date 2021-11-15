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
Requires PHP: 7.0
Requires At Least: 5.0
Tested Up To: 5.8.2
Stable Tag: 3.8.0

Schema BreadcrumbList markup in JSON-LD format for Google Rich Results.

== Description ==

<!-- about -->

**The most comprehensive Schema BreadcrumbsList markup of any plugin!**

Uses [Google's preferred Schema JSON-LD format for breadcrumbs markup](https://developers.google.com/search/docs/data-types/breadcrumb).

Adds Schema BreadcrumbList markup for posts, pages, custom post types, categories, tags, custom taxonomies, search results, and date archive pages.

Select between using ancestors (aka parents), the primary category, or all categories for posts, pages, and custom post types.

<h3>No Templates to Modify</h3>

Simply activate or deactivate the plugin to enable / disable the addition of Schema BreadcrumbList markup.

<!-- /about -->

<h3>WPSSO Core Required</h3>

WPSSO Schema Breadcrumbs Markup (WPSSO BC) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/).

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the Schema Breadcrumbs Markup add-on](https://wpsso.com/docs/plugins/wpsso-breadcrumbs/installation/install-the-plugin/).
* [Uninstall the Schema Breadcrumbs Markup add-on](https://wpsso.com/docs/plugins/wpsso-breadcrumbs/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

<h3 class="top">Frequently Asked Questions</h3>

* [How can I add WPSSO breadcrumbs to my theme?](https://wpsso.com/docs/plugins/wpsso-breadcrumbs/faqs/how-can-i-add-wpsso-breadcrumbs-to-my-theme/)

== Screenshots ==

01. WPSSO BC settings page with options to select the type of breadcrumbs for each post type (media, post, page, and custom post types) and taxonomy.

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Standard Edition Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-breadcrumbs/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-breadcrumbs/)

<h3>Development Version Updates</h3>

<p><strong>WPSSO Core Premium customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. Save the plugin settings and click the "Check for Plugin Updates" button to fetch the latest version information. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can always reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<h3>Changelog / Release Notes</h3>

**Version 3.8.1-b.1 (2021/11/15)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Removed duplicate 'wpsso_option_type' and 'wpsso_get_defaults' option filter hooks.
	* Refactored the `SucomAddOn->get_missing_requirements()` method.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.
	* WPSSO Core v9.8.0-b.1.

**Version 3.8.0 (2021/11/10)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated the default WPSSOBC_SCHEMA_BREADCRUMB_SCRIPTS_MAX value from 20 to 10.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.
	* WPSSO Core v9.7.0.

**Version 3.7.0 (2021/10/18)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added more human friendly function names to include breadcrumbs in theme templates:
		* wpsso_breadcrumbs_html()
		* wpsso_get_breadcrumbs_html()
	* Removed cache clearing on activation / deactivation.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.
	* WPSSO Core v9.2.0.

**Version 3.6.1 (2021/10/06)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Standardized `get_table_rows()` calls and filters in 'submenu' and 'sitesubmenu' classes.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.
	* WPSSO Core v9.1.0.

**Version 3.6.0 (2021/09/24)**

* **New Features**
	* None.
* **Improvements**
	* Renamed the Document SSO &gt; Customize &gt; Breadcrumb Title option to Breadcrumb Name and moved it under the Schema Altername Name option. 
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new `WpssoBcFiltersOptions` class.
	* Added a new `WpssoBcFiltersUpgrade` class.
	* Renamed the 'bc_title' metadata options key to 'schema_bc_title'.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.
	* WPSSO Core v9.0.0.

**Version 3.5.0 (2021/03/26)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added new 'wpsso_bc_add_home_url' and 'wpsso_bc_add_wp_url' filters.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.
	* WPSSO Core v8.34.0.

**Version 3.4.1 (2021/02/25)**

* **New Features**
	* None.
* **Improvements**
	* Updated the banners and icons of WPSSO Core and its add-ons.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.23.0.

**Version 3.4.0 (2020/12/17)**

* **New Features**
	* Added a new "Breadcrumb Title" option in the Document SSO metabox.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added support for `WpssoPost->get_primary_terms()` from WPSSO Core v8.18.0.
	* Added a new `wpsso_bc_show_itemlist_html()` function.
	* Added a new `wpsso_bc_get_itemlist_html()` function.
	* Added a new `WpssoBcBreadcrumb::get_mod_itemlist_html()` method.
	* Added a new `WpssoBcBreadcrumb::get_mod_itemlist_links()` method.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.18.0.

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
	* Added a lib/compat.php library file for third-party plugin and theme compatibility actions and filters.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v8.4.1.

== Upgrade Notice ==

= 3.8.1-b.1 =

(2021/11/15) Removed duplicate 'wpsso_option_type' and 'wpsso_get_defaults' option filter hooks.

= 3.8.0 =

(2021/11/10) Updated the default WPSSOBC_SCHEMA_BREADCRUMB_SCRIPTS_MAX value from 20 to 10.

= 3.7.0 =

(2021/10/18) Added more human friendly function names to include breadcrumbs in theme templates. Removed cache clearing on activation / deactivation.

= 3.6.1 =

(2021/10/06) Standardized `get_table_rows()` calls and filters in 'submenu' and 'sitesubmenu' classes.

= 3.6.0 =

(2021/09/24) Renamed the Document SSO &gt; Customize &gt; Breadcrumb Title option to Breadcrumb Name and moved it under the Schema Altername Name option. 

= 3.5.0 =

(2021/02/26) Added new 'wpsso_bc_add_home_url' and 'wpsso_bc_add_wp_url' filters.

= 3.4.1 =

(2021/02/25) Updated the banners and icons of WPSSO Core and its add-ons.

= 3.4.0 =

(2020/12/17) Added a new "Breadcrumb Title" option in the Document SSO metabox.

= 3.3.0 =

(2020/12/11) Added support for the new `$mod` array elements in WPSSO Core v8.17.0.

= 3.2.0 =

(2020/12/01) Included the `$addon` argument for library class constructors.

= 3.1.1 =

(2020/10/17) Refactored the add-on class to extend a new WpssoAddOn abstract class.

