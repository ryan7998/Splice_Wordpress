<?php

/**
 * Security Functions for Splice Theme
 * Implements comprehensive security best practices
 *
 * @package Splice_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Security Headers
 */
function splice_theme_security_headers()
{
    // Prevent clickjacking
    header('X-Frame-Options: SAMEORIGIN');

    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');

    // Enable XSS protection
    header('X-XSS-Protection: 1; mode=block');

    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Content Security Policy (CSP)
    $csp = "default-src 'self'; ";
    $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval'; ";
    $csp .= "style-src 'self' 'unsafe-inline'; ";
    $csp .= "img-src 'self' data: https:; ";
    $csp .= "font-src 'self' data:; ";
    $csp .= "connect-src 'self'; ";
    $csp .= "media-src 'self'; ";
    $csp .= "object-src 'none'; ";
    $csp .= "frame-src 'self'; ";
    $csp .= "worker-src 'self'; ";
    $csp .= "form-action 'self';";

    header("Content-Security-Policy: " . $csp);
}
add_action('send_headers', 'splice_theme_security_headers');

/**
 * Disable XML-RPC
 */
function splice_theme_disable_xmlrpc()
{
    // Disable XML-RPC completely
    add_filter('xmlrpc_enabled', '__return_false');

    // Remove XML-RPC from head
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
}
add_action('init', 'splice_theme_disable_xmlrpc');

/**
 * Remove WordPress version information
 */
function splice_theme_remove_version_info()
{
    // Remove version from head
    remove_action('wp_head', 'wp_generator');

    // Remove version from scripts and styles
    add_filter('style_loader_src', 'splice_theme_remove_version_from_assets', 9999);
    add_filter('script_loader_src', 'splice_theme_remove_version_from_assets', 9999);
}
add_action('init', 'splice_theme_remove_version_info');

/**
 * Remove version from asset URLs
 */
function splice_theme_remove_version_from_assets($src)
{
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * Disable file editing in admin
 */
function splice_theme_disable_file_editing()
{
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
}
add_action('init', 'splice_theme_disable_file_editing');

/**
 * Hide login errors
 */
function splice_theme_hide_login_errors()
{
    return 'Invalid login credentials.';
}
add_filter('login_errors', 'splice_theme_hide_login_errors');

/**
 * Limit login attempts (basic implementation)
 */
function splice_theme_check_login_attempts($user, $username, $password)
{
    if (!empty($username)) {
        $attempted_login = get_transient('attempted_login');

        if ($attempted_login === false) {
            $attempted_login = array();
        }

        if (isset($attempted_login[$username])) {
            if ($attempted_login[$username]['count'] >= 5) {
                $until = $attempted_login[$username]['time'] + 900; // 15 minutes
                if (time() < $until) {
                    return new WP_Error(
                        'too_many_attempts',
                        sprintf(
                            __('Too many failed login attempts. Please try again in %d minutes.', 'splice-theme'),
                            ceil(($until - time()) / 60)
                        )
                    );
                } else {
                    unset($attempted_login[$username]);
                }
            }
        }

        if (!isset($attempted_login[$username])) {
            $attempted_login[$username] = array('count' => 1, 'time' => time());
        } else {
            $attempted_login[$username]['count']++;
            $attempted_login[$username]['time'] = time();
        }

        set_transient('attempted_login', $attempted_login, 900);
    }

    return $user;
}
add_filter('authenticate', 'splice_theme_check_login_attempts', 30, 3);

/**
 * Clear login attempts on successful login
 */
function splice_theme_clear_login_attempts($username)
{
    $attempted_login = get_transient('attempted_login');
    if ($attempted_login && isset($attempted_login[$username])) {
        unset($attempted_login[$username]);
        set_transient('attempted_login', $attempted_login, 900);
    }
}
add_action('wp_login', 'splice_theme_clear_login_attempts');

/**
 * Sanitize and validate user input
 */
function splice_theme_sanitize_input($input, $type = 'text')
{
    switch ($type) {
        case 'email':
            return sanitize_email($input);
        case 'url':
            return esc_url_raw($input);
        case 'textarea':
            return sanitize_textarea_field($input);
        case 'html':
            return wp_kses_post($input);
        case 'int':
            return intval($input);
        case 'float':
            return floatval($input);
        case 'bool':
            return (bool) $input;
        case 'filename':
            return sanitize_file_name($input);
        case 'key':
            return sanitize_key($input);
        case 'title':
            return sanitize_text_field($input);
        default:
            return sanitize_text_field($input);
    }
}

/**
 * Validate and sanitize file uploads
 */
function splice_theme_validate_file_upload($file, $allowed_types = array(), $max_size = 5242880)
{
    $errors = array();

    // Check file size
    if ($file['size'] > $max_size) {
        $errors[] = sprintf(__('File size exceeds maximum allowed size of %s bytes.', 'splice-theme'), number_format($max_size));
    }

    // Check file type
    if (!empty($allowed_types)) {
        $file_type = wp_check_filetype($file['name']);
        if (!in_array($file_type['type'], $allowed_types)) {
            $errors[] = sprintf(
                __('File type %s is not allowed. Allowed types: %s', 'splice-theme'),
                $file_type['type'],
                implode(', ', $allowed_types)
            );
        }
    }

    // Check for malicious content
    $file_content = file_get_contents($file['tmp_name']);
    if (preg_match('/<\?php|<script|<iframe|<object|<embed/i', $file_content)) {
        $errors[] = __('File contains potentially malicious content.', 'splice-theme');
    }

    return $errors;
}

/**
 * Secure nonce generation and verification
 */
function splice_theme_create_secure_nonce($action, $user_id = null)
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $token = wp_create_nonce($action . '_' . $user_id);
    return $token;
}

