<?php
/**
 * @package elune
 * File for registering options page in back end
 * @link https://www.advancedcustomfields.com/resources/options-page/
 */

add_action( 'acf/init', function() {

  acf_add_options_page( [
    'page_title' => 'Theme Settings',
    'menu_slug' => 'theme-settings',
    'icon_url' => 'dashicons-tagcloud',
    'menu_title' => 'Theme Settings',
    'position' => 1000,
    'redirect' => true,
  ] );

  acf_add_options_page( [
    'page_title' => 'Header Settings',
    'menu_slug' => 'header-settings',
    'menu_title' => 'Header Settings',
    'parent_slug' => 'theme-settings',
    'position' => 0,
    'redirect' => false,
  ] );

  acf_add_options_page( [
    'page_title' => 'Footer Settings',
    'menu_slug' => 'footer-settings',
    'menu_title' => 'Footer Settings',
    'parent_slug' => 'theme-settings',
    'position' => 1,
    'redirect' => false,
  ] );

  acf_add_options_page( [
    'page_title' => 'Social Media Settings',
    'menu_slug' => 'social-media-settings',
    'menu_title' => 'Social Media Settings',
    'parent_slug' => 'theme-settings',
    'position' => 3,
    'redirect' => false,
  ] );
} );