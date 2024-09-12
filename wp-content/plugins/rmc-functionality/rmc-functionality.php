<?php

/**
 * Plugin Name:       RMC Functionality
 * Plugin URI:        n/a
 * Description:       Functionality Plugin for RateMyCollege
 * Version:           1.0.0
 * Author:            Paul Portelli
 * Author URI:        https://www.paulportelli.mt
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ratemycollege
 * Domain Path:       /languages
 */

/**
 * Enqueue the required scripts and styles
 *
 * @return void
 */
function rmc_autosuggest_styles_scripts()
{
  wp_enqueue_style('jquery-ui-styles', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
  wp_enqueue_style('rmc-styles', plugin_dir_url(__FILE__) . 'inc/css/rmc-functionality.css');
  wp_enqueue_style('rmc-slick', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');

  wp_enqueue_script('rmc-script', plugin_dir_url(__FILE__) . 'inc/js/scripts.js', array('jquery', 'jquery-ui-autocomplete'), '1.0.0', true);
  wp_enqueue_script('rmc-slick', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'rmc_autosuggest_styles_scripts');

/**
 * Add Google Adsence script
 */

add_action('wp_head', function () { ?>
<script data-ad-client="ca-pub-6461958168594703" async
  src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<?php });

/**
 * Require files for more specific functionality
 */
include 'inc/rmc-functionality-import-extras.php';
include 'inc/rmc-functionality-utility-functions.php';
include 'inc/rmc-functionality-search-autosuggest.php';
include 'inc/rmc-functionality-reviews-backend.php';
include 'inc/rmc-functionality-podcasts.php';
include 'inc/rmc-functionality-shortcodes.php';
include 'inc/rmc-functionality-heroes.php';