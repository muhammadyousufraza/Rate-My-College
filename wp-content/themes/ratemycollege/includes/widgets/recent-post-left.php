<?php
class recent_post_left extends WP_Widget {
	function __construct(){
		$widget_ops = array('description' => 'Recent posts on the left');
		$control_ops = array('width' => 200, 'height' => '');
		parent::__construct(false,$name='Recent posts on the left',$widget_ops,$control_ops);
	}
	function widget($args, $instance) {
		extract($args);
		$category = empty($instance['category']) ? '' : $instance['category'];
		echo $before_widget;
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		?>
        <div class="recent_post_left_out">
         <?php
			$args = array(
			'post_type' => 'post',
			'posts_per_page' => $instance['numbershow'],
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $category ,
				),
			),
			'post_status' => 'publish'
			);
			$posts=get_posts($args);
			$i=1;
			if($posts):foreach($posts as $post):
			$post_thumbnail_id = get_post_thumbnail_id($post->ID);
			$src_thumbnail =wp_get_attachment_image_src($post_thumbnail_id , 'recent_post_left');
			$cats = get_the_terms( $post->ID, "category" );
			$cat = get_term($cats[0]->term_id,"category" )
 		?>
        	<div class="recent_post_left_item">
            <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>">
            	<div class="recent_post_left_item_in">
            	<?php if($i==1): ?>
                <span class="top_story">Top story</span>
                <?php endif; ?>
                <div class="title">
                	<h2><?php echo $post->post_title; ?></h2>
                    <p><?php echo $cat->name; ?></p>
                </div>
                <img src="<?php echo $src_thumbnail[0]; ?>" alt="<?php echo $post->post_title; ?>">
                </div>
             </a>   
            </div>
        	<?php $i++; endforeach; endif; ?>
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
	register_widget('recent_post_left');