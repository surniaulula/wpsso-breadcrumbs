<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoBcConfig' ) ) {

	class WpssoBcConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssobc' => array(			// Plugin acronym.
					'version'     => '5.1.0',	// Plugin version.
					'opt_version' => '11',		// Increment when changing default option values.
					'short'       => 'WPSSO BC',	// Short plugin name.
					'name'        => 'WPSSO Schema Breadcrumbs Markup',
					'desc'        => 'Schema BreadcrumbList markup in JSON-LD format for Google Rich Results.',
					'slug'        => 'wpsso-breadcrumbs',
					'base'        => 'wpsso-breadcrumbs/wpsso-breadcrumbs.php',
					'update_auth' => '',		// No premium version.
					'text_domain' => 'wpsso-breadcrumbs',
					'domain_path' => '/languages',

					/*
					 * Required plugin and its version.
					 */
					'req' => array(
						'wpsso' => array(
							'name'          => 'WPSSO Core',
							'home'          => 'https://wordpress.org/plugins/wpsso/',
							'plugin_class'  => 'Wpsso',
							'version_const' => 'WPSSO_VERSION',
							'min_version'   => '17.8.0',
						),
					),

					/*
					 * URLs or relative paths to plugin banners and icons.
					 */
					'assets' => array(

						/*
						 * Icon image array keys are '1x' and '2x'.
						 */
						'icons' => array(
							'1x' => 'images/icon-128x128.png',
							'2x' => 'images/icon-256x256.png',
						),
					),

					/*
					 * Library files loaded and instantiated by WPSSO.
					 */
					'lib' => array(
						'submenu' => array(
							'breadcrumbs' => 'Breadcrumbs',
						),
					),
				),
			),

			/*
			 * Additional add-on setting options.
			 */
			'opt' => array(
				'defaults' => array(
					'bc_home_name'                      => 'Home',		// Site Home Page Name.
					'bc_wp_home_name'                   => 'Blog',		// WordPress Home Page Name.
					'bc_list_for_attachment'            => 'categories',
					'bc_list_for_download'              => 'categories',	// For Easy Digital Downloads.
					'bc_type_for_tc_events'             => 'categories',	// For Tickera.
					'bc_type_for_tribe_events'          => 'categories',	// For The Events Calendar.
					'bc_list_for_page'                  => 'ancestors',
					'bc_list_for_post'                  => 'categories',
					'bc_list_for_product'               => 'categories',	// For WooCommerce etc.
					'bc_list_for_question'              => 'categories',	// For WPSSO FAQ.
					'bc_list_for_tax_category'          => 'ancestors',
					'bc_list_for_tax_download_category' => 'ancestors',	// For Easy Digital Downloads.
					'bc_list_for_tax_faq_category'      => 'ancestors',	// For WPSSO FAQ.
					'bc_list_for_tax_link_category'     => 'ancestors',
					'bc_list_for_tax_post_tag'          => 'ancestors',
					'bc_list_for_tax_product_cat'       => 'ancestors',	// For WooCommerce etc.
				),
			),
		);

		public static function get_version( $add_slug = false ) {

			$info =& self::$cf[ 'plugin' ][ 'wpssobc' ];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function set_constants( $plugin_file ) {

			if ( defined( 'WPSSOBC_VERSION' ) ) {	// Define constants only once.

				return;
			}

			$info =& self::$cf[ 'plugin' ][ 'wpssobc' ];

			/*
			 * Define fixed constants.
			 */
			define( 'WPSSOBC_FILEPATH', $plugin_file );
			define( 'WPSSOBC_PLUGINBASE', $info[ 'base' ] );	// Example: wpsso-breadcrumbs/wpsso-breadcrumbs.php.
			define( 'WPSSOBC_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_file ) ) ) );
			define( 'WPSSOBC_PLUGINSLUG', $info[ 'slug' ] );	// Example: wpsso-breadcrumbs.
			define( 'WPSSOBC_URLPATH', trailingslashit( plugins_url( '', $plugin_file ) ) );
			define( 'WPSSOBC_VERSION', $info[ 'version' ] );

			/*
			 * Define variable constants.
			 */
			self::set_variable_constants();
		}

		public static function set_variable_constants( $var_const = null ) {

			if ( ! is_array( $var_const ) ) {

				$var_const = self::get_variable_constants();
			}

			/*
			 * Define the variable constants, if not already defined.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( ! defined( $name ) ) {

					define( $name, $value );
				}
			}
		}

		public static function get_variable_constants() {

			$var_const = array();

			$var_const[ 'WPSSOBC_SCHEMA_BREADCRUMB_SCRIPTS_MAX' ] = 10;

			/*
			 * Maybe override the default constant value with a pre-defined constant value.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( defined( $name ) ) {

					$var_const[ $name ] = constant( $name );
				}
			}

			return $var_const;
		}

		public static function require_libs( $plugin_file ) {

			require_once WPSSOBC_PLUGINDIR . 'lib/breadcrumb.php';
			require_once WPSSOBC_PLUGINDIR . 'lib/compat.php';	// Third-party plugin and theme compatibility actions and filters.
			require_once WPSSOBC_PLUGINDIR . 'lib/filters.php';
			require_once WPSSOBC_PLUGINDIR . 'lib/functions.php';
			require_once WPSSOBC_PLUGINDIR . 'lib/register.php';

			add_filter( 'wpssobc_load_lib', array( __CLASS__, 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $success = false, $filespec = '', $classname = '' ) {

			if ( false !== $success ) {

				return $success;
			}

			if ( ! empty( $classname ) ) {

				if ( class_exists( $classname ) ) {

					return $classname;
				}
			}

			if ( ! empty( $filespec ) ) {

				$file_path = WPSSOBC_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $file_path ) ) {

					require_once $file_path;

					if ( empty( $classname ) ) {

						return SucomUtil::sanitize_classname( 'wpssobc' . $filespec, $allow_underscore = false );
					}

					return $classname;
				}
			}

			return $success;
		}
	}
}
