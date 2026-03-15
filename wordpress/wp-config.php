<?php

define('APP_ENV', getenv('APP_ENV') ?: 'production');

define('DB_NAME',     getenv('DB_NAME'));
define('DB_USER',     getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST',     getenv('DB_HOST') ?: 'mysql');
define('DB_CHARSET',  getenv('DB_CHARSET') ?: 'utf8mb4');
define('DB_COLLATE',  getenv('DB_COLLATE') ?: '');

$table_prefix = 'wp_';

$wp_home = getenv('WP_HOME');
if (empty($wp_home)) {
    error_log('[wp-config] FATAL: WP_HOME environment variable is not set.');
    http_response_code(500);
    exit('Server configuration error.');
}
define('WP_HOME',    $wp_home);
define('WP_SITEURL', getenv('WP_SITEURL') ?: $wp_home);

define('WP_CONTENT_DIR', '/var/www/html/wp-content');
define('WP_CONTENT_URL', $wp_home . '/wp-content');

define('AUTH_KEY',         getenv('AUTH_KEY'));
define('SECURE_AUTH_KEY',  getenv('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY',    getenv('LOGGED_IN_KEY'));
define('NONCE_KEY',        getenv('NONCE_KEY'));
define('AUTH_SALT',        getenv('AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT',   getenv('LOGGED_IN_SALT'));
define('NONCE_SALT',       getenv('NONCE_SALT'));

define('WP_DEBUG', filter_var(getenv('WP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN));

$wp_debug_log_env = getenv('WP_DEBUG_LOG');
if ($wp_debug_log_env === false || $wp_debug_log_env === '' || strtolower($wp_debug_log_env) === 'false') {
    define('WP_DEBUG_LOG', false);
} elseif (strtolower($wp_debug_log_env) === 'true') {
    define('WP_DEBUG_LOG', true);
} else {
    define('WP_DEBUG_LOG', $wp_debug_log_env);
}

define('WP_DEBUG_DISPLAY', filter_var(getenv('WP_DEBUG_DISPLAY') ?: false, FILTER_VALIDATE_BOOLEAN));

if (!WP_DEBUG) {
    ini_set('display_errors', 0);
}

define('FORCE_SSL_ADMIN', filter_var(getenv('FORCE_SSL_ADMIN') ?: true, FILTER_VALIDATE_BOOLEAN));

if (
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
    (!empty($_SERVER['HTTP_X_FORWARDED_SSL'])   && $_SERVER['HTTP_X_FORWARDED_SSL']   === 'on')    ||
    (!empty($_SERVER['HTTPS'])                  && $_SERVER['HTTPS']                  !== 'off')
) {
    $_SERVER['HTTPS'] = 'on';
}

define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', APP_ENV === 'production');
define('FS_METHOD', 'direct');
define('WP_CACHE', true);

if (!filter_var(getenv('WP_REDIS_DISABLED') ?: false, FILTER_VALIDATE_BOOLEAN)) {
    define('WP_REDIS_HOST',     getenv('REDIS_HOST')     ?: 'redis');
    define('WP_REDIS_PORT',     intval(getenv('REDIS_PORT')    ?: 6379));
    define('WP_REDIS_DATABASE', intval(getenv('REDIS_DB')      ?: 0));
    define('WP_REDIS_PREFIX',   getenv('WP_REDIS_PREFIX') ?: 'wp_');

    $redis_password = getenv('REDIS_PASSWORD');
    if (!empty($redis_password)) {
        define('WP_REDIS_PASSWORD', $redis_password);
    }
}

define('DISABLE_WP_CRON', true);

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}
require_once ABSPATH . 'wp-settings.php';