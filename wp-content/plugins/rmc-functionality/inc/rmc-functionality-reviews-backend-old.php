<?php

/* Backend reviews manager */

/**
 * Register a custom menu page.
 * 
 * @return void
 */
function rmc_register_reviews_menu()
{
  add_menu_page(
    __('Reviews', 'textdomain'),
    'Reviews',
    'manage_options',
    'reviews',
    'rmc_reviews_page',
    'dashicons-megaphone',
    6
  );
}

add_action('admin_menu', 'rmc_register_reviews_menu');

/**
 * Reviews Page HTML
 *
 * @return void
 */
function rmc_reviews_page()
{
  global $wpdb;
  
  echo '<pre>';
  print_r($wpdb->get_results("SELECT * FROM {$wpdb->prefix}coach_rate ORDER BY id DESC",
  OBJECT);
  
  $reviews = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}coach_rate ORDER BY id DESC",
  OBJECT); 
  
  ?>
<div class="wrap">
  <h1 class="wp-heading-inline"><?php _e('Reviews', 'rmc'); ?></h1>
  <br /><br />
  <div id="sort-dropdowns"></div>
  <?php if (count($reviews) > 0) { ?>
  <table id="reviews-list" class="wp-list-table widefat striped posts">
    <thead>
      <tr>
        <td>ID</td>
        <td>Coach</td>
        <td>School</td>
        <td>Sport</td>
        <td>IQ</td>
        <td>In</td>
        <td>E</td>
        <td>A</td>
        <td>S</td>
        <td>R</td>
        <td>T</td>
        <td>Comment</td>
        <td>Date</td>
        <td>Actions</td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($reviews as $review) { ?>
      <tr>
        <td><?= $review->id; ?></td>
        <td><a href="<?= get_permalink($review->post_id); ?>"
            target="_blank"><?= get_the_title($review->post_id); ?></a></td>
        <td><?= get_the_title(get_field('school', $review->post_id)); ?></td>
        <td><?= strip_tags(get_the_term_list($review->post_id, 'rmc_sports', '', ', ', '')); ?></td>
        <td><?= $review->iq; ?></td>
        <td><?= $review->individual; ?></td>
        <td><?= $review->personable; ?></td>
        <td><?= $review->academics; ?></td>
        <td><?= $review->staff; ?></td>
        <td><?= ($review->answer1 == 1) ? 'Yes' : 'No'; ?></td>
        <td><?= ($review->answer2 == 1) ? 'Yes' : 'No'; ?></td>
        <td><span class="review-content"><?= strip_tags($review->review_content); ?></span></td>
        <td><small><?= $review->datetime; ?></small></td>
        <td><button class="delete-review" data-review="<?= $review->id; ?>"><?php _e('Delete'); ?></button></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php } else {
      _e('There are no reviews yet', 'rmc');
    }

    ?>
</div>
<?php }

/**
 * Enqueue styles and scripts
 */
function rmc_reviews_admin_styles_scripts()
{
  wp_enqueue_style('rmc-admin-styles', plugin_dir_url(__FILE__) . 'css/admin-styles.css');
  wp_enqueue_style('rmc-dt-style', '//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css');
  wp_enqueue_script('rmc-dt-script', '//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js', array('jquery'), '1.0.0', true);
  wp_enqueue_script('rmc-dt-buttons', '//cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js', null, '1.0.0', true);
  wp_enqueue_script('rmc-dt-zip', '//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js', null, '1.0.0', true);
  wp_enqueue_script('rmc-dt-pdf', '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js', null, '1.0.0', true);
  wp_enqueue_script('rmc-dt-pdf-fonts', '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js', null, '1.0.0', true);
  wp_enqueue_script('rmc-dt-html5-buttons', '//cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js', null, '1.0.0', true);
  wp_enqueue_script('rmc-admin-script', plugin_dir_url(__FILE__) . 'js/admin-scripts.js', array('jquery'), '1.0.0', true);
}

add_action('admin_enqueue_scripts', 'rmc_reviews_admin_styles_scripts');


/**
 * Delete Review
 *
 * @return void
 */
function rmc_delete_review()
{
  global $wpdb;

  if (is_admin() && isset($_GET['page']) && $_GET['page'] == 'reviews' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {

    $wpdb->delete("{$wpdb->prefix}coach_rate", array('id' => $_GET['id']));

    wp_redirect(get_admin_url() . '/admin.php?page=reviews');
  }
}

add_action('init', 'rmc_delete_review');