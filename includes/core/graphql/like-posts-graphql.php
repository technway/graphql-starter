<?php
/**
 * GraphQL integration for post likes functionality
 * 
 * Implementation details:
 * - Uses post meta '_like_count' to store total likes per post
 * - Tracks anonymous users via secure cookies (wp_anonymous_id)
 * - Prevents duplicate likes through user/cookie validation
 * - Implements rate limiting (max 10 likes/minute per user)
 * - Handles both logged-in and anonymous users
 * - Maintains data integrity with safe integer operations
 * - Provides clear error messages for invalid requests
 * 
 * @package GraphQL_Starter
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit('Direct access to this file is not permitted.');
}

// Include helper functions for like/favorite functionality
include_once get_template_directory() . '/includes/core/graphql/helpers.php';

/**
 * Register post meta field for storing like counts
 */
add_action('init', function() {
    register_post_meta('post', '_like_count', [
        'type' => 'integer',
        'single' => true,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'auth_callback' => '__return_true'
    ]);
});

// Add likeCount field to Post type
add_action('graphql_register_types', function() {
    // Add likeCount field
    register_graphql_field('Post', 'likeCount', [
        'type' => 'Int',
        'description' => __('The total number of likes for this post', 'graphql-starter'),
        'resolve' => function($post) {
            $likes = get_post_meta($post->ID, '_like_count', true);
            return !empty($likes) ? (int) $likes : 0;
        }
    ]);

    // Register toggleLike mutation
    register_graphql_mutation('toggleLike', [
        'inputFields' => [
            'id' => [
                'type' => ['non_null' => 'ID'],
                'description' => __('The ID of the post to toggle like status', 'graphql-starter'),
            ],
        ],
        'outputFields' => [
            'post' => [
                'type' => 'Post',
                'resolve' => function($payload) {
                    return \WPGraphQL\Data\DataSource::resolve_post_object($payload['postId'], $context);
                }
            ]
        ],
        'mutateAndGetPayload' => function($input) {
            // Rate limiting
            if (!graphql_starter_check_rate_limit('toggle_like', 10)) {
                throw new \GraphQL\Error\UserError(__('Rate limit exceeded. Please wait a moment before trying again.', 'graphql-starter'));
            }

            // Validate post ID
            $post_id = absint($input['id']);
            $post = get_post($post_id);
            // Validate that the post exists in the database before proceeding
            if (!$post) {
                throw new \GraphQL\Error\UserError(__('The specified post could not be found.', 'graphql-starter'));
            }

            // Only allow likes on standard posts, not other post types like pages or custom types
            if ($post->post_type !== 'post') {
                throw new \GraphQL\Error\UserError(__('This content type does not support likes.', 'graphql-starter'));
            }

            // Prevent liking draft or private posts (only published posts can receive likes)
            if ($post->post_status !== 'publish') {
                throw new \GraphQL\Error\UserError(__('Only published posts can receive likes.', 'graphql-starter'));
            }

            // Get current like count
            $current_likes = absint(get_post_meta($post_id, '_like_count', true));

            // Handle likes
            $user_id = get_current_user_id();

            if ($user_id) {
                // If the user is logged in, use the user ID to track likes.

                $user_likes = get_user_meta($user_id, '_user_likes', true) ?: [];
                $is_liked = in_array($post_id, $user_likes, true);
                
                if ($is_liked) {
                    // If the user has already liked the post, remove the post ID from the user's likes array. (Unlike the post)

                    $user_likes = array_values(array_diff($user_likes, [$post_id]));
                    update_user_meta($user_id, '_user_likes', $user_likes);
                    update_post_meta($post_id, '_like_count', max(0, $current_likes - 1));
                } else {
                    // If the user has not already liked the post, add the post ID to the user's likes array. (Like the post)

                    $user_likes[] = $post_id;
                    update_user_meta($user_id, '_user_likes', array_unique($user_likes));
                    update_post_meta($post_id, '_like_count', $current_likes + 1);
                }
            } else {
                // If the user is anonymous, use the anonymous ID to track likes.

                $anonymous_id = isset($_COOKIE['wp_anonymous_id']) 
                    ? sanitize_text_field($_COOKIE['wp_anonymous_id'])
                    : graphql_starter_generate_anonymous_id();
                
                if (!isset($_COOKIE['wp_anonymous_id'])) {
                    setcookie('wp_anonymous_id', $anonymous_id, time() + (86400 * 30), '/', '', true, true);
                }

                $anonymous_likes = get_option('anonymous_likes_' . $anonymous_id, []);
                $is_liked = in_array($post_id, $anonymous_likes, true);
                
                if ($is_liked) {
                    // If the user has already liked the post, remove the post ID from the anonymous user's likes array. (Unlike the post)

                    $anonymous_likes = array_values(array_diff($anonymous_likes, [$post_id]));
                    update_option('anonymous_likes_' . $anonymous_id, $anonymous_likes);
                    update_post_meta($post_id, '_like_count', max(0, $current_likes - 1));
                } else {
                    // If the user has not already liked the post, add the post ID to the anonymous user's likes array. (Like the post)

                    $anonymous_likes[] = $post_id;
                    update_option('anonymous_likes_' . $anonymous_id, array_unique($anonymous_likes));
                    update_post_meta($post_id, '_like_count', $current_likes + 1);
                }
            }

            // Log the action
            graphql_starter_log_like_action($post_id, $user_id ?? $anonymous_id, !$is_liked);

            // Return the post ID. 
            return [
                'postId' => $post_id
            ];
        }
    ]);
});