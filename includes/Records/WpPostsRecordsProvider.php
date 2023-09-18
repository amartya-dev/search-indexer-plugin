<?php

namespace SearchIndexerPlugin\Records;

use WP_Post;

/**
 * Extends the abstract wp records to work with posts
 */
class WpPostsRecordsProvider extends WpRecordsProvider {
	/**
	 * Function to get the records for a post
	 *
	 * @param \WP_Post $post the WordPress post
	 * @return array
	 */
	public function get_records_for_post( WP_Post $post ) {
		$user = get_userdata( $post->post_author );
		if ( $user instanceof \WP_User ) {
			$user_data = array(
				'raw'          => $user->user_login,
				'login'        => $user->user_login,
				'display_name' => $user->display_name,
				'id'           => $user->ID,
			);
		} else {
			$user_data = array(
				'raw'          => '',
				'login'        => '',
				'display_name' => '',
				'id'           => '',
			);
		}
		$post_date         = $post->post_date;
		$post_date_gmt     = $post->post_date_gmt;
		$post_modified     = $post->post_modified;
		$post_modified_gmt = $post->post_modified_gmt;
		$post_likes        = 0;
		$post_dislikes     = 0;
		$comment_count     = absint( $post->comment_count );
		$comment_status    = absint( $post->comment_status );
		$ping_status       = absint( $post->ping_status );
		$menu_order        = absint( $post->menu_order );

		if ( function_exists( 'get_field' ) ) {
			$likes_value = get_field( 'likes', $post->ID );
			if ( $likes_value ) {
				$post_likes = $likes_value;
			}

			$dislikes_value = get_field( 'dislikes', $post->ID );
			if ( $dislikes_value ) {
				$post_dislikes = $dislikes_value;
			}
		}

		$record = array(
			'id'                => strval( $post->ID ),
			'post_author'       => $user_data,
			'post_date'         => $post_date,
			'post_date_gmt'     => $post_date_gmt,
			'post_title'        => $this->prepare_text_context( get_the_title( $post->ID ) ),
			'post_excerpt'      => $this->prepare_text_context( $post->post_excerpt ),
			'post_content'      => $this->prepare_text_context(
				\apply_filters( 'the_content', $post->post_content )
			),
			'post_status'       => $post->post_status,
			'post_name'         => $post->post_name,
			'post_modified'     => $post_modified,
			'post_modified_gmt' => $post_modified_gmt,
			'post_parent'       => $post->post_parent,
			'post_type'         => $post->post_type,
			'post_mime_type'    => $post->post_mime_type,
			'post_likes'        => $post_likes,
			'post_dislikes'     => $post_dislikes,
			'permalink'         => get_permalink( $post->ID ),
			'comment_count'     => $comment_count,
			'comment_status'    => $comment_status,
			'ping_status'       => $ping_status,
			'menu_order'        => $menu_order,
		);

		// Retrieve featured image.
		$featured_image           = \get_the_post_thumbnail_url( $post, 'post-thumbnail' );
		$record['featured_image'] = $featured_image ? $featured_image : '';

		// Retrieve tags.
		$tags           = \wp_get_post_tags( $post->ID );
		$record['tags'] = \wp_list_pluck( $tags, 'name' );

		return array( $record );
	}

	/**
	 * Function to get the default query args
	 *
	 * @return array
	 */
	protected function get_default_query_args() {
		return array(
			'post_type'   => 'post',
			'post_status' => 'publish',
		);
	}

	/**
	 * Function to parse and store the content
	 *
	 * @param  string $content The content of the post
	 * @return string
	 */
	private function prepare_text_context( $content ) {
		$content = preg_replace( '#[\n\r]+#s', ' ', $content );

		return $content;
	}
}
