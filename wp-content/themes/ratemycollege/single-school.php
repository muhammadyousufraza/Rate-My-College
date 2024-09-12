<?php
add_action( 'genesis_meta', 'ratemycollege_home_genesis_meta' );
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
	if ( have_posts() ) {
	while ( have_posts() ) {
	the_post();
	$post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
	$src_thumbnail =wp_get_attachment_image_src($post_thumbnail_id , 'full');
	$_school_sports = get_post_meta(get_the_ID(),"_school_sports",true);
	$_school_sub_title = get_post_meta(get_the_ID(),"_school_sub_title",true);
	$_school_rating = get_post_meta(get_the_ID(),"_school_rating",true);
	$_school_details = get_post_meta(get_the_ID(),"_school_details",true);
	$_school_details_location = get_post_meta(get_the_ID(),"_school_details_location",true);
	$_school_details_banner= get_post_meta(get_the_ID(),"_school_details_banner",true);
    $_school_details_banner =wp_get_attachment_image_src($_school_details_banner, 'full');
    
    $sports_list = rmc_get_sports_list( get_the_ID() );

	?>
    <div class="school-out">
    	<div class="school-banner" style="background-image:url(<?php echo $_school_details_banner[0]; ?>);">
        	<div class="wrap">
                <h2><?php the_title(); ?> <span><?php echo $_school_sub_title; ?></span></h2>
                <div class="coach-actions mistake-popup">
                  <?php echo do_shortcode('[fl_builder_insert_layout slug="submit-correction-form"]'); ?>
                </div>
            </div>
        </div>
        <div class="school-detail">
        	<div class="wrap">
            	<div class="school-detail-in">
                	
                    <div class="school-detail-right">
                    	<div class="school-detail-right-detail">
                        <div class="cont-head">
                            <h3>School details</h3>
                            <div class="school-detail-content-tab">
                                <ul>
                                <li class="active"><a href="#men" ><?php _e( 'Men', 'ratemycollege' ); ?></a></li>
                                <li><a href="#women"><?php _e( 'Women', 'ratemycollege' ); ?></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="school-detail-right-detail-content">
                            <div class="school-logo">
                                <img src="<?php the_field('school_logo'); ?>">
                            </div>
                            <ul class="school-feature">
                                <li class="address">
                                    <span><img src="http://redcraftmedia.net/ratemycollege/wp-content/uploads/2024/02/MapPin.svg"></span>
                                    <?php the_field('_school_details_location'); ?>  
                                </li>
                                <li class="no_sport">
                                    <span><img src="http://redcraftmedia.net/ratemycollege/wp-content/uploads/2024/02/Football.svg"></span>
                                    <?php the_field('_no_of_sports'); ?>
                                </li>
                                <li class="no_sport">
                                    <span><img src="http://redcraftmedia.net/ratemycollege/wp-content/uploads/2024/02/staar.svg"></span>
                                    <?php the_field('school_rating'); ?>
                                </li>     
                            </ul>
                       	 <?php if($_school_details > 0): ?>
                                <ul>
                        <?php for($i=0;$i<$_school_details;$i++):
    					$name=get_post_meta(get_the_ID(),"_school_details_".$i."__school_details_name",true);
    					$value=get_post_meta(get_the_ID(),"_school_details_".$i."__school_details_value",true);
    					 ?>
                     
                     <li><div class="school_details_item">
                     	<span class="name"><?php echo $name; ?></span>
                        <span class="value"><?php echo $value; ?></span>
                        <div class="border"></div>
                     </div></li>
                    
                     <?php endfor; ?>
                      </ul>
                     <?php endif; ?>
                        </div>
                        </div>
                        <div class="school-sidebar">
                            <?php
                                genesis_widget_area( 'school-sidebar', array(
                                'before' => '',
                                'after'  => '',
                                ) );
                            ?>

                            <div class="ad" style="margin: 2rem 0">
                                <div id="338696397">
                                    <script type="text/javascript">
                                        try {
                                            window._mNHandle.queue.push(function (){
                                                window._mNDetails.loadTag("338696397", "300x250", "338696397");
                                            });
                                        }
                                        catch (error) {}
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="school-detail-left">
                        <div class="school-detail-content">
                            
                            <div class="school-detail-content-content">
                                <h3 class="main-title coaches-t">COACHES LIST</h3>
                                <div id="school-detail-content-men">
                                    <?php if( isset( $sports_list['men'] ) ) { ?>
                                        <ul class="sports-list">
                                            <?php foreach( $sports_list['men'] as $tid => $sport ) { ?>
                                                <li>
                                                    <div class="coach-item">
                                                    <?php 
                                                    echo $sport;
                                                    $coaches = rmc_get_school_sport_coaches( get_the_ID(), $tid );
                                                    rmc_render_coaches_list( $coaches );
                                                    ?>
                                                    </div>
                                                    <a href="<?php echo get_permalink($coaches[0]->ID); ?>" class="btn">Rate</a>
                                                </li>
                                                 
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </div>
                                <div id="school-detail-content-women">
                                    <?php if( isset( $sports_list['men'] ) ) { ?>
                                        <ul class="sports-list">
                                            <?php foreach( $sports_list['women'] as $tid => $sport ) { ?>
                                                <li>
                                                    <div class="coach-item">
                                                    <?php 
                                                    echo $sport;

                                                    $coaches = rmc_get_school_sport_coaches( get_the_ID(), $tid );
                                                    rmc_render_coaches_list( $coaches );
                                                    ?>
                                                    </div>
                                                    <a href="<?php echo get_permalink($coaches[0]->ID); ?>" class="btn">Rate</a>
                                                </li>
                                                
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                <?php if($_school_sports > 0): ?>
                                    <ul>
                                    <?php for($i=0;$i<$_school_sports;$i++):
                                    $name=get_post_meta(get_the_ID(),"_school_sports_".$i."__school_sports_name",true);
                                    ?>
                                    
                                    <li><div class="sport_item">
                                        <div class="name"><?php echo $name; ?></div>
                                    </div></li>
                                    
                                    <?php endfor; ?>
                                    </ul>
                                <?php endif; ?>
                                </div>
                                <div id="school-detail-content-rating">
                                 <?php if($_school_rating  > 0): ?>
                                <ul>
                    <?php for($i=0;$i<$_school_rating;$i++):
                    $name=get_post_meta(get_the_ID(),"_school_rating_".$i."__school_rating_name",true);
                    $value=get_post_meta(get_the_ID(),"_school_rating_".$i."__school_rating_value",true);
                     ?>
                     
                     <li><div class="rating_item">
                     <span class="value"><?php echo $value; ?></span>
                        <span class="name"><?php echo $name; ?></span>
                     </div></li>
                    
                     <?php endfor; ?>
                      </ul>
                     <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
	jQuery(function($){
		$('.school-detail-content-tab>ul>li>a').on('click',function(e){
			$('.school-detail-content-tab>ul>li').each(function(index, element) {
                $(this).removeClass('active');
            });
			$(this).parent().addClass('active');
			var show=$(this).attr("href").replace('#','');
			$('.school-detail-content-content>div').each(function(index, element) {
                $(this).hide();
            });
			$('#school-detail-content-'+show).show();
			//console.log($(this).attr("href").replace('#',''));	
			return false;
		});

        $('.school-detail-content-content>div').each(function(index, element) {
            $(this).hide();
        });
        $('.school-detail-content-content>div:first-child').show();
	});
	</script>
    <?php
	} 
	}
}
genesis();