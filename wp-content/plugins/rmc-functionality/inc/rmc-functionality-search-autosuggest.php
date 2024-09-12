<?php

function rmc_ajax_search() {
  global $wpdb;

  $search_by = $_POST['search_by'];
  $search_type = $_POST['search_type'];
  $query = $_POST['query'];
  $response = array();

  // handle school search
  if( $search_type == 'schoolName' ) {
    if( $search_by == 'name' ) {
      $final_results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_title LIKE '%{$query}%' AND post_type = 'school'", OBJECT );
    }
  
    if( $search_by == 'location' ) {
      $db_results = $wpdb->get_results( "SELECT `post_id` FROM {$wpdb->prefix}postmeta WHERE (meta_key = 'city' OR meta_key = 'state') AND meta_value LIKE '%{$query}%'", OBJECT );
      foreach( $db_results as $result ) {
        $final_results[] = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}posts WHERE ID = {$result->post_id}", OBJECT );
      }
    }
  
    foreach( $final_results as $i=>$result ) {
      $response[$i]['label'] = $result->post_title;
      $response[$i]['permalink'] = get_permalink( $result->ID );
  
      $city = get_field( 'city', (int)$result->ID );
      $state = get_field( 'state', (int)$result->ID );
      if( $city && $state ) {
        $response[$i]['label'] .= ' (' . $city . ', ' . $state . ')';
      }
    }
  }

  // handle coach search
  if( $search_type == 'coachName' || $search_type == 'coachName2' ) {

    if( $search_by == 'name' ) {
      if( isset( $_POST['sport'] ) ) {
        $final_results = get_posts(array(
          'post_type' => 'coach',
          's' => $query,
          'numberposts' => -1,
          'tax_query' => array(
            array(
                'taxonomy' => 'rmc_sports',
                'field'    => 'name',
                'terms'    => $_POST['sport'],
            ),
          ),
        ));
      } else {
        $final_results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_title LIKE '%{$query}%' AND post_type = 'coach'", OBJECT );
      }
    }

    if( $search_by == 'school' ) {
      // let's search for school first
      $school_results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_title LIKE '%{$query}%' AND post_type = 'school'", OBJECT );
      
      // now, let's get all coaches for this school
      foreach( $school_results as $i=>$result ) {

        $args = array(
          'post_type' => 'coach',
          'meta_key' => 'school',
          'meta_value' => $result->ID,
          'meta_compare' => '=',
          'numberposts' => -1
        );
        
        if( isset( $_POST['sport'] ) ) {
          $args['tax_query'] = array(
            array(
                'taxonomy' => 'rmc_sports',
                'field'    => 'name',
                'terms'    => $_POST['sport'],
            ),
          );
        }

        $coach_results[$i] = get_posts( $args );
      }

      // finally, loop coaches to provide final output
      foreach( $coach_results as $c_results ) {
        foreach( $c_results as $c_result ) {
          $final_results[] = $c_result;
        }
      }
    }

    foreach( $final_results as $i=>$result ) {
      $response[$i]['label'] = $result->post_title;
      $response[$i]['permalink'] = get_permalink( $result->ID );

      $school = get_field( 'school', (int)$result->ID );
      $school = get_the_title( $school );

      if( $school ) {
        $response[$i]['label'] .= ' (' . $school . ')';
      }
    }
  }

  // handle sport search
  if( $search_type == 'sportName' ) {

    if( $search_by == 'mensport' ) {
      $prefix = "Men's";
    } else {
      $prefix = "Women's";
    }

    $search_term = $prefix.' '.$query;

    $term_results = get_terms(array(
      'taxonomy' => 'rmc_sports',
      'name__like' => $search_term
    ));

    // format results
    // Since "Men" is also in "WoMEN" we need to clean these out
    foreach( $term_results as $i=>$result ) {
      if( $search_by == 'mensport' && strpos( $result->name, 'Women' ) === false ) {
        $response[$i]['label'] = $result->name;
      } 

      if( $search_by == 'womensport' ) {
        $response[$i]['label'] = $result->name;
      }
    }
  }
  

  wp_send_json($response);
}

add_action( 'wp_ajax_rmc_search', 'rmc_ajax_search' );
add_action( 'wp_ajax_nopriv_rmc_search', 'rmc_ajax_search' );