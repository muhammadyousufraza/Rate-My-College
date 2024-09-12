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
	$src_thumbnail =wp_get_attachment_image_src($post_thumbnail_id , 'coach');
	$_coach_school = get_post_meta(get_the_ID(),"_coach_school",true);
	$_coach_professor = get_post_meta(get_the_ID(),"_coach_professor",true);
	$_coach_phone = get_post_meta(get_the_ID(),"_coach_phone",true);
	$_coach_email = get_post_meta(get_the_ID(),"_coach_email",true);
	$_coach_rating = get_post_meta(get_the_ID(),"_coach_rating",true);
	$_coach_tinder_ticker = get_post_meta(get_the_ID(),"_coach_tinder_ticker",true);
	$_coach_ratings_arr=array();
	$_coach_ratings_arr['season']='Season';
	$_coach_ratings_arr['season2']='Season2';
	$_coach_ratings_arr['season3']='Season3';
	$_coach_ratings = get_post_meta(get_the_ID(),"_coach_ratings",true);
	$_coach_scouting_report = get_post_meta(get_the_ID(),"_coach_scouting_report",true);
	?>
    <div class="coaches-out">
    <div class="wrap">
        <div class="coaches-left">
        	<div class="coaches-profile">
        		<div class="coaches-profile-left">
        			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <img src="<?php echo $src_thumbnail[0]; ?>" alt="<?php the_title(); ?>">
                    </a>
        		</div>
                <div class="coaches-profile-right">
        			<div class="coaches-profile-right-in">
        				<div  class="coaches-profile-right-in-box-title">
                        	<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                            
                        </div>
                        <div  class="coaches-profile-right-in-box-profile">
                        	<ul>
                            	<li><?php echo $_coach_school; ?></li>
                                <li><?php echo $_coach_professor; ?></li>
                            </ul>
                        </div>
                        <div  class="coaches-profile-right-in-box-phone">
                        	<a href="tel:<?php echo $_coach_phone; ?>">
                            <?php echo $_coach_phone; ?>
                            </a>
                            
                        </div>
                        <div  class="coaches-profile-right-in-box-email">
                        	<a href="mailto:<?php echo $_coach_email; ?>">
                            <?php echo $_coach_email; ?>
                            </a>
                            
                        </div>
        			</div>
        		</div>
        	</div>
            <div class="coaches-records">
            	<h3 class="title">
                Record
            	</h3>
                <div class="coaches-records-content">
                	<div class="coaches-records-item">
                    	<div class="coaches-records-item-left">
                        Rating:
                    	</div>
                        <div class="coaches-records-item-right">
                        <?php if($_coach_rating > 0): ?>
                        <div class="coaches-records-item-right-rating">
                        <?php for($i=1;$i<=$_coach_rating;$i++): ?>
                          <span class="lp-star-box has-star"><i class="fa fa-star" aria-hidden="true"></i></span>
                         <?php endfor; ?> 
                         <?php for($i=1;$i<=5-$_coach_rating;$i++): ?>
                          <span class="lp-star-box"><i class="fa fa-star" aria-hidden="true"></i></span>
                         <?php endfor; ?> 
                         </div>
                         <?php endif; ?>
                    	</div>
                    </div>
                    <div class="coaches-records-item">
                    <div class="coaches-records-item-left">
                        TINDER TICKER:
                    	</div>
                        <div class="coaches-records-item-right">
                        	<div class="TINDER-TICKER">
                            <?php echo $_coach_tinder_ticker; ?>
                            </div>
                        </div>
                    </div>
                    <div class="coaches-records-item">
                    <div class="coaches-records-item-left">
                        RATINGS:
                    	</div>
                        <div class="coaches-records-item-right">
                        	<div class="ratings">
                            	<select name="ratings">
                                	<?php foreach($_coach_ratings_arr as $key=>$value):
										if($key == $_coach_ratings): $selected='selected="selected"';
										else: $selected=''; endif;
									
									 ?>
                                    <option value="<?php echo $key; ?>"  <?php echo $selected; ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="coaches-right">
        	<div class="coaches-right-in">
            	<h3>Scouting REPORT</h3>
                <div class="coaches-right-report">
                	<?php if($_coach_scouting_report > 0): ?>
                    <?php for($i=0;$i<$_coach_scouting_report;$i++):
					$label=get_post_meta(get_the_ID(),"_coach_scouting_report_".$i."__coach_report_item_label",true);
					$percent=get_post_meta(get_the_ID(),"_coach_scouting_report_".$i."__coach_report_item_value",true);
					 ?>
                	<div class="coaches-right-report-item">
                    	<div class="coaches-right-report-item-label">
                        <?php echo $label; ?>
                        </div>
                        <div class="coaches-right-report-item-bar">
  							<div class="coaches-right-report-item-bar-in" style="width:<?php echo $percent; ?>%"></div>
						</div>
                    </div>
                    <?php endfor; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php
	} 
	}
}
genesis();