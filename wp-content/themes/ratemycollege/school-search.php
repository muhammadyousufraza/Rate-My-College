<?php
/*
Template Name: School Search
*/
add_action( 'genesis_meta', 'ratemycollege_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function ratemycollege_home_genesis_meta() {

		//* Force full-width-content layout setting
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

		//* Add ratemycollege-pro-home body class
		//add_filter( 'body_class', 'ratemycollege_body_class' );

		//* Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Add home top widgets
		add_action( 'genesis_after_header', 'ratemycollege_after_header' );
		
		//* Add home bottom widgets
		//add_action( 'genesis_loop', 'ratemycollege_home_bottom_widgets' );

	
}
function ratemycollege_after_header(){
	?>
    <div class="school-search-list">
  		<div class="wrap">
        <h1 class="entry-title">Search Results for "<?php echo $_GET['search_key']; ?>"</h1>
        <?php
		 switch($_GET['search_by']){
		case 'location':
		   $args = array( 
			'post_type' => 'school',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key'     => '_school_details_address',
					'value'   => $_GET['search_key'],
					'compare' => 'LIKE',
				),
			),
			'post_status' => 'publish'
			);
		   break;
    	case 'name':
			$args = array( 
			'post_type' => 'school',
			's'=> $_GET['search_key'],
			'posts_per_page' => -1,
			'post_status' => 'publish'
			);

        break;
    default:
        $args = array( 
			'post_type' => 'school',
			's'=> $_GET['search_key'],
			'posts_per_page' => -1,
			'post_status' => 'publish'
			);
        break;
	}
	$schools=get_posts($args);
	if($schools):?>
        <div class="listings-header">
          <div class="listing-name">Name</div>
          <div class="listing-location">Location</div>
        </div>
        <div id="school-search-list-content">
        <ul>
        <?php
        foreach($schools as $school):
			$_school_details_address= get_post_meta($school->ID,'_school_details_address',true);
			?>
			<li><div class="school-search-item">
			<div class="school-search-item-name">
            <a href="<?php echo get_permalink($school->ID); ?>"><?php echo $school->post_title; ?></a>
            </div>
			<div class="school-search-item-location"><?php echo $_school_details_address; ?></div>
			</div></li>
            <?php endforeach; ?>
            </ul>
              </div>
              <?php else: ?>
              <p>No schools is found!</p>
              <?php endif; ?>
      	</div>
    </div>
    <?php
}
genesis();