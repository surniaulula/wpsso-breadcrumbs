<?php
/**
 * Plugin Name: WPSSO Schema Breadcrumbs Markup
 * Plugin Slug: wpsso-breadcrumbs
 * Text Domain: wpsso-breadcrumbs
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-breadcrumbs/
 * Assets URI: https://jsmoriss.github.io/wpsso-breadcrumbs/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Description: Schema BreadcrumbList Markup in JSON-LD for Better Google Rich Results with Structured Data.
 * Requires PHP: 5.6
 * Requires At Least: 4.4
 * Tested Up To: 5.5.1
 * Version: 3.1.0-b.1
 *
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes / re-writes or incompatible API changes.
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 *
 * Copyright 2017-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstracts/add-on.php';	// WpssoAddOn class.
}

if ( ! class_exists( 'WpssoBc' ) ) {

	class WpssoBc extends WpssoAddOn {

		public $breadcrumb;	// WpssoBcBreadcrumb class.
		public $compat;		// WpssoBcCompat class.
		public $filters;	// WpssoBcFilters class.

		protected $p;

		private static $instance = null;

		public function __construct() {

			parent::__construct( __FILE__, __CLASS__ );
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init_textdomain( $debug_enabled = false ) {

			static $local_cache = null;

			if ( null === $local_cache || $debug_enabled ) {

				$local_cache = 'wpsso-breadcrumbs';

				load_plugin_textdomain( 'wpsso-breadcrumbs', false, 'wpsso-breadcrumbs/languages/' );
			}

			return $local_cache;
		}

		public function init_objects() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $this->get_missing_requirements() ) {	// Returns false or an array of missing requirements.

				return;	// Stop here.
			}

			$this->breadcrumb = new WpssoBcBreadcrumb( $this->p );
			$this->compat     = new WpssoBcCompat( $this->p );	// 3rd party plugin and theme compatibility actions and filters.
			$this->filters    = new WpssoBcFilters( $this->p );
		}
	}

	WpssoBc::get_instance();
}
