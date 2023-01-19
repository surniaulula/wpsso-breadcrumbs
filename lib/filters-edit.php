<?php
/*
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

		/*
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

			$limits = WpssoConfig::get_input_limits();	// Uses a local cache.

			/*
			 * Use $title_sep = false to avoid adding term parent names in the term title.
			 */
			$def_title_bc = $this->p->page->get_title( $mod, $md_key = 'schema_title_alt', $max_len = 'schema_title_bc', $title_sep = false );

			SucomUtil::add_after_key( $table_rows, 'schema_title_alt', array(
				'schema_title_bc' => '' .
					$form->get_th_html( _x( 'Breadcrumb Name', 'option label', 'wpsso-breadcrumbs' ),
						$css_class = 'medium', $css_id = 'meta-schema_title_bc' ) .
					'<td>' . $form->get_input_dep( 'schema_title_bc', $css_class = 'wide', $css_id = '',
						$limits[ 'schema_title_bc' ], $def_title_bc, $is_disabled = false, $dep_id = 'schema_title_alt' ) . '</td>'
			) );

			return $table_rows;
		}
	}
}
