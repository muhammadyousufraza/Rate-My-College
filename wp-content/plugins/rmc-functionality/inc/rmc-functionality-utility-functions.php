<?php

/**
 * Returns a list of Sports
 *
 * @param integer $post_id
 * @return array
 */
function rmc_get_sports_list($post_id)
{
  $taxonomy = 'rmc_sports';

  $sports = wp_get_post_terms($post_id, $taxonomy);
  $sports_list = array();
  foreach ($sports as $sport) {
    if ($sport->parent == 50) { // men
      $sports_list['men'][$sport->term_id] = str_replace("Men's", "", $sport->name);
    }

    if ($sport->parent == 53) { // women
      $sports_list['women'][$sport->term_id] = str_replace("Women's", "", $sport->name);
    }
  }

  return $sports_list;
}

/**
 * Returns a list of Coaches for a particular school and sports
 *
 * @param integer $post_id
 * @param integer $term_id
 * @return array
 */
function rmc_get_school_sport_coaches($post_id, $term_id)
{
  $taxonomy = 'rmc_sports';

  // get coaches for this sport and school
  $coaches = get_posts(array(
    'post_type' => 'coach',
    'numberposts' => -1,
    'meta_key'     => 'school',
    'meta_value'   => $post_id,
    'meta_compare' => '=',
    'tax_query' => array(
      array(
        'taxonomy' => $taxonomy,
        'field'    => 'id',
        'terms'    => $term_id,
      ),
    ),
  ));

  return $coaches;
}

/**
 * Outputs HTML with a list of Coaches
 *
 * @param array $coaches
 * @return void
 */
function rmc_render_coaches_list($coaches)
{
  if (count($coaches) > 0) { ?>
<ul class="coaches">
  <?php foreach ($coaches as $coach) { ?>
  <li><a href="<?php echo get_permalink($coach->ID); ?>"><?php echo $coach->post_title; ?></a></li>
  <?php } ?>
</ul>
<?php }
}

/**
 * Get School Name from ID
 *
 * @param integer $school_id
 * @return string
 */
function rmc_get_school_name($school_id)
{
  if ((int)$school_id)
    return get_the_title($school_id);
}