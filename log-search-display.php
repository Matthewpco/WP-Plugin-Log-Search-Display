<?php
/*
Plugin Name: Log Search & Display
Plugin URI: https://github.com/Matthewpco/WP-Plugin-Log-Search-&-Display
Description: A plugin that scans your site for .log files and displays the file path as well as contents in the tools submenu.
Author: Gary Matthew Payne
Version: 1.0.0
Author URI: https://wpwebdevelopment.com
*/

function log_search_submenu() {
    add_submenu_page(
        'tools.php',
        'Log Search & Display',
        'Log Search & Display',
        'manage_options',
        'error-checker',
        'log_search_page'
    );
}

add_action('admin_menu', 'log_search_submenu');

function log_search_page() {
    // Check for errors, warnings, and notices here
    // Display them on the page

    try {
        // Look for .log files in all directories
        $directory = new RecursiveDirectoryIterator('../..');
        $iterator = new RecursiveIteratorIterator($directory);
        $log_files = new RegexIterator($iterator, '/^.+\.log$/i', RecursiveRegexIterator::GET_MATCH);

        if (empty(iterator_to_array($log_files))) {
            // No log files found
            echo '<p>No log files found.</p>';
        } else {
            // Read and display the contents of each log file
            foreach ($log_files as $log_file) {
                $log_contents = file_get_contents($log_file[0]);
                echo '<h2>' . $log_file[0] . '</h2>';
                echo '<pre>' . $log_contents . '</pre>';
            }
        }
    } catch (Exception $e) {
        // An error occurred
        echo '<p>An error occurred: ' . $e->getMessage() . '</p>';
    }
}