<?php
class recent_post_right extends WP_Widget {
	function __construct(){
		$widget_ops = array('description' => 'Recent posts on the Right');
		$control_ops = array('width' => 200, 'height' => '');
		parent::__construct(false,$name='Recent posts on the Right',$widget_ops,$control_ops);
	}
	function widget($args, $instance) {
		extract($args);
		$category = empty($instance['category']) ? '' : (int)$instance['category'];
		echo $before_widget;
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		?>
        <div class="recent_post_right_out">
        <ul>
         <?php
			$args = array(
			'post_type' => 'post',
			'posts_per_page' => (int)$instance['numbershow'],
			// 'category' => $category,
			// 'tax_query' => array(
			// 	array(
			// 		'taxonomy' => 'category',
			// 		'field'    => 'term_id',
			// 		'terms'    => $category ,
			// 	),
			// )
			);
			$posts=get_posts($args);
			
			if($posts):foreach($posts as $post):
			$post_thumbnail_id = get_post_thumbnail_id($post->ID);
			$src_thumbnail =wp_get_attachment_image_src($post_thumbnail_id , 'thumbnail');
			$cats = get_the_terms( $post->ID, "category" );
			$cat = get_term($cats[0]->term_id,"category" )
 		?>
        	<li>
            <div class="recent_post_right_item">
           <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>">
            <img src="<?php echo $src_thumbnail[0]; ?>" alt="<?php echo $post->post_title; ?>">
            <div class="recent_post_right_item_right">
            <h2><?php echo $post->post_title; ?></h2>
            <div class="recent_post_right_item_right_meta">
            <span>By:</span>
            <span><?php echo get_the_author_meta( 'display_name', $post->post_author ); ?></span>
            <span><?php echo get_the_date( 'M y', $post->ID); ?></span>
            </div>
            </div>
           </a>
            </div>
            </li>
        	<?php  endforeach; endif; ?>
            </ul>
        </div>
        <?php
		echo $after_widget;          
	}
	function update($new_instance, $old_instance) {
	//save the widget
	$instance = $old_instance;		
	$instance['title'] = stripslashes($new_instance['title']);
	$instance['category'] = $new_instance['category'];	
	$instance['numbershow'] = stripslashes($new_instance['numbershow']);	
	return $instance;
	}
	function form($instance) {
	//widgetform in backend
	$instance = wp_parse_args( (array) $instance, array('title'=>'', 'category' => '','numbershow'=>'' ) );	
	$title = htmlspecialchars($instance['title']);	
	$category = strip_tags($instance['category']);	
	$numbershow = htmlspecialchars($instance['numbershow']);
	?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>">Title </label>      
        <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" style="width:100%" value="<?php echo $title; ?>">
        </p>
	<p><label for="<?php echo $this->get_field_id('category'); ?>">Category </label>      
	<select name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>" style="width:170px">
    <?php 
			 	$args = array(
					'type'                     => 'post',
					'child_of'                 => 0,
					'parent'                   => 0,
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => 'category',
					'pad_counts'               => false );
			 	$categories = get_categories($args);
	foreach ($categories as $cat):
	if ($category == $cat->cat_ID) { $selected = ' selected="selected"'; } else { $selected = ''; }
	$opt = '<option value="' . $cat->cat_ID . '"' . $selected . '>' . $cat->cat_name .'('.$cat->count.')</option>';
	echo $opt; 
	$args = array(
					'type'                     => 'post',
					'child_of'                 => 0,
					'parent'                   => $cat->term_id,
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => 'category',
					'pad_counts'               => false );
					
					$subs = get_categories($args);
						foreach($subs as $sub):
						if ($category == $sub->cat_ID) { $selected = ' selected="selected"'; } else { $selected = ''; }
	$opt = '<option style="padding-left:15px" value="' . $sub->cat_ID . '"' . $selected . '>' . $sub->cat_name .'('.$sub->count.')</option>';
	echo $opt; 
	endforeach;

	endforeach;
	 ?>
	</select>
	</p> 
    <p><label for="<?php echo $this->get_field_id('numbershow'); ?>">Show number: </label>      
        <input type="text" name="<?php echo $this->get_field_name('numbershow'); ?>" id="<?php echo $this->get_field_id('numbershow'); ?>" style="width:100%" value="<?php echo $numbershow; ?>">
        </p> 
	<?php
	}
	}
	register_widget('recent_post_right');