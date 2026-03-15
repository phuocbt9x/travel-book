<?php
/**
 * WP Travel Engine Email class.
 *
 * @since 6.0.0
 */

namespace WPTravelEngine\Email;

/**
 * Email class.
 *
 * @since 6.0.0
 */
class Email extends TemplateTags {

	/**
	 * Email from.
	 *
	 * @var array
	 */
	protected array $from = array(
		'name'  => '',
		'email' => '',
	);

	/**
	 * Email to send with comma separated values.
	 *
	 * @var string
	 */
	protected string $to;

	/**
	 * Email Subject.
	 *
	 * @var string
	 */
	protected string $subject = '';

	/**
	 * Email message.
	 *
	 * @var string
	 * @since 6.5.0
	 */
	protected string $content = '';

	/**
	 * Email headers.
	 *
	 * @var array
	 */
	protected $headers = null;

	/**
	 * Email attachments.
	 *
	 * @var string|string[]
	 */
	protected $attachments = array();

	/**
	 * Email should use template if available.
	 *
	 * @var ?string Template Name.
	 * @since 6.5.0
	 */
	protected ?string $template = null;

	/**
	 * Email settings.
	 *
	 * @var null|array
	 * @since 6.5.0
	 */
	protected ?array $settings = null;

	/**
	 * Email content.
	 *
	 * @return $this
	 */
	public function set( string $property, $value ): Email {
		$this->{$property} = $value;

		return $this;
	}

	/**
	 * Add Headers.
	 *
	 * @param array $header Headers
	 *
	 * @return void
	 */
	public function add_headers( array $header = array() ): Email {

		if ( is_null( $this->headers ) ) {
			$this->headers = $this->get_headers();
		}

		$this->headers = array_merge( $this->headers, $header );
		return $this;
	}

	/**
	 * Get email headers.
	 *
	 * @return array
	 * @since 6.5.0
	 */
	public function get_headers(): array {
		return $this->headers ??= array(
			'charset'  => apply_filters( 'wp_travel_engine_mail_charset', 'Content-Type: text/html; charset=UTF-8; MIME-Version:1.0' ),
			'from'     => 'From: ' . $this->get_settings( 'name' ) . ' <' . $this->get_settings( 'from' ) . '>',
			'reply_to' => 'Reply-To: ' . $this->get_settings( 'reply_to' ),
		);
	}

	/**
	 * Get email property.
	 *
	 * @param string $property Property name.
	 *
	 * @return mixed
	 */
	public function get( string $property ) {

		if ( method_exists( $this, "get_$property" ) ) {
			return $this->{"get_$property"}();
		}

		return $this->{$property} ?? null;
	}

	/**
	 * Get email body.
	 *
	 * @param ?string $content Content.
	 * @param ?string $template Template.
	 *
	 * @return string
	 * @since 6.5.0
	 */
	protected function get_body( $content = null, $template = null ): string {

		$update_mail_template  = wptravelengine_toggled( get_option( 'wte_update_mail_template', false ) );
		$use_new_header_footer = ! in_array( $template ?? $this->template, array( 'booking_confirmation', 'payment_confirmation' ) ) || $update_mail_template;

		$body  = '';
		$body .= $use_new_header_footer ? wte_get_template_html( 'template-emails/email-header.php' ) : wte_get_template_html( 'emails/email-header.php' );
		$body .= $content ?? $this->content;
		$body .= $use_new_header_footer ? wte_get_template_html( 'template-emails/email-footer.php' ) : wte_get_template_html( 'emails/email-footer.php' );

		return $body;
	}

	/**
	 * Send email.
	 *
	 * @return mixed
	 * @updated 6.5.0
	 */
	public function send() {

		if ( empty( $this->content ) ) {
			return false;
		}

		$to          = array_map( 'trim', explode( ',', $this->get( 'to' ) ) );
		$subject     = $this->apply_tags( $this->get( 'my_subject' ) );
		$body        = $this->apply_tags( $this->get( 'body' ) );
		$headers     = $this->get( 'headers' );
		$attachments = $this->get( 'attachments' );

		foreach ( $to as $email ) {
			$result = wp_mail( $email, $subject, $body, $headers, $attachments );
		}

		return $result;
	}

	/**
	 * Get email settings.
	 *
	 * @param string|null $key Key.
	 *
	 * @return array|string|null
	 * @since 6.5.0
	 */
	public function get_settings( $key = null ) {
		$this->settings ??= wptravelengine_settings()->get( 'email', array() );

		return is_null( $key ) ? $this->settings : ( $this->settings[ $key ] ?? null );
	}

	/**
	 * Template preview.
	 *
	 * @param int    $payment_id Payment ID.
	 * @param string $email_template_type Email template type.
	 * @param string $to Recipient.
	 *
	 * @return void
	 * @since 6.5.0
	 */
	public function template_preview( $payment_id, $email_template_type = 'order', $to = 'customer' ) {

		! defined( 'WTE_EMAIL_TEMPLATE_PREVIEW' ) || define( 'WTE_EMAIL_TEMPLATE_PREVIEW', ! 0 );

		if ( + $payment_id === 0 ) {
			$settings = wptravelengine_settings();
			header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 200 OK' ); // phpcs:ignore
			$this->set( 'content', $settings->get( $to . '_email_notify_tabs.' . $email_template_type . '.content' ) );
			echo $this->get_body();
			exit;
		}
	}
}
