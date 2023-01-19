<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! function_exists( 'wpsso_breadcrumbs_html' ) ) {

	/*
	 * The wpsso_breadcrumbs_html() function is a human friendly name for the wpsso_bc_show_itemlist_html() function.
	 *
	 * Use $lists_max = 0 or false to include all WPSSO breadcrumb lists.
	 *
	 * Note that $link_sep is automatically encoded for display in the HTML webpage.
	 */
	function wpsso_breadcrumbs_html( $lists_max = 1, $link_sep = ' > ', $include_self = false ) {

		echo wpsso_bc_get_itemlist_html( $lists_max, $link_sep, $include_self );
	}
}

if ( ! function_exists( 'wpsso_get_breadcrumbs_html' ) ) {

	/*
	 * The wpsso_get_breadcrumbs_html() function is a human friendly name for the wpsso_bc_get_itemlist_html() function.
	 *
	 * Use $lists_max = 0 or false to include all WPSSO breadcrumb lists.
	 *
	 * Note that $link_sep is automatically encoded for display in the HTML webpage.
	 */
	function wpsso_get_breadcrumbs_html( $lists_max = 1, $link_sep = ' > ', $include_self = false ) {

		return wpsso_bc_get_itemlist_html( $lists_max, $link_sep, $include_self );
	}
}

if ( ! function_exists( 'wpsso_bc_show_itemlist_html' ) ) {

	/*
	 * Use $lists_max = 0 or false to include all WPSSO breadcrumb lists.
	 *
	 * Note that $link_sep is automatically encoded for display in the HTML webpage.
	 */
	function wpsso_bc_show_itemlist_html( $lists_max = 1, $link_sep = ' > ', $include_self = false ) {

		echo wpsso_bc_get_itemlist_html( $lists_max, $link_sep, $include_self );
	}
}

if ( ! function_exists( 'wpsso_bc_get_itemlist_html' ) ) {

	/*
	 * Use $lists_max = 0 or false to include all WPSSO breadcrumb lists.
	 *
	 * Note that $link_sep is automatically encoded for display in the HTML webpage.
	 */
	function wpsso_bc_get_itemlist_html( $lists_max = 1, $link_sep = ' > ', $include_self = false ) {

		$wpsso =& Wpsso::get_instance();

		$use_post = apply_filters( 'wpsso_use_post', in_the_loop() ? true : false );

		$mod = $wpsso->page->get_mod( $use_post );

		$html = WpssoBcBreadcrumb::get_mod_itemlist_html( $mod, $lists_max, $link_sep, $include_self );

		return $html;
	}
}
