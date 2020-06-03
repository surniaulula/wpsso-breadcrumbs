<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcSubmenuBcGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoBcSubmenuBcGeneral extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;
		}

		/**
		 * Called by the extended WpssoAdmin class.
		 */
		protected function add_meta_boxes() {

			$this->maybe_show_language_notice();

			$metabox_id      = 'bc';
			$metabox_title   = _x( 'Breadcrumbs Settings', 'metabox title', 'wpsso-breadcrumbs' );
			$metabox_screen  = $this->pagehook;
			$metabox_context = 'normal';
			$metabox_prio    = 'default';
			$callback_args   = array(	// Second argument passed to the callback function / method.
			);

			add_meta_box( $this->pagehook . '_' . $metabox_id, $metabox_title,
				array( $this, 'show_metabox_bc' ), $metabox_screen,
					$metabox_context, $metabox_prio, $callback_args );
		}

		public function show_metabox_bc() {

			$metabox_id = 'bc';

			$tab_key = 'general';

			if ( empty( $this->p->avail[ 'p' ][ 'schema' ] ) ) {	// Since WPSSO Core v6.23.3.

				$table_rows = array();

				$table_rows = $this->p->msgs->get_schema_disabled_rows( $table_rows, $col_span = 1 );

			} else {

				$filter_name = SucomUtil::sanitize_hookname( $this->p->lca . '_' . $metabox_id . '_' . $tab_key . '_rows' );

				$table_rows = apply_filters( $filter_name, $this->get_table_rows( $metabox_id, $tab_key ), $this->form );
			}

			$this->p->util->do_metabox_table( $table_rows, 'metabox-' . $metabox_id . '-' . $tab_key );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'bc-general':

					$table_rows[ 'bc_home_name' ] = '' . 
					$this->form->get_th_html_locale( _x( 'Home Page Name', 'option label', 'wpsso-breadcrumbs' ),
						$css_class = '', $css_id = 'bc_home_name' ) . 
					'<td>' . $this->form->get_input_locale( 'bc_home_name', $css_class = 'long_name' ) . '</td>';

					$bc_list_for_posts = $this->p->cf[ 'form' ][ 'breadcrumbs_for_posts' ];
					$bc_list_for_terms = $this->p->cf[ 'form' ][ 'breadcrumbs_for_terms' ];

					$bc_select_for_posts = '';
					$bc_select_for_terms = '';

					foreach ( SucomUtilWP::get_post_types( 'objects' ) as $obj ) {

						$bc_select_for_posts .= '<p>';
						$bc_select_for_posts .= $this->form->get_select( 'bc_list_for_ptn_' . $obj->name, $bc_list_for_posts ) . ' ';
						$bc_select_for_posts .= $obj->label . ( empty( $obj->description ) ? '' : ' (' . $obj->description . ')' );
						$bc_select_for_posts .= '</p>';
					}

					$table_rows[ 'bc_list_for_ptn' ] = '' . 
					$this->form->get_th_html( _x( 'Breadcrumbs for Post Types', 'option label', 'wpsso-breadcrumbs' ),
						$css_class = '', $css_id = 'bc_list_for_ptn' ) .
					'<td>' . $bc_select_for_posts . '</td>';

					break;
			}

			return $table_rows;
		}
	}
}
