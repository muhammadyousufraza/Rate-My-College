<?php
/*
Template Name: Full width
*/
add_action( 'genesis_meta', 'ratemycollege_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function ratemycollege_home_genesis_meta() {

		//* Force full-width-content layout setting
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

		//* Add ratemycollege-pro-home body class
		//add_filter( 'body_class', 'ratemycollege_body_class' );

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Add home top widgets
		add_action( 'genesis_after_header', 'ratemycollege_after_header' );
		
		//* Add home bottom widgets
		//add_action( 'genesis_loop', 'ratemycollege_home_bottom_widgets' );

	
}
function ratemycollege_after_header(){
	?>
    <div class="ratemycollege-full-wide">
    <?php 
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); 
		the_content();
	} // end while
} // end if
?>
    </div>
    <?php
}
genesis();