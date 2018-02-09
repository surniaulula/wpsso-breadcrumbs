<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoBcFilters' ) ) {

	class WpssoBcFilters {

		protected $p;

		public static $cf = array(
			'opt' => array(				// options
				'defaults' => array(
					'bc_list_for_ptn_attachment' => 'none',
					'bc_list_for_ptn_page' => 'ancestors',
					'bc_list_for_ptn_post' => 'categories',
				),
			),
		);

		public function __construct( &$plugin ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->p->util->add_plugin_filters( $this, array( 
				'get_defaults' => 1,
				'json_array_schema_page_type_ids' => 2,
				'json_data_https_schema_org_breadcrumblist' => 5,
			) );
		}

		public function filter_get_defaults( $def_opts ) {
			$def_opts = array_merge( $def_opts, self::$cf['opt']['defaults'] );
			/**
			 * Add options using a key prefix array and post type names.
			 */
			$def_opts = $this->p->util->add_ptns_to_opts( $def_opts, array(
				'bc_list_for_ptn' => 'categories',	// breacrumb list for post type name
			) );
			return $def_opts;
		}

		public function filter_json_array_schema_page_type_ids( $page_type_ids, $mod ) {

			$page_type_ids['breadcrumb.list'] = true;

			return $page_type_ids;
		}

		public function filter_json_data_https_schema_org_breadcrumblist( $json_data, $mod, $mt_og, $page_type_id, $is_main ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			/**
			 * Clear all properties inherited by previous filters except for the 'url' property.
			 */
			$json_data = array( 'url' => $json_data['url'] );
			$scripts_data = array();

			if ( $mod['is_post'] ) {

				$opt_key = 'bc_list_for_ptn_'.$mod['post_type'];
				$opt_val = $this->p->options[$opt_key];

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( $opt_key.' = '.$opt_val );
				}

				switch ( $opt_val ) {

					case 'none':	// nothing to do

						$json_data = array();

						break;

					case 'ancestors':	// get post/page parents, grand-parents, etc.
				
						$mods = array();
						$post_ids = get_post_ancestors( $mod['id'] ); 
						
						if ( is_array( $post_ids ) ) {
							$post_ids   = array_reverse( $post_ids );
							$post_ids[] = $mod['id'];
						} else {
							$post_ids = array( $mod['id'] );
						}

						foreach ( $post_ids as $mod_id ) {
							$mods[] = $this->p->m['util']['post']->get_mod( $mod_id );
						}

						// single breadcrumbs list - change $json_data directly
						WpssoBcBreadcrumb::add_mods_data( $json_data, $mods, $page_type_id );

						break;

					case 'categories':

						$post_ids = array( $mod['id'] );

						foreach ( wp_get_post_terms( $mod['id'], 'category' ) as $term ) {

							$mods = array();
							$term_ids = get_ancestors( $term->term_id, 'category', 'taxonomy' );

							if ( is_array( $term_ids ) ) {
								$term_ids   = array_reverse( $term_ids );
								$term_ids[] = $term->term_id;
							} else {
								$term_ids = array( $term->term_id );
							}

							foreach ( $term_ids as $mod_id ) {
								$mods[] = $this->p->m['util']['term']->get_mod( $mod_id );
							}

							foreach ( $post_ids as $mod_id ) {
								$mods[] = $this->p->m['util']['post']->get_mod( $mod_id );
							}

							// create a unique @id for the breadcrumbs of each term
							$term_data = array( '@id' => rtrim( $json_data['url'], '/' ).'#id/'.$page_type_id.'/'.$term->slug );

							WpssoBcBreadcrumb::add_mods_data( $term_data, $mods, $page_type_id );

							// multiple breadcrumbs list - merge $json_data and save to $scripts_data array
							$scripts_data[] = WpssoSchema::return_data_from_filter( $json_data, $term_data, $is_main );
						}
					
						break;
				}
			}

			return empty( $scripts_data ) ? $json_data : $scripts_data;
		}
	}
}

