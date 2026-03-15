<?php

namespace WPTravelEngine\Filters;

class FSE_Template {

	protected array $templates = array();

	public function __construct() {
		$this->set_templates();
	}

	/**
	 * Initializes hooks for template inclusion and excerpt modification.
	 */
	public function hooks() {
		add_filter( 'get_block_templates', array( $this, 'get_block_templates' ), 10, 2 );
		add_filter( 'pre_get_block_file_template', array( $this, 'get_block_file_template' ), 10, 3 );
	}

	/**
	 * Sets a template directory.
	 */
	public function set_templates(): void {
		$files = glob( ( $this->directory() . '/*.html' ) );
		foreach ( $files as $file ) {
			$this->templates[] = $file;
		}
	}

	/**
	 * Adds block templates to the query result.
	 *
	 * @param array $query_result The current query results.
	 * @param array $query The current query object.
	 *
	 * @return array The modified query result.
	 */
	public function get_block_templates( array $query_result, array $query ): array {
		if ( ! wp_is_block_theme() ) {
			return $query_result;
		}

		$slugs          = $query['slug__in'] ?? array();
		$template_files = $this->get_plugin_block_templates( $slugs );

		foreach ( $template_files as $plugin_template ) {
			if ( 'custom' !== $plugin_template->source ) {
				$template = $this->build_block_template( (object) $plugin_template );
				if ( null !== $template ) {
					$query_result[] = $template;
				}
				continue;
			} else {
				$query_result[] = $plugin_template;
				continue;
			}
		}

		return $this->filter_duplicate_templates( $query_result );
	}

	/**
	 * Filters out duplicate templates from the given query result.
	 *
	 * This function iterates through the provided query result, which is expected to contain
	 * template objects. It checks for duplicates based on the template slug and source,
	 * ensuring that only unique templates are retained. Templates with the same slug
	 * but different sources are considered unique and are not filtered out.
	 *
	 * @param array $query_result The array of template objects to filter.
	 *
	 * @return array The filtered array of unique template objects.
	 */
	public function filter_duplicate_templates( array $query_result ): array {
		$unique_templates = array();
		$slugs_seen       = array();

		foreach ( $query_result as $template ) {
			if ( ! in_array( $template->slug, $slugs_seen ) ) {
				$unique_templates[] = $template;
				$slugs_seen[]       = $template->slug;
			} else {
				foreach ( $unique_templates as $key => $unique_template ) {
					if ( $unique_template->slug === $template->slug && $unique_template->source === 'plugin' ) {
						unset( $unique_templates[ $key ] );
						break;
					}
				}
			}
		}

		return array_values( $unique_templates );
	}

	/**
	 * Retrieves Full Site Editing (FSE) templates based on the provided slugs.
	 *
	 * This function merges two sets of templates: those that have been customized and those that are to be registered.
	 * It first retrieves customized templates by calling get_customized_templates with the provided slugs.
	 * Then, it retrieves templates that are to be registered by calling set_fse_templates with the same slugs.
	 * Finally, it merges the two arrays of templates and returns the combined list.
	 *
	 * @param array $slugs An array of slugs to filter templates by. Defaults to an empty array.
	 *
	 * @return array A merged array of customized and to-be-registered templates.
	 */
	public function get_plugin_block_templates( array $slugs = array() ): array {
		$saved_templates          = $this->get_block_templates_from_db( $slugs );
		$registered_fse_templates = $this->get_block_templates_from_plugin( $slugs );

		return array_merge( $saved_templates, $registered_fse_templates );
	}

