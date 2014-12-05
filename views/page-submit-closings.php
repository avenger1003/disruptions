<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }


global $wpdb, $blog_id, $current_user;
$current_blog_id = $blog_id;
$wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
$wpdb->set_prefix( $wpdb->base_prefix );

get_current_user();
$userid = $current_user->ID;

$Post = Business_Disruptions_Post_Type::get_instance();

// Business Information
$post_id = $_GET[self::POSTID];
$business_data = $this->business_data;

$business_data[self::NAME] = (strlen( $business_data[self::NAME]) > 43) ? substr($business_data[self::NAME], 0, 43 ).' ...' :  $business_data[self::NAME];

// Timezone information
$timezone = self::get_user_time_zone($business_data[self::ZIP]);

// Get name of day (ex. Monday, Tuesday, etc ...
$default_timezone = date_default_timezone_get();
date_default_timezone_set($timezone['timezone_desc']);
$today_name = strtolower(date('l'));
$today_date = date('m/d/Y');
$tomorrow_name = strtolower(date('l', time()+86400));
$tomorrow_date = date('m/d/Y', time()+86400);
date_default_timezone_set($default_timezone);
/*
 * Initialize Today & Tomorrow Information
 */
$today = $tomorrow = array(
    'closings' => array(
        'time' => array(
            'calldayclosed' => '',
            'starttime' => '',
            'endtime' => '',
        ),
        'date' => '',
        'cur_status',
        'expiry',
        'statusmsg' => '',
        'statusdesc' => '',
        'statusslug' => '',
    ),
    'is_closed' => false,
    'is_scheduled' => false,
    'operation_hours' => ''
);

// Today Data
$today['date'] = $today_date;
// Scheduled Data
if(empty($business_data[self::CLOSING_DAY1])){
    $today['is_scheduled'] = true;
    // 1. Scheduled Closing
    $today['operation_hours'] = get_post_meta($post_id,'bd_'.$today_name,true);
    if(empty($today['operation_hours'])){
        $today['closings']['time']['calldayclosed'] = 'alldayclosed';
        $today['closings']['time']['starttime'] = $business_data[self::COMMON_TIME]['start'];
        $today['closings']['time']['endtime'] = $business_data[self::COMMON_TIME]['end'];
        $today['closings']['cur_status'] = 'Closed';
        $today['closings']['statusmsg'] = 'Closed Today';
        $today['closings']['statusslug'] = 'closed';
        $today['is_closed'] = true;
    // 2. Scheduled Open
    }else{
        $today['closings']['time']['starttime'] = $today['operation_hours']['start'];
        $today['closings']['time']['endtime'] = $today['operation_hours']['end'];
        $today['closings']['cur_status'] = 'Normal Hours';
        $today['closings']['statusmsg'] = 'Normal Hours';
        $today['closings']['statusslug'] = 'normal_hours';
    }
// Business Disruption Data
}else{
    $today['is_scheduled'] = false;
    $today['closings'] = $business_data[self::CLOSING_DAY1];
}

// Tomorrow Data
$tomorrow['date'] = $tomorrow_date;
// Scheduled Data
if(empty($business_data[self::CLOSING_DAY2])){
    $tomorrow['is_scheduled'] = true;
    // 1. Scheduled Closing
    $tomorrow['operation_hours'] = get_post_meta($post_id,'bd_'.$tomorrow_name,true);
    if(empty($tomorrow['operation_hours'])){
        $tomorrow['closings']['time']['calldayclosed'] = 'alldayclosed';
        $tomorrow['closings']['time']['starttime'] = $business_data[self::COMMON_TIME]['start'];
        $tomorrow['closings']['time']['endtime'] = $business_data[self::COMMON_TIME]['end'];
        $tomorrow['closings']['cur_status'] = 'Closed';
        $tomorrow['closings']['statusmsg'] = 'Closed Today';
        $tomorrow['closings']['statusslug'] = 'closed';
        $tomorrow['is_closed'] = true;
        // 2. Scheduled Open
    }else{
        $tomorrow['closings']['time']['starttime'] = $tomorrow['operation_hours']['start'];
        $tomorrow['closings']['time']['endtime'] = $tomorrow['operation_hours']['end'];
        $tomorrow['closings']['cur_status'] = 'Normal Hours';
        $tomorrow['closings']['statusmsg'] = 'Normal Hours';
        $tomorrow['closings']['statusslug'] = 'normal_hours';
    }

// Business Disruption Data
}else{
    $tomorrow['is_scheduled'] = false;
    $tomorrow['closings'] = $business_data[self::CLOSING_DAY2];
}

