<?php
function add_header_image(){
	global $post;
	$_page_image = get_post_meta($post->ID,"_page_image",true);
	$_page_image_src =wp_get_attachment_image_src($_page_image, 'full');
	//var_dump($_page_image);
	if(!empty($_page_image)):
	?>
    <div class="page_header_image" style="background:url(<?php echo $_page_image_src[0]; ?>) no-repeat top center;">
    </div>
    <?php
	endif;
}
add_action("genesis_after_header","add_header_image");
genesis();