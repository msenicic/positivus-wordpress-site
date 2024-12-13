<?php
/**
 * The template for displaying home page.
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post     = Timber::get_post();
$context['post'] = $timber_post;
//$context['content'] = $timber_post->content();
$context['content'] = get_flexible('content');

Timber::render( array( 'page-home.twig', 'page.twig' ), $context );
