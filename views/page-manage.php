<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $wpdb, $current_user;
$current_blog_id = $blog_id;
$wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
$wpdb->set_prefix( $wpdb->base_prefix );

get_current_user();
$is_logged_in = is_user_logged_in();
$user_id = $current_user->ID;
$user_email = $current_user->user_email;

$roles = get_user_meta( $user_id, Business_Disruptions_Roles::MEMBER_OF_SLUG, true);
$roles = (empty($roles)) ? array() : $roles;
$roles = (gettype($roles) != 'array') ? explode(',',$roles) : $roles;
$is_member = (in_array(Business_Disruptions_Roles::MEMBER_ID, $roles));

$manage_query = new WP_Query('post_type=' . Business_Disruptions_Post_Type::POST_TYPE_SLUG . '&post_status=publish&author=' . $user_id);
?>
<div id="bd_manage_business" class="'.$current_user->ID.'">
    <?php if($is_logged_in){ ?>
    <div class="title"><h2>MANAGE YOUR BUSINESS</h2></div>
        <div id="bd_manage_business_content">
            <?php

            /*
             * Loop through User Businesses
             */
            foreach($manage_query->posts as $post):

                $meta = self::load_business_data($post->ID);
                $meta['edit_url'] = self::add_url() . '?' . self::POSTID . '='.$post->ID;
                $meta['link'] = self::get_business_page_url($post);
                $meta['submit_closing'] = self::submit_closing_url() . '?' . self::POSTID . '=' . $post->ID . '&a=update';
                $meta['title'] = ( strlen($meta[self::NAME]) > 40 ) ? substr($meta[self::NAME], 0, 40).' ...' : $meta[self::NAME];

                $is_owner = ($is_logged_in && $user_email == $meta[self::EMAIL]);
                if($is_owner){
                    $status = (
                        !$meta[self::ZIP] ||
                        !$meta[self::CITY] ||
                        !$meta[self::NAME] ||
                        !$meta[self::ADDRESS] ||
                        !$meta[self::STATE] ||
                        !$meta[self::COMMON_TIME]
                    ) ? 'update' : 'change';
                }

                /*
                 * Get default status messages
                 * Uses submitted closings data if exists else uses standard Normal and Closed data
                 */
                $timezone = self::get_user_time_zone($meta[self::ZIP]);
                $default_timezone = date_default_timezone_get();
                date_default_timezone_set($timezone['timezone_desc']);
                $today_name = strtolower(date('l'));
                $today_date = date('m/d/Y');
                $tomorrow_name = strtolower(date('l', time()+86400));
                $tomorrow_date = date('m/d/Y', time()+86400);

                if(empty($meta[self::CLOSING_DAY1])){
                    $todaymessage = ($meta[self::DAY1_STATUS] == 'normal_hours') ? 'Normal Hours' : 'Closed';
                    $todaydesc = ($meta[self::DAY1_STATUS] == 'normal_hours') ? 'Normal Hours' : 'Closed';
                }else{
                    $todaymessage = $meta[self::CLOSING_DAY1]['statusmsg'];
                    $todaydesc = $meta[self::CLOSING_DAY1]['statusdesc'];
                }
                if(empty($meta[self::CLOSING_DAY2])){
                    $tomorrowmessage = ($meta[self::DAY2_STATUS] == 'normal_hours') ? 'Normal Hours' : 'Closed';
                    $tomorrowdesc = ($meta[self::DAY2_STATUS] == 'normal_hours') ? 'Normal Hours' : 'Closed';
                }else{
                    $tomorrowmessage = $meta[self::CLOSING_DAY2]['statusmsg'];
                    $tomorrowdesc = $meta[self::CLOSING_DAY2]['statusdesc'];
                }
                date_default_timezone_set($default_timezone);

                ?>
                <div id="bd_business_listing">
                    <div class="block1">
                        <h1 class="font_face"><a  href="<?php echo $meta['link']; ?>"><?php echo $meta['title']; ?></a></h1>
                        <?php if($is_owner) {?>
                        <div class="edit-link">
                            <a class="aleft editbusiness" href="<?php echo $meta['edit_url']; ?>" title="Edit Business"></a>
                            <a id="<?php echo $post->ID; ?>" href="javascript:void(0);" class="delete_post deletebusiness" title="Delete Business"></a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="block2">
                        <div class="stauts-alert">
                            <div class="sl_left">
                                <?php if ( $is_owner ) { ?>
                                <h4>Current Status
                                    <?php if( $status == 'update'){ ?>
                                    <span><a class="" href="<?php echo $meta['edit_url']; ?>">Update missing fields in your business to Change Status</a></span>
                                    <?php } else { ?>
                                    <span><a class="" href="<?php echo $meta['submit_closing']; ?>">Change</a></span>
                                    <?php } ?>
                                </h4>
                                <?php } ?>
                                <p>Today: <?php echo $todaymessage; ?></p>
                                <p>Tomorrow: <?php echo $tomorrowmessage; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if($manage_query->post_count < 1 ) { ?>
            <div class="nobusiness">
                <strong>You don't have a business listing, please use add business</strong>
            </div>
            <?php } ?>

            <?php if ( $is_member || is_super_admin() ) { ?>
            <div class="sl_right more_links manage-business">
                <a class="button submit-button button-primary" href="<?php echo self::add_url(); ?>">ADD NEW BUSINESS</a>
            </div>
            <div class="sl_right more_links manage-business">
                <a class="button submit-button button-primary" href="<?php echo self::closing_url(); ?>">VIEW CLOSINGS</a>
            </div>
            <?php } ?>
        </div>
    <?php } else { ?>
    <div class="success-message">Please <a href="<?php echo self::login_url()?>">Login</a> to manage your businesses</div>
    <?php } ?>
</div>
<?php
/*
 * Reset database pointer to current blog
 */
$wpdb->blogid = $current_blog_id;
$wpdb->set_prefix( $wpdb->base_prefix );