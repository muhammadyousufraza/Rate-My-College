<?php
remove_action("genesis_after_header","genesis_do_nav",10);
remove_action("genesis_header","genesis_header_markup_open",5);
remove_action("genesis_header","genesis_do_header",10);
remove_action("genesis_header","genesis_header_markup_close",15);
add_action("genesis_header","tl_custom_header");
add_action("custom_primary_menu","genesis_do_nav");
//add_action( 'genesis_before_header', 'header_sticky' );
function tl_custom_header(){
	?>
<header class="ratemycollege-header">
    <div class="ratemycollege-header-above">
        <div class="wrap">
            <div class="ratemycollege-header-above-left">
                <form class="search-form-custom" itemprop="potentialAction" itemscope=""
                    itemtype="https://schema.org/SearchAction" method="get" action="<?php echo home_url(); ?>"
                    role="search"><input class="search-form-input" type="search" itemprop="query-input" name="s"
                        placeholder="Search for a coach or school"><input class="search-form-submit" type="submit"
                        value="Search">
                    <meta itemprop="target">
                </form>
            </div>
            <div class="ratemycollege-header-above-right">
              <?php if( have_rows('service', 'option') ) :
                while( have_rows('service', 'option') ) : the_row(); ?>
                  <a href="<?= get_sub_field('url') ?>" target="_blank" title="<?= get_sub_field('title') ?>"><i class="fa <?= get_sub_field('icon_class') ?>"
                        aria-hidden="true"></i></a>
                <?php endwhile;
              endif; ?>
            </div>
        </div>
    </div>
    <div class="ratemycollege-header-bellow">
        <div class="wrap">
            <div class="custom-header-left">
                <a href="<?php echo home_url(); ?>">
                    <?php if( get_the_ID() == 33762 ) { ?>
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo-ub.png"
                        style="margin-top: 20px;" />
                    <?php } else { ?>
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png" />
                    <?php } ?>
                </a>
            </div>
            <div class="custom-header-right">
                <?php do_action("custom_primary_menu"); ?>
            </div>
        </div>
    </div>
</header>
<?php if(!is_user_logged_in()): ?>
<div class="modal fade" id="ratemycollege_login2_modal" tabindex="-1" role="dialog"
    aria-labelledby="ratemycollege_login2ModalLabel" aria-hidden="true">
    <form id="ratemycollege_login2_form">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratemycollege_login2ModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="ratemycollege_login2_username"
                            id="ratemycollege_login2_username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="ratemycollege_login2_password"
                            id="ratemycollege_login2_password" placeholder="Password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="ratemycollege_login2_message" role="alert"></span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="ratemycollege_login2_submit">Login</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php endif; ?>
<?php
}
function header_sticky(){
	?>
<div class="header-sticky">
    <div class="wrap">
        <div class="header-sticky-left">
            <a href="<?php echo get_option("home"); ?>"><img
                    src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo-sticky.png" /></a>
        </div>
        <div class="header-sticky-right">
            <?php do_action("custom_primary_menu"); ?>
        </div>
    </div>
</div>
<?php
}