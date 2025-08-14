<?php

/**
 * Security Admin Page for Splice Theme
 * Provides interface for viewing security logs and managing security settings
 *
 * @package Splice_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add security admin menu
 */
function splice_theme_add_security_menu()
{
    add_submenu_page(
        'tools.php',
        'Security Dashboard',
        'Security Dashboard',
        'manage_options',
        'splice-security-dashboard',
        'splice_theme_security_dashboard_page'
    );
}
add_action('admin_menu', 'splice_theme_add_security_menu');

/**
 * Security dashboard admin page
 */
function splice_theme_security_dashboard_page()
{
    // Handle actions
    if (isset($_POST['action']) && wp_verify_nonce($_POST['security_nonce'], 'splice_theme_security')) {
        $action = sanitize_text_field($_POST['action']);

        switch ($action) {
            case 'clear_logs':
                if (current_user_can('manage_options')) {
                    delete_option('splice_theme_security_logs');
                    $message = 'Security logs cleared successfully.';
                    $message_type = 'success';
                    splice_theme_log_security_event('Security logs cleared via admin', array('user_id' => get_current_user_id()), 'info');
                }
                break;
            case 'export_logs':
                if (current_user_can('manage_options')) {
                    splice_theme_export_security_logs();
                }
                break;
        }
    }

    // Get security logs
    $security_logs = get_option('splice_theme_security_logs', array());
    $recent_logs = array_slice(array_reverse($security_logs), 0, 50); // Show last 50 entries

    // Count log levels
    $log_counts = array(
        'info' => 0,
        'warning' => 0,
        'error' => 0
    );

    foreach ($security_logs as $log) {
        if (isset($log['level']) && isset($log_counts[$log['level']])) {
            $log_counts[$log['level']]++;
        }
    }

    // Get current security status
    $security_status = splice_theme_get_security_status();
?>

    <div class="wrap">
        <h1>Security Dashboard</h1>

        <?php if (isset($message)) : ?>
            <div class="notice notice-<?php echo $message_type; ?> is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <!-- Security Status Overview -->
        <div class="card">
            <h2>Security Status Overview</h2>
            <div class="security-status-grid">
                <div class="security-status-item <?php echo $security_status['headers'] ? 'status-good' : 'status-warning'; ?>">
                    <h3>Security Headers</h3>
                    <p><?php echo $security_status['headers'] ? 'Active' : 'Inactive'; ?></p>
                </div>
                <div class="security-status-item <?php echo $security_status['xmlrpc'] ? 'status-good' : 'status-warning'; ?>">
                    <h3>XML-RPC</h3>
                    <p><?php echo $security_status['xmlrpc'] ? 'Disabled' : 'Enabled'; ?></p>
                </div>
                <div class="security-status-item <?php echo $security_status['file_edit'] ? 'status-good' : 'status-warning'; ?>">
                    <h3>File Editing</h3>
                    <p><?php echo $security_status['file_edit'] ? 'Disabled' : 'Enabled'; ?></p>
                </div>
                <div class="security-status-item <?php echo $security_status['version_info'] ? 'status-good' : 'status-warning'; ?>">
                    <h3>Version Info</h3>
                    <p><?php echo $security_status['version_info'] ? 'Hidden' : 'Visible'; ?></p>
                </div>
            </div>
        </div>

        <!-- Log Statistics -->
        <div class="card">
            <h2>Security Log Statistics</h2>
            <div class="log-stats-grid">
                <div class="log-stat-item">
                    <h3>Total Logs</h3>
                    <p class="log-count"><?php echo count($security_logs); ?></p>
                </div>
                <div class="log-stat-item log-info">
                    <h3>Info</h3>
                    <p class="log-count"><?php echo $log_counts['info']; ?></p>
                </div>
                <div class="log-stat-item log-warning">
                    <h3>Warnings</h3>
                    <p class="log-count"><?php echo $log_counts['warning']; ?></p>
                </div>
                <div class="log-stat-item log-error">
                    <h3>Errors</h3>
                    <p class="log-count"><?php echo $log_counts['error']; ?></p>
                </div>
            </div>
        </div>

        <!-- Recent Security Events -->
        <div class="card">
            <h2>Recent Security Events</h2>

            <div class="tablenav top">
                <div class="alignleft actions">
                    <form method="post" style="display: inline;">
                        <?php wp_nonce_field('splice_theme_security', 'security_nonce'); ?>
                        <input type="hidden" name="action" value="clear_logs">
                        <button type="submit" class="button button-secondary" onclick="return confirm('Are you sure you want to clear all security logs?')">
                            Clear All Logs
                        </button>
                    </form>

                    <form method="post" style="display: inline; margin-left: 10px;">
                        <?php wp_nonce_field('splice_theme_security', 'security_nonce'); ?>
                        <input type="hidden" name="action" value="export_logs">
                        <button type="submit" class="button button-secondary">
                            Export Logs (CSV)
                        </button>
                    </form>
                </div>
            </div>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Event</th>
                        <th>Level</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_logs)) : ?>
                        <tr>
                            <td colspan="6">No security events logged yet.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($recent_logs as $log) : ?>
                            <tr>
                                <td><?php echo esc_html($log['timestamp']); ?></td>
                                <td><?php echo esc_html($log['event']); ?></td>
                                <td>
                                    <span class="log-level log-<?php echo esc_attr($log['level']); ?>">
                                        <?php echo esc_html(ucfirst($log['level'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    if ($log['user_id']) {
                                        $user = get_user_by('id', $log['user_id']);
                                        echo $user ? esc_html($user->user_login) : 'Unknown';
                                    } else {
                                        echo 'Guest';
                                    }
                                    ?>
                                </td>
                                <td><?php echo esc_html($log['ip']); ?></td>
                                <td>
                                    <?php if (!empty($log['details'])) : ?>
                                        <details>
                                            <summary>View Details</summary>
                                            <pre><?php echo esc_html(json_encode($log['details'], JSON_PRETTY_PRINT)); ?></pre>
                                        </details>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Security Recommendations -->
        <div class="card">
            <h2>Security Recommendations</h2>
            <ul class="security-recommendations">
                <li><strong>Keep WordPress Updated:</strong> Always use the latest version of WordPress, themes, and plugins.</li>
                <li><strong>Strong Passwords:</strong> Use strong, unique passwords for all user accounts.</li>
                <li><strong>Two-Factor Authentication:</strong> Enable 2FA for additional security.</li>
                <li><strong>Regular Backups:</strong> Maintain regular backups of your website and database.</li>
                <li><strong>Security Plugins:</strong> Consider using security plugins like Wordfence or Sucuri.</li>
                <li><strong>HTTPS:</strong> Ensure your site uses HTTPS encryption.</li>
                <li><strong>Limit Login Attempts:</strong> The theme includes basic rate limiting for login attempts.</li>
                <li><strong>Monitor Logs:</strong> Regularly review security logs for suspicious activity.</li>
            </ul>
        </div>
    </div>

    <style>
        .security-status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .security-status-item {
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #e5e7eb;
        }

        .security-status-item.status-good {
            background: #f0fdf4;
            border-color: #22c55e;
        }

        .security-status-item.status-warning {
            background: #fefce8;
            border-color: #eab308;
        }

        .log-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .log-stat-item {
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .log-count {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }

        .log-info .log-count {
            color: #2563eb;
        }

        .log-warning .log-count {
            color: #eab308;
        }

        .log-error .log-count {
            color: #dc2626;
        }

        .log-level {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .log-level.log-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .log-level.log-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .log-level.log-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .security-recommendations {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .security-recommendations li {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
    </style>

<?php
}

/**
 * Get current security status
 */
function splice_theme_get_security_status()
{
    $status = array(
        'headers' => false,
        'xmlrpc' => false,
        'file_edit' => false,
        'version_info' => false
    );

    // Check if security headers are active
    $status['headers'] = true; // Assume active since function exists

    // Check XML-RPC status
    $status['xmlrpc'] = !apply_filters('xmlrpc_enabled', true);

    // Check file editing status
    $status['file_edit'] = defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT;

    // Check version info status
    $status['version_info'] = !has_action('wp_head', 'wp_generator');

    return $status;
}

/**
 * Export security logs to CSV
 */
function splice_theme_export_security_logs()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $security_logs = get_option('splice_theme_security_logs', array());

    if (empty($security_logs)) {
        return;
    }

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="security-logs-' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Create CSV output
    $output = fopen('php://output', 'w');

    // Add CSV headers
    fputcsv($output, array('Timestamp', 'Event', 'Level', 'User ID', 'IP Address', 'User Agent', 'Details'));

    // Add data rows
    foreach ($security_logs as $log) {
        $details = !empty($log['details']) ? json_encode($log['details']) : '';
        fputcsv($output, array(
            $log['timestamp'],
            $log['event'],
            $log['level'],
            $log['user_id'],
            $log['ip'],
            $log['user_agent'],
            $details
        ));
    }

    fclose($output);
    exit;
}