/*
* Reset database pointer to current blog
*/
$wpdb->blogid = $current_blog_id;
$wpdb->set_prefix( $wpdb->base_prefix );

$is_owner = self::is_owner($post_id);
?>

<div id="bd_closings_alerts">
    <div class="backDiv"><a href="<?php echo self::closing_url(); ?>">&laquo; Back to Closings</a></div>
    <?php if($post_id != 'firsttime'): ?>
    <h1 class="font_face legcymig closingt">Submit Closing Alert</h1>
    <span><a href="<?php echo self::manage_url(); ?>" title="Add and manage businesses">Add and manage businesses</a></span>
    <?php elseif($post_id == 'firsttime'): ?>
    <div class="success-message">Your Business has been registered. Please <a href="<?php echo self::login_url(); ?>">login</a> to submit disruptions</div>
    <?php endif;?>

    <form enctype="multipart/form-data" method="POST" action="" class="login-registration validate <?php if(!is_owner) echo 'not_owner'; ?> clearfix" id="closing_form" onsubmit="javascript:void(0)">
        <div class="input  clearfix">
            <label for="bname"><strong>Business Name</strong></label>
            <input type="text" disabled="disabled" name="bname" id="bname" value="<?php echo $business_data[self::NAME]; ?>" rel="<?php echo $business_data[self::CITY]; ?>" otime="<?php echo $business_data[self::START]; ?>" totime="<?php echo $business_data[self::END]; ?>"/>
            <div class="clearfix"></div>
            <div class="todayDiv">
                <div id="todayAllFieldsHolder">
                    <div class="cdate">
                        <label><strong>Today&nbsp;</strong> (<?php echo $today['date']; ?>)</label>
                        <input type="hidden" value="<?php echo $today['date']; ?>" name="day1_date"/>
                        <select class="todayChangeStatus" name="todayChangeStatus" id="todayChangeStatus" <?php disabled($today['is_closed'],true); ?>>
                            <option value="normal_hours" <?php selected($today['closings']['statusslug'],'normal_hours'); ?>>Normal Hours</option>
                            <option value="closed" <?php selected($today['closings']['statusslug'],'closed'); ?>>Closed</option>
                            <option value="delayed" <?php selected($today['closings']['statusslug'],'delayed'); ?>>Delayed by</option>
                            <option value="early_dismissal" <?php selected($today['closings']['statusslug'],'early_dismissal'); ?>>Early Dismissal</option>
                        </select>
                    </div>
                    <input type="hidden" value="" name="today_delayexpiry"  id="today_delayexpiry" />
                    <div id="today_closed_details" class="gholder" <?php self::display($today['closings']['statusslug'],'closed'); ?>>
                        <div class="allday">
                            <input type="checkbox" name="calldayclosed" id="calldayclosed" value="alldayclosed" <?php checked($today['closings']['time']['calldayclosed'],'alldayclosed');disabled($today['is_closed'],true); ?> />
                            <span><strong>Closed all day</strong></span>
                        </div>
                        <div id="ttodayselctholder" <?php echo self::hide($today['is_closed'],true); ?>>
                            <div class="otime">
                                <label><strong>From</strong></label>
                                <select name="cfromhr" id="cfromhr" class="starttime">
                                    <?php echo self::get_select_from($today['closings']['time']['starttime']); ?>
                                </select>
                            </div>
                            <div class="ctime">
                                <label><strong>To</strong></label>
                                <select name="ctohr" id="ctohr" class="endtime">
                                    <?php echo self::get_select_from($today['closings']['time']['endtime']); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="today_delayed_details" class="gholder" <?php self::display($today['closings']['statusslug'],'delayed'); ?>>
                        <div class="gholder">
                            <div class="otime">
                                <label><strong>Hours</strong></label>
                                <select name="dtimehr" id="dtimehr" class="starttime delaytime">
                                    <?php echo self::get_select_hours_delay($today['closings']['time']['starttime']); ?>
                                </select>
                            </div>
                            <div class="ctime">
                                <label><strong>Mins</strong></label>
                                <select name="dtimemin" id="dtimemin" class="starttime delaytime">
                                    <?php echo self::get_select_mins($today['closings']['time']['endtime']); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="today_dismissal_details" class="gholder" <?php self::display($today['closings']['statusslug'],'early_dismissal'); ?>>
                        <div class="gholder">
                            <div class="ctime">
                                <label><strong>Closing Time</strong></label>
                                <select name="edtohr" id="edtohr" class="endtime">
                                    <?php echo self::get_select_from($today['closings']['time']['starttime']); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="day1defaultstart" id="day1defaultstart" class="daydefaultstart" value="<?php echo $today['closings']['time']['starttime']; ?>"/>
                <input type="hidden" name="day1message" id="day1message" value=""/>
                <div id="cpreview1">
                    <div class="business-status"></div>
                </div>
                <label for="closingDetails_day1" class="detailsDiv"><strong>Details</strong><em class="subtxt"> (Max 200 chars)</em></label>
                <textarea tabindex="9" cols="10" rows="2" maxlength="201" name="closingDetails_day1" id="closingDetails_day1" class="closingDetails"><?php echo $today['closings']['statusdesc']; ?></textarea>
                <span class="charCount"></span><br/>
            </div>
            <div class="tomorrowDiv">
                <div id="tomorrowAllFieldsHolder">
                    <div class="cdate">
                        <label><strong>Tomorrow&nbsp;</strong> (<?php echo $tomorrow['date']; ?>)</label>
                        <input type="hidden" value="<?php echo $tomorrow['date']; ?>" name="day2_date"/>
                        <select class="tomorrowChangeStatus" name="tomorrowChangeStatus" id="tomorrowChangeStatus" <?php disabled($tomorrow['is_closed'],true); ?>>
                            <option value="normal_hours" <?php selected($tomorrow['closings']['statusslug'],'normal_hours'); ?>>Normal Hours</option>
                            <option value="closed" <?php selected($tomorrow['closings']['statusslug'],'closed'); ?>>Closed</option>
                            <option value="delayed" <?php selected($tomorrow['closings']['statusslug'],'delayed'); ?>>Delayed by</option>
                            <option value="early_dismissal" <?php selected($tomorrow['closings']['statusslug'],'early_dismissal'); ?>>Early Dismissal</option>
                        </select>
                    </div>
                    <input type="hidden" value="" name="tomo_delayexpiry"  id="tomo_delayexpiry" />
                    <div id="tomorrow_closed_details" class="gholder" <?php self::display($tomorrow['closings']['statusslug'],'closed') ?>>
                        <div class="allday">
                            <input type="checkbox" name="talldayclosed" id="talldayclosed" value="alldayclosed" <?php checked($tomorrow['closings']['time']['calldayclosed'],'alldayclosed');disabled($tomorrow['is_closed'],true); ?> />
                            <span><strong>Closed all day</strong></span>
                        </div>
                        <div id="tomorrow_ttodayselctholder" <?php self::hide($tomorrow['closings']['time']['calldayclosed'],'alldayclosed') ?>>
                            <div class="otime">
                                <label><strong>From</strong></label>
                                <select name="ctfromhr" id="ctfromhr" class="starttime">
                                    <?php echo self::get_select_from($tomorrow['closings']['time']['starttime']); ?>
                                </select>
                            </div>
                            <div class="ctime">
                                <label><strong>To</strong></label>
                                <select name="cttohr" id="cttohr" class="endtime">
                                    <?php echo self::get_select_from($tomorrow['closings']['time']['endtime']); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="tomorrow_delayed_details" class="gholder" <?php self::display($tomorrow['closings']['statusslug'],'delayed') ?>>
                        <div class="gholder">
                            <div class="otime">
                                <label><strong>Hours</strong></label>
                                <select name="tomorrow_dtimehr" id="tomorrow_dtimehr" class="starttime delaytime">
                                    <?php echo self::get_select_hours_delay($tomorrow['closings']['time']['starttime']); ?>
                                </select>
                            </div>
                            <div class="ctime">
                                <label><strong>Mins</strong></label>
                                <select name="tomorrow_dtimemin" id="tomorrow_dtimemin" class="starttime delaytime">
                                    <?php echo self::get_select_mins($tomorrow['closings']['time']['endtime']); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="tomorrow_dismissal_details" class="gholder" <?php self::display($tomorrow['closings']['statusslug'],'early_dismissal') ?>>
                        <div class="gholder">
                            <div class="ctime">
                                <label><strong>Closing Time</strong></label>
                                <select name="tomorrow_edtohr" id="tomorrow_edtohr" class="endtime">
                                    <?php echo self::get_select_from($tomorrow['closings']['time']['starttime']); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="statusHolder"></div>
                <div class="clearfix"></div>
                <input type="hidden" name="day2defaultstart" id="day2defaultstart" class="daydefaultstart" value="<?php echo $tomorrow['closings']['time']['starttime']; ?>"/>
                <input type="hidden" name="day2message" id="day2message" value=""/>
                <div id="cpreview">
                    <div class="business-status"></div>
                </div>
                <label for="closingDetails_day2"><strong>Details</strong><em class="subtxt"> (Max 200 chars)</em></label>
                <textarea tabindex="9" cols="10" rows="2" maxlength="201" name="closingDetails_day2" id="closingDetails_day2" class="closingDetails"><?php echo $tomorrow['closings']['statusdesc'] ?></textarea>
                <span class="charCount"></span>
            </div>
        </div>
        <input type="hidden" name="<?php echo self::DAY1_SCHEDULED_CLOSED ?>" id="<?php echo self::DAY1_SCHEDULED_CLOSED ?>" class="<?php echo self::DAY1_SCHEDULED_CLOSED ?>" value="<?php echo $today['is_scheduled']; ?>"/>
        <input type="hidden" name="<?php echo self::DAY2_SCHEDULED_CLOSED ?>" id="<?php echo self::DAY2_SCHEDULED_CLOSED ?>" class="<?php echo self::DAY2_SCHEDULED_CLOSED ?>" value="<?php echo $tomorrow['is_scheduled']; ?>"/>
        <?php if($is_owner){ ?>
        <p class="closing-submit">
            <input type="hidden" name="timezone" id="timezone" class="timezone" value="<?php echo $timezone['timezone_short']; ?>"/>
            <?php wp_nonce_field(self::NONCE_NAME, self::NONCE_FIELD);?>
            <input type="submit" tabindex="13" name="<?php echo self::FORM_SUBMIT; ?>" value="Submit" class="button submit-button button-primary" />
            <input type="hidden" name="closings_action" value="<?php echo $_REQUEST['a']; ?>" />
            <span class="more_links"><a class="button submit-button button-primary" href="<?php echo self::closing_url(); ?>">CANCEL</a></span>
        </p>
        <div class="clearfix"></div>
        <p>To ensure that the information is updated, we request that you update it at least every 48 hours. If the information is not updated for 48 hours, the business will automatically be shown as "Normal Hours".</p>
        <?php } ?>
    </form>
</div>