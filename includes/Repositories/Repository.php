<?php

namespace SearchIndexerPlugin\Repositories;

/**
 * The base class for repositories
 */
interface Repository {
	/**
	 * Function to get an item based on the key
	 *
	 * @param string $key the query key
	 *
	 * @return Index
	 */
	public function get( $key );

	/**
	 * Function to check if a value exists for a given key
	 *
	 * @param string $key the query key
	 *
	 * @return bool
	 */
	public function has( $key );
}