	/**
	 * Filters and sets Full Site Editing (FSE) templates based on the provided slugs.
	 *
	 * This function iterates over a list of template files, filtering them based on whether their slugs match any of the provided slugs.
	 * It uses the apply_filters WordPress hook to allow for modifications to the list of template files before processing.
	 * For each template file, it extracts the slug by removing the '.html' extension from the file name.
	 * It then checks if the template's slug is in the list of slugs provided. If it is, the template is added to the list of templates to be returned.
	 * If the template's slug is not in the list, the iteration continues to the next template file.
	 * Finally, it returns an array of templates that match the provided slugs.
	 *
	 * @param array $slugs An array of slugs to filter templates by.
	 *
	 * @return array An array of FSE templates that match the provided slugs.
	 */
	public function get_block_templates_from_plugin( array $slugs ): array {
		$template_files = apply_filters( __METHOD__, $this->templates );
		$templates      = array();

		foreach ( $template_files as $template_file ) {

			$template_slug = basename( $template_file, '.html' );

			if ( count( $slugs ) > 0 ) {
				// Check if $template_slug is not in $slugs.
				if ( count( array_diff( array( $template_slug ), $slugs ) ) > 0 ) {
					continue;
				}
			}

			$template = $this->create_block_template_object( $template_file, $template_slug );

			if ( is_null( $template ) ) {
				continue;
			}

			$templates[] = $template;
		}

		return $templates;
	}

	/**
	 * Retrieves customized templates based on the provided slugs.
	 *
	 * This function queries for templates that match the provided slugs and belong to the
	 * specified plugin and file name. It returns an array of processed template results.
	 * If no slugs are provided, it returns an empty array.
	 *
	 * @param array $slugs An array of slugs to filter templates by. Defaults to an empty array.
	 *
	 * @return array An array of customized templates that match the provided slugs.
	 */
	public function get_block_templates_from_db( array $slugs = array() ): array {

		// Retrieve the plugin name and file name for later use in the query arguments.
		list( $plugin_name, $file_name ) = $this->get_plugins_info();

		// Prepare the query arguments for retrieving customized templates.
		$query_args = array(
			'post_type'      => 'wp_template',
			'posts_per_page' => - 1, // Retrieve all posts to ensure we get all matching templates.
			'tax_query'      => array(
				array(
					'taxonomy' => 'wp_theme',
					'field'    => 'name',
					'terms'    => array( $plugin_name . '/' . $file_name ),
				),
			),
		);

		// If slugs are provided, add them to the query arguments to filter the templates.
		if ( ! empty( $slugs ) ) {
			$query_args['post_name__in'] = $slugs;
		}

		// Retrieve the posts matching the query arguments and process each post to build the template result.
		return array_map(
			function ( $saved_template ) {
				return $this->_build_block_template_result_from_post( $saved_template );
			},
			get_posts( $query_args )
		);
	}

	/**
	 * This function builds a block template result from a given post.
	 * It takes reference from the WP wp-includes/block-template-utils.php file.
	 */
	protected function _build_block_template_result_from_post( $post ) {
		// Retrieve the plugin name and file name for later use.
		list( $plugin_name, $file_name ) = $this->get_plugins_info();

		// Get the terms associated with the post for the 'wp_theme' taxonomy.
		$terms = get_the_terms( $post, 'wp_theme' );

		// If there's an error retrieving the terms, return the error.
		if ( is_wp_error( $terms ) ) {
			return $terms;
		}

		// If no terms are found, return an error indicating that no theme is defined for this template.
		if ( ! $terms ) {
			return new \WP_Error(
				'template_missing_theme',
				__( 'No theme is defined for this template.', 'wp-travel-engine' )
			);
		}

		// Extract the theme name from the first term.
		$theme = $terms[0]->name;

		// Create a new WP_Block_Template object and populate its properties based on the post.
		$template                 = new \WP_Block_Template();
		$template->wp_id          = $post->ID;
		$template->id             = $theme . '//' . $post->post_name;
		$template->theme          = $theme;
		$template->content        = $post->post_content;
		$template->slug           = $post->post_name;
		$template->source         = 'custom';
		$template->type           = $post->post_type;
		$template->description    = $post->post_excerpt;
		$template->title          = $post->post_title;
		$template->status         = $post->post_status;
		$template->has_theme_file = true;
		if ( isset( $template->slug ) && in_array(
			$template->slug,
			array(
				'template-destination',
				'template-activities',
				'template-trip_types',
				'template-searchresultpage',
				'template-trip-checkout',
			),
			true
		)
		) {
			$template->post_types = array( 'post', 'page' );
			$template->is_custom  = true;
		} else {
			$template->is_custom = false;
		}

		if ( $plugin_name . '/' . $file_name === $theme ) {
			$template->origin = 'plugin';
		}

		return $template;
	}

