<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcFilters' ) ) {

	class WpssoBcFilters {

		private $p;	// Wpsso class object.
		private $a;	// WpssoBc class object.
		private $edit;	// WpssoBcFiltersEdit class object.
		private $msgs;	// WpssoBcFiltersMessages class object.
		private $opts;	// WpssoBcFiltersOptions class object.
		private $upg;	// WpssoBcFiltersUpgrade class object.

		/**
		 * Instantiated by WpssoBc->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			require_once WPSSOBC_PLUGINDIR . 'lib/filters-options.php';

			$this->opts = new WpssoBcFiltersOptions( $plugin, $addon );

			require_once WPSSOBC_PLUGINDIR . 'lib/filters-upgrade.php';

			$this->upg = new WpssoBcFiltersUpgrade( $plugin, $addon );

			$this->p->util->add_plugin_filters( $this, array( 
				'json_array_schema_page_type_ids'           => 2,
				'json_data_https_schema_org_breadcrumblist' => 5,
			) );

			if ( is_admin() ) {

				require_once WPSSOBC_PLUGINDIR . 'lib/filters-edit.php';

				$this->edit = new WpssoBcFiltersEdit( $plugin, $addon );

				require_once WPSSOBC_PLUGINDIR . 'lib/filters-messages.php';

				$this->msgs = new WpssoBcFiltersMessages( $plugin, $addon );
			}
		}

		public function filter_json_array_schema_page_type_ids( $page_type_ids, $mod ) {

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

			if ( is_array( $json_data ) ) {	// Just in case.

				$json_data = SucomUtil::preg_grep_keys( '/^(@.*|url)$/', $json_data );
			}

			if ( empty( $json_data ) ) {

				$page_type_url = $this->p->schema->get_schema_type_url( $page_type_id );

				$json_data = WpssoSchema::get_schema_type_context( $page_type_url );
			}

			if ( empty( $json_data[ 'url' ] ) ) {

				if ( empty( $mt_og[ 'og:url' ] ) ) {

					$json_data = array( 'url' => $this->p->util->get_canonical_url( $mod ) );

				} else {

					$json_data = array( 'url' => $mt_og[ 'og:url' ] );
				}
			}

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'maximum breadcrumb scripts is ' . WPSSOBC_SCHEMA_BREADCRUMB_SCRIPTS_MAX );
			}

			$item_mods  = false;	// Do not return breadcrumbs by default.
			$item_count = 0;

			/**
			 * Breacrumbs are not required for the home page.
			 */
			if ( $mod[ 'is_home' ] ) {	// Static front page (singular post).

				if ( $mod[ 'is_home_posts' ] ) {	// Static posts page or blog archive page.

					$home_url = SucomUtil::get_home_url( $this->p->options, $mixed = 'current' );

					$wp_url = SucomUtil::get_wp_url( $this->p->options, $mixed = 'current' );

					/**
					 * Add breadcrumbs if the blog page URL is different to the home page URL.
					 */
					if ( $wp_url !== $home_url ) {

						$item_mods = array();	// Return only the home page and blog page breadcrumbs.
					}
				}

			} elseif ( $mod[ 'is_post' ] ) {

				$opt_key = 'bc_list_for_ptn_' . $mod[ 'post_type' ];

				$parent_type = empty( $this->p->options[ $opt_key ] ) ? 'categories' : $this->p->options[ $opt_key ];

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

					case 'categories':	// Get post categories (ie. primary taxonomy terms).

						/**
						 * Returns an associative array of term IDs and their names or objects.
						 *
						 * If the custom primary or default term ID exists in the post terms array, it will be moved to the top.
						 */
						$post_terms = $this->p->post->get_primary_terms( $mod, $tax_slug = 'category', $output = 'objects' );

						if ( empty( $post_terms ) || ! is_array( $post_terms ) ) {	// No terms or taxonomy does not exist.

							$item_mods = array( $this->p->post->get_mod( $mod[ 'id' ] ) );	// Just do the current page.

							break;	// Stop here.
						}

						/**
						 * The 'wpsso_primary_tax_slug' filter is hooked by the EDD and WooCommerce integration modules.
						 */
						$primary_tax_slug = apply_filters( 'wpsso_primary_tax_slug', $tax_slug = 'category', $mod );

						/**
						 * Create a Schema BreadcrumbList item list for each category.
						 */
						$bc_list_num  = 0;
						$bc_list_data = array();

						foreach ( $post_terms as $term_obj ) {

							$bc_list_mods = array();

							$term_ids = get_ancestors( $term_obj->term_id, $primary_tax_slug, 'taxonomy' );

							if ( empty( $term_ids ) || ! is_array( $term_ids ) ) {

								$term_ids = array( $term_obj->term_id );	// Just do the parent term.

							} else {

								$term_ids = array_reverse( $term_ids );	// Add ancestors in reverse order.

								$term_ids[] = $term_obj->term_id;	// Add parent term last.
							}

							foreach ( $term_ids as $mod_id ) {

								$bc_list_mods[] = $this->p->term->get_mod( $mod_id );
							}

							$bc_list_mods[] = $this->p->post->get_mod( $mod[ 'id' ] );	// Add the current page last.

							/**
							 * Create a unique @id for the breadcrumbs of each top-level post term.
							 *
							 * Example "@id": "/2013/03/15/tiled-gallery/#sso/breadcrumb.list/post-format-gallery".
							 */
							$data_id    = $json_data[ 'url' ] . $id_anchor . $page_type_id . $id_delim . $term_obj->slug;
							$term_data  = array( '@id' => $data_id );
							$item_count = WpssoBcBreadcrumb::add_itemlist_data( $term_data, $bc_list_mods, $page_type_id );

							/**
							 * Multiple breadcrumbs list - merge $json_data and save to $bc_list_data array.
							 */
							$bc_list_data[] = WpssoSchema::return_data_from_filter( $json_data, $term_data, $is_main );

							$bc_list_num++;

							if ( $bc_list_num >= WPSSOBC_SCHEMA_BREADCRUMB_SCRIPTS_MAX ) {

								break;
							}
						}

						return $bc_list_data;	// Return the multi-dimensional array.
					}

			} elseif ( $mod[ 'is_term' ] ) {

				$opt_key = 'bc_list_for_tax_' . $mod[ 'tax_slug' ];

				$parent_type = empty( $this->p->options[ $opt_key ] ) ? 'ancestors' : $this->p->options[ $opt_key ];

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

			} elseif ( $mod[ 'is_search' ] ) {

				$item_mods = array( $mod );

			} elseif ( $mod[ 'is_archive' ] ) {

				if ( $mod[ 'is_month' ] ) {

					/**
					 * Add year and month.
					 */
					$item_mods = array (
						array_merge( $mod, array( 'is_year' => true ) ),
						$mod,
					);

				} elseif ( $mod[ 'is_day' ] ) {

					/**
					 * Add year, month, and day.
					 */
					$item_mods = array (
						array_merge( $mod, array( 'is_year'  => true ) ),
						array_merge( $mod, array( 'is_month' => true ) ),
						$mod,
					);

				} else {

					$item_mods = array( $mod );
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
