<?php

namespace SearchIndexerPlugin;

/**
 * Register the admin menu, assets etc.
 */
final class WP_Admin {
	/**
	 * Identifier for page and assets.
	 *
	 * @var string
	 */
	public static $slug = 'search-indexer-plugin';

	/**
	 * Tap WordPress Hooks
	 *
	 * @return void
	 */
	public function __construct() {
		\add_action( 'admin_menu', array( __CLASS__, 'register_admin_menu' ) );
		\add_action( 'admin_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
	}

	/**
	 * Register the admin menu for site migrator
	 */
	public static function register_admin_menu() {
		\add_menu_page(
			__( 'Search Indexer Plugin', 'search_indexer_plugin' ),
			__( 'Search Indexer Plugin', 'search_indexer_plugin' ),
			'manage_options',
			'search-indexer-plugin',
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Register built assets with WordPress
	 */
	public static function register_assets() {
		$asset_file = SEARCH_INDEXER_PLUGIN_BUILD_DIR . '/search-indexer-plugin.asset.php';

		if ( is_readable( $asset_file ) ) {
			$asset = include_once $asset_file;

			\wp_register_script(
				self::$slug,
				SEARCH_INDEXER_PLUGIN_BUILD_URL . '/search-indexer-plugin.js',
				array_merge( $asset['dependencies'], array() ),
				$asset['version'],
				true
			);

			\wp_register_style(
				self::$slug,
				SEARCH_INDEXER_PLUGIN_BUILD_URL . '/search-indexer-plugin.css',
				array(),
				$asset['version']
			);

			\wp_enqueue_style( self::$slug );
			\wp_enqueue_script( self::$slug );
		}
	}

	/**
	 * Render DOM element for React SPA mount.
	 *
	 * @return void
	 */
	public static function render_page() {
		echo PHP_EOL;
		echo '<!-- SEARCH:INDEXER:PLUGIN -->';
		echo PHP_EOL;
		echo '<div id="sip-app" class="sip-app"></div>';
		echo PHP_EOL;
		echo '<!-- /SEARCH:INDEXER:PLUGIN -->';
		echo PHP_EOL;
	}
}
