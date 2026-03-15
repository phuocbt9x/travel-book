<?php
/**
 * Review Model.
 *
 * @since 6.5.2
 */

namespace WPTravelEngine\Core\Models;

use WP_Comment;

/**
 * Class Review.
 *
 * @since 6.5.2
 */
#[\AllowDynamicProperties]
class Review {

	protected static ?Review $instance;

	protected int $comment_ID;

	protected int $comment_post_ID;

	protected string $comment_content = '';

	protected string $comment_author;

	protected string $comment_author_email;

	protected string $comment_approved;

	/**
	 * Constructor.
	 */
	public function __construct( WP_Comment $comment ) {
		foreach ( get_object_vars( $comment ) as $key => $value ) {
			$this->$key = $value;
		}
	}

	public static function instance( int $comment_id ): ?Review {

		$_comment = wp_cache_get( $comment_id, 'trip_reviews' );

		if ( $_comment instanceof Review ) {
			return $_comment;
		}

		$comment = get_comment( $comment_id );

		if ( ! $comment instanceof WP_Comment ) {
			return null;
		}

		$trip = get_post( $comment->comment_post_ID );

		if ( ! $trip || WP_TRAVEL_ENGINE_POST_TYPE !== $trip->post_type ) {
			return null;
		}

		$instance = new static( $comment );

		wp_cache_set( $comment_id, $instance, 'trip_reviews' );

		return $instance;
	}

	public function get_meta( string $key, $default = null ) {
		$meta = get_comment_meta( $this->comment_ID, $key, true );

		if ( empty( $meta ) ) {
			return $default;
		}

		return $meta;
	}

	public function get_rating(): int {
		return (int) $this->get_meta( 'stars', 0 );
	}

	public function get_content(): string {
		return wp_kses_post( $this->comment_content );
	}

	public function get_full_name(): string {
		return wp_kses_post( $this->comment_author );
	}

	public function get_email(): string {
		return sanitize_email( $this->comment_author_email );
	}

	public function get_ID(): int {
		return (int) $this->comment_ID;
	}

	public function get_trip_ID(): int {
		return (int) $this->comment_post_ID;
	}

	public function is_approved(): bool {
		return '1' === $this->comment_approved;
	}

	public function get_status(): string {
		return $this->is_approved() ? 'approved' : 'pending';
	}

	public function get_data(): array {
		return array(
			'id'        => $this->get_ID(),
			'full_name' => $this->get_full_name(),
			'email'     => $this->get_email(),
			'trip_name' => get_the_title( $this->get_trip_ID() ),
			'rating'    => $this->get_rating(),
			'content'   => $this->get_content(),
			'status'    => $this->get_status(),
		);
	}
}
