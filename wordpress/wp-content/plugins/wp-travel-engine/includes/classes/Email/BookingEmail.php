<?php
/**
 * Booking Email.
 *
 * @since 6.0.0
 */

namespace WPTravelEngine\Email;

use WPTravelEngine\Booking\Email\Template_Tags;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Core\Models\Settings\PluginSettings;

/**
 * Booking Email.
 * Handles logic previously in WTE_Booking_Emails.
 * WTE_Booking_Emails is now an alias of this class.
 *
 * @since 6.0.0
 */
class BookingEmail extends Email {

	/**
	 * Payment object.
	 *
	 * @var Payment|null Payment object.
	 */
	public ?Payment $payment = null;

	/**
	 * Booking object.
	 *
	 * @var Booking|null Booking object.
	 */
	public ?Booking $booking = null;

	/**
	 * Recipient.
	 *
	 * @var string Recipient.
	 */
	public string $sendto = 'customer';

	/**
	 * Email template type.
	 *
	 * @var string Email template type.
	 */
	public string $email_template_type = 'order_confirmation';

	/**
	 * Magic method to handle calls to undefined methods.
	 *
	 * @param string $method The method name.
	 * @param array  $arguments The method arguments.
	 * @return mixed The result of the method call.
	 */
	public function __call( $method, $arguments ) {
		switch ( $method ) {
			case 'replace_content_tags':
				return $this->apply_tags( $arguments[0] );
			case 'get_template':
			case 'generate_email_template':
				return $this->get_body();
			default:
				throw new \BadMethodCallException( "Method '$method' not found in class '" . get_class( $this ) . "'" );
		}
	}

	/**
	 * @param mixed       $payment Payment ID.
	 * @param string|null $template Template name.
	 *
	 * @return $this
	 */
	public function prepare( $payment, ?string $template = null ): BookingEmail {

		$this->payment = new Payment( $payment );
		if ( $booking = $this->payment->get_booking() ) {
			$this->booking = $booking;
		}

		$this->email_template_type = $template;

		$this->template = wptravelengine_map_email_template( $template );

		$this->set_my_tags();

		return $this;
	}

	/**
	 * @param string|string[] $to Email address or 'admin|customer'
	 *
	 * @return $this
	 */
	public function to( string $to ): BookingEmail {

		if ( is_string( $to ) ) {
			$to = array( $to );
		}

		foreach ( $to as $value ) {
			if ( is_email( $value ) ) {
				$to[] = $value;
				continue;
			}

			$this->sendto = $value;

			switch ( $value ) {
				case 'admin':
					$this->to = $this->get_settings( 'emails' );
					break;
				case 'customer':
					$this->to = $this->booking->get_billing_info( 'email' );
					break;
			}
		}

		return $this;
	}

	/**
	 * Get email body.
	 *
	 * @return string
	 */
	protected function get_body( $content = null, $template = null ): string {
		return apply_filters( 'wptravelengine_generate_email_template', parent::get_body( $content, $template ), $this );
	}

	/**
	 * @return string
	 */
	public function get_my_subject(): string {
		$plugin_settings = new PluginSettings();
		$subject         = $plugin_settings->get( "{$this->sendto}_email_notify_tabs." . $this->template . '.subject', '' );
		$subject         = apply_filters( "wptravelengine_{$this->email_template_type}_email_template_subject_{$this->sendto}", $subject, $this );
		return $subject;
	}

	/**
	 * Sets booking email template tags.
	 *
	 * @param array $tags The tags.
	 *
	 * @return $this
	 */
	public function set_my_tags(): BookingEmail {
		$template_tags = new Template_Tags( $this->booking->ID, $this->payment->ID );
		$this->set_tags( $template_tags->get_email_tags() );
		return $this;
	}

	/**
	 *
	 * @return $this
	 */
	public function set_headers() {
		$this->add_headers(
			array(
				'reply_to' => 'Reply-To: ' . ( 'admin' === $this->sendto ? $this->booking->get_billing_info( 'email' ) : wptravelengine_settings()->get( 'email.reply_to' ) ),
			)
		);
		return $this;
	}

