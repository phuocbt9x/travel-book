<?php

namespace WPTravelEngine\Blocks;

use WPTravelEngine\Blocks\Helpers;
use WPTravelEngine\Blocks\Render;
use WPTravelEngine\Blocks\Util\WP_Kses;

/**
 * WTE Blocks.
 *
 * @since __addonmigration__
 */
class Blocks {

	/**
	 * Blocks constructor.
	 *
	 * @var array $blocks
	 * @since 5.8.3
	 */
	protected array $blocks = array();

	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Add a block to the list of registered blocks.
	 *
	 * @param string $block_dir Path to the directory of the block.
	 *
	 * @since 5.8.3
	 */
	public function set( string $block_dir ): void {
		$this->blocks[] = $block_dir;
	}

	/**
	 * Initializes hooks for WPTravelEngine Blocks.
	 *
	 * @since 5.8.3
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 20 );
		add_filter( 'block_type_metadata', array( 'WPTravelEngine\Blocks\MetaData', 'filter_block_metadata' ) );
		add_filter( 'wte_register_block_types', array( $this, 'get_core_blocks_settings' ), 9 );

		add_action( 'wp_footer', array( $this, 'print_booking_script_template' ) );

		new Scripts();
		do_action( __NAMESPACE__ . __FUNCTION__, $this );
		new Helpers();
		new WP_Kses();
	}

	/**
	 * This function returns the settings for the core blocks.
	 *
	 * @param array $attributes The attributes for the block.
	 * @param bool  $elementor Whether the block is being used in Elementor.
	 * @return array The parsed arguments.
	 */
	public function get_core_blocks_settings( $attributes = array(), $elementor = false ) {
		return wp_parse_args(
			$attributes,
			array(
				'trip-search' => array(
					'title'      => __( 'WP Travel Engine - Trip Search', 'wp-travel-engine' ),
					'attributes' => array(
						'title'                 => array(
							'type'    => 'string',
							'default' => '',
						),
						'subtitle'              => array(
							'type'    => 'string',
							'default' => '',
						),
						'titleLevel'            => array(
							'type'    => 'number',
							'default' => 2,
						),
						'searchFormOrientation' => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'searchButtonLabel'     => array(
							'type'    => 'string',
							'default' => __( 'Search', 'wp-travel-engine' ),
						),
						'searchFilters'         => array(
							'type'    => 'object',
							'default' => array(
								'destination' => array(
									'label'   => __( 'Destination', 'wp-travel-engine' ),
									'default' => __( 'Destination', 'wp-travel-engine' ),
									'show'    => true,
									'order'   => 1,
									'icon'    => 'marker',
								),
								'trip_types'  => array(
									'label'   => __( 'Trip Types', 'wp-travel-engine' ),
									'default' => __( 'Trip Types', 'wp-travel-engine' ),
									'show'    => false,
									'order'   => 2,
									'icon'    => 'cycle',
								),
								'activities'  => array(
									'label'   => __( 'Activity', 'wp-travel-engine' ),
									'default' => __( 'Activity', 'wp-travel-engine' ),
									'show'    => true,
									'order'   => 3,
									'icon'    => 'cycle',
								),
								'duration'    => array(
									'label'   => __( 'Duration', 'wp-travel-engine' ),
									'default' => __( 'Duration', 'wp-travel-engine' ),
									'show'    => true,
									'order'   => 4,
									'icon'    => 'duration',
								),
								'price'       => array(
									'label'   => __( 'Price', 'wp-travel-engine' ),
									'default' => __( 'Price', 'wp-travel-engine' ),
									'show'    => true,
									'order'   => 5,
									'icon'    => 'money',
								),
							),
						),
						'layoutFilters'         => array(
							'type'    => 'object',
							'default' => array(
								'showDropdownIcon'  => true,
								'showIcons'         => true,
								'showFilterLabels'  => true,
								'showDestinations'  => true,
								'showDateSelector'  => false,
								'showActivities'    => true,
								'showDurationRange' => true,
								'showPriceRange'    => true,
								'showTitle'         => true,
								'showSubtitle'      => true,
							),
						),
					),
				),
			)
		);
	}

	public function init() {
		$this->iterateOverDirectories( $this->directory() );
		$this->register();
		$this->register_meta();
	}

	/**
	 * Returns the directory where the block files are located.
	 *
	 * @return array The directory path.
	 * @since 5.8.3
	 */
	public function directory(): array {
		return array(
			dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/dist/blocks/core',
			dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/dist/blocks/single-trip',
			dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/dist/blocks/trip-pages',
		);
	}

	/**
	 * Directory for trip blocks.
	 *
	 * @since 5.8.3
	 */
	public function iterateOverDirectories( $currentDirectory ) {
		if ( is_array( $currentDirectory ) ) {
			foreach ( $currentDirectory as $directory ) {
				$this->iterateOverDirectories( $directory );
			}
		} else {
			$dir = new \DirectoryIterator( $currentDirectory );

			foreach ( $dir as $fileinfo ) {
				if ( ! $fileinfo->isDot() ) {
					$block = $fileinfo->getPathname() .
					( ( defined( 'WTE_FIXED_DEPARTURE_VERSION' ) && WTE_FIXED_DEPARTURE_VERSION >= '2.4.0' && file_exists( $fileinfo->getPathname() . '/modified/block.json' ) )
						? '/modified/block.json'
						: '/block.json' );

					if ( file_exists( $block ) ) {
						$this->set( $block );
					} elseif ( $fileinfo->isDir() ) {
						$this->iterateOverDirectories( $fileinfo->getPathname() );
					}
				}
			}
		}
	}

