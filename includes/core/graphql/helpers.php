<?php
/**
 * Helper functions for GraphQL like/favorite functionality
 * 
 * @package GraphQL_Starter
 */

/**
 * Check rate limiting for an action
 * 
 * Uses WordPress transients to implement rate limiting based on IP address.
 * Limits are enforced per minute.
 *
 * @param string $action The action to rate limit (e.g. 'toggle_like')
 * @param int $limit Maximum number of requests allowed per minute
 * @return bool True if under limit, false if limit exceeded
 */
function graphql_starter_check_rate_limit(string $action, int $limit): bool {
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'rate_limit_' . $action . '_' . md5($ip);
    $current = get_transient($transient_key);
    
    if (false === $current) {
        set_transient($transient_key, 1, 60); // 1 minute
        return true;
    }
    
    if ($current >= $limit) {
        return false;
    }
    
    set_transient($transient_key, $current + 1, 60);
    return true;
}

/**
 * Generate a secure anonymous identifier for non-logged in users
 * 
 * Creates a unique hash based on user agent, IP, language and WordPress salt
 * for tracking anonymous user interactions.
 *
 * @return string Anonymized identifier prefixed with 'anon_'
 */
function graphql_starter_generate_anonymous_id(): string {
    $factors = [
        $_SERVER['HTTP_USER_AGENT'] ?? '',
        $_SERVER['REMOTE_ADDR'] ?? '',
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '',
        wp_salt('auth')
    ];
    
    return 'anon_' . md5(implode('|', $factors));
}

/**
 * Log post like/unlike actions for monitoring
 *
 * Records post interactions including user info, timestamp and action type
 * to the error log for debugging and analytics.
 *
 * @param int $post_id The ID of the post being liked/unliked
 * @param string|int $user_identifier User ID or anonymous identifier
 * @param bool $action True for like, false for unlike
 * @return void
 */
function graphql_starter_log_like_action(int $post_id, string|int $user_identifier, bool $action): void {
    $log_data = [
        'post_id' => $post_id,
        'user' => $user_identifier,
        'action' => $action ? 'like' : 'unlike',
        'timestamp' => current_time('mysql'),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ];
}
