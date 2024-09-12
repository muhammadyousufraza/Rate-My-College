<?php
//* Start the engine
include_once get_template_directory() . "/lib/init.php";
//* Setup Theme
include_once get_stylesheet_directory() . "/lib/theme-defaults.php";
//* Set Localization (do not remove)
load_child_theme_textdomain(
  "standard",
  apply_filters(
    "child_theme_textdomain",
    get_stylesheet_directory() . "/languages",
    "standard"
  )
);
//* Child theme (do not remove)
define("CHILD_THEME_NAME", __("ratemycollege Theme", "standard"));
define("CHILD_THEME_URL", "");
define("CHILD_THEME_VERSION", "1.0.0");
define("THEME_URL", get_stylesheet_directory_uri());
if (!defined("THEME_DIR")) {
  define("THEME_DIR", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}
// // 1. customize ACF path
// add_filter('acf/settings/path', 'my_acf_settings_path');
// function my_acf_settings_path( $path ) {
//     // update path
//     $path = get_stylesheet_directory() . '/includes/custom-fields/';
//     // return
//     return $path;
// }
// // 2. customize ACF dir
// add_filter('acf/settings/dir', 'my_acf_settings_dir');
// function my_acf_settings_dir( $dir ) {
//     // update path
//     $dir = THEME_URL . '/includes/custom-fields/';
//     // return
//     return $dir;
// }
// // 3. Hide ACF field group menu item
// add_filter('acf/settings/show_admin', '__return_true');
// // 4. Include ACF
// include_once( get_stylesheet_directory(). '/includes/custom-fields/acf.php' );
//* Add HTML5 markup structure
add_theme_support("html5", [
  "search-form",
  "comment-form",
  "comment-list",
  "gallery",
  "caption",
]);
//* Add viewport meta tag for mobile browsers
add_theme_support("genesis-responsive-viewport");
//* Enqueue Scripts
add_action("wp_enqueue_scripts", "standard_load_scripts");
function standard_load_scripts()
{
  wp_enqueue_script(
    "ratemycollege-bootstrap-js",
    get_bloginfo("stylesheet_directory") . "/bootstrap/js/bootstrap.min.js",
    ["jquery"],
    "4.3.0"
  );
  wp_enqueue_script(
    "ratemycollege-js",
    get_bloginfo("stylesheet_directory") . "/js/ratemycollege.js",
    ["jquery"],
    "1.0.0"
  );
  wp_localize_script(
    "ratemycollege-js",
    "ratemycollege_vars",
    apply_filters("ratemycollege_vars", [
      "ajax_url" => admin_url("admin-ajax.php", "relative"),
    ])
  );
  wp_enqueue_style("dashicons");
  wp_enqueue_style(
    "ratemycollege-bootstrap-css",
    CHILD_URL . "/bootstrap/css/bootstrap.css",
    [],
    "4.3.0"
  );
  wp_enqueue_style(
    "ratemycollege-font-awesome",
    CHILD_URL . "/includes/fonts/font-awesome/css/font-awesome.min.css",
    [],
    PARENT_THEME_VERSION
  );
  wp_enqueue_style(
    "ratemycollege-custom-css",
    CHILD_URL . "/add.css",
    [],
    PARENT_THEME_VERSION
  );
}
//* Add new image sizes
add_image_size("recent_post_left", 380, 227, ["center", "top"]);
add_image_size("coach", 258, 258, ["center", "top"]);
//* Add support for custom header
add_theme_support("custom-header", [
  "header-selector" => ".site-title a",
  "header-text" => false,
  "height" => 80,
  "width" => 320,
]);
//* Add support for structural wraps
add_theme_support("genesis-structural-wraps", [
  "header",
  "nav",
  "subnav",
  "site-inner",
  "footer-widgets",
  "footer",
]);
//* Reposition the secondary navigation menu
remove_action("genesis_after_header", "genesis_do_subnav");
add_action("genesis_footer", "genesis_do_subnav", 7);
//* Reduce the secondary navigation menu to one level depth
add_filter("wp_nav_menu_args", "standard_secondary_menu_args");
function standard_secondary_menu_args($args)
{
  if ("secondary" != $args["theme_location"]) {
    return $args;
  }
  $args["depth"] = 1;
  return $args;
}
//* Remove comment form allowed tags
add_filter(
  "comment_form_defaults",
  "standard_remove_comment_form_allowed_tags"
);
function standard_remove_comment_form_allowed_tags($defaults)
{
  $defaults["comment_notes_after"] = "";
  return $defaults;
}
//* Add support for 3-column footer widgets
add_theme_support("genesis-footer-widgets", 3);
function custom_excerpt($string = "", $limitword = "", $kytu = "")
{
  $string = strip_tags($string);
  $string = preg_replace('/\n/', " ", trim($string));
  $array = explode(" ", $string);
  $string = "";
  for ($i = 0; $i <= $limitword; $i++):
    $string .= $array[$i] . " ";
  endfor;
  $string = strip_shortcodes($string);
  return $string . $kytu;
}
function getPostViews($postID)
{
  $count_key = "post_views_count";
  $count = get_post_meta($postID, $count_key, true);
  if ($count == "") {
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, "0");
    return "0";
  }
  return $count;
}
function setPostViews($postID)
{
  $count_key = "post_views_count";
  $count = get_post_meta($postID, $count_key, true);
  if ($count == "") {
    $count = 0;
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, "0");
  } else {
    $count++;
    update_post_meta($postID, $count_key, $count);
  }
}
function the_get_first_image_in_gallery($post_id, $size)
{
  global $wpdb;
  $mediumSRC = "";
  $args = [
    "post_parent" => $post_id,
    "numberposts" => 1,
    "post_type" => "attachment",
    "post_mime_type" => "image",
    "order" => "DESC",
    "orderby" => "ID",
    "post_status" => null,
  ];

  $attachments = get_posts($args);
  foreach ($attachments as $attachment) {
    $attachmentArray = wp_get_attachment_image_src($attachment->ID, $size);
    $caption = $wpdb->get_row(
      "select * from $wpdb->posts where `ID` = '{$attachment->ID}'"
    ); //var_dump($attachmentArray);
    $width = $attachmentArray[1];
    $height = $attachmentArray[2];
    if ($attachmentArray) {
      $mediumSRC = $attachmentArray[0];
      if (trim($mediumSRC != "")) {
        $mediumSRC;
      }
    }
  }
  ?>
        <img width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="<?php echo $mediumSRC; ?>" class="attachment-<?php echo $size; ?> wp-post-image" alt="<?php echo $caption->post_excerpt; ?>" />
        <?php return;
}
unregister_sidebar("sidebar-alt");
include_once "includes/header.php";
include_once "includes/widgets/widgets.php";
include_once "includes/footer.php";
function ratemycollege_register_post_type()
{
  register_post_type("coach", [
    "labels" => [
      "name" => __("Coach", "ratemycollege"),
      "singular_name" => __("Coach", "ratemycollege"),
      "menu_name" => __("Coaches", "ratemycollege"),
      "name_admin_bar" => __("Coaches", "ratemycollege"),
      "add_new" => __("Add Coach", "ratemycollege"),
      "add_new_item" => __("Add Coach", "ratemycollege"),
      "edit_item" => __("Edit Coach", "ratemycollege"),
      "new_item" => __("New Coach", "ratemycollege"),
      "all_items" => __("All Coaches", "ratemycollege"),
      "view_item" => __("View Coaches", "ratemycollege"),
      "search_items" => __("Search Coaches", "ratemycollege"),
      "not_found" => __("No Coaches", "ratemycollege"),
      "not_found_in_trash" => __("No Coaches found in Trash", "ratemycollege"),
      "parent_item_colon" => "",
      "menu_name" => __("Coaches", "ratemycollege"),
    ],
    "has_archive" => true,
    "public" => true,
    "publicly_queryable" => true,
    "supports" => ["title", "editor", "author", "thumbnail"],
    "exclude_from_search" => false,
    "capability_type" => "post",
    "rewrite" => ["slug" => "coach"],
    "menu_icon" => "dashicons-admin-users", // NOTE: For a full (searchable) list, see \wp-includes\css\dashicons.css
    "menu_position" => 6,
  ]);
  register_post_type("school", [
    "labels" => [
      "name" => __("School", "ratemycollege"),
      "singular_name" => __("School", "ratemycollege"),
      "menu_name" => __("Schools", "ratemycollege"),
      "name_admin_bar" => __("Schools", "ratemycollege"),
      "add_new" => __("Add School", "ratemycollege"),
      "add_new_item" => __("Add School", "ratemycollege"),
      "edit_item" => __("Edit School", "ratemycollege"),
      "new_item" => __("New School", "ratemycollege"),
      "all_items" => __("All Schools", "ratemycollege"),
      "view_item" => __("View Schools", "ratemycollege"),
      "search_items" => __("Search Schools", "ratemycollege"),
      "not_found" => __("No Schools", "ratemycollege"),
      "not_found_in_trash" => __("No Schools found in Trash", "ratemycollege"),
      "parent_item_colon" => "",
      "menu_name" => __("Schools", "ratemycollege"),
    ],
    "has_archive" => true,
    "public" => true,
    "publicly_queryable" => true,
    "supports" => ["title", "editor", "author", "thumbnail"],
    "exclude_from_search" => false,
    "capability_type" => "post",
    "rewrite" => ["slug" => "school"],
    "menu_icon" => "dashicons-universal-access-alt", // NOTE: For a full (searchable) list, see \wp-includes\css\dashicons.css
    "menu_position" => 7,
  ]);

  // Register Sport Taxonomy
  $labels = [
    "name" => _x("Sports", "Taxonomy General Name", "ratemycollege"),
    "singular_name" => _x("Sport", "Taxonomy Singular Name", "ratemycollege"),
    "menu_name" => __("Sports", "ratemycollege"),
    "all_items" => __("All Sports", "ratemycollege"),
    "parent_item" => __("Parent Sport", "ratemycollege"),
    "parent_item_colon" => __("Parent Sport:", "ratemycollege"),
    "new_item_name" => __("New Sport", "ratemycollege"),
    "add_new_item" => __("Add New Sport", "ratemycollege"),
    "edit_item" => __("Edit Sport", "ratemycollege"),
    "update_item" => __("Update Sport", "ratemycollege"),
    "view_item" => __("View Sport", "ratemycollege"),
    "separate_items_with_commas" => __(
      "Separate items with commas",
      "ratemycollege"
    ),
    "add_or_remove_items" => __("Add or remove items", "ratemycollege"),
    "choose_from_most_used" => __("Choose from the most used", "ratemycollege"),
    "popular_items" => __("Popular Items", "ratemycollege"),
    "search_items" => __("Search Items", "ratemycollege"),
    "not_found" => __("Not Found", "ratemycollege"),
    "no_terms" => __("No items", "ratemycollege"),
    "items_list" => __("Items list", "ratemycollege"),
    "items_list_navigation" => __("Items list navigation", "ratemycollege"),
  ];
  $args = [
    "labels" => $labels,
    "hierarchical" => true,
    "public" => true,
    "show_ui" => true,
    "show_admin_column" => true,
    "show_in_nav_menus" => true,
    "show_tagcloud" => false,
  ];
  register_taxonomy("rmc_sports", ["school", "coach"], $args);
}
add_action("init", "ratemycollege_register_post_type", 0);

