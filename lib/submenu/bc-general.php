<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
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

			if ( isset( $this->p->avail[ 'p' ][ 'schema' ] ) && empty( $this->p->avail[ 'p' ][ 'schema' ] ) ) {	// Since WPSSO Core v6.23.3.

				$table_rows = array();	// Older WPSSO Core versions forced a reference argument.

				$table_rows = $this->p->msgs->get_schema_disabled_rows( $table_rows );

			} else {

				$filter_name = SucomUtil::sanitize_hookname( 'wpsso_' . $metabox_id . '_' . $tab_key . '_rows' );

				$table_rows = $this->get_table_rows( $metabox_id, $tab_key );

				$table_rows = apply_filters( $filter_name, $table_rows, $this->form, $network = false );
			}

			$this->p->util->metabox->do_table( $table_rows, 'metabox-' . $metabox_id . '-' . $tab_key );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'bc-general':

					$table_rows[ 'bc_home_name' ] = '' . 
						$this->form->get_th_html_locale( _x( 'Site Home Page Name', 'option label', 'wpsso-breadcrumbs' ),
							$css_class = '', $css_id = 'bc_home_name' ) . 
						'<td>' . $this->form->get_input_locale( 'bc_home_name', $css_class = 'long_name' ) . '</td>';

					$table_rows[ 'bc_wp_home_name' ] = '' . 
						$this->form->get_th_html_locale( _x( 'WordPress Home Page Name', 'option label', 'wpsso-breadcrumbs' ),
							$css_class = '', $css_id = 'bc_wp_home_name' ) . 
						'<td>' . $this->form->get_input_locale( 'bc_wp_home_name', $css_class = 'long_name' ) . '</td>';

					/**
					 * Breadcrumbs List by Post Type.
					 */
					$type_select = '';
					$type_labels = SucomUtil::get_post_type_labels( $val_prefix = 'bc_list_for_' );

					foreach ( $type_labels as $opt_key => $obj_label ) {

						$type_select .= '<p>' . $this->form->get_select( $opt_key, $this->p->cf[ 'form' ][ 'breadcrumbs_for_posts' ],
							$css_class = 'long_name' ) . ' ' . sprintf( _x( 'for %s', 'option comment', 'wpsso-breadcrumbs' ),
								$obj_label ) . '</p>';
					}

					$table_rows[ 'bc_list_for_pt' ] = '' .
						$this->form->get_th_html( _x( 'Breadcrumbs by Post Type', 'option label', 'wpsso-breadcrumbs' ),
							$css_class = '', $css_id = 'bc_list_for_pt' ) .
						'<td>' . $type_select . '</td>';

					/**
					 * Breadcrumbs List by Taxonomy.
					 */
					$type_select = '';
					$type_labels = SucomUtil::get_taxonomy_labels( $val_prefix = 'bc_list_for_tax_' );

					foreach ( $type_labels as $opt_key => $obj_label ) {

						$type_select .= '<p>' . $this->form->get_select( $opt_key, $this->p->cf[ 'form' ][ 'breadcrumbs_for_terms' ],
							$css_class = 'long_name' ) . ' ' . sprintf( _x( 'for %s', 'option comment', 'wpsso-breadcrumbs' ),
								$obj_label ) . '</p>';
					}

					$table_rows[ 'bc_list_for_tax' ] = '' .
						$this->form->get_th_html( _x( 'Breadcrumbs by Taxonomy', 'option label', 'wpsso-breadcrumbs' ),
							$css_class = '', $css_id = 'bc_list_for_tax' ) .
						'<td>' . $type_select . '</td>';

					break;
			}

			return $table_rows;
		}
	}
}
