<?php
/**
 * Upcoming tours details controller.
 *
 * @since 6.5.0
 */
namespace WPTravelEngine\Core\Controllers\Ajax;

use WPTravelEngine\Abstracts\AjaxController;
use WPTravelEngine\Core\Models\Settings\Options;
use WPTravelEngine\Core\Models\Settings\PluginSettings;
use WPTravelEngine\Email\BookingEmail;

class UpdateMailTemplate extends AjaxController {

	const NONCE_KEY    = 'nonce';
	const NONCE_ACTION = 'wte_update_email_templates';
	const ACTION       = 'wte_update_email_templates';


	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Process request
	 */
	protected function process_request() {
		$plugin_settings = new PluginSettings();
		// Update Booking confirmation templates.
		$plugin_settings->set( 'email.booking_notification_template_admin', BookingEmail::get_template_content( 'order', 'template-emails/booking/notification.php', 'admin', true ) );
		$plugin_settings->set( 'admin_email_notify_tabs.booking_confirmation.content', BookingEmail::get_template_content( 'order', 'template-emails/booking/notification.php', 'admin', true ) );
		$plugin_settings->set( 'email.booking_notification_template_customer', BookingEmail::get_template_content( 'order', 'template-emails/booking/notification.php', 'customer', true ) );
		$plugin_settings->set( 'customer_email_notify_tabs.booking_confirmation.content', BookingEmail::get_template_content( 'order', 'template-emails/booking/notification.php', 'customer', true ) );

		// Update payment_confirmation templates.
		$plugin_settings->set( 'email.sales_wpeditor', BookingEmail::get_template_content( 'order_confirmation', 'template-emails/booking/confirmation.php', 'admin', true ) );
		$plugin_settings->set( 'admin_email_notify_tabs.payment_confirmation.content', BookingEmail::get_template_content( 'order_confirmation', 'template-emails/booking/confirmation.php', 'admin', true ) );
		$plugin_settings->set( 'email.purchase_wpeditor', BookingEmail::get_template_content( 'order_confirmation', 'template-emails/booking/confirmation.php', 'customer', true ) );
		$plugin_settings->set( 'customer_email_notify_tabs.payment_confirmation.content', BookingEmail::get_template_content( 'order_confirmation', 'template-emails/booking/confirmation.php', 'customer', true ) );
		$plugin_settings->save();
		Options::update( 'wte_update_mail_template', 'yes' );
		wp_send_json_success( array( 'message' => 'Your Mails Template have been Updated.' ) );
	}
}
