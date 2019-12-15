<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2019 Jean-Sebastien Morisset (https://wpsso.com/)
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

			/**
			 * Sanity checks.
			 */
			if ( empty( $mods ) ) {

				if ( $wpsso->debug->enabled ) {
					$wpsso->debug->log( 'exiting early: mods is empty' );
				}

				return $item_count;

			} elseif ( empty( $page_type_id ) ) {

				if ( $wpsso->debug->enabled ) {
					$wpsso->debug->log( 'exiting early: page_type_id is empty' );
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
			 * Begin timer.
			 */
			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->mark( 'adding mods data' );	// Begin timer.
			}

			/**
			 * Add the home page.
			 */
			$home_url  = SucomUtil::get_site_url( $wpsso->options );
			$home_name = SucomUtil::get_key_value( 'bc_home_name', $wpsso->options, 'current' );

			$item_count++;

			$list_item = WpssoSchema::get_schema_type_context( 'https://schema.org/ListItem', array(
				'position' => $item_count,
				'name'     => $home_name,
				'item'     => $home_url,
			) );

			$json_data[ $prop_name ][] = $list_item;

			/**
			 * Add each post parent or term.
			 */
			foreach ( $mods as $mod ) {

				$item_count++;

				$item_name = $wpsso->page->get_title( $max_len = 0, $dots = '', $mod, $read_cache = true,
					$add_hashtags = false, $do_encode = true, $md_key = 'schema_title', $sep = false );

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

			return $item_count;
		}
	}
}
