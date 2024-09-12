<?php

// [break text="some text here"]
function break_function($atts)
{
  $a = shortcode_atts(array(
    'text' => '',
  ), $atts);

  return "<div class='content-break'>{$a['text']}</div>";
}

add_shortcode('break', 'break_function');

// [latestnews count="4"]
function latest_news_function($atts)
{
  $a = shortcode_atts(array(
    'count' => '4',
  ), $atts);

  $count = (int)$a['count'];

  ob_start(); ?>

<div class="related">
  <h2><?php _e('Other Stories'); ?></h2>
  <div class="latest-posts">
    <?php
      $latest_posts = new WP_Query(array(
        'posts_per_page' => 4,
        'post__not_in' => array(get_the_ID())
      ));

      if ($latest_posts->have_posts()) {
        // Load posts loop.
        while ($latest_posts->have_posts()) {
          $latest_posts->the_post();
          get_template_part('template-parts/content/content');
        }
      }
      ?>
  </div>
</div>
<div class="ad">
  <div id="303482715">
    <script type="text/javascript">
    try {
      window._mNHandle.queue.push(function() {
        window._mNDetails.loadTag("303482715", "728x90", "303482715");
      });
    } catch (error) {}
    </script>
  </div>
</div>
<?php
  $output_string = ob_get_contents();
  ob_end_clean();
  return $output_string;
  wp_reset_postdata();
}

add_shortcode('latestnews', 'latest_news_function');

// podcast player
add_shortcode('rmc_podcast_player', 'rmc_podcast_player');

// podcast links
add_shortcode('rmc_podcast_links', 'rmc_podcast_links');