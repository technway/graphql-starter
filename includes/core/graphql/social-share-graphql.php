<?php
/**
 * GraphQL integration for social sharing functionality
 * 
 * Adds social sharing URLs to posts in GraphQL schema
 * 
 * @package GraphQL_Starter
 */

if (!defined('ABSPATH')) {
    exit('Direct access to this file is not permitted.');
}

/**
 * Register socialShare field to Post type in GraphQL
 */
add_action('init', function() {
    // Register the types after WPGraphQL initializes
    add_action('graphql_register_types', function() {
        // First register the object type
        register_graphql_object_type('PostSocialShare', [
            'description' => __('Social sharing URLs for a post', 'graphql-starter'),
            'fields' => [
                'facebook' => [
                    'type' => 'String',
                    'description' => __('Facebook sharing URL', 'graphql-starter'),
                ],
                'twitter' => [
                    'type' => 'String',
                    'description' => __('Twitter sharing URL', 'graphql-starter'),
                ],
                'linkedin' => [
                    'type' => 'String',
                    'description' => __('LinkedIn sharing URL', 'graphql-starter'),
                ],
                'whatsapp' => [
                    'type' => 'String',
                    'description' => __('WhatsApp sharing URL', 'graphql-starter'),
                ],
                'telegram' => [
                    'type' => 'String',
                    'description' => __('Telegram sharing URL', 'graphql-starter'),
                ],
                'email' => [
                    'type' => 'String',
                    'description' => __('Email sharing URL', 'graphql-starter'),
                ],
                'copyLink' => [
                    'type' => 'String',
                    'description' => __('Direct link to copy', 'graphql-starter'),
                ],
            ],
        ]);

        // Then register the field on the Post type
        register_graphql_field('Post', 'socialShare', [
            'type' => 'PostSocialShare',
            'description' => __('Social sharing URLs for this post', 'graphql-starter'),
            'resolve' => function($post) {
                if (!$post instanceof \WPGraphQL\Model\Post) {
                    return null;
                }

                $permalink = get_permalink($post->databaseId);
                $title = get_the_title($post->databaseId);
                $encoded_url = urlencode($permalink);
                $encoded_title = urlencode($title);

                // Get excerpt or generate one from content
                $excerpt = get_the_excerpt($post->databaseId);
                if (empty($excerpt)) {
                    $content = get_post_field('post_content', $post->databaseId);
                    $excerpt = wp_trim_words($content, 20);
                }
                $encoded_excerpt = urlencode($excerpt);

                return [
                    // Facebook sharing
                    'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$encoded_url}",
                    
                    // Twitter sharing
                    'twitter' => "https://twitter.com/intent/tweet?url={$encoded_url}&text={$encoded_title}",
                    
                    // LinkedIn sharing
                    'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$encoded_url}",
                    
                    // WhatsApp sharing
                    'whatsapp' => "https://wa.me/?text={$encoded_title}%20{$encoded_url}",
                    
                    // Telegram sharing
                    'telegram' => "https://t.me/share/url?url={$encoded_url}&text={$encoded_title}",
                    
                    // Email sharing
                    'email' => "mailto:?subject={$encoded_title}&body={$encoded_url}",
                    
                    // Direct link
                    'copyLink' => $permalink,
                ];
            }
        ]);
    });
}); 