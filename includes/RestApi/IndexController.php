<?php

namespace SearchIndexerPlugin\RestApi;

use SearchIndexerPlugin\Index\Index;

/**
 * The index options
 */
class IndexController extends \WP_REST_Controller {
	/**
	 * The namespace of the rest routes
	 *
	 * @var string
	 */
	protected $namespace = 'search-indexer-plugin/v1';

	/**
	 * The controllers base
	 *
	 * @var string
	 */
	protected $rest_base = 'index';

	/**
	 * Register the routes
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/settings/(?P<index_name>[a-zA-Z0-9-]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_index_settings' ),
				'permission_callback' => array( $this, 'check_permission' ),
				'args'                => array(
					'index_name' => array(
						'required' => true,
						'type'     => 'string',
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/save',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'save_index_settings' ),
				'args'                => array(
					'index_name' => array(
						'required' => true,
						'type'     => 'string',
					),
					'settings'   => array(
						'required' => true,
					),
				),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/default',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_default_index' ),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/default',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'set_default_index' ),
				'args'                => array(
					'index_name' => array(
						'required' => true,
						'type'     => 'string',
					),
				),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/list/(?P<index_name>[a-zA-Z0-9-]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'list_indexes' ),
				'permission_callback' => array( $this, 'check_permission' ),
				'args'                => array(
					'index_name' => array(
						'required' => true,
						'type'     => 'string',
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/re-index',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 're_index' ),
				'args'                => array(
					'index_name' => array(
						'required' => true,
						'type'     => 'string',
					),
				),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);
	}

	/**
	 * Get the index settings
	 *
	 * @param \WP_REST_Request $request The Request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_index_settings( $request ) {
		$index_name        = $request->get_param( 'index_name' );
		$available_indexes = Index::supported_indexes();

		// Check if the requested index exists in the predefined settings
		if ( array_key_exists( $index_name, $available_indexes ) ) {
			$settings = get_option( $available_indexes[ $index_name ]['settings_option'], array() );
			return rest_ensure_response(
				array(
					'status'   => 200,
					'settings' => $settings,
				)
			);
		} else {
			return new \WP_Error( 'invalid_index', 'Invalid index name', array( 'status' => 404 ) );
		}
	}

	/**
	 * Store the index settings
	 *
	 * @param \WP_REST_Request $request The Request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function save_index_settings( $request ) {
		$available_indexes = Index::supported_indexes();
		$index_name        = $request->get_param( 'index_name' );
		if ( array_key_exists( $index_name, $available_indexes ) ) {
			update_option(
				$available_indexes[ $index_name ]['settings_option'],
				$request->get_param( 'settings' )
			);
			return rest_ensure_response(
				array(
					'status'  => 200,
					'message' => 'Updated Index Settings',
				)
			);
		}
		return new \WP_Error(
			'invalid_index',
			'Invalid index type',
			array(
				'status' => 404,
			)
		);
	}

	/**
	 * Set the default index
	 *
	 * @param \WP_REST_Request $request The Request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_default_index( $request ) {
		$default_index = get_option( SEARCH_INDEXER_PLUGIN_DEFAULT_INDEXER_OPTION, null );
		if ( $default_index ) {
			return rest_ensure_response(
				array(
					'status'  => 200,
					'default' => $default_index,
				)
			);
		}
		return new \WP_Error( 'not_set', 'default index not set yet', array( 'status' => 404 ) );
	}

	/**
	 * Set the default index
	 *
	 * @param \WP_REST_Request $request The Request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function set_default_index( $request ) {
		$available_indexes = Index::supported_indexes();
		$index_name        = $request->get_param( 'index_name' );
		if ( array_key_exists( $index_name, $available_indexes ) ) {
			update_option( SEARCH_INDEXER_PLUGIN_DEFAULT_INDEXER_OPTION, $index_name );
			return rest_ensure_response(
				array(
					'status'  => 200,
					'message' => 'Updated Default Index',
				)
			);
		}
		return new \WP_Error( 'invalid_index', 'Invalid index type', array( 'status' => 404 ) );
	}

	/**
	 * Get all indexes
	 *
	 * @param \WP_REST_Request $request The Request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function list_indexes( $request ) {
		$available_indexes = Index::supported_indexes();
		$index_name        = $request->get_param( 'index_name' );
		if ( array_key_exists( $index_name, $available_indexes ) ) {
			$index_instance = new $available_indexes[ $index_name ]['index_class']();
			return rest_ensure_response(
				array(
					'status'  => 200,
					'indexes' => $index_instance->get_all_indexes(),
				)
			);
		}
		return new \WP_Error(
			'invalid_index',
			'Invalid index type',
			array(
				'status' => 404,
			)
		);
	}

	/**
	 * Re index the requested index
	 *
	 * @param \WP_REST_Request $request The Request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function re_index( $request ) {
		$available_indexes = Index::supported_indexes();
		$index_name        = $request->get_param( 'index_name' );
		if ( array_key_exists( $index_name, $available_indexes ) ) {
			$index_instance = new $available_indexes[ $index_name ]['index_class']();
			$index_instance->re_index();
			return rest_ensure_response(
				array(
					'status'  => 200,
					'message' => 'Re-Indexed',
				)
			);
		}
		return new \WP_Error( 'invalid_index', 'Invalid index type', array( 'status' => 404 ) );
	}

	/**
	 * Check permissions for routes.
	 *
	 * @return bool|WP_Error
	 */
	public function check_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error(
				'rest_forbidden_context',
				'Sorry, you are not allowed to access this endpoint.',
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}
}
