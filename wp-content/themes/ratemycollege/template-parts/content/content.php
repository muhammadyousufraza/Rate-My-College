<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="image">
    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
  </div>
  <div class="info">
    <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
    <div class="meta">
      <?php printf(
				/* translators: 1: SVG icon. 2: Post author, only visible to screen readers. 3: Author link. */
				'<span class="byline"><span class="screen-reader-text">%1$s</span> <span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span></span>',
				__('Posted by', 'twentynineteen'),
				esc_url(get_author_posts_url(get_the_author_meta('ID'))),
				esc_html(get_the_author())
			); ?>
    </div>
  </div>
</article><!-- #post-<?php the_ID(); ?> -->