	/**
	 * Get email subject.
	 *
	 * @param string $email_template_type Email template type.
	 * @param string $to Recipient.
	 *
	 * @return string
	 */
	public static function get_subject( $email_template_type, $to ) {
		$options = array(
			'order_confirmation' => array(
				'admin'    => 'email.sale_subject',
				'customer' => 'email.subject',
			),
			'order'              => array(
				'admin'    => 'email.booking_notification_subject_admin',
				'customer' => 'email.booking_notification_subject_customer',
			),
		);
		$subject = wte_array_get( get_option( 'wp_travel_engine_settings', array() ), $options[ $email_template_type ][ $to ], '' );
		if ( ! empty( trim( $subject ) ) ) {
			return $subject;
		}
		$subjects = array(
			'order'              => array(
				'customer' => sprintf( __( 'Your booking for %1$s is confirmed – %2$s', 'wp-travel-engine' ), '{booked_trip_name}', '{booking_id}' ),
				'admin'    => sprintf( __( 'New Trip is Booked - %1$s - %2$s', 'wp-travel-engine' ), '{booked_trip_name}', '{booking_id}' ),
			),
			'order_confirmation' => array(
				'customer' => sprintf( __( 'Payment Received for %1$s – %2$s', 'wp-travel-engine' ), '{booked_trip_name}', '{booking_id}' ),
				'admin'    => sprintf( __( 'Payment has been received for %1$s', 'wp-travel-engine' ), '{booking_id}' ),
			),
			'due_payment'        => array(
				'customer' => sprintf( __( 'Your due payment of booking %1$s has been confirmed', 'wp-travel-engine' ), '{booking_id}' ),
				'admin'    => sprintf( __( 'Due payment has been received for booking %1$s', 'wp-travel-engine' ), '{booking_id}' ),
			),
		);

		return isset( $subjects[ $email_template_type ][ $to ] ) ? $subjects[ $email_template_type ][ $to ] : '';
	}

	/**
	 * Set content.
	 *
	 * @param string $content Content.
	 *
	 * @return $this
	 */
	public function set_content() {
		$plugin_settings = new PluginSettings();
		$content         = $plugin_settings->get( $this->sendto . '_email_notify_tabs.' . $this->template . '.content', '' );
		$content         = apply_filters( "wptravelengine_{$this->email_template_type}_email_template_content_{$this->sendto}", $content, $this );
		$this->set( 'content', $content );
		return $this;
	}

	/**
	 * Get email template content.
	 *
	 * @param string $email_template_type Email template type.
	 * @param string $template Template name.
	 * @param string $sendto Recipient.
	 * @param bool   $update Update template.
	 *
	 * @return string
	 */
	public static function get_template_content( $email_template_type = 'order', $template = '', $sendto = 'admin', $update = false ) {
		if ( ! $update ) {
			$settings  = get_option( 'wp_travel_engine_settings', array() );
			$templates = array(
				'order'              => array(
					'customer' => wte_array_get( $settings, 'email.booking_notification_template_customer', '' ),
					'admin'    => wte_array_get( $settings, 'email.booking_notification_template_admin', '' ),
				),
				'order_confirmation' => array(
					'customer' => wte_array_get( $settings, 'email.purchase_wpeditor', '' ),
					'admin'    => wte_array_get( $settings, 'email.sales_wpeditor', '' ),
				),
			);
		}

		$content = empty( $templates[ $email_template_type ][ $sendto ] ) ? '' : $templates[ $email_template_type ][ $sendto ];

		if ( ! empty( $content ) ) {
			return $content;
		}
		if ( empty( $template ) ) {
			switch ( $email_template_type ) {
				case 'order':
					$template = 'emails/booking/notification.php';
					break;
				case 'order_confirmation':
					$template = 'emails/booking/confirmation.php';
					break;
				default:
					$template = 'emails/booking/notification.php';
					break;
			}
		}

		$args = array(
			'sent_to' => $sendto,
			'strings' => self::get_strings( $email_template_type, $sendto ),
		);
		ob_start();
		wte_get_template( $template, $args );

		return ob_get_clean();
	}

