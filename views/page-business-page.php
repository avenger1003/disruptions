<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $wpdb, $blog_id, $current_user, $wp_query;
get_current_user();
$current_blog_id = $blog_id;
$user_id = $current_user->ID;
$wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
$wpdb->set_prefix( $wpdb->base_prefix );

$page_slug = basename($_SERVER['REQUEST_URI']);
add_filter( 'query_vars', 'add_query_vars_filter' );
$query = new WP_Query(array(
        'name' => $page_slug,
        'post_type' => Business_Disruptions_Post_Type::POST_TYPE_SLUG,
    )
);
$query->the_post();
global $post;

//if(empty($post)) exit;

$meta = self::load_business_data($post->ID);
$meta = self::load_business_history($post->ID,$meta);
$meta['edit_url'] = self::add_url() . '?' . self::POSTID . '='.$post->ID;
$meta['link'] = self::get_business_page_url($post);
$meta['submit_closing'] = self::submit_closing_url() . '?' . self::POSTID . '=' . $post->ID . '&a=update';
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

$meta = self::get_daily_operation_hours($post->ID,$meta);
date_default_timezone_set($default_timezone);

?>

<div id="bd_business_listing">
    <div class="backDiv"><a href="<?php echo self::closing_url()?>">&laquo; Back to Closings</a></div>
    <div class="block1">
        <h1 class="font_face"><?php the_title()?></h1>
        <?php if($meta['is_owner']) {?>
        <div class="edit-link">
            <a class="aleft editbusiness" href="<?php echo $meta['edit_url']?>" title="Edit Business"></a>
            <a id="<?php echo $post->ID; ?>" href="javascript:void(0);" class="delete_post deletebusiness" title="Delete Business"></a>
        </div>
        <?php } ?>
    </div>
    <div class="block2">
        <div class="bd_list_addr">
            <ul class="detail-ads">
                <li><?php echo $meta[self::ADDRESS].', '.$meta[self::CITY].', '.$meta[self::STATE].', '.$meta[self::ZIP]?></li>
                <?php  if(!empty($meta[self::SITE])){?>
                <li><a href="http://<?php echo $meta[self::SITE]?>" target="_blank"><?php echo $meta[self::SITE]?></a></li>
                <?php }if(!empty($meta[self::CONTACT]) && $current_user->email == $meta[self::EMAIL]){?>
                <br/><li><label>Contact Person: </label><span><?php echo $meta[self::CONTACT]?></span></li>
                <?php }if(!empty($meta[self::PHONE]) &&  $current_user->email == $meta[self::EMAIL]){?>
                <li><label>Phone: </label><span><?php echo $meta[self::PHONE]?></span></li>
                <?php }if($meta['is_owner']){?>
                <li><span><a href="mailto:<?php echo $meta[self::EMAIL]?>" target="_blank"><?php echo $meta[self::EMAIL]?></a></span></li>
                <?php }?>
            </ul>
            <div class="stauts-alert">
                <div class="sl_left">
                    <?php if($is_owner) {?>
                    <h4>Current Status <span><a class="" href="<?php echo self::submit_closing_url().'?p='.$post->ID.'&a=update'?>">Change</a></span></h4>
                    <?php }else{?>
                    <h4>Current Status</h4>
                    <?php }?>
                    <p class='todaystatus'>Today: <?php echo $todaymessage?></p>
                    <p class='detailsb' <?php self::hide($todaydesc,'')?>>Details: <?php echo $todaydesc?></p>
                    <p class='tomostatus'>Tomorrow: <?php echo $tomorrowmessage?></p>
                    <p class='detailsb' <?php self::hide($tomorrowdesc,'')?>>Details: <?php echo $tomorrowdesc?></p>
                </div>
            </div>
            <?php if($meta[self::EVERYDAY] == 'all'){?>
            <div class="detail-ads">
                <h4>Business Hours (<?php echo $timezone['timezone_short']?>)</h4>
                <div class="days-of-operation"><span>Sun - Sat</span> <?php echo date('g:i A',strtotime($meta[self::COMMON_TIME]['start'].':00'))?> - <?php echo date('g:i A',strtotime($meta[self::COMMON_TIME]['end'].':00'))?></div>
            </div>
            <?php }else{?>
            <div class="detail-ads">
                <h4>Business Hours</h4>
                <div class="clearfix"></div>
                <div>
                    <ul>
                    <?php
                        $days = array('mon','tue','wed','thu','fri','sat','sun');
                        foreach($days as $day){
                            $name = ucfirst($day);
                            if(empty($meta[$day.'_fromhr'])){
                                echo '<li><span>'.ucfirst($name).'</span> (Closed)</li>';
                            }else{
                                $start = date('g:i A',strtotime($meta[$day.'_fromhr'].':00'));
                                $end = date('g:i A',strtotime($meta[$day.'_tohr'].':00'));
                                echo '<li><span>'.ucfirst($name).'</span> '.$start.' - '.$end.'</li>';
                            }
                        }?>
                    </ul>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
</div>
<div class="bd_list_map"></div>
<?php if(!empty($meta['history'])){?>
<div class="bd_business_history" id="<?php echo $meta[self::CLOSING_HISTORY]?>">
    <h1>Closing History</h1>
    <div class="bd_business_history_header">
        <span class="date">Date</span>
        <span class="status">Status</span>
        <span class="details">Details</span>
        <div class="clear"></div>
    </div>
    <?php foreach($meta['history'] as $history){ if(empty($history)) continue;?>
    <div class="business-status">
        <span class="date"><?php echo $history['date']?></span>
        <div class="status"><?php echo $history['cur_status']?></div>
        <div class="details"><?php echo $history['statusdesc']?></div>
    </div>
    <?php }?>
</div>
<?php }?>
<div class="nobusiness" style="display:none">
    <strong>Your business has been successfully deleted, <a href="<?php echo self::manage_url()?>">Back to manage business</a></strong>
</div>
<?php if($meta['is_owner']) {?>
<div class="sl_right more_links manage-business"><a class="button submit-button button-primary" href="<?php echo self::add_url()?>">ADD NEW BUSINESS</a></div>
<div class="sl_right more_links manage-business"><a class="button submit-button button-primary" href="<?php echo self::manage_url()?>">MANAGE BUSINESSES</a></div>
<?php }

/*
 * Reset database pointer to current blog
 */
$wpdb->blogid = $current_blog_id;
$wpdb->set_prefix( $wpdb->base_prefix );