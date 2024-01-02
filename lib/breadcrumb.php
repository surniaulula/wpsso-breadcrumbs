<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcBreadcrumb' ) ) {

	class WpssoBcBreadcrumb {

		private $p;	// Wpsso class object.
		private $a;	// WpssoBc class object.

		/*
		 * Instantiated by WpssoBc->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;
		}

		/*
		 * See WpssoBcFilters->filter_json_data_https_schema_org_breadcrumblist().
		 */
		public static function add_breadcrumblist_data( array &$json_data, array $mods, $page_type_id ) {	// Pass by reference is OK.

			static $added_page_type_ids = array();

			$wpsso =& Wpsso::get_instance();

			$prop_name  = 'itemListElement';
			$item_count = isset( $json_data[ $prop_name ] ) ? count( $json_data[ $prop_name ] ) : 0;

			if ( empty( $page_type_id ) ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'exiting early: page_type_id is empty and required' );
				}

				return $item_count;
			}

			/*
			 * Prevent recursion - i.e. breadcrumb.list in breadcrumb.list, etc.
			 */
			if ( isset( $added_page_type_ids[ $page_type_id ] ) ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'exiting early: preventing recursion of page_type_id ' . $page_type_id );
				}

				return $item_count;

			}

			$added_page_type_ids[ $page_type_id ] = true;

			/*
			 * Add the website home page.
			 */
			$home_url = SucomUtil::get_home_url( $wpsso->options, $mixed = 'current' );

			if ( ! apply_filters( 'wpsso_bc_add_home_url', true ) ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'adding site home listitem is disabled' );
				}

			} else {

				$item_count++;

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'adding site home listitem #' . $item_count );
				}

				$home_name = SucomUtil::get_key_value( $key = 'bc_home_name', $wpsso->options, $mixed = 'current' );

				$list_item = WpssoSchema::get_schema_type_context( 'https://schema.org/ListItem', array(
					'position' => $item_count,
					'name'     => $home_name,
					'item'     => $home_url,
				) );

				$json_data[ $prop_name ][] = $list_item;
			}

			/*
			 * Add the WordPress home page (ie. the blog page).
			 */
			$wp_url = SucomUtil::get_wp_url( $wpsso->options, $mixed = 'current' );

			if ( $wp_url === $home_url ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'adding wp home listitem skipped - same as site home' );
				}

			} elseif ( ! apply_filters( 'wpsso_bc_add_wp_url', true ) ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'adding wp home listitem is disabled' );
				}

			} else {

				$item_count++;

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'adding wp home listitem #' . $item_count);
				}

				$wp_home_name = SucomUtil::get_key_value( $key = 'bc_wp_home_name', $wpsso->options, $mixed = 'current' );

				$list_item = WpssoSchema::get_schema_type_context( 'https://schema.org/ListItem', array(
					'position' => $item_count,
					'name'     => $wp_home_name,
					'item'     => $wp_url,
				) );

				$json_data[ $prop_name ][] = $list_item;
			}

			if ( ! empty( $mods ) ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->mark( 'adding mods data' );	// Begin timer.
				}

				foreach ( $mods as $mod ) {

					$item_count++;

					/*
					 * Use $title_sep = false to avoid adding term parent names in the term title.
					 */
					$item_name = $wpsso->page->get_title( $mod, $md_key = 'schema_title_bc', $max_len = 'schema_title_bc', $title_sep = false );
					$item_url  = $wpsso->util->get_canonical_url( $mod );

					$list_item = WpssoSchema::get_schema_type_context( 'https://schema.org/ListItem', array(
						'position' => $item_count,
						'name'     => $item_name,
						'item'     => $item_url,
					) );

					$json_data[ $prop_name ][] = $list_item;
				}

				unset( $added_page_type_ids[ $page_type_id ] );

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->mark( 'adding mods data' );	// End timer.
				}
			}

			return $item_count;
		}

		/*
		 * Returns an HTML breadcrumbs string for the given $mod.
		 *
		 * Use $lists_max = 0 or false to include all WPSSO breadcrumb lists.
		 *
		 * Note that $link_sep is automatically encoded for display in the HTML webpage.
		 */
		public static function get_mod_itemlist_html( array $mod, $lists_max = 1, $link_sep = ' > ', $include_self = false ) {

			$itemlist_links = self::get_mod_itemlist_links( $mod, $lists_max );

			$link_sep_encoded = SucomUtil::encode_html_emoji( $link_sep );	// Does not double-encode.

			$html = '';

			foreach ( $itemlist_links as $single_list ) {

				if ( ! $include_self ) {

					array_pop( $single_list );
				}

				$html .= '<div class="wpsso-bc-breadcrumbs">' . "\n";
				$html .= "\t" . implode( $link_sep_encoded, $single_list ) . "\n";
				$html .= '</div>' . "\n\n";
			}

			return $html;
		}

		/*
		 * Returns an array of arrays with HTML link elements.
		 *
		 * Use $lists_max = 0 or false to include all breadcrumb lists.
		 */
		public static function get_mod_itemlist_links( array $mod, $lists_max = 1 ) {

			$wpsso =& Wpsso::get_instance();

			/*
			 * WpssoSchema->get_json_data() returns a two dimensional array of json data unless $single is true.
			 */
			$json_data = $wpsso->schema->get_json_data( $mod, $mt_og = array(), $page_type_id = 'breadcrumb.list', $is_main = false, $single = false );

			if ( empty( $json_data ) || ! is_array( $json_data ) ) {	// Just in case

				return array();
			}

			if ( $lists_max ) {

				$json_data = array_slice( $json_data, 0, $lists_max );
			}

			$itemlist_links = array();

			foreach ( $json_data as $list_idx => $itemlist ) {

				if ( empty( $itemlist[ 'itemListElement' ] ) || ! is_array( $itemlist[ 'itemListElement' ] ) ) {	// Just in case.

					continue;
				}

				$last_num = count( $itemlist[ 'itemListElement' ] ) - 1;

				foreach ( $itemlist[ 'itemListElement' ] as $item_idx => $list_item ) {

					if ( $item_idx < $last_num ) {

						$itemlist_links[ $list_idx ][ $item_idx ] = '<a href="' . $list_item[ 'item' ] . '">' . $list_item[ 'name' ] . '</a>';

					} else {

						$itemlist_links[ $list_idx ][ $item_idx ] = $list_item[ 'name' ];
					}
				}
			}

			return $itemlist_links;
		}
	}
}
