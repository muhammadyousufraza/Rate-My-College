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
  <header class="entry-header">
    <div class="category-list">
      <?php
			$categories_list = get_the_category_list(__(', ', 'twentynineteen'));
			if ($categories_list) {
				printf(
					/* translators: 1: SVG icon. 2: Posted in label, only visible to screen readers. 3: List of categories. */
					'<span class="cat-links">%1$s</span>',
					$categories_list
				); // WPCS: XSS OK.
			}
			?>
    </div>
    <h1><?php the_title(); ?></h1>
    <?php if (get_field('subtitle')) { ?>
    <div class="subtitle"><?php echo get_field('subtitle'); ?></div>
    <?php } ?>
  </header>
  <div class="meta">
    <?php
		printf(
			/* translators: 1: SVG icon. 2: Post author, only visible to screen readers. 3: Author link. */
			'<span class="byline"><span class="screen-reader-text">%1$s</span> <span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span></span>',
			__('Posted by', 'twentynineteen'),
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			esc_html(get_the_author())
		);

		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="updated" datetime="%1$s">%2$s</time>';
		}
		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_modified_date(DATE_W3C)),
			esc_html(get_the_modified_date())
		);
		printf(
			'<span class="posted-on">%2$s</span>',
			esc_url(get_permalink()),
			$time_string
		);
		?>
  </div>

  <div class="entry-content">
    <?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Post title. Only visible to screen readers. */
					__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentynineteen'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __('Pages:', 'twentynineteen'),
				'after'  => '</div>',
			)
		);
		?>
  </div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->