	/**
	 * Get string.
	 *
	 * @param string $email_template_type Email template type.
	 * @param string $sendto Recipient.
	 * @param string $for String type.
	 *
	 * @since 6.5.0
	 * @return string
	 */
	public static function get_string( $email_template_type = 'order', $sendto = 'customer', $for = 'heading' ) {
		$strings = self::get_strings( $email_template_type, $sendto );

		return wte_array_get( $strings, "{$for}", '' );
	}

	/**
	 * Get strings.
	 *
	 * @param string $email_template_type Email template type.
	 * @param string $sentto Recipient.
	 *
	 * @since 6.5.0
	 * @return array
	 */
	private static function get_strings( $email_template_type, $sentto ) {
		$strings = apply_filters(
			'wte_booking_mail_strings',
			array(
				'order'              => array(
					'admin'    => array(
						'heading'         => __( 'Your Trip is Booked.', 'wp-travel-engine' ),
						'greeting'        => __( 'Dear Admin,', 'wp-travel-engine' ),
						'greeting_byline' => sprintf( __( 'Your trip <strong>%1$s</strong> is booked by <strong>%2$s</strong> on <strong>%3$s</strong>.<br>Here\'s the booked trip details:', 'wp-travel-engine' ), '{booked_trip_name}', '{customer_full_name}', '{trip_booked_date}' ),
						'footer'          => sprintf( __( 'Regards, <br>%1$s', 'wp-travel-engine' ), '{sitename}' ),
					),
					'customer' => array(
						'heading'         => __( 'Your Trip is Booked.', 'wp-travel-engine' ),
						'greeting'        => __( 'Dear {customer_first_name},', 'wp-travel-engine' ),
						'greeting_byline' => sprintf( __( 'Thank you for booking <strong>%1$s</strong> with us! <br> Your trip has been successfully booked on <strong>%2$s</strong> which starts on <strong>%3$s</strong>.<br> We\'re excited to have you on board.<br>Here\'s a quick summary of your booking:', 'wp-travel-engine' ), '{booked_trip_name}', '{trip_booked_date}', '{trip_start_date}' ),
						'footer'          => sprintf( __( 'If you have any questions, feel free to reach out. <br>Safe travels, <br>%1$s Team', 'wp-travel-engine' ), '{sitename}' ),
					),
				),
				'order_confirmation' => array(
					'admin'    => array(
						'heading'         => __( 'New Payment Received.', 'wp-travel-engine' ),
						'greeting'        => __( 'Dear Admin,', 'wp-travel-engine' ),
						'greeting_byline' => sprintf( __( 'The payment for the trip <strong>%1$s</strong>, booked by <strong>%2$s</strong> on <strong>%3$s</strong>, has been successfully received.<br>Here are the booked trip details:', 'wp-travel-engine' ), '{booked_trip_name}', '{customer_full_name}', '{trip_booked_date}' ),
						'footer'          => sprintf( __( 'Regards, <br>%1$s', 'wp-travel-engine' ), '{sitename}' ),
					),
					'customer' => array(
						'heading'         => __( 'Your booking has been confirmed.', 'wp-travel-engine' ),
						'greeting'        => __( 'Dear {customer_first_name},', 'wp-travel-engine' ),
						'greeting_byline' => sprintf( __( 'We\'ve received your payment for the trip <strong>%1$s</strong>. Thank you!<br>Here\'s a summary of your payment:', 'wp-travel-engine' ), '{booked_trip_name}' ),
						'footer'          => sprintf( __( 'We look forward to making your journey unforgettable!<br>Warm regards,<br>%1$s Team', 'wp-travel-engine' ), '{sitename}' ),
					),
				),
			)
		);

		if ( isset( $strings[ $email_template_type ][ $sentto ] ) ) {
			return $strings[ $email_template_type ][ $sentto ];
		}

		return array();
	}
}
