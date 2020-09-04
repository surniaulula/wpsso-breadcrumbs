<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcBreadcrumb' ) ) {

	class WpssoBcBreadcrumb {

		private $p;

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}
		}

		public static function add_itemlist_data( array &$json_data, array $mods, $page_type_id ) {	// Pass by reference is OK.

			static $added_page_type_ids = array();

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->mark();
			}

			$prop_name = 'itemListElement';

			$item_count = isset( $json_data[ $prop_name ] ) ? count( $json_data[ $prop_name ] ) : 0;

			if ( empty( $page_type_id ) ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'exiting early: page_type_id is empty and required' );
				}

				return $item_count;
			}

			/**
			 * Prevent recursion - i.e. breadcrumb.list in breadcrumb.list, etc.
			 */
			if ( isset( $added_page_type_ids[ $page_type_id ] ) ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'exiting early: preventing recursion of page_type_id ' . $page_type_id );
				}

				return $item_count;

			} else {

				$added_page_type_ids[ $page_type_id ] = true;
			}

			/**
			 * Add the site home page.
			 */
			if ( $wpsso->debug->enabled ) {

				$wpsso->debug->log( 'adding site home' );
			}

			$site_url = SucomUtil::get_site_url( $wpsso->options, $mixed = 'current' );

			$home_name = SucomUtil::get_key_value( $key = 'bc_home_name', $wpsso->options, $mixed = 'current' );

			$item_count++;

			$list_item = WpssoSchema::get_schema_type_context( 'https://schema.org/ListItem', array(
				'position' => $item_count,
				'name'     => $home_name,
				'item'     => $site_url,
			) );

			$json_data[ $prop_name ][] = $list_item;

			/**
			 * Add the WordPress home page (ie. the blog page).
			 */
			$wp_url = SucomUtil::get_wp_url( $wpsso->options, $mixed = 'current' );

			if ( $wp_url !== $site_url ) {

				if ( $wpsso->debug->enabled ) {

					$wpsso->debug->log( 'adding wordpress home' );
				}

				$wp_home_name = SucomUtil::get_key_value( $key = 'bc_wp_home_name', $wpsso->options, $mixed = 'current' );

				$item_count++;

				$list_item = WpssoSchema::get_schema_type_context( 'https://schema.org/ListItem', array(
					'position' => $item_count,
					'name'     => $wp_home_name,
					'item'     => $wp_url,
				) );

				$json_data[ $prop_name ][] = $list_item;
			}

			if ( ! empty( $mods ) ) {

				/**
				 * Begin timer.
				 */
				if ( $wpsso->debug->enabled ) {
	
					$wpsso->debug->mark( 'adding mods data' );	// Begin timer.
				}
	
				foreach ( $mods as $mod ) {
	
					$item_count++;
	
					$item_name = $wpsso->page->get_title( $max_len = 0, $dots = '', $mod, $read_cache = true,
						$add_hashtags = false, $do_encode = true, $md_key = 'schema_title' );
	
					$item_url = $wpsso->util->get_canonical_url( $mod );
	
					$list_item = WpssoSchema::get_schema_type_context( 'https://schema.org/ListItem', array(
						'position' => $item_count,
						'name'     => $item_name,
						'item'     => $item_url,
					) );
	
					$json_data[ $prop_name ][] = $list_item;
				}
	
				unset( $added_page_type_ids[ $page_type_id ] );
	
				/**
				 * End timer.
				 */
				if ( $wpsso->debug->enabled ) {
	
					$wpsso->debug->mark( 'adding mods data' );	// End timer.
				}
			}

			return $item_count;
		}
	}
}
