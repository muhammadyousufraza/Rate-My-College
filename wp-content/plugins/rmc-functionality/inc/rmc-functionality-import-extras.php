<?php

// add_action( 'init', function() {

//   $post_terms = wp_get_post_terms( 34099, 'rmc_sports' );
//   echo 'Current post terms for post: 34099 => ';
//   var_dump($post_terms);
//   echo '<br/>';
//   die();
// });

// Format sport text so the correct hierarcy can be defined in WPAI
function rmc_format_sport( $text ) {
  $exploded = explode( "'s ", $text );
  $exploded[1] = $text;

  return implode( '>', $exploded);
}

// Create coach post if it doesn't exist
// Else, update coach data
function rmc_add_edit_coach_post( $id, $xml_node ) {
  // only run on School import
  $import_id = ( isset( $_GET['id'] ) ? $_GET['id'] : ( isset( $_GET['import_id'] ) ? $_GET['import_id'] : 'new' ) );

  if( $import_id == '1' ) {
    // get CSV row data and assign to vars
    $record = json_decode(json_encode((array) $xml_node), 1);

    $taxonomy = 'rmc_sports';

    // Add Sports terms
    // Had to go this route as the default WPAI settings don't allow this
    $sport = $record['sports'];

    // Process Coach
    $post_type = 'coach';

    $name = $record['contactname'];
    $title = $record['title'];

    // check if coach post already exists
    $post = get_posts( array( 
      'post_type' => $post_type, 
      'title' => $name
    ) );

    if( count( $post ) == 0 ) {
      // coach not found, create
      $new_post_ID = wp_insert_post( array( 
        'post_type' => $post_type,
        'post_title' => $name,
        'post_status' => 'publish'
      ) );

      if( is_int( $new_post_ID ) && $new_post_ID !== 0 ) {
        $post_ID = $new_post_ID;
      }
    } else {
      $post_ID = $post[0]->ID;
    }

    // update title field in both cases
    update_field( 'title', $title,  $post_ID );

    // set sports terms
    $sanitized_sport = sanitize_title( $sport );
    $sport_term = get_term_by( 'slug', $sanitized_sport, $taxonomy );

    if( isset( $sport_term->term_id ) ) {
      wp_set_post_terms( $post_ID, array( (int)$sport_term->term_id ), $taxonomy, true );
    }

    // set school
    update_field( 'school', $id,  $post_ID );

  }
}

add_action( 'pmxi_saved_post', 'rmc_add_edit_coach_post', 10, 2 );