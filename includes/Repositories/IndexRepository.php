<?php

namespace SearchIndexerPlugin\Repositories;

use SearchIndexerPlugin\Index\Index;

/**
 * An in memory index repository to prevent duplicate indices and other utils
 */
class IndexRepository implements Repository {
	/**
	 * The list of indices currently active
	 *
	 * @var Index[]
	 */
	private $indices = array();

	/**
	 * Get an index by name
	 *
	 * @param string $key The key for identifying the index
	 *
	 * @return Index
	 *
	 * @throws \RuntimeException When the index is already in the repository.
	 */
	public function get( $key ) {
		if ( ! $this->has( $key ) ) {
			throw new \RuntimeException(
				sprintf( 'No index keyed "%s" is in the repository.', esc_xml( $key ) )
			);
		}

		return $this->indices[ $key ];
	}

	/**
	 * Function to check if we have the index in memory
	 *
	 * @param string $key The key for identifying the index
	 *
	 * @return bool
	 */
	public function has( $key ) {
		return isset( $this->indices[ $key ] );
	}

	/**
	 * Add an index
	 *
	 * @param string $key   The key for identifying the index
	 * @param Index  $index The index
	 *
	 * @throws \LogicException When the index is already present.
	 */
	public function add( $key, Index $index ) {
		if ( $this->has( $key ) ) {
			throw new \LogicException(
				sprintf(
					'An index keyed "%s" is already in the repository.',
					esc_xml( $key )
				)
			);
		}

		$this->indices[ $key ] = $index;
	}

	/**
	 * Get the indices in memory
	 *
	 * @return Index[]
	 */
	public function all() {
		return $this->indices;
	}
}
