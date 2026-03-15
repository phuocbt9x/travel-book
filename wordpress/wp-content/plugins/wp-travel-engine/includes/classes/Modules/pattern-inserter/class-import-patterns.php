<?php

namespace CWPatternImport;

class CW_Pattern_Import {

    /**
     * The instance of the class.
     *
     * @var CW_Pattern_Import
     */
    private static $instance = null;

    /**
     * Returns the instance of the class.
     *
     * @return CW_Pattern_Import
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * CW_Pattern_Import constructor.
     */
    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'pattern_import_rest_endpoints' ) );
        add_action( 'admin_enqueue_scripts', array($this, 'pattern_import_assets') );

    }

    /**
     * Register the REST endpoints.
     */
    public function pattern_import_rest_endpoints() {

        register_rest_route(
	        'block-patterns/v1',
            '/patterns/',
            array(
                'methods' => 'GET',
                'callback' => array( $this, 'list_cached_pattern_data' ),
                'permission_callback' => '__return_true',
                'args' => array(
                    'sync' => array(
                        'validate_callback' => __CLASS__ . '::validate_string'
                    ),
                ),
            )
        );
    }

    /**
     * Enqueue the assets for the admin.
     */
    public function pattern_import_assets(){
        $admin_dependencies = plugin_dir_path(__FILE__) . 'build/patternImport.asset.php'; //@todo change the path as needed

        if ( file_exists( $admin_dependencies ) ) {
            $admin_asset_file      = require $admin_dependencies;
            $admin_js_dependencies = ( ! empty( $admin_asset_file['dependencies'] ) ) ? $admin_asset_file['dependencies'] : [];
            $admin_version         = ( ! empty( $admin_asset_file['version'] ) ) ? $admin_asset_file['version'] : '1.0.0';
            $admin_js_dependencies[] = 'updates';
            wp_enqueue_style(
                'cw-pattern-import-admin',
                plugin_dir_url(__FILE__) . 'build/patternImport.css',
                array(),
                filemtime( plugin_dir_path(__FILE__) . 'build/patternImport.css' )
            );

            wp_enqueue_script(
                'cw-pattern-import-admin',
                plugin_dir_url(__FILE__) . 'build/patternImport.js',
                $admin_js_dependencies ,
                $admin_version,
                true
            );

            $license_key = get_option('block_pattern_license_key', 'free');

            wp_localize_script(
                'cw-pattern-import-admin',
	            'cwAdmin',
                [
                    'url'           => get_site_url(),
                    'activePlugin'  => $this->get_active_plugins(),
                    'nonce'         => wp_create_nonce( 'wp_rest' ),
                    'inactive'      => $this->get_inactive_plugins(),
                    'userCan'       => [
                        'installPlugins' => current_user_can('install_plugins') && current_user_can('activate_plugins'),
                        'editPost'       => current_user_can('edit_posts'),
                    ],
                    'freeProFilter' => apply_filters('block_pattern_free_pro_filter', true),
                    'license_key'   => $license_key,
                ]
            );

        }
    }

    /**
     * List the cached pattern data.
     *
     * @param \WP_REST_Request $request
     *
     * @return \WP_REST_Response
     */
    public function list_cached_pattern_data( $request ) {

	    if ( $request->get_param( 'sync' ) === 'true'  ) {
            $this->cw_delete_cache();
        }

        return rest_ensure_response( $this->get_pattern_list() );
    }

    /**
     * Deletes all caches.
     */
    public function cw_delete_cache() {
        delete_transient( 'cw_block_pattern_local_transient' );
        do_action( 'cw_block_pattern_local_transient_cache' );
    }

    /**
     * Get the pattern list from tansient and set transient if cache is deleted.
     *
     * @return array
     */
	public function get_pattern_list() {

        $designapi = get_transient( 'cw_block_pattern_local_transient' );

        if ( $designapi ) {
            return $designapi;
        }else{
            $designapi = $this->list_patterns_from_server();
	        set_transient( 'cw_block_pattern_local_transient', $designapi, DAY_IN_SECONDS );
            return $designapi;
        }
    }

    /**
     * Get the pattern list from the server.
     *
     * @return array
     */
    public function list_patterns_from_server() {
        $pattern_data = [];
        $import_url = apply_filters('block_pattern_import_url', 'https://fsedemo.com/pattern-engine/wp-json/block-pattern/v1/patterns/?niche=travel'); // @todo change the url as needed
        $apiData = wp_remote_get( $import_url );

        if ( !is_wp_error( $apiData ) && wp_remote_retrieve_response_code( $apiData ) == 200) {
            $content_body = wp_remote_retrieve_body( $apiData );
            $pattern_data = json_decode( $content_body );
        }

        return $pattern_data;
    }

    /**
     * Validate the string.
     *
     * @param mixed $value
     * @param \WP_REST_Request $request
     * @param string $param
     *
     * @return bool|\WP_Error
     */
    public static function validate_string( $value, $request, $param ) {
        if ( ! is_string( $value ) ) {
	        return new \WP_Error( 'invalid_param', sprintf( esc_html__( '%s must be a string.', 'wp-travel-engine' ), $param ) );
        }
        return true;
    }

    /**
     * Get the inactive plugins.
     *
     * @return array
     */
    public function get_inactive_plugins() {
        if (!current_user_can('install_plugins') && !current_user_can('activate_plugins')) {
	        return new \WP_Error( 'rest_forbidden', esc_html__( 'Sorry, you are not allowed to do that.', 'wp-travel-engine' ), array( 'status' => 403 ) );
        }

	    // Get the list of all installed plugins
        $all_plugins = get_plugins();

	    // Fetch the row from the options table containing active plugins
        $active_plugins_option = get_option('active_plugins');

	    // Unserialize the active plugins data
        $active_plugins = is_array($active_plugins_option) ? $active_plugins_option : [];

	    // Get the slugs of active plugins
        $active_plugin_slugs = array_map(function($plugin) {
            return plugin_basename($plugin);
        }, $active_plugins);

	    // Get the slugs of inactive plugins
        $inactive_plugin_slugs = array_diff(array_keys($all_plugins), $active_plugin_slugs);

	    // Get the details of inactive plugins
        $inactive_plugins = array_intersect_key($all_plugins, array_flip($inactive_plugin_slugs));

	    // Initialize an empty array to hold the modified inactive plugins
        $modified_inactive_plugins = array();
        // Iterate over each inactive plugin
        foreach ($inactive_plugins as $key => $plugin_data) {
            $extract = explode( '/', $key );
            // Extract the necessary information
            $name = $plugin_data['Name'];
            $slug = $extract[0];

	        // Add the plugin to the modified array
            $modified_inactive_plugins[] = array(
                'name' => $name,
                'slug' => $slug,
                'url'  => $this->get_activation_url($slug)
            );
        }

	    // Return the modified array
        return $modified_inactive_plugins;
    }

	/**
     * Get the activation URL for a plugin.
     *
     * @param string $plugin_slug The plugin slug.
     *
     * @return string|bool The activation URL if the plugin exists, false otherwise.
     */
    public function get_activation_url($plugin_slug) {
        if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
            $plugins = get_plugins( '/' . $plugin_slug );
            if ( ! empty( $plugins ) ) {
                $keys        = array_keys( $plugins );
                $plugin_file = $plugin_slug . '/' . $keys[0];
                $url         = wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'activate',
                            'plugin' => $plugin_file,
                        ),
                        admin_url( 'plugins.php' )
                    ),
                    'activate-plugin_' . $plugin_file
                );
                return $url;
            }
        }
        return false;
    }

	/**
     * Get the active plugins.
     *
     * @return array
     */
    public function get_active_plugins() {
        $active_plugins = get_plugins();
        $plugins = array();

	    foreach ($active_plugins as $key => $plugin) {
            if ( is_plugin_active( $key ) ) {
                $extract = explode( '/', $key );
                $path    = ABSPATH . 'wp-content/plugins/' . $key;
                $plugin_data = get_plugin_data($path);
                $plugins[] = array(
                    'name'    => $plugin_data['Name'],
                    'slug'    => $extract[0],
                    'version' => $plugin_data['Version'],
                );
            }
        }

	    return $plugins;
    }

}
CW_Pattern_Import::instance();
