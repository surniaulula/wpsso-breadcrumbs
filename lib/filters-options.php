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
				'option_type'                               => 2,
				'get_defaults'                              => 1,
			) );
		}

		/**
		 * Return the sanitation type for a given option key.
		 */
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

		public function filter_get_defaults( $defs ) {

			$this->p->util->add_post_type_names( $defs, array(
				'bc_list_for_ptn' => 'ancestors',
			) );

			$this->p->util->add_taxonomy_names( $defs, array(
				'bc_list_for_tax' => 'ancestors',
			) );

			return $defs;
		}
	}
}
