<?php
/**
 * Email Footer
 *
 * @since 6.5.0
 */
use WPTravelEngine\Core\Models\Settings\Options;

$settings = Options::get( 'wp_travel_engine_settings' );
$logo_url = $settings['email']['logo']['url'];
$footer   = $settings['email']['footer'];
?>
</td>
	</tr>
		</tbody>
		<tfoot>
			<tr>
				<td style="text-align: center;padding: 24px 0;">
					<img style="max-width: 100px;margin: 0 auto;height: auto;" src="<?php echo esc_url( $logo_url ); ?>" alt="">
					<p style="margin: 0;"><em><?php echo esc_html( $footer ); ?></em></p>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
