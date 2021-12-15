<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcFiltersOptions' ) ) {

	class WpssoBcFiltersOptions {

		private $p;	// Wpsso class object.
		private $a;	// WpssoBc class object.

		/**
		 * Instantiated by WpssoBcFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array( 
				'add_custom_post_type_options' => 1,
				'add_custom_taxonomy_options'  => 1,
				'option_type'                  => 2,
			) );
		}

		public function filter_add_custom_post_type_options( $opt_prefixes ) {

			$opt_prefixes[ 'bc_list_for_ptn' ] = 'ancestors';

			return $opt_prefixes;
		}

		public function filter_add_custom_taxonomy_options( $opt_prefixes ) {

			$opt_prefixes[ 'bc_list_for_tax' ] = 'ancestors';

			return $opt_prefixes;
		}

		public function filter_option_type( $type, $base_key ) {

			if ( ! empty( $type ) ) {	// Return early if we already have a type.

				return $type;

			} elseif ( 0 !== strpos( $base_key, 'bc_' ) ) {	// Nothing to do.

				return $type;
			}

			switch ( $base_key ) {

				case 'bc_home_name':
				case 'bc_wp_home_name':
				case ( 0 === strpos( $base_key, 'bc_list_for_' ) ? true : false ):

					return 'not_blank';
			}

			return $type;
		}
	}
}
