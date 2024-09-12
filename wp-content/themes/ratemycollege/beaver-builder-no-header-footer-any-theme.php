<?php
/**
 * This file adds the Landing template to the Enterprise Pro Theme.
 *
 * @author StudioPress
 * @package Enterprise Pro
 * @subpackage Customizations
 */

/*
Template Name: Beaver builder no header and footer for any theme
*/
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );
	?>
    </title>

<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" media="screen" />
<?php
wp_head();

?>

</head>

<body <?php body_class( $class ); ?>>

<?php 
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); 
		the_content();
	} // end while
} // end if
?>

<?php

wp_footer(); //

?>

</body>

</html>