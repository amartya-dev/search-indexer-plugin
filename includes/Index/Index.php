<?php

namespace SearchIndexerPlugin\Index;

/**
 * Abstract index class
 */
abstract class Index {
	/**
	 * Get the index name
	 *
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * Delete the index.
	 */
	abstract public function delete();

	/**
	 * Remove all the records from the index.
	 */
	abstract public function clear();

	/**
	 * Get all available indexes
	 */
	abstract public function get_all_indexes();

	/**
	 * Abstract function to push all records for a post
	 *
	 * @param \WP_Post $post The wp post to push records for.
	 */
	abstract public function push_records_for_post( \WP_Post $post );

	/**
	 * Abstract function to delete all records for a post
	 *
	 * @param \WP_Post $post The wp post to push records for.
	 */
	abstract public function delete_records_for_post( \WP_Post $post );

	/**
	 * Get a list of all supported indexes and their corresponding options
	 *
	 * @return array
	 */
	public static function supported_indexes() {
		return array(
			'typesense'   => array(
				'settings_option' => SEARCH_INDEXER_PLUGIN_TYPESENSE_SETTINGS_OPTION,
				'index_class'     => 'SearchIndexerPlugin\\Index\\TypeSenseIndex',
			),
			'meilisearch' => array(
				'settings_option' => SEARCH_INDEXER_PLUGIN_MEILI_SETTINGS_OPTION,
				'index_class'     => 'SearchIndexerPlugin\\Index\\MeiliSearchIndex',
			),
		);
	}

	/**
	 * Push records
	 *
	 * @param int           $page           The page
	 * @param int           $per_page       Number of records per page
	 * @param callable|null $batch_callback The batch callback (optional)
	 *
	 * @return int the number of records pushed
	 */
	public function push_records( $page, $per_page, $batch_callback = null ) {
		$records_provider  = $this->get_records_provider();
		$total_pages_count = $records_provider->get_total_pages_count( $per_page );

		$records = $records_provider->getRecords( $page, $per_page );
		if ( count( $records ) > 0 ) {
			$this->add_documents_by_records( $records );
		}

		if ( is_callable( $batch_callback ) ) {
			call_user_func( $batch_callback, $records, $page, $total_pages_count );
		}

		return count( $records );
	}

	/**
	 * Push records for an id
	 *
	 * @param mixed $id The post id
	 *
	 * @return int
	 */
	public function push_records_for_id( $id ) {
		$records = $this->get_records_provider()->get_records_for_id( $id );
		if ( count( $records ) > 0 ) {
			$this->add_documents_by_records( $records );
		}

		return count( $records );
	}

	/**
	 * Push all records per page
	 *
	 * @param int           $per_page       Number of records per page
	 * @param callable|null $batch_callback Callback for batch (optional)
	 *
	 * @return int
	 */
	public function push_all_records( $per_page, $batch_callback = null ) {
		$records_provider    = $this->get_records_provider();
		$total_pages         = $records_provider->get_total_pages_count( $per_page );
		$total_records_count = 0;
		for ( $page = 1; $page <= $total_pages; ++$page ) {
			$total_records_count += $this->push_records( $page, $per_page, $batch_callback );
		}

		return $total_records_count;
	}

	/**
	 * Delete records by ids
	 *
	 * @param array $record_ids List of ids to delete records
	 *
	 * @return int
	 */
	public function delete_records_by_ids( array $record_ids ) {
		if ( empty( $record_ids ) ) {
			return 0;
		}
		$this->delete_documents_by_ids( $record_ids );

		return count( $record_ids );
	}

	/**
	 * RE index
	 *
	 * @param bool          $clear_existing_records Indicates if we need to clear records
	 * @param int           $per_page              Number of records per page
	 * @param callable|null $batch_callback        Batch callback (optional)
	 *
	 * @return int
	 */
	public function re_index( $clear_existing_records = true, $per_page = 500, $batch_callback = null ) {
		if ( true === (bool) $clear_existing_records ) {
			$this->clear();
		}

		return $this->push_all_records( $per_page, $batch_callback );
	}

	/**
	 * Get records from provider
	 *
	 * @return WpRecordsProvider
	 */
	abstract protected function get_records_provider();

	/**
	 * Get the index client
	 *
	 * @return Client
	 */
	abstract protected function get_client();

	/**
	 * Add documents to the index
	 *
	 * @param array $records The records to be added to the index.
	 */
	abstract public function add_documents_by_records( $records );

	/**
	 * Function to delete documents by a list of ids
	 *
	 * @param array $ids The list of ids.
	 */
	abstract public function delete_documents_by_ids( $ids );
}
