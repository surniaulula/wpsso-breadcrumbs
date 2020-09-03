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
					$bc_list_select = '';
					$post_types     = SucomUtilWP::get_post_types( 'objects' );

					foreach ( $post_types as $obj ) {

						$opt_key   = 'bc_list_for_ptn_' . $obj->name;
						$obj_label = SucomUtilWP::get_object_label( $obj );

						$bc_list_select .= '<p>' . $this->form->get_select( $opt_key, $this->p->cf[ 'form' ][ 'breadcrumbs_for_posts' ],
							$css_class = 'long_name' ) . ' ' . sprintf( _x( 'for %s', 'option comment', 'wpsso-breadcrumbs' ), $obj_label ) . '</p>';
					}

					$tr_key   = 'bc_list_for_ptn';
					$th_label = _x( 'Breadcrumbs by Post Type', 'option label', 'wpsso-breadcrumbs' );

					$table_rows[ $tr_key ] = $this->form->get_th_html( $th_label, $css_class = '', $css_id = $tr_key ) .
						'<td>' . $bc_list_select . '</td>';

					unset( $bc_list_select, $post_types, $tr_key, $th_label );	// Just in case.

					/**
					 * Breadcrumbs List by Taxonomy.
					 */
					$bc_list_select = '';
					$taxonomies     = SucomUtilWP::get_taxonomies( 'objects' );

					foreach ( $taxonomies as $obj ) {
				
						$opt_key   = 'bc_list_for_tax_' . $obj->name;
						$obj_label = SucomUtilWP::get_object_label( $obj );
				
						$bc_list_select .= '<p>' . $this->form->get_select( $opt_key, $this->p->cf[ 'form' ][ 'breadcrumbs_for_terms' ],
							$css_class = 'long_name' ) . ' ' . sprintf( _x( 'for %s', 'option comment', 'wpsso-breadcrumbs' ),
								$obj_label ) . '</p>' . "\n";
					}

					$tr_key   = 'bc_list_for_ttn';
					$th_label = _x( 'Breadcrumbs by Taxonomy', 'option label', 'wpsso-breadcrumbs' );

					$table_rows[ $tr_key ] = $this->form->get_th_html( $th_label, '', $tr_key ) . '<td>' . $bc_list_select . '</td>';

					unset( $bc_list_select, $taxonomies, $tr_key, $th_label );	// Just in case.

					/**
					 * Breadcrumbs List for User Profile.
					 */
					$tr_key   = $opt_key = 'bc_list_for_user_page';
					$th_label = _x( 'Breadcrumbs for User Profile', 'option label', 'wpsso-breadcrumbs' );

					$table_rows[ $tr_key ] = $this->form->get_th_html( $th_label, $css_class = '', $css_id = $tr_key ) . 
						'<td>' . $this->form->get_select( $opt_key, $this->p->cf[ 'form' ][ 'breadcrumbs_for_users' ],
							$css_class = 'long_name' ) . '</td>';

					unset( $tr_key, $th_label );	// Just in case.

					break;
			}

			return $table_rows;
		}
	}
}
