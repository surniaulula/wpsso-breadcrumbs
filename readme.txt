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
Requires Plugins: wpsso
Requires PHP: 7.2.34
Requires At Least: 5.5
Tested Up To: 6.4.0
Stable Tag: 4.3.2

Schema BreadcrumbList markup in JSON-LD format for Google Rich Results.

== Description ==

<!-- about -->

**The Most Comprehensive Schema BreadcrumbsList Markup of Any Plugin.**

Adds Schema BreadcrumbList markup for posts, pages, custom post types, categories, tags, custom taxonomies, search results, and date archive pages.

Select between using ancestors (aka parents), the primary category, or all categories for posts, pages, and custom post types.

**No Templates to Modify:**

Simply activate or deactivate the plugin to enable / disable the addition of Schema BreadcrumbList markup.

<!-- /about -->

<h3>WPSSO Core Required</h3>

WPSSO Schema Breadcrumbs Markup (WPSSO BC) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/), which provides complete structured data for WordPress to present your content at its best on social sites and in search results – no matter how URLs are shared, reshared, messaged, posted, embedded, or crawled.

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

* {major} = Major structural code changes and/or incompatible API changes (ie. breaking changes).
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Standard Edition Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-breadcrumbs/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-breadcrumbs/)

<h3>Development Version Updates</h3>

<p><strong>WPSSO Core Premium edition customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<p><strong>WPSSO Core Standard edition users (ie. the plugin hosted on WordPress.org) have access to <a href="https://wordpress.org/plugins/wpsso-breadcrumbs/advanced/">the latest development version under the Advanced Options section</a>.</strong></p>

<h3>Changelog / Release Notes</h3>

**Version 4.4.0-dev.9 (2021/11/04)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Refactored the settings page load process.
* **Requires At Least**
	* PHP v7.2.34.
	* WordPress v5.5.
	* WPSSO Core v16.7.0-dev.9.

**Version 4.3.2 (2023/07/13)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Renamed the `WpssoBcBreadcrumb->add_itemlist_data()` method to `WpssoBcBreadcrumb->add_breadcrumblist_data()`.
* **Requires At Least**
	* PHP v7.2.34.
	* WordPress v5.5.
	* WPSSO Core v15.16.0.

**Version 4.3.1 (2023/01/26)**

* **New Features**
	* None.
* **Improvements**
	* Updated the minimum WordPress version from v5.2 to v5.5.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated the `WpssoAbstractAddOn` library class.
* **Requires At Least**
	* PHP v7.2.34.
	* WordPress v5.5.
	* WPSSO Core v14.7.0.

**Version 4.3.0 (2023/01/20)**

* **New Features**
	* None.
* **Improvements**
	* Added a Yoast SEO compatibility filter to remove its breadcrumbs markup.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated the `SucomAbstractAddOn` common library class.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v14.5.0.

**Version 4.2.0 (2022/03/10)**

* **New Features**
	* None.
* **Improvements**
	* Added dynamic placeholders to Document SSO metabox title options.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.6.0.

**Version 4.1.1 (2022/03/07)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated `SucomUtilWP` method calls to `SucomUtil` for WPSSO Core v11.5.0.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.5.0.

**Version 4.1.0 (2022/02/17)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated the `WpssoPage` class `get_title()`, `get_description()`, and `get_caption()` method arguments for WPSSO Core v11.0.0.
	* Renamed the 'schema_bc_title' option key to 'schema_title_bc'.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v11.0.0.

**Version 4.0.0 (2022/02/02)**

* **New Features**
	* None.
* **Improvements**
	* Updated methods and add-on config array keys for WPSSO Core v10.0.0.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Renamed 'bc_list_for_ptn_*' option keys to 'bc_list_for_*'.
	* Updated `SucomUtilWP::get_post_types()` and `SucomUtilWP::get_taxonomies()` method arguments.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v10.0.0.

== Upgrade Notice ==

= 4.4.0-dev.9 =

(2021/11/04) Refactored the settings page load process.

= 4.3.2 =

(2023/07/13) Renamed the `WpssoBcBreadcrumb->add_itemlist_data()` method to `WpssoBcBreadcrumb->add_breadcrumblist_data()`.

= 4.3.1 =

(2023/01/26) Updated the minimum WordPress version from v5.2 to v5.5.

= 4.3.0 =

(2023/01/20) Added a Yoast SEO compatibility filter to remove its breadcrumbs markup.

= 4.2.0 =

(2022/03/10) Added dynamic placeholders to Document SSO metabox title options.

= 4.1.1 =

(2022/03/07) Updated `SucomUtilWP` method calls to `SucomUtil` for WPSSO Core v11.5.0.

= 4.1.0 =

(2022/02/17) Updated `WpssoPage` class method arguments for WPSSO Core v11.0.0.

= 4.0.0 =

(2022/02/02) Updated methods and add-on config array keys for WPSSO Core v10.0.0.

