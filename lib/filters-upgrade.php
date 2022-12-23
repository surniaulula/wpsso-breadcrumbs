<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcFiltersUpgrade' ) ) {

	class WpssoBcFiltersUpgrade {

		private $p;	// Wpsso class object.
		private $a;	// WpssoBc class object.

		/**
		 * Instantiated by WpssoBcFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'rename_md_options_keys' => 1,
			) );
		}

		public function filter_rename_md_options_keys( $rename_keys_by_ext ) {

			$rename_keys_by_ext[ 'wpssobc' ] = array(
				7 => array(
					'bc_title' => 'schema_title_bc',
				),
				8 => array(
					'bc_list_for_ptn_attachment'   => 'bc_list_for_attachment',
					'bc_list_for_ptn_download'     => 'bc_list_for_download',
					'bc_type_for_ptn_tc_events'    => 'bc_type_for_tc_events',
					'bc_type_for_ptn_tribe_events' => 'bc_type_for_tribe_events',
					'bc_list_for_ptn_page'         => 'bc_list_for_page',
					'bc_list_for_ptn_post'         => 'bc_list_for_post',
					'bc_list_for_ptn_product'      => 'bc_list_for_product',
					'bc_list_for_ptn_question'     => 'bc_list_for_question',
				),
				9 => array(
					'schema_bc_title' => 'schema_title_bc',
				),
			);

			return $rename_keys_by_ext;
		}
	}
}
