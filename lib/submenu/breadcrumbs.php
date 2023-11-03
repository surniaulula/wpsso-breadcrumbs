<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2023 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcSubmenuBreadcrumbs' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoBcSubmenuBreadcrumbs extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;

			$this->menu_metaboxes = array(
				'settings' => _x( 'Breadcrumbs Settings', 'metabox title', 'wpsso-breadcrumbs' ),
			);
		}

		protected function add_meta_boxes( $callback_args = array() ) {

			$this->maybe_show_language_notice();

			parent::add_meta_boxes( $callback_args );
		}

		public function show_metabox_settings( $obj, $mb ) {

			if ( isset( $this->p->avail[ 'p' ][ 'schema' ] ) && empty( $this->p->avail[ 'p' ][ 'schema' ] ) ) {

				$page_id    = isset( $mb[ 'args' ][ 'page_id' ] ) ? $mb[ 'args' ][ 'page_id' ] : '';
				$metabox_id = isset( $mb[ 'args' ][ 'metabox_id' ] ) ? $mb[ 'args' ][ 'metabox_id' ] : '';
				$table_rows = array();	// Older WPSSO Core versions forced a reference argument.
				$table_rows = $this->p->msgs->get_schema_disabled_rows( $table_rows );

				$this->p->util->metabox->do_table( $table_rows, 'metabox-' . $page_id . '-' . $metabox_id );

			} else $this->show_metabox_table( $obj, $mb );
		}

		protected function get_table_rows( $page_id, $metabox_id ) {

			$table_rows = array();

			$table_rows[ 'bc_home_name' ] = '' .
				$this->form->get_th_html_locale( _x( 'Site Home Page Name', 'option label', 'wpsso-breadcrumbs' ),
					$css_class = '', $css_id = 'bc_home_name' ) .
				'<td>' . $this->form->get_input_locale( 'bc_home_name', $css_class = 'long_name' ) . '</td>';

			$table_rows[ 'bc_wp_home_name' ] = '' .
				$this->form->get_th_html_locale( _x( 'WordPress Home Page Name', 'option label', 'wpsso-breadcrumbs' ),
					$css_class = '', $css_id = 'bc_wp_home_name' ) .
				'<td>' . $this->form->get_input_locale( 'bc_wp_home_name', $css_class = 'long_name' ) . '</td>';

			/*
			 * Breadcrumbs by Post Type.
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

			/*
			 * Breadcrumbs by Taxonomy.
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

			return $table_rows;
		}
	}
}
