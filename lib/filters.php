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

			if ( is_admin() ) {
				$this->p->util->add_plugin_filters( $this, array( 
					'option_type'      => 2,
					'messages_tooltip' => 2,
				) );
			}

			/**
			 * Disable addition of Schema BreadcrumbList JSON-LD markup by the WooCommerce WC_Structured_Data class (since v3.0.0).
			 */
			if ( ! empty( $this->p->avail['ecom']['woocommerce'] ) ) {
				add_filter( 'woocommerce_structured_data_breadcrumblist', '__return_empty_array' );
			}
		}

		public function filter_get_defaults( $def_opts ) {

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

			if ( is_array( $json_data ) ) {
				$json_data = SucomUtil::preg_grep_keys( '/^(@.*|url)$/', $json_data );
			}

			if ( empty( $json_data ) ) {
				$page_type_url = $this->p->schema->get_schema_type_url( $page_type_id );
				$json_data     = WpssoSchema::get_schema_type_context( $page_type_url );
			}

			if ( empty( $json_data[ 'url' ] ) ) {
				if ( ! empty( $mt_og['og:url'] ) ) {
					$json_data = array( 'url' => $mt_og['og:url'] );
				} else {
					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'exiting early: url not found for json data' );
					}
					return array();	// Stop here.
				}
			}

			$bclist_max = SucomUtil::get_const( 'WPSSOBC_SCHEMA_BREADCRUMB_SCRIPTS_MAX', 10 );

			$bclist_data = array();

			if ( $mod[ 'is_post' ] ) {

				$opt_key = 'bc_list_for_ptn_'.$mod[ 'post_type' ];

				/**
				 * The default for any undefined post type is 'categories'.
				 */
				$opt_val = isset( $this->p->options[ $opt_key ] ) ? $this->p->options[ $opt_key ] : 'categories';

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( $opt_key . ' is ' . $opt_val );
				}

				/**
				 * Breacrumbs are not required for the home page. The Google testing tool also gives
				 * an error if an item in the breadcrumbs list is a Schema WebSite type.
				 */
				if ( $mod[ 'is_home' ] ) {
				
					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'exiting early: breadcrumbs not required for home page' );
					}

					return array();	// Stop here.
				}

				switch ( $opt_val ) {

					case 'none':		// Nothing to do.

						return array();	// Stop here.

					case 'ancestors':	// Get post/page parents, grand-parents, etc.
				
						$post_ids = get_post_ancestors( $mod[ 'id' ] ); 

						if ( empty( $post_ids ) || ! is_array( $post_ids ) ) {

							if ( $this->p->debug->enabled ) {
								$this->p->debug->log( 'no ancestors found for ' . $mod[ 'name' ] . ' id ' . $mod[ 'id' ] );
							}

							/**
							 * Add the current webpage.
							 */
							$post_ids = array( $mod[ 'id' ] );

						} else {

							$post_ids = array_reverse( $post_ids );

							/**
							 * Add the current webpage.
							 */
							$post_ids[] = $mod[ 'id' ];
						}


						if ( $this->p->debug->enabled ) {
							$this->p->debug->log_arr( '$post_ids', $post_ids );
						}

						$mods = array();

						foreach ( $post_ids as $mod_id ) {
							$mods[] = $this->p->m[ 'util' ][ 'post' ]->get_mod( $mod_id );
						}

						WpssoBcBreadcrumb::add_itemlist_data( $json_data, $mods, $page_type_id );

						return $json_data;	// Stop here.

					case 'categories':

						$tax_slug = 'category';

						if ( $mod[ 'post_type' ] === 'product' ) {
							if ( ! empty( $this->p->avail['ecom']['woocommerce'] ) ) {
								$tax_slug = 'product_cat';
							}
						}

						if ( $this->p->debug->enabled ) {
							$this->p->debug->log( 'taxonomy slug is ' . $tax_slug );
						}

						$post_terms  = wp_get_post_terms( $mod[ 'id' ], $tax_slug );

						if ( empty( $post_terms ) || ! is_array( $post_terms ) ) {

							if ( $this->p->debug->enabled ) {
								$this->p->debug->log( 'no categories found for ' . $mod[ 'name' ] . ' id ' . $mod[ 'id' ] );
							}

							$mods = array();

							/**
							 * Add the current webpage.
							 */
							$mods[] = $this->p->m[ 'util' ][ 'post' ]->get_mod( $mod[ 'id' ] );

							WpssoBcBreadcrumb::add_itemlist_data( $json_data, $mods, $page_type_id );

							return $json_data;	// Stop here.
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

							/**
							 * Add each post term.
							 */
							foreach ( $term_ids as $mod_id ) {
								$mods[] = $this->p->m[ 'util' ][ 'term' ]->get_mod( $mod_id );
							}

							/**
							 * Add the current webpage.
							 */
							$mods[] = $this->p->m[ 'util' ][ 'post' ]->get_mod( $mod[ 'id' ] );

							/**
							 * Create a unique @id for the breadcrumbs of each top-level post term.
							 */
							$term_data = array( '@id' => $json_data[ 'url' ] . '#id/' . $page_type_id . '/' . $post_term->slug );

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

		public function filter_option_type( $type, $base_key ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! empty( $type ) ) {
				return $type;
			} elseif ( strpos( $base_key, 'bc_' ) !== 0 ) {
				return $type;
			}

			switch ( $base_key ) {

				case 'bc_home_name':
				case ( strpos( $base_key, 'bc_list_for_' ) === 0 ? true : false ):

					return 'not_blank';

					break;
			}

			return $type;
		}

		public function filter_messages_tooltip( $text, $msg_key ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( strpos( $msg_key, 'tooltip-bc_' ) !== 0 ) {
				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-bc_list_for_ptn':

					$text = __( 'Select the source of breadcrumbs for each public post type.', 'wpsso-breadcrumbs' );

					break;

				case 'tooltip-bc_home_name':

					$text = __( 'The home page name in the breadcrumbs markup.', 'wpsso-breadcrumbs' );

					break;
			}

			return $text;
		}
	}
}
