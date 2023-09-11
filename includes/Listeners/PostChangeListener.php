<?php

namespace SearchIndexerPlugin\Listeners;

use SearchIndexerPlugin\Index\Index;

/**
 * Listen to post changes and auto index them
 */
class PostChangeListener {
	/**
	 * The post index instance
	 *
	 * @var Index
	 */
	private $index;

	/**
	 * Declare the post type
	 *
	 * @var string
	 */
	private $post_type = 'post';

	/**
	 * Constructor, adds the hooks, accepts the index
	 *
	 * @param Index $index the index
	 */
	public function __construct( Index $index ) {
		$this->index = $index;
		\add_action( 'save_post', array( $this, 'push_records' ), 10, 2 );
		\add_action( 'before_delete_post', array( $this, 'delete_records' ) );
		\add_action( 'wp_trash_post', array( $this, 'delete_records' ) );
	}

	/**
	 * Push records to the index.
	 *
	 * @param int      $post_id The post id
	 * @param \WP_Post $post    The WordPress post
	 */
	public function push_records( $post_id, $post ) {
		if ( \wp_is_post_autosave( $post ) || \wp_is_post_revision( $post ) ) {
			return;
		}

		if ( $this->post_type !== $post->post_type ) {
			return;
		}

		if ( 'publish' !== $post->post_status || ! empty( $post->post_password ) ) {
			return $this->delete_records( $post_id );
		}

		$this->index->push_records_for_post( $post );
	}

	/**
	 * Delete records for a post
	 *
	 * @param int $post_id the WordPress post id.
	 */
	public function delete_records( $post_id ) {
		$post = \get_post( $post_id );
		if ( $post instanceof \WP_Post && $post->post_type === $this->post_type ) {
			$this->index->delete_records_for_post( $post );
		}
	}
}