	/**
	 * Constructs a complete FSE template object that can be directly used by WordPress for rendering templates.
	 * This function is used when the template needs to be fully instantiated as a WP_Block_Template object.
	 *
	 * @param object $template An object containing template details.
	 *
	 * @return ?\WP_Block_Template A WP_Block_Template object fully populated with the details of the template.
	 */
	public function build_block_template( object $template ) {
		$template_path = $template->path ?? '';

		// Validate path is within allowed directory
		$real_path   = realpath( $template_path );
		$allowed_dir = realpath( $this->directory() );

		if ( ! $real_path || ! $allowed_dir || strpos( $real_path, $allowed_dir ) !== 0 ) {
			return null;
		}

		list( $plugin_name, $file_name ) = $this->get_plugins_info();

		$template_content = file_get_contents( $real_path );

		$wp_block_template = new \WP_Block_Template(); // Instantiate WP_Block_Template

		// Retrieve the theme name
		$theme_slug = get_stylesheet();

		$wp_block_template->id    = $plugin_name . '/' . $file_name . '//' . $template->slug;
		$wp_block_template->theme = $plugin_name . '/' . $file_name;
		$wp_block_template->theme = $plugin_name . '/' . $file_name;
		// Retrieve settings to determine the inclusion of header and footer
		$wptravelengine_settings = get_option( 'wp_travel_engine_settings', array() );
		$is_checkout_page        = $template->slug === 'template-trip-checkout';
		$checkout_version        = $wptravelengine_settings['checkout_page_template'] ?? '1.0';
		$display_header_footer   = $wptravelengine_settings['display_header_footer'] ?? 'no';
		$include_header_footer   = $is_checkout_page && $checkout_version === '2.0' && $display_header_footer === 'yes';

		// Add header template part conditionally
		if ( ! $is_checkout_page || $include_header_footer ) {
			$header_template_part        = '<!-- wp:template-part {"slug":"header","tagName":"header","theme":"' . esc_attr( $theme_slug ) . '"} /-->';
			$wp_block_template->content .= $header_template_part;
		}

		// Add the original template content
		$wp_block_template->content .= $template_content;

		// Add footer template part conditionally
		if ( ! $is_checkout_page || $include_header_footer ) {
			$footer_template_part        = '<!-- wp:template-part {"slug":"footer","tagName":"footer","theme":"' . esc_attr( $theme_slug ) . '"} /-->';
			$wp_block_template->content .= $footer_template_part;
		}

		$wp_block_template->source         = $template->source ?? 'plugin';
		$wp_block_template->slug           = $template->slug ?? '';
		$wp_block_template->type           = 'wp_template';
		$wp_block_template->title          = $template->title ?? '';
		$wp_block_template->description    = $template->description ?? '';
		$wp_block_template->status         = 'publish';
		$wp_block_template->has_theme_file = true;
		$wp_block_template->origin         = $template->source ?? 'plugin';
		$wp_block_template->post_types     = in_array( $template->slug, array( 'template-destination', 'template-activities', 'template-trip_types', 'template-searchresultpage', 'template-trip-checkout' ), true ) ? array( 'post', 'page' ) : array();
		$wp_block_template->is_custom      = ! empty( $wp_block_template->post_types );

		return $wp_block_template;
	}

