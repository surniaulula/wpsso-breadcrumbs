<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcConfig' ) ) {

	class WpssoBcConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssobc' => array(			// Plugin acronym.
					'version'     => '2.3.0',	// Plugin version.
					'opt_version' => '3',		// Increment when changing default option values.
					'short'       => 'WPSSO BC',	// Short plugin name.
					'name'        => 'WPSSO Schema Breadcrumbs Markup',
					'desc'        => 'Schema BreadcrumbList markup in JSON-LD for better Google Rich Results with Structured Data.',
					'slug'        => 'wpsso-breadcrumbs',
					'base'        => 'wpsso-breadcrumbs/wpsso-breadcrumbs.php',
					'update_auth' => '',		// No premium version.
					'text_domain' => 'wpsso-breadcrumbs',
					'domain_path' => '/languages',
					'req' => array(
						'short'       => 'WPSSO Core',
						'name'        => 'WPSSO Core',
						'min_version' => '6.27.0',
					),
					'assets' => array(
						'icons' => array(
							'low'  => 'images/icon-128x128.png',
							'high' => 'images/icon-256x256.png',
						),
					),
					'lib' => array(
						'pro' => array(
						),
						'std' => array(
						),
						'submenu' => array(
							'bc-general' => 'Breadcrumbs',
						),
					),
				),
			),
			'opt' => array(
				'defaults' => array(
					'bc_list_for_ptn_attachment' => 'none',
					'bc_list_for_ptn_page'       => 'ancestors',
					'bc_list_for_ptn_post'       => 'categories',
					'bc_home_name'               => 'Home',
				),
			),
		);

		public static function get_version( $add_slug = false ) {

			$info =& self::$cf[ 'plugin' ][ 'wpssobc' ];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function set_constants( $plugin_file_path ) { 

			if ( defined( 'WPSSOBC_VERSION' ) ) {	// Define constants only once.
				return;
			}

			$info =& self::$cf[ 'plugin' ][ 'wpssobc' ];

			/**
			 * Define fixed constants.
			 */
			define( 'WPSSOBC_FILEPATH', $plugin_file_path );						
			define( 'WPSSOBC_PLUGINBASE', $info[ 'base' ] );	// Example: wpsso-breadcrumbs/wpsso-breadcrumbs.php.
			define( 'WPSSOBC_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_file_path ) ) ) );
			define( 'WPSSOBC_PLUGINSLUG', $info[ 'slug' ] );	// Example: wpsso-breadcrumbs.
			define( 'WPSSOBC_URLPATH', trailingslashit( plugins_url( '', $plugin_file_path ) ) );
			define( 'WPSSOBC_VERSION', $info[ 'version' ] );						
		}

		public static function require_libs( $plugin_file_path ) {

			require_once WPSSOBC_PLUGINDIR . 'lib/breadcrumb.php';
			require_once WPSSOBC_PLUGINDIR . 'lib/filters.php';
			require_once WPSSOBC_PLUGINDIR . 'lib/register.php';

			add_filter( 'wpssobc_load_lib', array( 'WpssoBcConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {

			if ( false === $ret && ! empty( $filespec ) ) {

				$file_path = WPSSOBC_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $file_path ) ) {

					require_once $file_path;

					if ( empty( $classname ) ) {
						return SucomUtil::sanitize_classname( 'wpssobc' . $filespec, $allow_underscore = false );
					} else {
						return $classname;
					}
				}
			}

			return $ret;
		}
	}
}

