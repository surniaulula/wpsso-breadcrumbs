<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcFilters' ) ) {

	class WpssoBcFilters {

		private $p;
		private $msgs;		// WpssoBcFiltersMessages class object.

		public function __construct( &$plugin ) {

			/**
			 * Just in case - prevent filters from being hooked and executed more than once.
			 */
			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->p->util->add_plugin_filters( $this, array( 
				'option_type'                               => 2,
				'get_defaults'                              => 1,
				'json_array_schema_page_type_ids'           => 2,
				'json_data_https_schema_org_breadcrumblist' => 5,
			) );

			if ( is_admin() ) {

				/**
				 * Instantiate the WpssoBcFiltersMessages class object.
				 */
				if ( ! class_exists( 'WpssoBcFiltersMessages' ) ) {

					require_once WPSSOBC_PLUGINDIR . 'lib/filters-messages.php';
				}

				$this->msgs = new WpssoBcFiltersMessages( $plugin );
			}

			/**
			 * Disable addition of Schema BreadcrumbList JSON-LD markup by the WooCommerce WC_Structured_Data class (since v3.0.0).
			 */
			if ( ! empty( $this->p->avail[ 'ecom' ][ 'woocommerce' ] ) ) {

				add_filter( 'woocommerce_structured_data_breadcrumblist', '__return_empty_array' );
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
				case 'bc_wp_home_name':
				case ( strpos( $base_key, 'bc_list_for_' ) === 0 ? true : false ):

					return 'not_blank';
			}

			return $type;
		}

		public function filter_get_defaults( $defs ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			/**
			 * Add options using a key prefix array and post type names.
			 */
			$this->p->util->add_post_type_names( $defs, array(
				'bc_list_for_ptn' => 'ancestors',
			) );

			$this->p->util->add_taxonomy_names( $defs, array(
				'bc_list_for_tax' => 'ancestors',
			) );

			return $defs;
		}

		public function filter_json_array_schema_page_type_ids( $page_type_ids, $mod ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$page_type_ids[ 'breadcrumb.list' ] = true;

			return $page_type_ids;
		}

		public function filter_json_data_https_schema_org_breadcrumblist( $json_data, $mod, $mt_og, $page_type_id, $is_main ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'page_type_id is ' . $page_type_id );
			}

			static $id_anchor = null;
			static $id_delim  = null;

			if ( null === $id_anchor || null === $id_delim ) {	// Optimize and call just once.

				$id_anchor = WpssoSchema::get_id_anchor();
				$id_delim  = WpssoSchema::get_id_delim();
			}

			if ( is_array( $json_data ) ) {

				$json_data = SucomUtil::preg_grep_keys( '/^(@.*|url)$/', $json_data );
			}

			if ( empty( $json_data ) ) {

				$page_type_url = $this->p->schema->get_schema_type_url( $page_type_id );

				$json_data = WpssoSchema::get_schema_type_context( $page_type_url );
			}

			if ( empty( $json_data[ 'url' ] ) ) {

				if ( ! empty( $mt_og[ 'og:url' ] ) ) {

					$json_data = array( 'url' => $mt_og[ 'og:url' ] );

				} else {

					if ( $this->p->debug->enabled ) {

						$this->p->debug->log( 'exiting early: url not found for json data' );
					}

					return array();	// Stop here.
				}
			}

			$bc_list_max = SucomUtil::get_const( 'WPSSOBC_SCHEMA_BREADCRUMB_SCRIPTS_MAX', 20 );

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'maximum breadcrumb scripts is ' . $bc_list_max );
			}

			$bc_list_data = array();

			/**
			 * Breacrumbs are not required for the home page.
			 */
			if ( $mod[ 'is_home' ] ) {
		
				if ( $mod[ 'is_home_posts' ] ) {

					$site_url = SucomUtil::get_site_url( $this->p->options, $mixed = 'current' );
					$wp_url   = SucomUtil::get_wp_url( $this->p->options, $mixed = 'current' );

					/**
					 * Add breadcrumbs if the blog page URL is different to the home page URL.
					 */
					if ( $wp_url !== $site_url ) {
							
						WpssoBcBreadcrumb::add_itemlist_data( $json_data, array(), $page_type_id );

						return $json_data;	// Stop here.
					}
				}

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'exiting early: breadcrumbs not required for top-level home page' );
				}

				return array();	// Stop here.
			}

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'getting breadcrumbs for ' . $mod[ 'name' ] . ' id ' . $mod[ 'id' ] );
			}

			if ( $mod[ 'is_post' ] ) {

				$opt_key = 'bc_list_for_ptn_' . $mod[ 'post_type' ];

				$default_parent_type = 'categories';

			} elseif ( $mod[ 'is_term' ] ) {

				$opt_key = 'bc_list_for_tax_' . $mod[ 'tax_slug' ];

				$default_parent_type = 'ancestors';

			} elseif ( $mod[ 'is_user' ] ) {

				$opt_key = 'bc_list_for_user_page';

				$default_parent_type = 'home';

			} else {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'unknown module type' );
				}

				return array();	// Stop here.
			}

			$parent_type = isset( $this->p->options[ $opt_key ] ) ? $this->p->options[ $opt_key ] : $default_parent_type;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'option ' . $opt_key . ' is ' . $parent_type );
			}

			if ( 'none' === $parent_type ) {	// Nothing to do.

				return array();	// Stop here.
			}

			$mods = array();

			if ( $mod[ 'is_post' ] ) {

				switch ( $parent_type ) {

					case 'ancestors':	// Get page parents, grand-parents, etc.
				
						$post_ids = get_post_ancestors( $mod[ 'id' ] ); 

						if ( empty( $post_ids ) || ! is_array( $post_ids ) ) {

							if ( $this->p->debug->enabled ) {

								$this->p->debug->log( 'no ancestors found for ' . $mod[ 'name' ] . ' id ' . $mod[ 'id' ] );
							}

							$post_ids = array( $mod[ 'id' ] );	// Just do the current page.

						} else {

							$post_ids = array_reverse( $post_ids );

							$post_ids[] = $mod[ 'id' ];	// Add the current page last.
						}


						if ( $this->p->debug->enabled ) {

							$this->p->debug->log_arr( '$post_ids', $post_ids );
						}

						foreach ( $post_ids as $mod_id ) {

							$mods[] = $this->p->post->get_mod( $mod_id );
						}

						WpssoBcBreadcrumb::add_itemlist_data( $json_data, $mods, $page_type_id );

						return $json_data;	// Stop here.

					case 'categories':

						if ( taxonomy_exists( $mod[ 'post_type' ] . '_category' ) ) {	// Easy Digital Download (ie. 'download_category').

							$tax_slug = $mod[ 'post_type' ] . '_category';
	
						} elseif ( taxonomy_exists( $mod[ 'post_type' ] . '_cat' ) ) {	// WooCommerce (ie. 'product_cat').

							$tax_slug = $mod[ 'post_type' ] . '_cat';

						} else {	// WordPress default.

							$tax_slug = 'category';
						}

						/**
						 * The following filter, for example, is used by the WPSSO FAQ add-on to return
						 * 'faq_category' for the 'question' post type.
						 */
						$filter_name = SucomUtil::sanitize_hookname( $this->p->lca .  '_bc_category_tax_slug' );

						$tax_slug = apply_filters( $filter_name, $tax_slug, $mod );

						if ( $this->p->debug->enabled ) {

							$this->p->debug->log( 'taxonomy slug is ' . $tax_slug );
						}

						$post_terms = wp_get_post_terms( $mod[ 'id' ], $tax_slug );

						if ( empty( $post_terms ) || ! is_array( $post_terms ) ) {

							if ( $this->p->debug->enabled ) {

								$this->p->debug->log( 'no post terms found for ' . $mod[ 'name' ] . ' id ' . $mod[ 'id' ] );
							}

							$mods[] = $this->p->post->get_mod( $mod[ 'id' ] );	// Just do the current page.

							WpssoBcBreadcrumb::add_itemlist_data( $json_data, $mods, $page_type_id );

							return $json_data;	// Stop here.
						}

						if ( $this->p->debug->enabled ) {

							$this->p->debug->log( count( $post_terms ) . ' post terms found' );
						}

						$bc_list_num = 0;

						foreach ( $post_terms as $post_term ) {

							$mods = array();

							$term_ids = get_ancestors( $post_term->term_id, $tax_slug, 'taxonomy' );

							if ( empty( $term_ids ) || ! is_array( $term_ids ) ) {

								$term_ids = array( $post_term->term_id );	// Just do the parent term.

							} else {

								$term_ids = array_reverse( $term_ids );

								$term_ids[] = $post_term->term_id;		// Add parent term last.
							}

							foreach ( $term_ids as $mod_id ) {

								$mods[] = $this->p->term->get_mod( $mod_id );
							}

							$mods[] = $this->p->post->get_mod( $mod[ 'id' ] );	// Add the current page last.

							/**
							 * Create a unique @id for the breadcrumbs of each top-level post term.
							 */
							$data_id = $json_data[ 'url' ] . $id_anchor . $page_type_id . $id_delim . $post_term->slug;

							$term_data = array( '@id' => $data_id );

							WpssoBcBreadcrumb::add_itemlist_data( $term_data, $mods, $page_type_id );

							/**
							 * Multiple breadcrumbs list - merge $json_data and save to $bc_list_data array.
							 */
							$bc_list_data[] = WpssoSchema::return_data_from_filter( $json_data, $term_data, $is_main );

							$bc_list_num++;

							if ( $bc_list_num >= $bc_list_max ) {

								break;
							}
						}

						return $bc_list_data;	// Stop here.
				}

			} elseif ( $mod[ 'is_term' ] ) {

				switch ( $parent_type ) {

					case 'ancestors':	// Get term parents, grand-parents, etc.

						$term_ids = get_ancestors( $mod[ 'id' ], $mod[ 'tax_slug' ], 'taxonomy' );

						if ( empty( $term_ids ) || ! is_array( $term_ids ) ) {

							$term_ids = array( $mod[ 'id' ] );	// Just do the current term.

						} else {

							$term_ids = array_reverse( $term_ids );

							$term_ids[] = $mod[ 'id' ];	// Add current term last.
						}

						foreach ( $term_ids as $mod_id ) {

							$mods[] = $this->p->term->get_mod( $mod_id );
						}

						WpssoBcBreadcrumb::add_itemlist_data( $json_data, $mods, $page_type_id );

						return $json_data;	// Stop here.
				}

			} elseif ( $mod[ 'is_user' ] ) {

				switch ( $parent_type ) {

					case 'home':
				
						$mods[] = $this->p->user->get_mod( $mod[ 'id' ] );

						WpssoBcBreadcrumb::add_itemlist_data( $json_data, $mods, $page_type_id );

						return $json_data;	// Stop here.
				}
			}

			return array();	// Stop here.
		}
	}
}
