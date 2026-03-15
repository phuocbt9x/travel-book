<?php
/**
 * Admin Email Notification Settings.
 *
 * @since 6.5.0
 */

use WPTravelEngine\Core\Models\Settings\Options;

$subject_email_tags = apply_filters(
	'wptravelengine_email_subject_tags',
	array(
		'{sitename}'            => __( 'Your site name', 'wp-travel-engine' ),
		'{customer_first_name}' => __( 'The customer\'s first name.', 'wp-travel-engine' ),
		'{customer_full_name}'  => __( 'The customer\'s full name.', 'wp-travel-engine' ),
		'{booked_trip_name}'    => __( 'The name of the trip booked.', 'wp-travel-engine' ),
		'{booking_id}'          => __( 'The booking order ID', 'wp-travel-engine' ),
		'{payment_id}'          => __( 'The payment ID of trip.', 'wp-travel-engine' ),
	)
);

$common_email_tags = apply_filters(
	'wptravelengine_common_email_tags',
	array(
		'{sitename}'            => __( 'Your site name', 'wp-travel-engine' ),
		'{customer_first_name}' => __( 'The customer\'s first name.', 'wp-travel-engine' ),
		'{customer_full_name}'  => __( 'The customer\'s full name.', 'wp-travel-engine' ),
		'{customer_last_name}'  => __( 'The customer\'s last name.', 'wp-travel-engine' ),
		'{customer_email}'      => __( 'The customer\'s email address.', 'wp-travel-engine' ),
		'{ip_address}'          => __( 'The buyer\'s IP Address', 'wp-travel-engine' ),
		'{site_admin_email}'    => __( 'The site admin email address.', 'wp-travel-engine' ),
	)
);

$customer_email_tags = apply_filters(
	'wptravelengine_customer_email_template_tags',
	array_merge(
		$common_email_tags,
		array(
			'{password_reset_link}' => __( 'The link to reset the password.', 'wp-travel-engine' ),
		)
	)
);

$all_email_tags = apply_filters(
	'wptravelengine_booking_email_tags',
	array_merge(
		$common_email_tags,
		array(
			'{booked_trip_name}'          => __( 'The name of the trip booked.', 'wp-travel-engine' ),
			'{trip_url}'                  => __( 'The trip URL for each booked trip', 'wp-travel-engine' ),
			'{trip_code}'                 => __( 'The trip code for each booked trip', 'wp-travel-engine' ),
			'{name}'                      => __( 'The buyer\'s first name', 'wp-travel-engine' ),
			'{fullname}'                  => __( 'The buyer\'s full name, first and last', 'wp-travel-engine' ),
			'{user_email}'                => __( 'The buyer\'s email address', 'wp-travel-engine' ),
			'{billing_address}'           => __( 'The buyer\'s billing address', 'wp-travel-engine' ),
			'{city}'                      => __( 'The buyer\'s city', 'wp-travel-engine' ),
			'{country}'                   => __( 'The buyer\'s country', 'wp-travel-engine' ),
			'{tdate}'                     => __( 'The starting date of the trip', 'wp-travel-engine' ),
			'{date}'                      => __( 'The trip booking date', 'wp-travel-engine' ),
			'{traveler}'                  => __( 'The total number of traveller(s)', 'wp-travel-engine' ),
			'{tprice}'                    => __( 'The trip price', 'wp-travel-engine' ),
			'{price}'                     => __( 'The total payment made of the booking', 'wp-travel-engine' ),
			'{total_cost}'                => __( 'The total price of the booking', 'wp-travel-engine' ),
			'{due}'                       => __( 'The due balance', 'wp-travel-engine' ),
			'{booking_url}'               => __( 'The trip booking link', 'wp-travel-engine' ),
			'{booking_details}'           => __( 'The booking details: Booked trips, Extra Services, Traveller details etc', 'wp-travel-engine' ),
			'{traveler_data}'             => __( 'The traveller details: Traveller details and Emergency Contact Details', 'wp-travel-engine' ),
			'{payment_method}'            => __( 'Payment Method used to checkout.', 'wp-travel-engine' ),
			'{trip_booking_details}'      => __( 'The trip booking & Payment details.', 'wp-travel-engine' ),
			'{trip_booking_summary}'      => __( 'The trip booking summary.', 'wp-travel-engine' ),
			'{trip_payment_details}'      => __( 'The trip payment details.', 'wp-travel-engine' ),
			'{trip_booked_date}'          => __( 'The date and time when the trip was booked.', 'wp-travel-engine' ),
			'{trip_start_date}'           => __( 'The start date of the trip.', 'wp-travel-engine' ),
			'{trip_end_date}'             => __( 'The end date of the trip.', 'wp-travel-engine' ),
			'{no_of_travellers}'          => __( 'The total number of travellers.', 'wp-travel-engine' ),
			'{trip_total_price}'          => __( 'The total price of the trip.', 'wp-travel-engine' ),
			'{trip_paid_amount}'          => __( 'The amount paid by the customer.', 'wp-travel-engine' ),
			'{trip_due_amount}'           => __( 'The due amount for the trip.', 'wp-travel-engine' ),
			'{payment_id}'                => __( 'The payment ID of trip.', 'wp-travel-engine' ),
			'{booking_id}'                => __( 'The booking order ID', 'wp-travel-engine' ),
			'{billing_details}'           => __( 'The billing details filled in new checkout template.', 'wp-travel-engine' ),
			'{traveller_details}'         => __( 'The traveler\'s details filled in new checkout template.', 'wp-travel-engine' ),
			'{emergency_details}'         => __( 'The emergency contact details filled in new checkout template.', 'wp-travel-engine' ),
			'{additional_note}'           => __( 'The additional note filled in new checkout template.', 'wp-travel-engine' ),
			'{bank_details}'              => __( 'Banks Accounts Details. This tag will be replaced with the bank details and sent to the customer receipt email when Bank Transfer method has been chosen by the customer.', 'wp-travel-engine' ),
			'{check_payment_instruction}' => __( 'Instructions to make check payment.', 'wp-travel-engine' ),
			'{trip_extra_fee}'            => __( 'The extra fee for the trip.', 'wp-travel-engine' ),
		)
	)
);

return apply_filters(
	'emails-notification',
	array(
		'title'  => __( 'Notifications', 'wp-travel-engine' ),
		'order'  => 1,
		'id'     => 'emails_notification',
		'icon'   => 'email',
		'fields' => apply_filters(
			'emails-notification-fields',
			array(
				array(
					'field_type' => 'EMAILS_NOTIFICATION',
					'name'       => 'email_notification',
					'data'       => array(
						'all_email_tags'        => $all_email_tags,
						'customer_email_tags'   => $customer_email_tags,
						'subject_email_tags'    => $subject_email_tags,
						'nonce_test_mail'       => wp_create_nonce( 'wptravelengine_test_email_nonce' ),
						'nonce_update_template' => wp_create_nonce( 'wte_update_email_templates' ),
						'updated_template'      => wptravelengine_toggled( Options::get( 'wte_update_mail_template' ) ),
					),
				),
			)
		),
	),
);
