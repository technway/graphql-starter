<?php
/**
 * "Rename Theme" Script
 * 
 * This script helps you rename the GraphQL Starter theme to your custom theme name.
 * It automatically updates all theme references across multiple files while preserving
 * code blocks and formatting.
 * 
 * - Updates theme name in all relevant files (functions.php, style.css, README.md, etc.)
 * - Handles multiple name formats (regular, uppercase, lowercase, kebab-case)
 * - Preserves code blocks and formatting during replacement
 * - Provides feedback on processed files
 * 
 * Usage:
 *   Run the script from the root of the theme directory:
 *   php ./scripts/rename-theme.php "Your New Theme Name"
 * 
 * Example:
 *   php ./scripts/rename-theme.php "My Custom GraphQL"
 */

if ($argc < 2) {
    echo "Usage: php rename-theme.php '<New Theme Name>'\n";
    exit(1);
}

$newThemeName = trim($argv[1]);

// Generate name variations
$newThemeNameTrimmed = preg_replace('/\s+/', '_', $newThemeName);
$newThemeNameLower = strtolower($newThemeNameTrimmed);
$newThemeNameKebab = strtolower(str_replace('_', '-', $newThemeNameTrimmed));
$newThemeNameUnderscored = str_replace(' ', '_', $newThemeName);

$starterThemeName = "GraphQL Starter";
$starterThemeNameUpper = "_GRAPHQL_STARTER_";
$starterThemeNameLower = "graphql_starter";
$starterThemeNameKebab = "graphql-starter";
$starterThemeNameUnderscored = "GraphQL_Starter";

// Define files to update
$filesToUpdate = [
    'functions.php',
    'theme-config.php',
    'readme.txt',
    'style.css',
    'README.md',
    'testing-graphql/testing-graphql.md',
];

foreach ($filesToUpdate as $file) {
    if (!file_exists($file)) {
        echo "File not found: $file\n";
        continue;
    }

    $content = file_get_contents($file);
    
    // Temporarily replace content between backticks with placeholders
    $backtickBlocks = [];
    $content = preg_replace_callback('/`[^`]*`/', function($matches) use (&$backtickBlocks) {
        $placeholder = '{{BACKTICK_BLOCK_' . count($backtickBlocks) . '}}';
        $backtickBlocks[] = $matches[0];
        return $placeholder;
    }, $content);

    // Replace variations of the starter theme name
    $content = str_replace($starterThemeName, $newThemeName, $content);
    $content = str_replace($starterThemeNameUpper, '_' . strtoupper($newThemeNameTrimmed) . '_', $content);
    $content = str_replace($starterThemeNameLower, $newThemeNameLower, $content);
    $content = str_replace($starterThemeNameKebab, $newThemeNameKebab, $content);
    $content = str_replace($starterThemeNameUnderscored, $newThemeNameUnderscored, $content);

    // Restore backtick blocks
    foreach ($backtickBlocks as $index => $block) {
        $content = str_replace('{{BACKTICK_BLOCK_' . $index . '}}', $block, $content);
    }

    file_put_contents($file, $content);
    echo "Updated: $file\n";
}

echo "Theme successfully renamed to '$newThemeName'.\n";
