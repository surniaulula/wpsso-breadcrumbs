=== WPSSO Schema Breadcrumbs Markup ===
Plugin Name: WPSSO Schema Breadcrumbs Markup
Plugin Slug: wpsso-breadcrumbs
Text Domain: wpsso-breadcrumbs
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-breadcrumbs/assets/
Tags: schema, breadcrumbs, seo, google, rich results
Contributors: jsmoriss
Requires Plugins: wpsso
Requires PHP: 7.4.33
Requires At Least: 5.9
Tested Up To: 6.8.2
Stable Tag: 5.3.0

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

WPSSO Schema Breadcrumbs Markup is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/), which creates extensive and complete structured data to present your content at its best for social sites and search results – no matter how URLs are shared, reshared, messaged, posted, embedded, or crawled.

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

**Version 5.3.0 (2024/08/25)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Changed the main instantiation action hook from 'init_objects' to 'init_objects_preloader'.
* **Requires At Least**
	* PHP v7.4.33.
	* WordPress v5.9.
	* WPSSO Core v18.20.0.

== Upgrade Notice ==

= 5.3.0 =

(2024/08/25) Changed the main instantiation action hook from 'init_objects' to 'init_objects_preloader'.

