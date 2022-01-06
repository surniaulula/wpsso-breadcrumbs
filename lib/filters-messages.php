<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcFiltersMessages' ) ) {

	class WpssoBcFiltersMessages {

		private $p;	// Wpsso class object.
		private $a;	// WpssoBc class object.

		/**
		 * Instantiated by WpssoBcFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array( 
				'messages_tooltip'      => 2,
			) );
		}

		public function filter_messages_tooltip( $text, $msg_key ) {

			if ( strpos( $msg_key, 'tooltip-bc_' ) !== 0 ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-bc_home_name':	// Site Home Page Name.

					$text = __( 'The site home page name in the breadcrumbs markup.', 'wpsso-breadcrumbs' );

					break;

				case 'tooltip-bc_wp_home_name':	// WordPress Home Page Name.

					$text = __( 'The WordPress home page (ie. the blog page) name in the breadcrumbs markup.', 'wpsso-breadcrumbs' );

					break;

				case 'tooltip-bc_list_for_user_page':	// Breadcrumbs for User Profiles.

					$text = __( 'Select the source of breadcrumbs for user profile pages.', 'wpsso-breadcrumbs' );

					break;

				case 'tooltip-bc_list_for_ptn':	// Breadcrumbs by Post Type.

					$text = __( 'Select the source of breadcrumbs for each public post type (ie. posts, pages, products, and other custom post types).', 'wpsso-breadcrumbs' ) . ' ';

					$text .= __( 'The post categories selection uses the \'category\' taxonomy by default, and the \'product_cat\' taxonomy for WooCommerce products.', 'wpsso-breadcrumbs' );

					break;

				case 'tooltip-bc_list_for_ttn':	// Breadcrumbs by Taxonomy.

					$text = __( 'Select the source of breadcrumbs for each public taxonomy (ie. categories, tags, and other custom taxonomies).', 'wpsso-breadcrumbs' );

					break;
			}

			return $text;
		}
	}
}
