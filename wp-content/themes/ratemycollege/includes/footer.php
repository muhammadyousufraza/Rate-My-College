<?php
remove_action("genesis_footer",	"genesis_footer_markup_open",5);
remove_action("genesis_footer",	"genesis_do_footer");
remove_action("genesis_footer",	"genesis_footer_markup_close",15);
remove_action("genesis_footer",	"genesis_do_subnav",7);
add_action("genesis_footer",	"tl_custom_footer");
function tl_custom_footer(){

	?>
<footer class="site-footer" itemscope="" itemtype="https://schema.org/WPFooter">
    <div class="wrap">
        <div class="site-footer-left">
            <a href="<?php echo home_url(); ?>"><img
                    src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo-footer.png" /></a>
        </div>
        <div class="site-footer-right">
            <div class="site-footer-right-above">
              <?php if( have_rows('service', 'option') ) :
                while( have_rows('service', 'option') ) : the_row(); ?>
                  <a href="<?= get_sub_field('url') ?>" target="_blank" title="<?= get_sub_field('title') ?>"><i class="fa <?= get_sub_field('icon_class') ?>"
                        aria-hidden="true"></i></a>
                <?php endwhile;
              endif; ?>
            </div>
            <div class="site-footer-right-below">
                <?php genesis_do_subnav(); ?>
            </div>
        </div>
    </div>
</footer>
<?php

}