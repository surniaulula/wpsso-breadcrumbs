<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoBcSubmenuBcGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoBcSubmenuBcGeneral extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->menu_id = $id;
			$this->menu_name = $name;
			$this->menu_lib = $lib;
			$this->menu_ext = $ext;
		}

		// called by the extended WpssoAdmin class
		protected function add_meta_boxes() {
			add_meta_box( $this->pagehook.'_breadcrumbs', 
				_x( 'Breadcrumbs Settings', 'metabox title', 'wpsso-breadcrumbs' ),
					array( &$this, 'show_metabox_breadcrumbs' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_breadcrumbs() {
			$metabox_id = 'bc';
			$key = 'general';
			$this->p->util->do_table_rows( apply_filters( $this->p->cf['lca'].'_'.$metabox_id.'_'.$key.'_rows', 
				$this->get_table_rows( $metabox_id, $key ), $this->form, false ), 'metabox-'.$metabox_id.'-'.$key );
		}

		protected function get_table_rows( $metabox_id, $key ) {
			$table_rows = array();
			switch ( $metabox_id.'-'.$key ) {
				case 'bc-general':

					$bc_list_for_posts = $this->p->cf['form']['breadcrumbs_for_posts'];
					$bc_list_for_terms = $this->p->cf['form']['breadcrumbs_for_terms'];

					$bc_select_for_posts = '';
					$bc_select_for_terms = '';

					foreach ( $this->p->util->get_post_types( 'objects' ) as $pt ) {
						$bc_select_for_posts .= '<p>'.$this->form->get_select( 'bc_list_for_ptn_'.$pt->name, $bc_list_for_posts ).
							' '.$pt->label.( empty( $pt->description ) ? '' : ' ('.$pt->description.')' ).'</p>';
					}

					$table_rows[] = $this->form->get_th_html( _x( 'Breadcrumbs for Post Types',
						'option label', 'wpsso-breadcrumbs' ), null, 'bc_list_for_ptn' ).'<td>'.$bc_select_for_posts.'</td>';

					break;
			}
			return $table_rows;
		}
	}
}

