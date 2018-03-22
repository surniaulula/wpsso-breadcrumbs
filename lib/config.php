<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoBcConfig' ) ) {

	class WpssoBcConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssobc' => array(			// Plugin acronym.
					'version' => '1.1.2-b.1',		// Plugin version.
					'opt_version' => '2',		// Increment when changing default option values.
					'short' => 'WPSSO BC',		// Short plugin name.
					'name' => 'WPSSO Schema Breadcrumbs Markup',
					'desc' => 'WPSSO Core add-on to add JSON-LD formatted Schema BreadcrumbList markup for Google and Search Engine Optimization (SEO).',
					'slug' => 'wpsso-breadcrumbs',
					'base' => 'wpsso-breadcrumbs/wpsso-breadcrumbs.php',
					'update_auth' => '',
					'text_domain' => 'wpsso-breadcrumbs',
					'domain_path' => '/languages',
					'req' => array(
						'short' => 'WPSSO Core',
						'name' => 'WPSSO Core',
						'min_version' => '3.56.2-b.1',
					),
					'img' => array(
						'icons' => array(
							'low' => 'images/icon-128x128.png',
							'high' => 'images/icon-256x256.png',
						),
					),
					'lib' => array(
						'submenu' => array(	// Note that submenu elements must have unique keys.
							'bc-general' => 'Breadcrumbs',
						),
						'gpl' => array(
						),
						'pro' => array(
						),
					),
				),
			),
		);

		public static function get_version( $add_slug = false ) {
			$ext = 'wpssobc';
			$info =& self::$cf['plugin'][$ext];
			return $add_slug ? $info['slug'].'-'.$info['version'] : $info['version'];
		}

		public static function set_constants( $plugin_filepath ) { 
			if ( defined( 'WPSSOBC_VERSION' ) ) {			// execute and define constants only once
				return;
			}
			define( 'WPSSOBC_VERSION', self::$cf['plugin']['wpssobc']['version'] );						
			define( 'WPSSOBC_FILEPATH', $plugin_filepath );						
			define( 'WPSSOBC_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSOBC_PLUGINSLUG', self::$cf['plugin']['wpssobc']['slug'] );		// wpsso-breadcrumbs
			define( 'WPSSOBC_PLUGINBASE', self::$cf['plugin']['wpssobc']['base'] );		// wpsso-breadcrumbs/wpsso-breadcrumbs.php
			define( 'WPSSOBC_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
		}

		public static function require_libs( $plugin_filepath ) {

			require_once WPSSOBC_PLUGINDIR.'lib/register.php';
			require_once WPSSOBC_PLUGINDIR.'lib/filters.php';
			require_once WPSSOBC_PLUGINDIR.'lib/breadcrumb.php';

			add_filter( 'wpssobc_load_lib', array( 'WpssoBcConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {
			if ( false === $ret && ! empty( $filespec ) ) {
				$filepath = WPSSOBC_PLUGINDIR.'lib/'.$filespec.'.php';
				if ( file_exists( $filepath ) ) {
					require_once $filepath;
					if ( empty( $classname ) ) {
						return SucomUtil::sanitize_classname( 'wpssobc'.$filespec, false );	// $underscore = false
					} else {
						return $classname;
					}
				}
			}
			return $ret;
		}
	}
}

