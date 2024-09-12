<?php
add_action("genesis_meta", "ratemycollege_home_genesis_meta");
function ratemycollege_home_genesis_meta()
{
    //* Force full-width-content layout setting
    add_filter(
        "genesis_pre_get_option_site_layout",
        "__genesis_return_full_width_content"
    );
    //* Add ratemycollege-pro-home body class
    //add_filter( 'body_class', 'ratemycollege_body_class' );
    //* Remove the default Genesis loop
    remove_action("genesis_loop", "genesis_do_loop");
    //* Add home top widgets
    add_action("genesis_after_header", "ratemycollege_after_header");

    //* Add home bottom widgets
    //add_action( 'genesis_loop', 'ratemycollege_home_bottom_widgets' );
}
function ratemycollege_after_header()
{
    global $wpdb;
    $current_user = wp_get_current_user();
    if (have_posts()) {
        while (have_posts()) {

            the_post();
            $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
            $src_thumbnail = wp_get_attachment_image_src(
                $post_thumbnail_id,
                "coach"
            );
            $_coach_school = get_post_meta(get_the_ID(), "_coach_school", true);
            $_coach_sports = get_post_meta(get_the_ID(), "_coach_sports", true);
            $_coach_phone = get_post_meta(get_the_ID(), "_coach_phone", true);
            $_coach_email = get_post_meta(get_the_ID(), "_coach_email", true);
            //$_coach_rating = get_post_meta(get_the_ID(),"_coach_rating",true);
            //$_coach_tinder_ticker = get_post_meta(get_the_ID(),"_coach_tinder_ticker",true);
            //$_coach_ratings_arr=array();
            //$_coach_ratings_arr['season']='Season';
            //$_coach_ratings_arr['season2']='Season2';
            //$_coach_ratings_arr['season3']='Season3';
            //$_coach_ratings = get_post_meta(get_the_ID(),"_coach_ratings",true);
            //$_coach_scouting_report = get_post_meta(get_the_ID(),"_coach_scouting_report",true);
            $post_id = get_the_ID();
            $ratings = $wpdb->get_results(
                "SELECT * FROM `" .
                    $wpdb->prefix .
                    "coach_rate` where `post_id` = '{$post_id}' ORDER BY `id` DESC"
            );
            ?>
<div class="coaches-out">
  <div class="wrap">
    <div class="coach-left-area">
      <div class="coach-profile-head">
          <div class="coaches-profile-left">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
              <?php if (has_post_thumbnail()): ?>
              <img src="<?php echo $src_thumbnail[0]; ?>" alt="<?php the_title(); ?>">
              <?php else: ?>
              <img src="<?php echo THEME_URL; ?>/images/no-avatar.png" alt="No avatar">
              <?php endif; ?>
            </a>
          </div>
          <div class="coaches-profile-right-in-box-title">
            <h2>
              <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_title(); ?>
              </a>
              <div class="school-name">
                <span style="margin-right: 10px;"><img src="http://redcraftmedia.net/ratemycollege/wp-content/uploads/2024/02/Buildings.svg"></span>
                <span>
                <?= rmc_get_school_name(
                  get_field("school") ) ?>
                </span>
            </div>
            </h2>
          </div>
      </div>
      <div class="coach-scoting-report">
        <div class="coaches-right-in">
          <div class="scot-t">
            <h3>Scouting REPORT</h3>
            <div class="total-rating">
              <span>Total Rating</span> <?php echo scouting_report_average($post_id, "iq", "IQ"); ?>
            </div>
          </div>
          
          <div class="coaches-right-report">
            <div class="coaches-right-report-in">
              <?php echo scouting_report_average($post_id, "iq", "IQ"); ?>
              <?php echo scouting_report_average(
                  $post_id,
                  "personable",
                  "Ethical"
              ); ?>
              <?php echo scouting_report_average(
                  $post_id,
                  "communication",
                  "Communication"
              ); ?>
              <?php echo scouting_report_average($post_id, "staff", "Staff"); ?>
              <?php echo scouting_report_average(
                  $post_id,
                  "individual",
                  "Player/Individual Development"
              ); ?>
              <?php echo scouting_report_average(
                  $post_id,
                  "academics",
                  "Academics"
              ); ?>
            </div>

          </div>
        </div>
      </div>
      <button class="btn btn-primary" id="coaches-records-show-rating">RATE THIS COACH</button>
    </div>
    <div class="coach-right-area">
      <div class="rating-coach-out">
        <h3 class="rating-ti">Ratings <span>(<?php echo count($ratings); ?>)</span></h3>
        <?php foreach ($ratings as $item):
            echo rating_row($item);
        endforeach; ?>
      </div>
    </div>
    <div class="coaches-left-n">
      <?php if ($ratings): ?>
      <div class="coaches-records">
        <div class="ratemycollege-form-register-login-out">
          <div class="dos-and-donts">
            <?php echo do_shortcode(
                '[fl_builder_insert_layout slug="ratings-dos-and-donts"]'
            ); ?>
          </div>
          <h3 class="title">
            RATE THIS COACH
          </h3>
          <?php
          //if(is_user_logged_in()):

          //var_dump($current_user->ID);
          ?>
          <div class="rating-coach-form-out">
            <form id="rating-coach-form">
              <div class="rating-coach-form-star" data-post_id="<?php the_ID(); ?>" data-user_id="<?php echo $current_user->ID; ?>">
                <div class="rating-coach-form-star-left">
                  <div class="rate-the-coach-item iq" id="rate-the-coach-item-iq">
                    <div class="rate-the-coach-item-left">
                      IQ ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>
                  </div>
                  <div class="rate-the-coach-item personable" id="rate-the-coach-item-personable">
                    <div class="rate-the-coach-item-left">
                      Ethical ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>
                  </div>
                  <div class="rate-the-coach-item communication" id="rate-the-coach-item-communication">
                    <div class="rate-the-coach-item-left">
                      Communication ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>
                  </div>

                </div>
                <div class="rating-coach-form-star-right">
                  <div class="rate-the-coach-item staff" id="rate-the-coach-item-staff">
                    <div class="rate-the-coach-item-left">
                      Staff ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>
                  </div>
                  <div class="rate-the-coach-item player-individual-development"
                    id="rate-the-coach-item-player-individual-development">
                    <div class="rate-the-coach-item-left">
                      Player/Individual Development ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>
                  </div>

                  <div class="rate-the-coach-item  academics" id="rate-the-coach-item-academics">
                    <div class="rate-the-coach-item-left">
                      Academics ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>


                  </div>
                </div>
              </div>
              <div class="rating-coach-form-review">
                <textarea id="rating-coach-form-review-text" placeholder="Your review"></textarea>
              </div>
              <div class="rating-coach-form-quetion">
                <div class="rating-coach-form-quetion-item">
                  <div class="rating-coach-form-quetion-item-question">1. Was your recruiting visit a good
                    respresentation of your experience as a student-athlete under this coach?</div>
                  <div class="rating-coach-form-quetion-item-answer">
                    <span><input type="radio" name="rating-coach-form-quetion-item-answer-1" value="1"></span>
                    <span>yes</span>
                    <span><input type="radio" name="rating-coach-form-quetion-item-answer-1" value="0"></span>
                    <span>no</span>
                  </div>
                </div>
                <div class="rating-coach-form-quetion-item">
                  <div class="rating-coach-form-quetion-item-question">2. Did your coach promote inclusion and promote a
                    team environment?</div>
                  <div class="rating-coach-form-quetion-item-answer">
                    <span><input type="radio" name="rating-coach-form-quetion-item-answer-2" value="1"></span>
                    <span>yes</span>
                    <span><input type="radio" name="rating-coach-form-quetion-item-answer-2" value="0"></span>
                    <span>no</span>
                  </div>
                </div>
              </div>
              <div class="rating-coach-form-submit">
                <button type="submit" class="btn btn-primary btn-lg" id="rating-coach-form-submit">Submit</button>
              </div>
            </form>
          </div>
          <?php
          /*else:
                <div class="ratemycollege-not-login">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#ratemycollege_login_modal">
                Login
                </button>
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#ratemycollege_register_modal">
                Register
                </button>
                </div>
                <div class="modal fade" id="ratemycollege_register_modal" tabindex="-1" role="dialog" aria-labelledby="ratemycollege_registerModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="ratemycollege_registerModalLabel">Register</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form id="ratemycollege_register_form">
                <div class="modal-body">
                <div class="form-group"> 
                <div class="form-row">
                <div class="col">
                <input type="text" class="form-control" name="ratemycollege_register_first_name" id="ratemycollege_register_first_name" placeholder="First name" required>
                </div>
                <div class="col">
                <input type="text" class="form-control" name="ratemycollege_register_last_name" id="ratemycollege_register_last_name"  placeholder="Last name" required>
                </div>
                </div>
                </div>
                <div class="form-group">
                <input type="email" class="form-control" name="ratemycollege_register_email" id="ratemycollege_register_email"  placeholder="Email" required>
                </div>
                <div class="form-group">
                <input type="password" class="form-control" name="ratemycollege_register_password" id="ratemycollege_register_password" placeholder="Password" required>
                </div>
                
                
                </div>
                <div class="modal-footer">
                <span class="ratemycollege_register_message"  role="alert"></span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button  type="submit" class="btn btn-primary" id="ratemycollege_register_trigger">Submit</button>
                </div>
                </form>
                </div>
                </div>
                </div>
                <div class="modal fade" id="ratemycollege_login_modal" tabindex="-1" role="dialog" aria-labelledby="ratemycollege_loginModalLabel" aria-hidden="true">
                <form id="ratemycollege_login_form">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="ratemycollege_loginModalLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <div class="form-group">
                <input type="text" class="form-control" name="ratemycollege_login_username" id="ratemycollege_login_username"  placeholder="Username" required>
                </div>
                <div class="form-group">
                <input type="password" class="form-control" name="ratemycollege_login_password" id="ratemycollege_login_password" placeholder="Password" required>
                </div>
                </div>
                <div class="modal-footer">
                <span class="ratemycollege_login_message"  role="alert"></span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Login</button>
                </div>
                </div>
                </div>
                </form>
                </div>
                <?php endif; */
          ?>
        </div>

      </div>
      <?php
          //if (is_user_logged_in()):
          //var_dump($current_user->ID);
          /*else:
?>
          <div class="ratemycollege-not-login">
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
              data-target="#ratemycollege_login_modal">
              Login
            </button>
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
              data-target="#ratemycollege_register_modal">
              Register
            </button>
          </div>
          <div class="modal fade" id="ratemycollege_register_modal" tabindex="-1" role="dialog"
            aria-labelledby="ratemycollege_registerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ratemycollege_registerModalLabel">Register</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="ratemycollege_register_form">
                  <div class="modal-body">
                    <div class="form-group">
                      <div class="form-row">
                        <div class="col">
                          <input type="text" class="form-control" name="ratemycollege_register_first_name"
                            id="ratemycollege_register_first_name" placeholder="First name" required>
                        </div>
                        <div class="col">
                          <input type="text" class="form-control" name="ratemycollege_register_last_name"
                            id="ratemycollege_register_last_name" placeholder="Last name" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <input type="email" class="form-control" name="ratemycollege_register_email"
                        id="ratemycollege_register_email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" name="ratemycollege_register_password"
                        id="ratemycollege_register_password" placeholder="Password" required>
                    </div>


                  </div>
                  <div class="modal-footer">
                    <span class="ratemycollege_register_message" role="alert"></span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="ratemycollege_register_trigger">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal fade" id="ratemycollege_login_modal" tabindex="-1" role="dialog"
            aria-labelledby="ratemycollege_loginModalLabel" aria-hidden="true">
            <form id="ratemycollege_login_form">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="ratemycollege_loginModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <input type="text" class="form-control" name="ratemycollege_login_username"
                        id="ratemycollege_login_username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" name="ratemycollege_login_password"
                        id="ratemycollege_login_password" placeholder="Password" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <span class="ratemycollege_login_message" role="alert"></span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="ratemycollege_login_submit">Login</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <?php
                endif; */
          //if (is_user_logged_in()):
          //var_dump($current_user->ID);
          /*else:
?>
          <div class="ratemycollege-not-login">
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
              data-target="#ratemycollege_login_modal">
              Login
            </button>
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
              data-target="#ratemycollege_register_modal">
              Register
            </button>
          </div>
          <div class="modal fade" id="ratemycollege_register_modal" tabindex="-1" role="dialog"
            aria-labelledby="ratemycollege_registerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ratemycollege_registerModalLabel">Register</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="ratemycollege_register_form">
                  <div class="modal-body">
                    <div class="form-group">
                      <div class="form-row">
                        <div class="col">
                          <input type="text" class="form-control" name="ratemycollege_register_first_name"
                            id="ratemycollege_register_first_name" placeholder="First name" required>
                        </div>
                        <div class="col">
                          <input type="text" class="form-control" name="ratemycollege_register_last_name"
                            id="ratemycollege_register_last_name" placeholder="Last name" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <input type="email" class="form-control" name="ratemycollege_register_email"
                        id="ratemycollege_register_email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" name="ratemycollege_register_password"
                        id="ratemycollege_register_password" placeholder="Password" required>
                    </div>


                  </div>
                  <div class="modal-footer">
                    <span class="ratemycollege_register_message" role="alert"></span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="ratemycollege_register_trigger">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal fade" id="ratemycollege_login_modal" tabindex="-1" role="dialog"
            aria-labelledby="ratemycollege_loginModalLabel" aria-hidden="true">
            <form id="ratemycollege_login_form">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="ratemycollege_loginModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <input type="text" class="form-control" name="ratemycollege_login_username"
                        id="ratemycollege_login_username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" name="ratemycollege_login_password"
                        id="ratemycollege_login_password" placeholder="Password" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <span class="ratemycollege_login_message" role="alert"></span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="ratemycollege_login_submit">Login</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <?php
                endif; */
          else: ?>
      <div class="coaches-records">
        <div class="ratemycollege-form-register-login-out">
          <div class="dos-and-donts">
            <?php echo do_shortcode(
                '[fl_builder_insert_layout slug="ratings-dos-and-donts"]'
            ); ?>
          </div>
          <h3 class="title">
            RATE THIS COACH
          </h3>
          <?php
          //if (is_user_logged_in()):
          //var_dump($current_user->ID);
          ?>
          <div class="rating-coach-form-out">
            <form id="rating-coach-form">
              <div class="rating-coach-form-star" data-post_id="<?php the_ID(); ?>" data-user_id="<?php echo $current_user->ID; ?>">
                <div class="rating-coach-form-star-left">
                  <div class="rate-the-coach-item iq" id="rate-the-coach-item-iq">
                    <div class="rate-the-coach-item-left">
                      IQ ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>
                  </div>
                  <div class="rate-the-coach-item personable" id="rate-the-coach-item-personable">
                    <div class="rate-the-coach-item-left">
                      Ethical ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>
                  </div>
                  <div class="rate-the-coach-item communication" id="rate-the-coach-item-communication">
                    <div class="rate-the-coach-item-left">
                      Communication ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>
                  </div>

                </div>
                <div class="rating-coach-form-star-right">
                  <div class="rate-the-coach-item staff" id="rate-the-coach-item-staff">
                    <div class="rate-the-coach-item-left">
                      Staff ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>
                  </div>
                  <div class="rate-the-coach-item player-individual-development"
                    id="rate-the-coach-item-player-individual-development">
                    <div class="rate-the-coach-item-left">
                      Player/Individual Development ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>
                  </div>

                  <div class="rate-the-coach-item  academics" id="rate-the-coach-item-academics">
                    <div class="rate-the-coach-item-left">
                      Academics ?
                    </div>
                    <div class="rate-the-coach-item-right">
                      <span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="1"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="2"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="3"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="4"></span><span class="rating-coach-star-rating-form dashicons dashicons-star-empty"
                        data-index="5"></span>
                    </div>


                  </div>
                </div>
              </div>
              <div class="rating-coach-form-review">
                <textarea id="rating-coach-form-review-text" placeholder="Your review"></textarea>
              </div>
              <div class="rating-coach-form-quetion">
                <div class="rating-coach-form-quetion-item">
                  <div class="rating-coach-form-quetion-item-question">1. Was your recruiting visit a good
                    respresentation of your experience as a student-athlete under this coach?</div>
                  <div class="rating-coach-form-quetion-item-answer">
                    <span><input type="radio" name="rating-coach-form-quetion-item-answer-1" value="1"></span>
                    <span>yes</span>
                    <span><input type="radio" name="rating-coach-form-quetion-item-answer-1" value="0"></span>
                    <span>no</span>
                  </div>
                </div>
                <div class="rating-coach-form-quetion-item">
                  <div class="rating-coach-form-quetion-item-question">2. Did your coach promote inclusion and promote a
                    team environment?</div>
                  <div class="rating-coach-form-quetion-item-answer">
                    <span><input type="radio" name="rating-coach-form-quetion-item-answer-2" value="1"></span>
                    <span>yes</span>
                    <span><input type="radio" name="rating-coach-form-quetion-item-answer-2" value="0"></span>
                    <span>no</span>
                  </div>
                </div>
              </div>
              <div class="rating-coach-form-submit">
                <button type="submit" class="btn btn-primary btn-lg" id="rating-coach-form-submit">Submit</button>
              </div>
            </form>
          </div>
          <?php
          /*else:
?>
          <div class="ratemycollege-not-login">
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
              data-target="#ratemycollege_login_modal">
              Login
            </button>
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
              data-target="#ratemycollege_register_modal">
              Register
            </button>
          </div>
          <div class="modal fade" id="ratemycollege_register_modal" tabindex="-1" role="dialog"
            aria-labelledby="ratemycollege_registerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ratemycollege_registerModalLabel">Register</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="ratemycollege_register_form">
                  <div class="modal-body">
                    <div class="form-group">
                      <div class="form-row">
                        <div class="col">
                          <input type="text" class="form-control" name="ratemycollege_register_first_name"
                            id="ratemycollege_register_first_name" placeholder="First name" required>
                        </div>
                        <div class="col">
                          <input type="text" class="form-control" name="ratemycollege_register_last_name"
                            id="ratemycollege_register_last_name" placeholder="Last name" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <input type="email" class="form-control" name="ratemycollege_register_email"
                        id="ratemycollege_register_email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" name="ratemycollege_register_password"
                        id="ratemycollege_register_password" placeholder="Password" required>
                    </div>


                  </div>
                  <div class="modal-footer">
                    <span class="ratemycollege_register_message" role="alert"></span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="ratemycollege_register_trigger">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal fade" id="ratemycollege_login_modal" tabindex="-1" role="dialog"
            aria-labelledby="ratemycollege_loginModalLabel" aria-hidden="true">
            <form id="ratemycollege_login_form">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="ratemycollege_loginModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <input type="text" class="form-control" name="ratemycollege_login_username"
                        id="ratemycollege_login_username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" name="ratemycollege_login_password"
                        id="ratemycollege_login_password" placeholder="Password" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <span class="ratemycollege_login_message" role="alert"></span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="ratemycollege_login_submit">Login</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <?php
                endif; */
          ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <!-- <div class="coaches-right">
      
      <div class="ad" style="margin: 2rem 0;">
        <div id="631380723">
          <script type="text/javascript">
          try {
            window._mNHandle.queue.push(function() {
              window._mNDetails.loadTag("631380723", "300x250", "631380723");
            });
          } catch (error) {}
          </script>
        </div>
      </div>
    </div> -->
  </div>
</div>
<?php
        }
    }
}
genesis();