//add_action('wp_ajax_school_search','school_search');
//add_action('wp_ajax_nopriv_school_search','school_search');
function school_search()
{
  $json_var = [];
  switch ($_POST["search_by"]) {
    case "location":
      break;
    case "name":
      $args = [
        "post_type" => "school",
        "s" => $_POST["search_key"],
        "posts_per_page" => -1,
        "post_status" => "publish",
      ];
      break;
    default:
      $args = [
        "post_type" => "school",
        "s" => $_POST["search_key"],
        "posts_per_page" => -1,
        "post_status" => "publish",
      ];
      break;
  }
  $schools = get_posts($args);
  $schools_html = "";
  if ($schools):
    $schools_html .= '<ul class="listings">';
    foreach ($schools as $school):
      $_school_details_address = get_post_meta(
        $school->ID,
        "_school_details_address",
        true
      );
      $schools_html .= '<li><div class="school-search-item">';
      $schools_html .=
        '<div class="school-search-item-name"><a href="' .
        get_permalink($school->ID) .
        '">' .
        $school->post_title .
        "</a></div>";
      $schools_html .=
        '<div class="school-search-item-location">' .
        $_school_details_address .
        "</div>";
      $schools_html .= "</div></li>";
    endforeach;
    $schools_html .= "</ul>";
  else:
    $schools_html .= "<p>No schools is found!</p>";
  endif;
  $json_var["status"] = "success";
  $json_var["schools_html"] = $schools_html;
  wp_send_json($json_var);
  die();
}
//register
function ratemycollege_register()
{
  $response = [];
  $user_name = $_POST["first_name"] . $_POST["last_name"];
  $user_email = $_POST["email"];
  $user_id = username_exists($user_name);
  $password = $_POST["password"];
  $new_user_data = [
    "user_login" => $user_name,
    "user_pass" => $password,
    "user_email" => $user_email,
    "role" => "subscriber",
  ];
  $user_id = wp_insert_user($new_user_data);
  if (!is_wp_error($user_id)):
    $response["status"] = "success";
    update_user_meta($user_id, "first_name", $_POST["first_name"]);
    update_user_meta($user_id, "last_name", $_POST["last_name"]);
    //auto login
    $creds = [];
    $creds["user_login"] = $user_name;
    $creds["user_password"] = $password;
    $creds["remember"] = true;
    $user = wp_signon($creds, false);
  else:
    $response["status"] = $user_id->get_error_message();
  endif;
  wp_send_json($response);
  die();
}
add_action("wp_ajax_ratemycollege_register", "ratemycollege_register");
add_action("wp_ajax_nopriv_ratemycollege_register", "ratemycollege_register");
//login
function ratemycollege_login()
{
  $response = [];
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  //login
  $creds = [];
  $creds["user_login"] = $username;
  $creds["user_password"] = $password;
  $creds["remember"] = true;
  $user = wp_signon($creds, false);
  if ($user->ID > 0):
    $response["status"] = "success";
  else:
    $response["status"] = $user->get_error_message();
  endif;
  wp_send_json($response);
  die();
}
add_action("wp_ajax_ratemycollege_login", "ratemycollege_login");
add_action("wp_ajax_nopriv_ratemycollege_login", "ratemycollege_login");
//login2
function ratemycollege_login2()
{
  $response = [];
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  //login
  $creds = [];
  $creds["user_login"] = $username;
  $creds["user_password"] = $password;
  $creds["remember"] = true;
  $user = wp_signon($creds, false);
  if ($user->ID > 0):
    $response["status"] = "success";
  else:
    $message = trim(str_replace("ERROR", "", $user->get_error_message()));
    $message = str_replace(":", "", $message);
    $response["status"] = $message;
  endif;
  wp_send_json($response);
  die();
}
add_action("wp_ajax_ratemycollege_login2", "ratemycollege_login2");
add_action("wp_ajax_nopriv_ratemycollege_login2", "ratemycollege_login2");
//rating and review
function ratemycollege_rating()
{
  global $wpdb;
  $response = [];
  $iq = $_POST["iq"];
  $individual = $_POST["individual"];
  $personable = $_POST["personable"];
  $academics = $_POST["academics"];
  $staff = $_POST["staff"];
  $communication = $_POST["communication"];
  $answer1 = $_POST["answer1"];
  $answer2 = $_POST["answer2"];
  $user_id = $_POST["user_id"];
  $post_id = $_POST["post_id"];
  $review_content = $_POST["review_content"];
  $datetime = date("Y-m-d H:i:s");
  $sql =
    "INSERT INTO `" .
    $wpdb->prefix .
    "coach_rate` (`iq`, `individual`, `personable`, `academics`, `staff`,`communication`, `answer1`, `answer2`, `post_id`, `review_content`, `user_id`, `datetime`) VALUES (
	'{$iq}', '{$individual}', '{$personable}', '{$academics}', '{$staff}','{$communication}', '{$answer1}', '{$answer2}', '{$post_id}', '{$review_content}', '{$user_id}','{$datetime}')";
  $result = $wpdb->query($sql);

  if ($result !== false):
    $response["status"] = "success";
  else:
    $response["status"] = "error";
  endif;
  wp_send_json($response);
  die();
}
add_action("wp_ajax_ratemycollege_rating", "ratemycollege_rating");
add_action("wp_ajax_nopriv_ratemycollege_rating", "ratemycollege_rating");
function scouting_report_average($post_id, $field, $label)
{
  global $wpdb;
  $ratings = $wpdb->get_results(
    "SELECT * FROM `" .
      $wpdb->prefix .
      "coach_rate` where `post_id` = '{$post_id}'"
  );
  $scouting_report = 0;
  $rating_count = count($ratings);
  if ($ratings):
    if (count($ratings) == 1):
      $rating_count_text = "rating";
    else:
      $rating_count_text = "ratings";
    endif;
  else:
    $rating_count_text = "rating";
  endif;
  if ($ratings):
    foreach ($ratings as $item):
      $scouting_report = $scouting_report + $item->$field;
    endforeach;
  endif;
  if ($scouting_report == 0):
  else:
    $scouting_report = round($scouting_report / $rating_count, 1);
  endif;
  //var_dump($scouting_report);
  if ($scouting_report == 0):
    $build_stars =
      '<span class="Scouting-Report-average">' .
      $scouting_report .
      '</span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
  else:
    $scouting_report_round_floor = floor($scouting_report);
    switch ($scouting_report_round_floor) {
      case "1":
        if ($scouting_report > 1.5):
          $build_stars =
            '<span class="Scouting-Report-average">' .
            $scouting_report .
            '</span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-half" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        else:
          $build_stars =
            '<span class="Scouting-Report-average">' .
            $scouting_report .
            '</span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        endif;
        break;
      case "2":
        if ($scouting_report > 2.5):
          $build_stars =
            '<span class="Scouting-Report-average">' .
            $scouting_report .
            '</span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-half" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        else:
          $build_stars =
            '<span class="Scouting-Report-average">' .
            $scouting_report .
            '</span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        endif;
        break;
      case "3":
        if ($scouting_report > 3.5):
          $build_stars =
            '<span class="Scouting-Report-average">' .
            $scouting_report .
            '</span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-half" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        else:
          $build_stars =
            '<span class="Scouting-Report-average">' .
            $scouting_report .
            '</span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        endif;
        break;
      case "4":
        if ($scouting_report > 4.5):
          $build_stars =
            '<span class="Scouting-Report-average">' .
            $scouting_report .
            '</span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-half" data-index="5"></span>';
        else:
          $build_stars =
            '<span class="Scouting-Report-average">' .
            $scouting_report .
            '</span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        endif;
        break;
      case "5":
        $build_stars =
          '<span class="Scouting-Report-average">' .
          $scouting_report .
          '</span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="5"></span>';

        break;
      default:
        $build_stars =
          '<span class="Scouting-Report-average">' .
          $scouting_report .
          '</span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        break;
    }
  endif;
  $build_html =
    '<div class="coaches-right-report-item"><div class="label"><span>' .
    $label .
    "</span><span> ( " .
    $rating_count .
    " " .
    $rating_count_text .
    ' )</span></div><div class="rating-coach-star-rating-wrap ">' .
    $build_stars .
    "</div></div>";
  return $build_html;
}
function rating_row($item)
{
  $scouting_report = round(
    ($item->iq +
      $item->individual +
      $item->personable +
      $item->academics +
      $item->staff +
      $item->communication) /
      6,
    1
  );
  $user_info = get_userdata($item->user_id);
  if (empty($user_info->first_name) && empty($user_info->last_name)):
    $by = $user_info->user_login;
  else:
    $by =
      ucfirst($user_info->first_name) . " " . ucfirst($user_info->last_name);
  endif;
  $datetime = date("F j, Y g:i a", strtotime($item->datetime));
  if ($scouting_report == 0):
    $build_stars =
      '<span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
  else:
    $scouting_report_round_floor = floor($scouting_report);
    switch ($scouting_report_round_floor) {
      case "1":
        if ($scouting_report > 1.5):
          $build_stars =
            '<span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-half" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        else:
          $build_stars =
            '<span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        endif;
        break;
      case "2":
        if ($scouting_report > 2.5):
          $build_stars =
            '<span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-half" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        else:
          $build_stars =
            '<span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        endif;
        break;
      case "3":
        if ($scouting_report > 3.5):
          $build_stars =
            '<span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-half" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        else:
          $build_stars =
            '<span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        endif;
        break;
      case "4":
        if ($scouting_report > 4.5):
          $build_stars =
            '<span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-half" data-index="5"></span>';
        else:
          $build_stars =
            '<span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        endif;
        break;
      case "5":
        $build_stars =
          '<span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-filled" data-index="5"></span>';

        break;
      default:
        $build_stars =
          '<span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="1"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="2"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="3"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="4"></span><span class="rating-coach-star-rating dashicons dashicons-star-empty" data-index="5"></span>';
        break;
    }
  endif;
  $build_html =
    '<div class="item">
                <div class="rating-coach-scouting-report">
	<div class="rating-coach-star-rating-wrap ">
    <span class="Scouting-Report">Scouting Report</span>
<span class="Scouting-Report-average">' .
    $scouting_report .
    "</span>" .
    $build_stars .
    '
<div class="rating-coach-date">' .
    $datetime .
    '</div></div></div><div class="rating-coach-content">' .
    $item->review_content .
    '</div>
</div>';
  return $build_html;
}
function ratemycollege_sport_suggest()
{
  $response = [];
  switch ($_POST["search_by"]) {
    case "mensport":
      $args = [
        "post_type" => "coach",
        "posts_per_page" => -1,
        "meta_query" => [
          [
            "key" => "_coach_sports",
            "value" => "Men",
            "compare" => "LIKE",
          ],
        ],
        "post_status" => "publish",
      ];
      break;
    case "womensport":
      $args = [
        "post_type" => "coach",
        "meta_query" => [
          [
            "key" => "_coach_sports",
            "value" => "Women",
            "compare" => "LIKE",
          ],
        ],
        "posts_per_page" => -1,
        "post_status" => "publish",
      ];
      break;
    default:
      $args = [
        "post_type" => "coach",
        "posts_per_page" => -1,
        "meta_query" => [
          [
            "key" => "_coach_sports",
            "value" => "Men",
            "compare" => "LIKE",
          ],
        ],
        "post_status" => "publish",
      ];
      break;
  }
  //search by
  $coach_array_ids = [];
  $coach = get_posts($args);
  if ($coach):
    foreach ($coach as $item):
      array_push($coach_array_ids, $item->ID);
    endforeach;
  endif;
  //search key
  $args = [
    "post_type" => "coach",
    "posts_per_page" => -1,
    "meta_query" => [
      [
        "key" => "_coach_sports",
        "value" => $_POST["key"],
        "compare" => "LIKE",
      ],
    ],
    "post_status" => "publish",
  ];

  $coach2_array_ids = [];
  $coach2 = get_posts($args);
  if ($coach2):
    foreach ($coach2 as $item):
      array_push($coach2_array_ids, $item->ID);
    endforeach;
  endif;
  $coach_ids = array_unique(array_merge($coach_array_ids, $coach2_array_ids));
  $build_html = "";
  if ($coach_ids):
    $response["status"] = "success";
    foreach ($coach_ids as $item):
      $_coach_sports = get_post_meta($item, "_coach_sports", true);
      $build_html .= "<li>" . $_coach_sports . "</li>";
    endforeach;
  endif;
  $response["html"] = $build_html;
  wp_send_json($response);
  die();
}
add_action(
  "wp_ajax_ratemycollege_sport_suggest",
  "ratemycollege_sport_suggest"
);
add_action(
  "wp_ajax_nopriv_ratemycollege_sport_suggest",
  "ratemycollege_sport_suggest"
);
function ratemycollege_coach_school_suggest()
{
  $response = [];
  $build_html = "";
  //get coach by coach name or school name
  switch ($_POST["search_by"]) {
    case "school":
      $args = [
        "post_type" => "coach",
        "posts_per_page" => -1,
        "meta_query" => [
          [
            "key" => "_coach_school",
            "value" => $_POST["key"],
            "compare" => "LIKE",
          ],
        ],
        "post_status" => "publish",
      ];
      $coach1 = get_posts($args);
      if ($coach1):
        $response["status"] = "success";
        foreach ($coach1 as $item):
          $_coach_school = get_post_meta($item->ID, "_coach_school", true);
          $build_html .= "<li>" . $_coach_school . "</li>";
        endforeach;
      endif;
      break;
    case "name":
      $args = [
        "post_type" => "coach",
        "s" => $_POST["key"],
        "posts_per_page" => -1,
        "post_status" => "publish",
      ];
      $coach1 = get_posts($args);
      if ($coach1):
        $response["status"] = "success";
        foreach ($coach1 as $item):
          $build_html .= "<li>" . $item->post_title . "</li>";
        endforeach;
      endif;
      break;
    default:
      $args = [
        "post_type" => "coach",
        "s" => $_POST["key"],
        "posts_per_page" => -1,
        "post_status" => "publish",
      ];
      $coach1 = get_posts($args);
      if ($coach1):
        $response["status"] = "success";
        foreach ($coach1 as $item):
          $build_html .= "<li>" . $item->post_title . "</li>";
        endforeach;
      endif;
      break;
  }

  $response["html"] = $build_html;
  wp_send_json($response);
  die();
}
add_action(
  "wp_ajax_ratemycollege_coach_school_suggest",
  "ratemycollege_coach_school_suggest"
);
add_action(
  "wp_ajax_nopriv_ratemycollege_coach_school_suggest",
  "ratemycollege_coach_school_suggest"
);

function my_theme_enqueue_scripts() {
    wp_enqueue_script('jquery-ui-dialog');
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_scripts');


/**
 * Add ACF options page
 */
if (function_exists("acf_add_options_page")) {
  acf_add_options_page([
    "page_title" => "Theme General Settings",
    "menu_title" => "Theme Settings",
    "menu_slug" => "theme-general-settings",
    "capability" => "edit_posts",
    "redirect" => false,
  ]);
}
