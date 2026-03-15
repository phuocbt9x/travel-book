<?php
/**
 * Helper functions.
 *
 * @since 5.8.3
 */

namespace WPTravelEngine\Blocks;

class Helpers {
	private static $instance = null;

	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new Helpers();
		}
		return self::$instance;
	}

	public static function generate_random_string(): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return sprintf( '%05s', substr( str_shuffle( str_repeat( $characters, ceil( 5 / strlen( $characters ) ) ) ), 1, 5 ) );
	}

	/**
	 * Generates a unique ID string with a prefix.
	 *
	 * @param string $prefix The prefix to append to the generated unique ID.
	 *
	 * @return string The generated unique ID.
	 */
	public static function unique_id( string $prefix = '', $sections = 3 ): string {
		return $prefix . implode( '-', array_map( array( __CLASS__, 'generate_random_string' ), range( 1, $sections ) ) );
	}

	/**
	 * FSD Date Format.
	 *
	 * @param array  $sorted_fsd
	 * @param string $date_format
	 * @return array
	 */
	public static function fsd_date_format( $sorted_fsd, $date_format, $custom_date_format ) {
		$keys                 = 0;
		$today                = gmdate( 'Y-m-d' );
		$dates_data           = array();
		$availability_options = class_exists( '\WTE_Fixed_Starting_Dates_Functions' ) ? \WTE_Fixed_Starting_Dates_Functions::availability() : array();
		foreach ( $sorted_fsd as $key => $fsd ) {
			if ( strtotime( $today ) <= strtotime( $fsd['start_date'] ) ) {
				$content_id = isset( $fsd['content_id'] ) ? $fsd['content_id'] : '';
				$startDate  = new \DateTime( $fsd['start_date'] );
				$endDate    = new \DateTime( $fsd['end_date'] );
				if ( $date_format == 'custom' ) {
					$start_date = $startDate->format( $custom_date_format );
					$end_date   = $endDate->format( $custom_date_format );
				} else {
					$start_date = $startDate->format( $date_format );
					$end_date   = $endDate->format( $date_format );
				}
				$availability            = isset( $fsd['availability'] ) ? $fsd['availability'] : 'guaranteed';
				$space                   = isset( $fsd['seats_left'] ) ? $fsd['seats_left'] : '';
				$price                   = isset( $fsd['fsd_cost'] ) ? $fsd['fsd_cost'] : '';
				$availability_label      = isset( $availability_options[ $availability ] ) ? $availability_options[ $availability ] : __( 'Guaranteed', 'wp-travel-engine' );
				$dates_data[ ( $keys ) ] = array(
					'content_id'   => $content_id,
					'start_date'   => $start_date,
					'end_date'     => $end_date,
					'availability' => $availability_label,
					'price'        => $price,
					'space'        => $space,
				);
			}
			++$keys;
		}
		return $dates_data;
	}
}
