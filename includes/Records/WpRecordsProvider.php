<?php

namespace SearchIndexerPlugin\Records;

/**
 * An abstract class implementing records provider for WordPress Records
 */
abstract class WpRecordsProvider implements RecordsProvider {
	/**
	 * Function to get the total page count
	 *
	 * @param int $per_page The number of records to be included per page
	 * @return int
	 */
	public function get_total_pages_count( $per_page ) {
		$results = $this->new_query( array( 'posts_per_page' => (int) $per_page ) );
		return (int) $results->max_num_pages;
	}

	/**
	 * Function to get the records for a particular page
	 *
	 * @param int $page     the page number
	 * @param int $per_page the number of records to be included per page
	 */
	public function get_records( $page, $per_page ) {
		$query = $this->new_query(
			array(
				'posts_per_page' => $per_page,
				'paged'          => $page,
			)
		);

		return $this->get_records_for_query( $query );
	}

	/**
	 * Function to get records for an id
	 *
	 * @param  mixed $id The blog post id
	 * @return array
	 */
	public function get_records_for_id( $id ) {
		$post = get_post( $id );

		if ( ! $post instanceof \WP_Post ) {
			return array();
		}

		return $this->get_records_for_post( $post );
	}

	/**
	 * Function to get records for a post
	 *
	 * @param \WP_Post $post the WordPress post
	 * @return array
	 */
	abstract public function get_records_for_post( \WP_Post $post );

	/**
	 * Function to get records for a query
	 *
	 * @param \WP_Query $query the WordPress query
	 * @return array
	 */
	private function get_records_for_query( \WP_Query $query ) {
		$records = array();
		foreach ( $query->posts as $post ) {
			$post = get_post( $post );
			if ( ! $post instanceof \WP_Post ) {
				continue;
			}
			$records = array_merge( $records, $this->get_records_for_post( $post ) );
		}
		return $records;
	}

	/**
	 * Function to run a WordPress query
	 *
	 * @param array $args the query args
	 * @return \WP_Query
	 */
	private function new_query( array $args = array() ) {
		$default_args = $this->get_default_query_args();
		$args         = array_merge( $default_args, $args );
		$query        = new \WP_Query( $args );
		return $query;
	}

	/**
	 * Function to get the default query args, to be extended by the class using
	 *
	 * @return array
	 */
	abstract protected function get_default_query_args();
}
