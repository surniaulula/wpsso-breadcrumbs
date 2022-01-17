<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2022 Jean-Sebastien Morisset (https://wpsso.com/)
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
				'metabox_sso_edit_schema_rows' => 4,	// Since WPSSO Core v9.0.0.
			) );
		}

		public function filter_metabox_sso_edit_schema_rows( $table_rows, $form, $head_info, $mod ) {

			$dots         = '';
			$read_cache   = true;
			$no_hashtags  = false;
			$do_encode    = true;
			$def_opt_keys = array( 'schema_title', 'og_title' );
			$def_bc_title = $this->p->page->get_title( $max_len = 0, $dots, $mod, $read_cache, $no_hashtags, $do_encode, $def_opt_keys );

			SucomUtil::add_after_key( $table_rows, 'schema_title_alt', array( 
				'schema_bc_title' => '' .
					$form->get_th_html( _x( 'Breadcrumb Name', 'option label', 'wpsso-breadcrumbs' ),
						$css_class = 'medium', $css_id = 'meta-schema_bc_title' ) . 
					'<td>' . $form->get_input( 'schema_bc_title', $css_class = 'wide', $css_id = '', $max_len = 0, $def_bc_title ) . '</td>'
			) );

			return $table_rows;
		}
	}
}
