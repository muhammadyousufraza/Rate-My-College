<?php
class find_what extends WP_Widget
{
     function __construct(){
        $widget_ops = array('description' => 'Find what');
        $control_ops = array('width' => 300, 'height' => 300);
        parent::__construct(false,$name='Find what',$widget_ops,$control_ops);
    }
	function widget($args, $instance) {
		extract($args);
		?>
        
        <div class="find-what-out">
        	<div class="find-what-in">
            	<div class="find-what-in-3box">
            	<div class="find-what-title">
                <h2>What are you looking for?</h2>
                </div>
                <div class="find-what-items" >
                	 <div class="find-what-item" data-show_box='school-block-form'>
                     	<div class="find-what-item-in">
                        	<div class="find-what-item-box">
                        		<img src="<?php echo home_url(); ?>/wp-content/uploads/2024/01/Star.svg">
                            	<h3>FIND A SCHOOL</h3>
                            </div>
                		</div>
                	</div>
                    <div class="find-what-item" data-show_box="coach-block-form">
                     	<div class="find-what-item-in">
                        	<div class="find-what-item-box">
                        		<img src="<?php echo home_url(); ?>/wp-content/uploads/2024/01/Baseball.svg">
                            	<h3>FIND A COACH</h3>
                            </div>
                		</div>
                	</div>
                    <div class="find-what-item" data-show_box="rating-block-form">
                     	<div class="find-what-item-in">
                        	<div class="find-what-item-box">
                        		<img src="<?php echo home_url(); ?>/wp-content/uploads/2024/01/Star.svg">
                            	<h3>RATE A COACH</h3>
                            </div>
                		</div>
                	</div>
                </div>
                </div>
                <div class="school-block-form">
  <div class="center-block-form">
    <div class="h1 mobile-header">Find a School</div>
    
    <div class="search-by" data-search="schoolMenu">
      <span class="label">SEARCH BY</span>
      <a href="#" class="school-search-by active" data-school_search_by="name" data-placeholder="School Name" >Name</a>
      <a href="#" class="school-search-by"  data-school_search_by="location" data-placeholder="School Location" >Location</a>
    </div>
      <div class="search-info">
        <div class="drop-down-fix">
          <input type="text" id="schoolName" name="query" placeholder="School Name" autocorrect="off" autocomplete="off" class="ui-autocomplete-input">
          
          
        </div>
        <div class="cta">
          <!-- <input type="submit" name="_action_search" value="Search" id="schoolNames-btn"> -->
          <br>
          <a class="reset-search-form" href="#" >CANCEL</a>
        </div>
        
      </div>
   
    
  </div>
</div>
				<div class="coach-block-form">
  <div class="center-block-form">
    <div class="h1 mobile-header">Find a Coach</div>
    
    <div class="search-by" data-search="coachMenu" >
      <span class="label">SEARCH BY</span>
      <a href="#" class="coach-search-by active" data-coach_search_by="name" data-placeholder="Coach's name" >Name</a>
      <a href="#" class="coach-search-by"  data-coach_search_by="school" data-placeholder="Coach's school" >School</a>
    </div>
      <div class="search-info">
        <div class="drop-down-fix">
          <input type="text" id="coachName"  placeholder="Coach's name" autocorrect="off" autocomplete="off" >
          
          
        </div>
        <div class="cta">
          <!-- <input type="submit" name="_action_search" value="Search" id="coachNames-btn"> -->
          <br>
          <a class="reset-search-form" href="#" >CANCEL</a>
        </div>
        
      </div>
   
    
  </div>
</div>
				<div class="rating-block-form">
  <div class="center-block-form">
    <div class="h1 mobile-header">Rate a Coach</div>
    <div class="search-by" data-search="sportMenu">
      <span class="label">SEARCH BY</span>
      <a href="#" class="sport-search-by active" data-sport_search_by="mensport" data-placeholder="Men's Sport" >Men's</a>
      <a href="#" class="sport-search-by"  data-sport_search_by="womensport" data-placeholder="Women's Sport" >Women's</a>
    </div>
    <div class="search-info">
        <div class="drop-down-fix" id="sport-search-by-out">
          <input type="text" id="sportName"  placeholder="Men's Sport" autocorrect="off" autocomplete="off" >
        </div>
        
        
      </div>
    <div class="search-by" data-search="coachMenu2">
      <span class="label">AND SEARCH BY</span>
      <a href="#" class="coach2-search-by active" data-coach2_search_by="name" data-placeholder="Coach's name" >Name</a>
      <a href="#" class="coach2-search-by"  data-coach2_search_by="school" data-placeholder="Coach's school" >School</a>
    </div>
      <div class="search-info">
        <div class="drop-down-fix" id="coach2-search-by-out">
          <input type="text" id="coachName2"  placeholder="Coach's name" autocorrect="off" autocomplete="off" >
        </div>
        <div class="cta">
          <!-- <input type="submit" name="_action_search" value="Search" id="coachsportNames-btn"> -->
          <br>
          <a class="reset-search-form" href="#" >CANCEL</a>
        </div>
        
      </div>
   
    
  </div>
</div>
        	</div>
        </div>
<script>
	jQuery(function($){
		//find what
		$('.find-what-item').on('click',function(e){
			var show = $(this).data('show_box');
			$('.find-what-in-3box').hide();
			$('.'+show).show();
		});
		$('.school-search-by').on('click',function(e){
			$('.school-search-by').each(function(index, element) {
				$(this).removeClass('active');
			});
			var placeholder = $(this).data('placeholder');
			$(this).addClass('active');
			//console.log(placeholder);
			$('#schoolName').attr('placeholder',placeholder);
			$('#schoolName').val('');
			return false;
		});
		$('.coach-search-by').on('click',function(e){
			$('.coach-search-by').each(function(index, element) {
				$(this).removeClass('active');
			});
			var placeholder = $(this).data('placeholder');
			$(this).addClass('active');
			//console.log(placeholder);
			$('#coachName').attr('placeholder',placeholder);
			$('#coachName').val('');
			return false;
		});
		$('.coach2-search-by').on('click',function(e){
			$('.coach2-search-by').each(function(index, element) {
				$(this).removeClass('active');
			});
			var placeholder = $(this).data('placeholder');
			$(this).addClass('active');
			//console.log(placeholder);
			$('#coachName2').attr('placeholder',placeholder);
			$('#coachName2').val('');
			return false;
		});
		$('.sport-search-by').on('click',function(e){
			$('.sport-search-by').each(function(index, element) {
				$(this).removeClass('active');
			});
			var placeholder = $(this).data('placeholder');
			$(this).addClass('active');
			//console.log(placeholder);
			$('#sportName').attr('placeholder',placeholder);
			$('#sportName').val('');
			return false;
		});
		$('.reset-search-form').on('click',function(e){
			$('.coach-block-form').hide();
			$('.school-block-form').hide();
			$('.rating-block-form').hide();
			$('.find-what-in-3box').show();
			return false;
		});	
		$('#schoolNames-btn').on('click',function(e){
			var search_by = $('a.school-search-by.active').data('school_search_by');
			var search_key = $('#schoolName').val();
			if(search_key != ''){
				var url_search='<?php echo get_permalink(95); ?>/?search_by='+search_by+'&search_key='+search_key;
				$(location).prop('href', url_search)
			}else{
				alert('Please input key!');
				$('#schoolName').focus();
			}
		});
		
		$('#coachNames-btn').on('click',function(e){
			var search_by = $('a.coach-search-by.active').data('coach_search_by');
			var search_key = $('#coachName').val();
			if(search_key != ''){
				var url_search='<?php echo get_permalink(106); ?>/?search_by='+search_by+'&search_key='+search_key;
				$(location).prop('href', url_search)
			}else{
				alert('Please input key!');
				$('#coachName').focus();
			}
		});
		$('#coachsportNames-btn').on('click',function(e){
			var coach_search_by = $('a.coach2-search-by.active').data('coach2_search_by');
			var coach_search_key = $('#coachName2').val();
			// console.log(coach_search_key);
			var sport_search_by = $('a.sport-search-by.active').data('sport_search_by');
			var sport_search_key = $('#sportName').val();
			if(coach_search_key != '' && sport_search_key != '' ){
				if(coach_search_key == ''){
					alert('Please input coach name or school name key!');
					$('#coachName2').focus();
					return;
				}
				if(sport_search_key == ''){
					alert('Please input sport key!');
					$('#sportName').focus();
					return;
				}
				var url_search='<?php echo get_permalink(127); ?>/?coach_search_by='+coach_search_by+'&coach_search_key='+coach_search_key+'&sport_search_by='+sport_search_by+'&sport_search_key='+sport_search_key;
				$(location).prop('href', url_search)
			} else {
				alert( 'Please fill in both Sport and Coach field.' )
			}
		});
		
	});
</script>
       <?php
		}
}// end class
register_widget('find_what');
?>