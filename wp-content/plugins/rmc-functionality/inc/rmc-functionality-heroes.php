<?php

// Register Horoes Custom Post Type
function rmc_heroes()
{

  $labels = array(
    'name'                  => _x('Heroes', 'Post Type General Name', 'rmc'),
    'singular_name'         => _x('Hero', 'Post Type Singular Name', 'rmc'),
    'menu_name'             => __('Page Heroes', 'rmc'),
    'name_admin_bar'        => __('Heroes', 'rmc'),
    'archives'              => __('Hero Archives', 'rmc'),
    'attributes'            => __('Hero Attributes', 'rmc'),
    'parent_item_colon'     => __('Parent Hero:', 'rmc'),
    'all_items'             => __('All Heroes', 'rmc'),
    'add_new_item'          => __('Add New Hero', 'rmc'),
    'add_new'               => __('Add New', 'rmc'),
    'new_item'              => __('New Item', 'rmc'),
    'edit_item'             => __('Edit Item', 'rmc'),
    'update_item'           => __('Update Item', 'rmc'),
    'view_item'             => __('View Item', 'rmc'),
    'view_items'            => __('View Items', 'rmc'),
    'search_items'          => __('Search Item', 'rmc'),
    'not_found'             => __('Not found', 'rmc'),
    'not_found_in_trash'    => __('Not found in Trash', 'rmc'),
    'featured_image'        => __('Featured Image', 'rmc'),
    'set_featured_image'    => __('Set featured image', 'rmc'),
    'remove_featured_image' => __('Remove featured image', 'rmc'),
    'use_featured_image'    => __('Use as featured image', 'rmc'),
    'insert_into_item'      => __('Insert into item', 'rmc'),
    'uploaded_to_this_item' => __('Uploaded to this item', 'rmc'),
    'items_list'            => __('Items list', 'rmc'),
    'items_list_navigation' => __('Items list navigation', 'rmc'),
    'filter_items_list'     => __('Filter items list', 'rmc'),
  );
  $args = array(
    'label'                 => __('Hero', 'rmc'),
    'description'           => __('Page Hero Manager', 'rmc'),
    'labels'                => $labels,
    'supports'              => array('title'),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'menu_icon'             => 'dashicons-cover-image',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => false,
    'exclude_from_search'   => true,
    'publicly_queryable'    => true,
    'capability_type'       => 'page',
  );
  register_post_type('rmc_heroes', $args);
}

add_action('init', 'rmc_heroes', 0);

// register new image size
add_action('after_setup_theme', function () {
  add_image_size('hero-banner', 1920, 600, true);
});

// use genesis hook to show heroes
add_action('genesis_after_header', function () {
  $heroes = get_posts(array(
    'post_type' => 'rmc_heroes',
    'numberposts' => -1
  ));

  $page_id = get_the_ID();

  foreach ($heroes as $hero) {
    if (get_field('show_on_page', $hero->ID) === $page_id) {
      if (have_rows('slides', $hero->ID)) : ?>
<div class="hero-slider">
  <?php while (have_rows('slides', $hero->ID)) : the_row();
            $image = get_sub_field('background_image');
            if ($image) { ?>
  <div class="slide">
    <?= wp_get_attachment_image($image, 'hero-banner', false); ?>
    <?php
                $text = get_sub_field('banner_text');
                if ($text) { ?>
    <div class="text-wrap">
      <div class="text"><?= nl2br($text) ?></div>
    </div>
    <?php }
                ?>
  </div>
  <?php } ?>
  <?php endwhile; ?>
</div>
<script>
jQuery(document).ready(function() {
  jQuery('.hero-slider').slick({
    arrows: false,
    autoplay: true,
    fade: true,
    speed: 2000,
    pauseOnFocus: false,
    pauseOnHover: false
  });
});
</script>
<?php endif;
    }
  }
});