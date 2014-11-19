<?php
/**
* The template for displaying Archive pages.
*
* Used to display archive-type pages if nothing more specific matches a query.
* For example, puts together date-based pages if no date.php file exists.
*
* Learn more: http://codex.wordpress.org/Template_Hierarchy
*/

global $wp_query;

$templates = array(
	'archive-download.twig',
);

$data = Timber::get_context();

$data['title'] = __( 'Downloads', 'maera_edd' );
$data['posts'] = Timber::query_posts( false, 'TimberPost' );
$data['query'] = $wp_query->query_vars;
Timber::render( $templates, $data, apply_filters( 'maera/timber/cache', false ) );
