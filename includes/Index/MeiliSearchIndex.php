<?php

namespace SearchIndexerPlugin\Index;

use SearchIndexerPlugin\Records\WpPostsRecordsProvider;
use Meilisearch\Client;
use Meilisearch\Contracts\IndexesQuery;

/**
 * The MeiliSearch Index.
 */
class MeiliSearchIndex extends Index {
	/**
	 * The index name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The index client
	 *
	 * @var Client
	 */
	private $client;

	/**
	 * The records provider
	 *
	 * @var WpRecordsProvider
	 */
	private $records_provider;

	/**
	 * Initialize the required variables
	 */
	public function __construct() {
		$records_provider       = new WpPostsRecordsProvider();
		$details                = $this->connect();
		$this->name             = $details['name'];
		$this->client           = $details['client'];
		$this->records_provider = $records_provider;
		$this->create_index();
	}

	/**
	 * Get the index name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Delete records fro the post
	 *
	 * @param \WP_Post $post The WordPress post
	 */
	public function delete_records_for_post( \WP_Post $post ) {
		$records    = $this->records_provider->get_records_for_post( $post );
		$record_ids = array();

		foreach ( $records as $record ) {
			if ( ! isset( $record['id'] ) ) {
				continue;
			}

			$record_ids[] = $record['id'];
		}

		if ( empty( $record_ids ) ) {
			return;
		}

		$this->delete_documents_by_ids( $record_ids );
	}

	/**
	 * Push index records for the post
	 *
	 * @param \WP_Post $post The WordPress post
	 *
	 * @return int
	 */
	public function push_records_for_post( \WP_Post $post ) {
		$records             = $this->records_provider->get_records_for_post( $post );
		$total_records_count = count( $records );

		if ( empty( $total_records_count ) ) {
			return 0;
		}

		$this->add_documents_by_records( $records );

		return $total_records_count;
	}

	/**
	 * Get the records provider
	 *
	 * @return RecordsProvider
	 */
	public function get_records_provider() {
		return $this->records_provider;
	}

	/**
	 * Add documents by records
	 *
	 * @param array $records The records to be inserted in the index
	 */
	public function add_documents_by_records( $records ) {
		$response = $this->get_index()->addDocuments( $records );
	}

	/**
	 * Delete documents by ids
	 *
	 * @param array $ids The document ids.
	 */
	public function delete_documents_by_ids( $ids ) {
		$this->get_index()->deleteDocuments( $ids );
	}

	/**
	 * Delete the index
	 */
	public function delete() {
		$this->client->deleteIndex( $this->name );
	}

	/**
	 * Get the index
	 */
	private function get_index() {
		return $this->client->index( $this->name );
	}

	/**
	 * Clear the index i . e . delete all documents for this index
	 */
	public function clear() {
		$this->get_index()->deleteAllDocuments();
	}

	/**
	 * Get all the available collections
	 */
	public function get_all_indexes() {
		return $this->client->getIndexes( new IndexesQuery() );
	}

	/**
	 * Get the TypeSense Client
	 *
	 * @return Client
	 */
	protected function get_client() {
		return $this->client;
	}

	/**
	 * Create a connection to the meilisearch client
	 *
	 * @throws \Exception In case the settings are not available.
	 */
	private function connect() {
		$index_settings = get_option( SEARCH_INDEXER_PLUGIN_MEILI_SETTINGS_OPTION );
		if ( ! $index_settings ) {
			throw new \Exception( 'Could not load index settings', '404' );
		}
		$connection_information = $index_settings['connection'];
		return array(
			'client' => new Client( $connection_information['host'], $connection_information['apiKey'] ),
			'name'   => $index_settings['index_name'],
		);
	}

	/**
	 * A function to create the index if it does not exist
	 */
	private function create_index() {
		try {
			$this->client->index( $this->name )->fetchRawInfo();
		} catch ( \Exception ) {
			$this->client->createIndex( $this->name );
		}
	}
}
