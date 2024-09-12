<?php
/*
Template Name: Sport and coach search
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
		//get coach by coach name or school name
		 switch($_GET['coach_search_by']){
		case 'school':
		   $args = array( 
			'post_type' => 'coach',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key'     => '_coach_school',
					'value'   => $_GET['coach_search_key'],
					'compare' => 'LIKE',
				),
			),
			'post_status' => 'publish'
			);
		   break;
    	case 'name':
			$args = array( 
			'post_type' => 'coach',
			's'=> $_GET['coach_search_key'],
			'posts_per_page' => -1,
			'post_status' => 'publish'
			);
        break;
    default:
        $args = array( 
			'post_type' => 'coach',
			's'=> $_GET['coach_search_key'],
			'posts_per_page' => -1,
			'post_status' => 'publish'
			);
        break;
	}
	$coach1_array_ids=array();
	$coach1=get_posts($args);
	if($coach1):foreach($coach1 as $item):
		array_push($coach1_array_ids,$item->ID);
	endforeach;endif;
	//var_dump($coach1_array_ids);
	//get coach by men or women
	switch($_GET['sport_search_by']){
		case 'mensport':
		   $args = array( 
			'post_type' => 'coach',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key'     => '_coach_sports',
					'value'   => 'Men',
					'compare' => 'LIKE',
				),
			),
			'post_status' => 'publish'
			);
		   break;
    	case 'womensport':
			$args = array( 
			'post_type' => 'coach',
			'meta_query' => array(
				array(
					'key'     => '_coach_sports',
					'value'   => 'Women',
					'compare' => 'LIKE',
				),
			),
			'posts_per_page' => -1,
			'post_status' => 'publish'
			);
        break;
    default:
        $args = array( 
			'post_type' => 'coach',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key'     => '_coach_sports',
					'value'   => 'Men',
					'compare' => 'LIKE',
				),
			),
			'post_status' => 'publish'
			);
        break;
	}
	$coach2_array_ids=array();
	$coach2=get_posts($args);
	if($coach2):foreach($coach2 as $item):
		array_push($coach2_array_ids,$item->ID);
	endforeach;endif;
	//var_dump($coach2_array_ids);
	//get coach by sport name
	if(isset($_GET['sport_search_key'])):
	   $args = array( 
		'post_type' => 'coach',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => '_coach_sports',
				'value'   => $_GET['sport_search_key'],
				'compare' => 'LIKE',
			),
		),
		'post_status' => 'publish'
		);   
		$coach3_array_ids=array();
		$coach3=get_posts($args);
		if($coach3):foreach($coach3 as $item):
			array_push($coach3_array_ids,$item->ID);
		endforeach;endif;
		//var_dump($coach3_array_ids);
	endif;	
	$coach_ids=array_unique(array_merge($coach1_array_ids,$coach2_array_ids));
	$schools=array_unique(array_merge($coach_ids,$coach3_array_ids));
	//var_dump($coach_ids);
	if($schools):?>
        <div class="listings-header">
          <div class="listing-name">Name</div>
          <div class="listing-location">School</div>
        </div>
        <div id="school-search-list-content">
        <ul>
        <?php
        foreach($schools as $item):
			$school=get_post($item);
			$_coach_school= get_post_meta($school->ID,'_coach_school',true);
			?>
			<li><div class="school-search-item">
			<div class="school-search-item-name">
            <a href="<?php echo get_permalink($school->ID); ?>"><?php echo $school->post_title; ?></a>
            </div>
			<div class="school-search-item-location"><?php echo $_coach_school; ?></div>
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