<?php
genesis_register_sidebar( array(
'id' => 'school-sidebar',
'name' => __( 'School sidebar', 'standard' ),
'description' => __( 'School sidebar', 'standard' ),
) );
genesis_register_sidebar( array(
'id' => 'about-sidebar-bottom',
'name' => __( 'About sidebar bottom', 'standard' ),
'description' => __( 'About sidebar bottom', 'standard' ),
) );
include "find-what.php";
include "recent-post-left.php";
include "recent-post-right.php";
?>