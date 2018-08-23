<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoBcBreadcrumb' ) ) {

	class WpssoBcBreadcrumb {

		private $p;
		private static $cache_exp_secs = null;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}
		}

		public static function add_mods_data( array &$json_data, array $mods, $page_type_id ) {

			static $added_page_type_ids = array();

			$wpsso =& Wpsso::get_instance();

			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->mark();
			}

			$items_count = isset( $json_data['itemListElement'] ) ? count( $json_data['itemListElement'] ) : 0;

			/**
			 * Sanity checks.
			 */
			if ( empty( $mods ) ) {

				if ( $wpsso->debug->enabled ) {
					$wpsso->debug->log( 'exiting early: mods is empty' );
				}

				return $items_count;

			} elseif ( empty( $page_type_id ) ) {

				if ( $wpsso->debug->enabled ) {
					$wpsso->debug->log( 'exiting early: page_type_id is empty' );
				}

				return $items_count;
			}

			/**
			 * Prevent recursion - i.e. breadcrumb.list in breadcrumb.list, etc.
			 */
			if ( isset( $added_page_type_ids[$page_type_id] ) ) {
				if ( $wpsso->debug->enabled ) {
					$wpsso->debug->log( 'exiting early: preventing recursion of page_type_id '.$page_type_id );
				}
				return $items_count;
			} else {
				$added_page_type_ids[$page_type_id] = true;
			}

			/**
			 * Begin timer.
			 */
			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->mark( 'adding mods data' );	// begin timer
			}

			global $wpsso_paged;

			$wpsso_paged = 1;

			foreach ( $mods as $mod ) {

				if ( $wpsso->debug->enabled ) {
					$wpsso->debug->log( 'getting single mod data for '.$mod['name'].' id '.$mod['id'] );
				}

				$mod_data = WpssoSchema::get_single_mod_data( $mod, false, $page_type_id );	// $mt_og is false.

				if ( empty( $mod_data ) ) {	// Prevent null assignment.

					if ( $wpsso->debug->enabled ) {
						$wpsso->debug->log( 'single mod data for '.$mod['name'].' id '.$mod['id'].' is empty' );
					}

					continue;	// Get the next mod.
				}

				$items_count++;

				$list_item = WpssoSchema::get_schema_type_context( 'https://schema.org/ListItem', array(
					'position' => $items_count,
					'item'     => $mod_data,
				) );

				$json_data['itemListElement'][] = $list_item;
			}

			unset( $wpsso_paged );
			unset( $added_page_type_ids[$page_type_id] );

			/**
			 * End timer.
			 */
			if ( $wpsso->debug->enabled ) {
				$wpsso->debug->mark( 'adding mods data' );	// end timer
			}

			return $items_count;
		}
	}
}
