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
					'bc_list_for_ptn_page'       => 'ancestors',
					'bc_list_for_ptn_post'       => 'categories',
				),
			),
		);

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->p->util->add_plugin_filters( $this, array( 
				'get_defaults'                              => 1,
				'json_array_schema_page_type_ids'           => 2,
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
				$this->p->debug->log( 'page_type_id is ' . $page_type_id );
			}

			if ( empty( $json_data ) ) {
				$page_type_url = $this->p->schema->get_schema_type_url( $page_type_id );
				$json_data = WpssoSchema::get_schema_type_context( $page_type_url );
			}

			if ( empty( $json_data['url'] ) ) {
				if ( ! empty( $mt_og['og:url'] ) ) {
					$json_data = array( 'url' => $mt_og['og:url'] );
				} else {
					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'exiting early: url not found for json data' );
					}
					return array();	// Stop here.
				}
			}

			$bclist_max  = SucomUtil::get_const( 'WPSSOBC_SCHEMA_BREADCRUMB_SCRIPTS_MAX', 5 );
			$bclist_data = array();

			if ( $mod['is_post'] ) {

				$opt_key = 'bc_list_for_ptn_'.$mod['post_type'];

				/**
				 * The default for any undefined post type is 'categories'.
				 */
				$opt_val = isset( $this->p->options[$opt_key] ) ? $this->p->options[$opt_key] : 'categories';

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( $opt_key.' is '.$opt_val );
				}

				/**
				 * Breacrumbs are not required for the home page. The Google testing tool also gives
				 * an error if an item in the breadcrumbs list is a Schema WebSite type.
				 */
				if ( $mod['is_home'] ) {
				
					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'exiting early: breadcrumbs not required for home page' );
					}

					return array();	// Stop here.
				}

				switch ( $opt_val ) {

					case 'none':		// Nothing to do.

						return array();	// Stop here.

					case 'ancestors':	// Get post/page parents, grand-parents, etc.
				
						$post_ids = get_post_ancestors( $mod['id'] ); 

						if ( empty( $post_ids ) || ! is_array( $post_ids ) ) {
							if ( $this->p->debug->enabled ) {
								$this->p->debug->log( 'no ancestors for ' . $mod['name'] . ' id ' . $mod['id'] );
							}
							return array();	// Stop here.
						}

						$post_ids = array_reverse( $post_ids );

						if ( $this->p->debug->enabled ) {
							$this->p->debug->log_arr( '$post_ids', $post_ids );
						}

						$mods = array();

						foreach ( $post_ids as $mod_id ) {
							$mods[] = $this->p->m['util']['post']->get_mod( $mod_id );
						}

						WpssoBcBreadcrumb::add_itemlist_data( $json_data, $mods, $page_type_id );

						return $json_data;	// Stop here.

					case 'categories':

						$tax_slug = 'category';

						if ( $mod['post_type'] === 'product' ) {
							if ( ! empty( $this->p->avail['ecom']['woocommerce'] ) ) {
								$tax_slug = 'product_cat';
							}
						}

						if ( $this->p->debug->enabled ) {
							$this->p->debug->log( 'taxonomy slug is ' . $tax_slug );
						}

						$post_terms  = wp_get_post_terms( $mod['id'], $tax_slug );

						if ( empty( $post_terms ) || ! is_array( $post_terms ) ) {
							if ( $this->p->debug->enabled ) {
								$this->p->debug->log_arr( 'no categories for ' . $mod['name'] . ' id ' . $mod['id'] );
							}
							return array();	// Stop here.
						}

						if ( $this->p->debug->enabled ) {
							$this->p->debug->log_arr( '$post_terms', $post_terms );
						}

						$bclist_num = 0;

						foreach ( $post_terms as $post_term ) {

							$term_ids = get_ancestors( $post_term->term_id, $tax_slug, 'taxonomy' );

							if ( empty( $term_ids ) || ! is_array( $term_ids ) ) {
								$term_ids = array( $post_term->term_id );	// Just do the parent.
							} else {
								$term_ids   = array_reverse( $term_ids );
								$term_ids[] = $post_term->term_id;	// Add parent term last.
							}

							$mods = array();

							foreach ( $term_ids as $mod_id ) {
								$mods[] = $this->p->m['util']['term']->get_mod( $mod_id );
							}

							/**
							 * Create a unique @id for the breadcrumbs of each top-level post term.
							 */
							$term_data = array( '@id' => $json_data['url'] . '#id/' . $page_type_id . '/' . $post_term->slug );

							WpssoBcBreadcrumb::add_itemlist_data( $term_data, $mods, $page_type_id );

							/**
							 * Multiple breadcrumbs list - merge $json_data and save to $bclist_data array.
							 */
							$bclist_data[] = WpssoSchema::return_data_from_filter( $json_data, $term_data, $is_main );

							$bclist_num++;

							if ( $bclist_num >= $bclist_max ) {	// Default max is 5.
								break;
							}
						}

						return $bclist_data;
				}
			}
		}
	}
}
