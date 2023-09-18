<?php

namespace SearchIndexerPlugin\Index;

use SearchIndexerPlugin\Records\WpPostsRecordsProvider;
use SearchIndexerPlugin\Records\WpRecordsProvider;
use Typesense\Client;
use Symfony\Component\HttpClient\HttplugClient;

/**
 * The TypeSense Index class to manage type sense
 */
class TypeSenseIndex extends Index {
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
		$client                 = $details['client'];
		$this->name             = $details['name'];
		$this->client           = $client;
		$this->records_provider = $records_provider;
		$this->create_collection();
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
		$response = $this->get_collection()->documents->import(
			$records,
			array( 'action' => 'create' )
		);
	}

	/**
	 * Delete documents by ids
	 *
	 * @param array $ids The document ids.
	 */
	public function delete_documents_by_ids( $ids ) {
		$this->get_collection()->documents->delete(
			array( 'filter_by' => sprintf( 'id: [%s]', implode( ',', $ids ) ) )
		);
	}

	/**
	 * Delete the index
	 */
	public function delete() {
		$this->get_collection()->delete();
	}

	/**
	 * Clear the index i.e. delete all documents for this index
	 */
	public function clear() {
		$response = $this->get_collection()->documents->delete(
			array( 'filter_by' => 'comment_count:>=0' )
		);
		return $response;
	}

	/**
	 * Get all the available collections
	 */
	public function get_all_indexes() {
		return $this->client->collections->retrieve();
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
	 * A function to create the collection if it does not exist
	 */
	private function create_collection() {
		// Check if we do not have the collection then make it
		try {
			$this->client->collections[ $this->name ]->retrieve();
		} catch ( \Exception ) {
			// Create the collection here
			$this->client->collections->create(
				array(
					'name'   => $this->name,
					'fields' => array(
						array(
							'name' => '.*',
							'type' => 'auto',
						),
					),
				)
			);
		}
	}

	/**
	 * Get the current collection
	 */
	private function get_collection() {
		return $this->client->collections[ $this->name ];
	}

	/**
	 * Create a connection to the typesense client
	 *
	 * @throws \Exception In case the settings are not available.
	 */
	private function connect() {
		$index_settings = get_option(
			SEARCH_INDEXER_PLUGIN_TYPESENSE_SETTINGS_OPTION,
			null
		);
		if ( ! $index_settings ) {
			throw new \Exception( 'Could not load index settings', '404' );
		}
		$connection_information = $index_settings['connection'];
		return array(
			'client' => new Client(
				array(
					'api_key' => $connection_information['apiKey'],
					'nodes'   => array(
						array(
							'host'     => $connection_information['host'],
							'port'     => $connection_information['port'],
							'protocol' => $connection_information['protocol'],
						),
					),
					'client'  => new HttplugClient(),
				)
			),
			'name'   => $index_settings['index_name'],
		);
	}
}