function splice_theme_verify_secure_nonce($nonce, $action, $user_id = null)
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    return wp_verify_nonce($nonce, $action . '_' . $user_id);
}

/**
 * Rate limiting for API endpoints
 */
function splice_theme_check_rate_limit($key, $limit = 100, $period = 3600)
{
    $current_time = time();
    $rate_limit_key = 'rate_limit_' . $key;

    $attempts = get_transient($rate_limit_key);
    if ($attempts === false) {
        $attempts = array('count' => 0, 'reset_time' => $current_time + $period);
    }

    if ($current_time > $attempts['reset_time']) {
        $attempts = array('count' => 0, 'reset_time' => $current_time + $period);
    }

    if ($attempts['count'] >= $limit) {
        return false; // Rate limit exceeded
    }

    $attempts['count']++;
    set_transient($rate_limit_key, $attempts, $period);

    return true;
}

/**
 * Sanitize output for display
 */
function splice_theme_safe_output($content, $context = 'display')
{
    switch ($context) {
        case 'title':
            return esc_html($content);
        case 'url':
            return esc_url($content);
        case 'attr':
            return esc_attr($content);
        case 'js':
            return esc_js($content);
        case 'textarea':
            return esc_textarea($content);
        case 'html':
            return wp_kses_post($content);
        case 'email':
            return antispambot($content);
        default:
            return esc_html($content);
    }
}

/**
 * Prevent SQL injection in custom queries
 */
function splice_theme_prepare_safe_query($query, $args = array())
{
    global $wpdb;

    // Use prepared statements
    if (!empty($args)) {
        return $wpdb->prepare($query, $args);
    }

    return $query;
}

/**
 * Validate and sanitize taxonomy terms
 */
function splice_theme_validate_taxonomy_term($term, $taxonomy)
{
    // Check if taxonomy exists
    if (!taxonomy_exists($taxonomy)) {
        return false;
    }

    // Sanitize term
    $sanitized_term = sanitize_term_field('name', $term, 0, $taxonomy, 'db');

    // Check if term exists
    $existing_term = term_exists($sanitized_term, $taxonomy);

    return $existing_term ? $existing_term : false;
}

/**
 * Secure redirect function
 */
function splice_theme_safe_redirect($location, $status = 302)
{
    // Validate redirect location
    $location = wp_validate_redirect($location);

    // Ensure it's a safe redirect
    if (wp_safe_redirect($location, $status)) {
        exit;
    }
}

/**
 * Add security meta tags
 */
function splice_theme_security_meta_tags()
{
    echo '<meta name="robots" content="noindex,nofollow" />' . "\n";
    echo '<meta name="referrer" content="strict-origin-when-cross-origin" />' . "\n";
}
add_action('wp_head', 'splice_theme_security_meta_tags');

/**
 * Disable directory browsing
 */
function splice_theme_disable_directory_browsing()
{
    if (!is_admin()) {
        $request_uri = $_SERVER['REQUEST_URI'];
        if (preg_match('/\/wp-content\/themes\/.*\/inc\//', $request_uri)) {
            wp_die('Access denied.', 'Security', array('response' => 403));
        }
    }
}
add_action('init', 'splice_theme_disable_directory_browsing');

/**
 * Validate and sanitize JSON input
 */
function splice_theme_sanitize_json_input($json_string)
{
    // Decode JSON
    $data = json_decode($json_string, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }

    // Recursively sanitize array data
    return splice_theme_sanitize_array($data);
}

/**
 * Recursively sanitize array data
 */
function splice_theme_sanitize_array($array)
{
    if (!is_array($array)) {
        return splice_theme_sanitize_input($array);
    }

    $sanitized = array();
    foreach ($array as $key => $value) {
        $sanitized_key = sanitize_key($key);
        $sanitized[$sanitized_key] = splice_theme_sanitize_array($value);
    }

    return $sanitized;
}

/**
 * Log security events
 */
function splice_theme_log_security_event($event, $details = array(), $level = 'info')
{
    $log_entry = array(
        'timestamp' => current_time('mysql'),
        'event' => sanitize_text_field($event),
        'details' => $details,
        'level' => sanitize_text_field($level),
        'user_id' => get_current_user_id(),
        'ip' => splice_theme_get_client_ip(),
        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : ''
    );

    // Store in WordPress options (for demonstration - in production, use proper logging)
    $security_logs = get_option('splice_theme_security_logs', array());
    $security_logs[] = $log_entry;

    // Keep only last 100 entries
    if (count($security_logs) > 100) {
        $security_logs = array_slice($security_logs, -100);
    }

    update_option('splice_theme_security_logs', $security_logs);
}

/**
 * Get client IP address
 */
function splice_theme_get_client_ip()
{
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');

    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/**
 * Initialize security features
 */
function splice_theme_init_security()
{
    // Log theme activation
    splice_theme_log_security_event('Theme activated', array('version' => _S_VERSION), 'info');
}
add_action('after_switch_theme', 'splice_theme_init_security');