	/**
	 * Register the block types.
	 */
	public function register(): void {
		$blocks = apply_filters( __METHOD__, $this->blocks );

		$current_theme = wp_get_theme();
		$templates     = get_block_templates();

		foreach ( $blocks as $block ) {

			// Skip registering 'trip-fsd-date-selector' block if version >= 2.4.0
			if ( ( 'wptravelenginetripblocks/trip-fsd-date-selector' === $block || 'wptravelenginetripblocks/trip-fsd-loadmore' === $block ) && defined( 'WTE_FIXED_DEPARTURE_VERSION' ) && WTE_FIXED_DEPARTURE_VERSION >= '2.4.0' ) {
				continue;
			}
			$template_dir  = dirname( $block ) . '/';
			$template_file = ( defined( 'WTE_FIXED_DEPARTURE_VERSION' ) && WTE_FIXED_DEPARTURE_VERSION >= '2.4.0' && file_exists( $template_dir . 'modified/block.php' ) )
				? 'modified/block.php'
				: 'block.php';

			$template_path = wp_normalize_path( realpath( $template_dir . $template_file ) );
			$args          = array();
			if ( $template_path ) {
				$args['render_callback'] = static function ( ...$args ) use ( $template_path ) {
					list( $attributes, $content, $block ) = $args;

					ob_start();
					$render = new Render(
						compact( 'attributes', 'content', 'block', 'template_path' )
					);
					$render->render();

					return ob_get_clean();
				};
			}

			register_block_type( $block, $args );

		}
	}

	/**
	 * Register the meta for block.
	 *
	 * @since 5.8.3
	 */
	protected function register_meta() {
		register_rest_field(
			'comment',
			'images',
			array(
				'get_callback' => function ( $object, $field_name, $default ) {
					$image_ids = get_comment_meta( $object['id'], 'gallery_images', true );
					$images    = array();
					if ( isset( $image_ids ) && ! is_object( $image_ids ) && ! empty( $image_ids ) ) {
						foreach ( $image_ids as $image_id ) {
							$images[] = array(
								'id'        => $image_id,
								'url'       => wp_get_attachment_image_url( $image_id, 'full' ),
								'thumbnail' => wp_get_attachment_image_url( $image_id, 'thumbnail' ),
							);
						}
					}

					return $images;
				},
			)
		);

		// Add Meta for Comments.
		foreach ( array( 'experience_date', 'title', 'client_location', 'stars' ) as $meta ) {
			register_meta(
				'comment',
				$meta,
				array(
					'type'         => 'string',
					'single'       => true,
					'show_in_rest' => true,
				)
			);
		}

		// Register Block Patterns.
		$review_template = file_get_contents( __DIR__ . '/Templates/review_template.php' );
		register_block_pattern(
			'wptravelenginetripblocks/review-template',
			array(
				'title'       => __( 'Review Block Pattern', 'wp-travel-engine' ),
				'content'     => $review_template,
				'categories'  => array( 'wptravelenginetripblocks' ),
				'description' => __( 'A template for displaying trip reviews.', 'wp-travel-engine' ),
			)
		);

		// Register Block Pattern for Aggregate Reviews.
		$aggregrate_review = file_get_contents( __DIR__ . '/Templates/aggregrate_review.php' );
		register_block_pattern(
			'wptravelenginetripblocks/aggregate-reviews',
			array(
				'title'       => __( 'Aggregate Reviews Pattern', 'wp-travel-engine' ),
				'content'     => $aggregrate_review,
				'categories'  => array( 'wptravelenginetripblocks' ),
				'description' => __( 'A block to display aggregated reviews.', 'wp-travel-engine' ),
			)
		);

		// Register Block Pattern for Average Ratings.
		$average_rating = file_get_contents( __DIR__ . '/Templates/average_rating.php' );
		register_block_pattern(
			'wptravelenginetripblocks/average-review',
			array(
				'title'       => __( 'Average Ratings Pattern', 'wp-travel-engine' ),
				'content'     => $average_rating,
				'categories'  => array( 'wptravelenginetripblocks' ),
				'description' => __( 'A block to display average ratings.', 'wp-travel-engine' ),
			)
		);

		// Register Block Pattern for Review Listing.
		$review_list = file_get_contents( __DIR__ . '/Templates/review_list.php' );
		register_block_pattern(
			'wptravelenginetripblocks/review-list',
			array(
				'title'       => __( 'Review List Pattern', 'wp-travel-engine' ),
				'content'     => $review_list,
				'categories'  => array( 'wptravelenginetripblocks' ),
				'description' => __( 'A block to display reviews.', 'wp-travel-engine' ),
			)
		);
	}

	/**
	 * Print booking script template.
	 *
	 * @since 5.8.3
	 */
	public function print_booking_script_template() {
		global $post;
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );
		$is_enabled_fse_template   = $wp_travel_engine_settings['enable_fse_template'] ?? 'no';
		if ( $post !== null && $post->post_type == \WP_TRAVEL_ENGINE_POST_TYPE && ( current_theme_supports( 'wptravelengine-templates' ) || ( wp_is_block_theme() && $is_enabled_fse_template == 'yes' ) ) ) {
			wte_get_template( 'script-templates/booking-process/wte-booking.php' );
			wp_enqueue_script( 'wte-redux' );
		}
	}
}
