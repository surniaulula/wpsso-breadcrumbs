<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcFiltersEdit' ) ) {

	class WpssoBcFiltersEdit {

		private $p;	// Wpsso class object.
		private $a;	// WpssoBc class object.

		/**
		 * Instantiated by WpssoBcFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array( 
				'metabox_sso_edit_rows' => 4,
			) );
		}

		public function filter_metabox_sso_edit_rows( $table_rows, $form, $head_info, $mod ) {

			$dots         = '';
			$read_cache   = true;
			$no_hashtags  = false;
			$do_encode    = true;
			$def_opt_key  = array( 'schema_title', 'og_title' );
			$def_bc_title = $this->p->page->get_title( $max_len = 0, $dots, $mod, $read_cache, $no_hashtags, $do_encode, $def_opt_key );

			SucomUtil::add_after_key( $table_rows, 'og_title', array( 
				'bc_title' => $form->get_th_html( _x( 'Breadcrumb Title', 'option label', 'wpsso-breadcrumbs' ), $css_class = 'medium', 'meta-bc_title' ) . 
					'<td>' . $form->get_input( 'bc_title', $css_class = 'wide', $css_id = '', $max_len = 0, $def_bc_title ) . '</td>'
			) );

			return $table_rows;
		}
	}
}
