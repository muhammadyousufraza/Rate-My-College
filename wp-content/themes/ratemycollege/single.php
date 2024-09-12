<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content/content', 'single' );

      endwhile; // End of the loop.

			?>

		</main><!-- #main -->

    <?php if( is_singular('post') ) { 
      // show latest posts ?>
      <div class="article-sidebar">
        <?php echo do_shortcode( '[latestnews count="4"]' ); ?>
      </div>
    <?php } ?>
	</div><!-- #primary -->

<?php
get_footer();