	/**
	 * Retrieves template information based on the slug.
	 *
	 * @param string $template_slug The template slug.
	 *
	 * @return array|null The template information or null if not found.
	 */
	public function get_template_info( string $template_slug ): ?array {
		// Get the template slug from the template file name.
		$directory      = $this->directory();
		$template_files = glob( $directory . '/*.html' );

		// If no template files are found then return null.
		if ( empty( $template_files ) ) {
			return null;
		}

		$template_info = array();
		// Get the template slug from the template file name.
		foreach ( $template_files as $template_file ) {
			$template_name = basename( $template_file, '.html' );
			// Convert dashes and underscores to spaces and capitalize words for the title
			$template_title = ucwords( str_replace( array( '-', '_' ), ' ', $template_name ) );
			if ( $template_name === 'template-trip-checkout' ) {
				$template_title = __( 'WP Travel Engine - Checkout', 'wp-travel-engine' );
			}
			$template_description            = sprintf( __( 'Displays a %s template.', 'wp-travel-engine' ), $template_title );
			$template_info[ $template_name ] = array(
				'title'       => $template_title,
				'description' => $template_description,
			);
		}

		return $template_info[ $template_slug ] ?? null;
	}

	/**
	 * Creates a template object representing an FSE template.
	 * This function is primarily used for preparing template information that can be added to the list of available templates in WordPress.
	 *
	 * @param string $template_slug The slug used to identify the template.
	 * @param string $template_file The path to the template file.
	 *
	 * @return object|null An object representing the FSE template with the specified details, or null if template information is not found.
	 */
	public function create_block_template_object( string $template_file, string $template_slug ): ?object {
		list( $plugin_name, $file_name ) = $this->get_plugins_info();
		$template_info                   = $this->get_template_info( $template_slug );

		if ( ! $template_info ) {
			return null;
		}

		return (object) array(
			'id'          => $plugin_name . '/' . $file_name . '//' . $template_slug,
			'theme'       => $file_name,
			'slug'        => $template_slug,
			'title'       => $template_info['title'],
			'description' => $template_info['description'],
			'source'      => 'plugin',
			'type'        => 'wp_template',
			'path'        => $template_file,
		);
	}

	/**
	 * Retrieves the plugin name and file name from the WP_TRAVEL_ENGINE_FILE_PATH constant.
	 *
	 * This method extracts the plugin name and file name from the path specified by the
	 * WP_TRAVEL_ENGINE_FILE_PATH constant. It uses the basename() and dirname() functions
	 * to parse the path and returns an array containing the plugin name and file name.
	 *
	 * @return array An array containing the plugin name and file name.
	 */
	protected function get_plugins_info(): array {
		return wptravelengine_plugin_file_info();
	}

	/**
	 * Retrieves the directory path for templates.
	 *
	 * @return string The directory path.
	 */
	public function directory(): string {
		return dirname( WP_TRAVEL_ENGINE_FILE_PATH ) . '/includes/templates/templates';
	}

	/**
	 * Retrieves the block file template.
	 *
	 * @param string|null $template The template content.
	 * @param string      $id The template ID.
	 * @param string      $template_type The template type.
	 *
	 * @return \WP_Block_Template|string The modified template content.
	 */
	public function get_block_file_template( $template, $id, $template_type ) {
		list( $plugin_name, $file_name ) = $this->get_plugins_info();
		// Split the template ID to retrieve actual plugin's id.
		$template_name = explode( '//', $id );

		list( $template_id, $template_slug ) = $template_name;

		if ( $plugin_name . '/' . $file_name !== $template_id ) {
			return $template;
		}

		// Get the directory path for the template
		$directory = $this->directory( $template_type );

		// Construct the full file path for the template
		$template_path = $directory . '/' . $template_slug . '.html';

		// Create a template object
		$template_object = $this->create_block_template_object( $template_path, $template_slug );

		if ( ! is_object( $template_object ) ) {
			return '';
		}

		// Build the template from the file
		$built_template = $this->build_block_template( $template_object );

		return $built_template ?? $template;
	}
}
