<?php
/**
 * Thank you Shortcode.
 *
 * @package WPTravelEngine
 * @since 6.0.0
 */

namespace WPTravelEngine\Core\Shortcodes;

use Exception;
use WPTravelEngine\Abstracts\Shortcode;
use WPTravelEngine\Assets;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Filters\ThankYouPageTemplate;

/**
 * Thank you Shortcode class.
 *
 * @since 6.0.0
 */
class ThankYou extends Shortcode {

	/**
	 * The shortcode tag.
	 *
	 * @var string $tag
	 */
	const TAG = 'WP_TRAVEL_ENGINE_THANK_YOU';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'body_class', array( $this, 'body_class' ) );
	}

	/**
	 * Get the default attributes for the shortcode.
	 *
	 * @return array The default attributes.
	 */
	protected function default_attributes(): array {
		return array(
			'legacy' => false,
		);
	}

	/**
	 * Add thank you body class.
	 */
	public function body_class( $classes ) {
		global $post;
		if ( is_object( $post ) ) {
			if ( has_shortcode( $post->post_content, 'WP_TRAVEL_ENGINE_THANK_YOU' ) ) {
				$classes[] = 'thank-you';
			}
		}

		return $classes;
	}

	public static function get_booking_details_html( $payment_id, $booking_id = null ) {
		if ( is_null( $booking_id ) ) {
			$booking_id = get_post_meta( $payment_id, 'booking_id', true );
		}
		do_action( 'wte_booking_cleanup', $payment_id, 'thankyou' );

		ob_start();
		wte_get_template(
			'thank-you/thank-you.php',
			array(
				'payment_id' => $payment_id,
				'booking_id' => $booking_id,
			)
		);

		return ob_get_clean();
	}

	/**
	 * Place order form shortcode callback function.
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function output( array $atts ): string {

		if ( is_admin() ) {
			return '';
		}

		$booking_id = null;
		$payment_id = null;
		if ( isset( $_GET['payment_key'] ) ) {

			$payment_key = sanitize_text_field( wp_unslash( $_GET['payment_key'] ) );

			try {
				$payment = Payment::from_payment_key( $payment_key );
				$booking = Booking::from_payment( $payment );

				if ( ! $atts['legacy'] ) {
					$thank_you_page_template = new ThankYouPageTemplate( $booking, $payment );
					$thank_you_page_template->hooks();

					Assets::instance()->enqueue_script( 'wte-popper' )
							->dequeue_script( 'wp-travel-engine' )
							->dequeue_style( 'wp-travel-engine' )
							->enqueue_script( 'wte-popper' )
							->enqueue_script( 'wte-tippyjs' )
							->enqueue_style( 'trip-thank-you' )
							->enqueue_script( 'trip-thank-you' );

					ob_start();
					do_action( 'wptravelengine_thankyou_before_content' );
					do_action( 'wptravelengine_thankyou_content' );
					do_action( 'wptravelengine_thankyou_after_content' );

					return ob_get_clean();
				}
				$booking_id = $booking->get_id();
				$payment_id = $payment->get_id();

			} catch ( Exception $e ) {
				wp_safe_redirect( home_url( '/404' ) );
				exit;
			}
		} else {
			$data = \WTE_Booking::get_callback_token_payload( 'thankyou' );
			if ( is_array( $data ) && isset( $data['bid'] ) ) {
				$booking_id = $data['bid'];
				$payment_id = $data['pid'];
			}
		}

		if ( ! $booking_id || ! $payment_id ) {
			return __( 'Thank you for booking the trip. Please check your email for confirmation.', 'wp-travel-engine' );
		}

		return self::get_booking_details_html( $payment_id, $booking_id );
	}
}
