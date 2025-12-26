<?php
/*
 * Plugin Name: WPSSO Schema Breadcrumbs Markup
 * Plugin Slug: wpsso-breadcrumbs
 * Text Domain: wpsso-breadcrumbs
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-breadcrumbs/
 * Assets URI: https://jsmoriss.github.io/wpsso-breadcrumbs/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Schema BreadcrumbList markup in JSON-LD format for Google Rich Results.
 * Requires Plugins: wpsso
 * Requires PHP: 7.4.33
 * Requires At Least: 6.0
 * Tested Up To: 6.9
 * Version: 5.5.0
 *
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes and/or incompatible API changes (ie. breaking changes).
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 *
 * Copyright 2018-2025 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoAbstractAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstract/add-on.php';
}

if ( ! class_exists( 'WpssoBc' ) ) {

	class WpssoBc extends WpssoAbstractAddOn {

		protected $p;	// Wpsso class object.

		private static $instance = null;	// WpssoBc class object.

		public function __construct() {

			parent::__construct( __FILE__, __CLASS__ );
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init_textdomain() {

			load_plugin_textdomain( 'wpsso-breadcrumbs', false, 'wpsso-breadcrumbs/languages/' );
		}

		/*
		 * Called by Wpsso->set_objects() which runs at init priority 10.
		 */
		public function init_objects_preloader() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $this->get_missing_requirements() ) {	// Returns false or an array of missing requirements.

				return;	// Stop here.
			}

			new WpssoBcCompat( $this->p, $this );	// Third-party plugin and theme compatibility actions and filters.
			new WpssoBcFilters( $this->p, $this );
		}
	}

	WpssoBc::get_instance();
}
