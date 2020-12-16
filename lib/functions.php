<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! function_exists( 'wpsso_bc_show_itemlist_html' ) ) {

	function wpsso_bc_show_itemlist_html( $itemlist_max = 1, $item_sep = ' > ', $include_last = false ) {

		echo wpsso_bc_get_itemlist_html( $itemlist_max, $item_sep, $include_last );
	}
}

if ( ! function_exists( 'wpsso_bc_get_itemlist_html' ) ) {

	function wpsso_bc_get_itemlist_html( $itemlist_max = 1, $item_sep = ' > ', $include_last = false ) {

		$wpsso =& Wpsso::get_instance();

		$mod = $wpsso->page->get_mod( $use_post = false );	// Use the WP_Query, not the current $post global.

		return WpssoBcBreadcrumb::get_mod_itemlist_html( $mod, $itemlist_max, $item_sep, $include_last );
	}
}
