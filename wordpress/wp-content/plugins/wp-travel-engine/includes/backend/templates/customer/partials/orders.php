<?php
/**
 * Customer Orders.
 *
 * @var array $orders_data
 */
?>

<div class="wpte-form-section" data-target-id="orders">
	<div class="wpte-accordion">
		<div class="wpte-accordion-content">
			<h3 class="wpte-accordion-title"><?php esc_html_e( 'Orders', 'wp-travel-engine' ); ?></h3>
			<div class="wpte-table-wrap">
				<table class="wpte-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Order ID', 'wp-travel-engine' ); ?></th>
							<th><?php esc_html_e( 'Tour Name', 'wp-travel-engine' ); ?></th>
							<th style="width: 144px;"><?php esc_html_e( 'Total', 'wp-travel-engine' ); ?></th>
							<th style="width: 160px;"><?php esc_html_e( 'Date', 'wp-travel-engine' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $orders_data as $order ) : ?>
						<tr>
							<td><a href="<?php echo esc_url( get_edit_post_link( $order->ID, 'display' ) ); ?>"><?php echo esc_attr( '#' . $order->ID ); ?></a></td>
							<td><?php echo esc_attr( $order->get_trip_title() ); ?></td>
							<td><?php echo wp_travel_engine_get_currency_symbol( $order->get_currency() ) . esc_attr( $order->get_total() ); ?></td>
							<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $order->get_trip_datetime() ) ) ); ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
