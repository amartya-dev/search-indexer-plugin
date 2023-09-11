<?php

namespace SearchIndexerPlugin;

use SearchIndexerPlugin\Index\Index;
use SearchIndexerPlugin\Listeners\PostChangeListener;
use SearchIndexerPlugin\Repositories\IndexRepository;

/**
 * Set up the listeners for posts in WordPress to auto-index
 */
class SetupListeners {
	public function __construct() {
		\add_action(
			'plugins_loaded',
			function () {
				$index_repository = new IndexRepository();
				$default_index    = get_option( SEARCH_INDEXER_PLUGIN_DEFAULT_INDEXER_OPTION, null );
				if ( ! $default_index ) {
					return;
				}
				$available_indexes = Index::supported_indexes();
				if ( array_key_exists( $default_index, $available_indexes ) ) {
					// Check if we already have the index in the repository else load
					if ( $index_repository->has( $default_index ) ) {
						$index_instance = $index_repository->get( $default_index );
					} else {
						$index_instance = new $available_indexes[ $default_index ]['index_class']();
                        $index_repository->add( $default_index, $index_instance );
					}
                    // Listen to the post changes now
                    new PostChangeListener( $index_instance );
				}
			}
		);
	}
}
