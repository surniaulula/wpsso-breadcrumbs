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

		private $p;	// Wpsso class object.
		private $msgs;	// WpssoBcFiltersMessages class object.

		public function __construct( &$plugin ) {

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

			$item_mods = false;	// Do not return breadcrumbs by default.

			$item_count = 0;

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

						$item_mods = array();	// Return only the home page and blog page breadcrumbs.
					}
				}

			} elseif ( $mod[ 'is_post' ] ) {

				$opt_key = 'bc_list_for_ptn_' . $mod[ 'post_type' ];

				$def_parent_type = 'categories';

				$parent_type = empty( $this->p->options[ $opt_key ] ) ? $def_parent_type : $this->p->options[ $opt_key ];

				switch ( $parent_type ) {

					case 'none':	// Nothing to do.

						break;

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

						$item_mods = array();	// False by default.

						foreach ( $post_ids as $mod_id ) {

							$item_mods[] = $this->p->post->get_mod( $mod_id );
						}

						break;

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
						$filter_name = SucomUtil::sanitize_hookname( 'wpsso_bc_category_tax_slug' );

						$tax_slug = apply_filters( $filter_name, $tax_slug, $mod );

						if ( $this->p->debug->enabled ) {

							$this->p->debug->log( 'taxonomy slug is ' . $tax_slug );
						}

						$post_terms = wp_get_post_terms( $mod[ 'id' ], $tax_slug );

						if ( empty( $post_terms ) || ! is_array( $post_terms ) ) {

							if ( $this->p->debug->enabled ) {

								$this->p->debug->log( 'no post terms found for ' . $mod[ 'name' ] . ' id ' . $mod[ 'id' ] );
							}

							$item_mods = array( $this->p->post->get_mod( $mod[ 'id' ] ) );	// Just do the current page.

							break;	// Stop here.
						}

						/**
						 * Create one or more breadcrumb lists and return the multi-dimensional array.
						 */
						if ( $this->p->debug->enabled ) {

							$this->p->debug->log( count( $post_terms ) . ' post terms found' );
						}

						$bc_list_num = 0;

						$bc_list_data = array();

						foreach ( $post_terms as $post_term ) {

							$bc_list_mods = array();

							$term_ids = get_ancestors( $post_term->term_id, $tax_slug, 'taxonomy' );

							if ( empty( $term_ids ) || ! is_array( $term_ids ) ) {

								$term_ids = array( $post_term->term_id );	// Just do the parent term.

							} else {

							$term_ids = array_reverse( $term_ids );

								$term_ids[] = $post_term->term_id;		// Add parent term last.
							}

							foreach ( $term_ids as $mod_id ) {

								$bc_list_mods[] = $this->p->term->get_mod( $mod_id );
							}

							$bc_list_mods[] = $this->p->post->get_mod( $mod[ 'id' ] );	// Add the current page last.

							/**
							 * Create a unique @id for the breadcrumbs of each top-level post term.
							 */
							$data_id = $json_data[ 'url' ] . $id_anchor . $page_type_id . $id_delim . $post_term->slug;

							$term_data = array( '@id' => $data_id );

							$item_count = WpssoBcBreadcrumb::add_itemlist_data( $term_data, $bc_list_mods, $page_type_id );

							/**
							 * Multiple breadcrumbs list - merge $json_data and save to $bc_list_data array.
							 */
							$bc_list_data[] = WpssoSchema::return_data_from_filter( $json_data, $term_data, $is_main );

							$bc_list_num++;

							if ( $bc_list_num >= $bc_list_max ) {

								break;
							}
						}

						return $bc_list_data;	// Return the multi-dimensional array.
					}

			} elseif ( $mod[ 'is_term' ] ) {

				$opt_key = 'bc_list_for_tax_' . $mod[ 'tax_slug' ];

				$def_parent_type = 'ancestors';

				$parent_type = empty( $this->p->options[ $opt_key ] ) ? $def_parent_type : $this->p->options[ $opt_key ];

				switch ( $parent_type ) {

					case 'none':	// Nothing to do.

						break;

					case 'ancestors':	// Get term parents, grand-parents, etc.

						$term_ids = get_ancestors( $mod[ 'id' ], $mod[ 'tax_slug' ], 'taxonomy' );

						if ( empty( $term_ids ) || ! is_array( $term_ids ) ) {

							$term_ids = array( $mod[ 'id' ] );	// Just do the current term.

						} else {

							$term_ids = array_reverse( $term_ids );

							$term_ids[] = $mod[ 'id' ];	// Add current term last.
						}

						$item_mods = array();	// False by default.

						foreach ( $term_ids as $mod_id ) {

							$item_mods[] = $this->p->term->get_mod( $mod_id );
						}

						break;
				}

			} elseif ( $mod[ 'is_user' ] ) {

				$item_mods = array( $mod );

			} elseif ( is_search() ) {

				$item_mods = array( array_merge( $mod, array( 'is_search' => true ) ) );

			} elseif ( SucomUtil::is_archive_page() ) {

				$mod[ 'is_archive' ] = true;

				if ( is_date() ) {

					$mod[ 'is_date' ] = true;

					if ( is_year() ) {

						$item_mods = array ( array_merge( $mod, array( 'is_year' => true ) ) );

					} elseif ( is_month() ) {

						$item_mods = array (
							array_merge( $mod, array( 'is_year' => true ) ),
							array_merge( $mod, array( 'is_month' => true ) ),
						);

					} elseif ( is_day() ) {

						$item_mods = array (
							array_merge( $mod, array( 'is_year' => true ) ),
							array_merge( $mod, array( 'is_month' => true ) ),
							array_merge( $mod, array( 'is_day' => true ) ),
						);
					}
				}

			}

			/**
			 * Passing an empty $item_mods array will only add the home page and the blog page URL(s).
			 *
			 * The returned $item_count will be at least 1 for the home page.
			 */
			if ( is_array( $item_mods ) ) {	// False by default.

				$item_count = WpssoBcBreadcrumb::add_itemlist_data( $json_data, $item_mods, $page_type_id );
			}

			return $item_count ? $json_data : array();	// Stop here.
		}
	}
}
