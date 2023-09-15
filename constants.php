<?php

define( 'SEARCH_INDEXER_PLUGIN_VERSION', '1.0.0' );
define( 'SEARCH_INDEXER_PLUGIN_NAME', 'search-indexer-plugin' );
define( 'SEARCH_INDEXER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SEARCH_INDEXER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SEARCH_INDEXER_PLUGIN_BUILD_DIR', plugin_dir_path( __FILE__ ) . 'build/' . SEARCH_INDEXER_PLUGIN_VERSION );
define( 'SEARCH_INDEXER_PLUGIN_BUILD_URL', plugin_dir_url( __FILE__ ) . 'build/' . SEARCH_INDEXER_PLUGIN_VERSION );

if ( ! defined( 'SEARCH_INDEXER_PLUGIN_TYPESENSE_SETTINGS_OPTION' ) ) {
	define( 'SEARCH_INDEXER_PLUGIN_TYPESENSE_SETTINGS_OPTION', 'sip-typesense-settings' );
}

if ( ! defined( 'SEARCH_INDEXER_PLUGIN_MEILI_SETTINGS_OPTION' ) ) {
	define( 'SEARCH_INDEXER_PLUGIN_MEILI_SETTINGS_OPTION', 'sip-meili-settings' );
}

if ( ! defined( 'SEARCH_INDEXER_PLUGIN_DEFAULT_INDEXER_OPTION' ) ) {
	define( 'SEARCH_INDEXER_PLUGIN_DEFAULT_INDEXER_OPTION', 'sip-default-indexer' );
}
