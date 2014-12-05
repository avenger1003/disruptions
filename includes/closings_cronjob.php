<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/template-loader.php');

require_once($_SERVER['DOCUMENT_ROOT']).DIRECTORY_SEPARATOR.'wp-content'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'business-disruptions'.DIRECTORY_SEPARATOR.'Business_Disruptions_Plugin.php';
require_once($_SERVER['DOCUMENT_ROOT']).DIRECTORY_SEPARATOR.'wp-content'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'business-disruptions'.DIRECTORY_SEPARATOR.'pages'.DIRECTORY_SEPARATOR.'Business_Disruptions_Page.php';
require_once($_SERVER['DOCUMENT_ROOT']).DIRECTORY_SEPARATOR.'wp-content'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'business-disruptions'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'Business_Disruptions_Network_Admin.php';

/*
 * Business_Disruptions_Plugin
 * Business_Disruptions_Network_Admin
 * Business_Disruptions_Page
 */

function closing_cron() {

    global $wp_query, $wpdb, $bd, $current_user;
    $wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
    $wpdb->set_prefix( $wpdb->base_prefix );


    $args = array(
        'post_type'=>'business',
        'post_status'=>'publish',
        'posts_per_page' =>'-1'
    );
    $the_query = new WP_Query( $args );

    if ( $the_query->post_count > 0 ) {
        foreach( $the_query->posts as $post ) {
            $postid = $post->ID;

            $business_data = Business_Disruptions_Page::load_business_data($postid);

            // Timezone information
            $timezone = Business_Disruptions_Plugin::get_user_time_zone($business_data[Business_Disruptions_Page::ZIP]);

            /*
             *  Get the end of day for business timezone
             *  If doesn't exist assign today as new end of day
             */
            date_default_timezone_set($timezone['timezone_desc']);
            $end_of_day = get_post_meta($postid,'bd_end_of_day',true);
            if(empty($end_of_day)){
                $end_of_day = Business_Disruptions_Plugin::convert_time_zone_to_zone(date('m/d/Y 23:50:00'),$timezone['timezone_desc'],'GMT');
                update_post_meta($postid,'bd_end_of_day',$end_of_day);
            }

            /*
             * Set default closed days
             */
            $today_name = strtolower(date('l'));
            $tomorrow_name = strtolower(date('l', time()+86400));
            $overmorrow_name = strtolower(date('l', time()+172800));
            $today_status = (empty($business_data['bd_'.$today_name])) ? 'closed' : 'normal_hours';
            $tomorrow_status = (empty($business_data['bd_'.$tomorrow_name])) ? 'closed' : 'normal_hours';
            $overmorrow_status = (empty($business_data['bd_'.$overmorrow_name])) ? 'closed' : 'normal_hours';

            // Forcing Server to GMT
            date_default_timezone_set('GMT');
            // Getting Server Time to compare business expiry by
            $current_time = date( 'm/d/Y H:i:s');

            // Closings Day Data
            $today = $business_data['bd_closing_day1'];
            $tomorrow = $business_data['bd_closing_day2'];
            // Closings History Count
            $closings_history_count = $business_data['bd_closing_dhistory_count'];

echo '<pre>Post ID ('.$postid.'): Current Time: '.$current_time.', End of Day Time: '.$end_of_day.'</pre>';
            /*
             * If the business has reached the end of day:
             * 1. Push most recent closings to history
             * 2. Push tomorrows closings to todays closings
             * 3. Set tomorrows closings to default
             * 4. Empty today's end of day and let cronjob set new end of day
             */
            if($current_time > $end_of_day) {
echo '<pre>Post ID ('.$postid.'): End of Day Cron</pre>';
                // 1. Pushing Closing Day 1 data into History only if it existed
                if (!empty($today)) {
echo '<pre>Post ID ('.$postid.'): EoD: Pushing Day 1 into History</pre>';
                    $closings_history_count++;
                    update_post_meta($postid, 'bd_closing_dhistory_'.$closings_history_count.'', $today);
                    update_post_meta($postid, 'bd_closing_dhistory_count', $closings_history_count );
                }
                // 2. Pushing tomorrows closings to todays closing only if it exists
                // ** Updating status message to say today instead of tomorrow
                if (!empty($tomorrow)) {
echo '<pre>Post ID ('.$postid.'): EoD: Pushing Day 2 into Day 1</pre>';
                    $tomorrow['statusmsg'] = str_replace('tomorrow', 'today', $tomorrow['statusmsg']);
                    update_post_meta($postid, 'bd_closing_day1', $tomorrow);
                    update_post_meta($postid, 'bd_day1_status', $tomorrow['statusslug']);
                    update_post_meta($postid, 'bd_day1_search_string', 'search_'.$tomorrow['statusslug']);
                }else{
echo '<pre>Post ID ('.$postid.'): EoD: Resetting Day 1 to Scheduled Default</pre>';
                    update_post_meta($postid, 'bd_closing_day1', '');
                    update_post_meta($postid, 'bd_day1_status', $tomorrow_status);
                    update_post_meta($postid, 'bd_day1_search_string', 'search_'.$tomorrow_status);
                }
                // 3. Remove tomorrows closing and update tomorrow status with default scheduled day
echo '<pre>Post ID ('.$postid.'): EoD: Resetting Day 2 to Scheduled Default</pre>';
                update_post_meta($postid, 'bd_closing_day2', '');
                update_post_meta($postid, 'bd_day2_status', $overmorrow_status);
                update_post_meta($postid, 'bd_day2_search_string', 'search_'.$overmorrow_status);
                // 4. Empty end of day meta data
                update_post_meta($postid, 'bd_end_of_day', '');
            }

            /*
             * If todays expiry time has been reached:
             * 1. Pushed expired closings into history
             * 2. Set todays closings to default
             * No need to push tomorrows closing into today.  The end of day cronjob will do that
             */
            elseif($current_time > $today['expiry']) {
echo '<pre>Post ID ('.$postid.'): Day 1 has expired</pre>';
                // 1. Pushing Closing Day 1 data into History only if it exists
                if (!empty($today)) {
echo '<pre>Post ID ('.$postid.'): Expired: Pushing Day 1 into History</pre>';
                    $closings_history_count++;
                    update_post_meta($postid, 'bd_closing_dhistory_'.$closings_history_count.'', $today);
                    update_post_meta($postid, 'bd_closing_dhistory_count', $closings_history_count );
echo '<pre>Post ID ('.$postid.'): Expired: Resetting Day 1 to Scheduled Default</pre>';
                    // 2. Set todays closing to default
                    update_post_meta($postid, 'bd_closing_day1', '');
                    update_post_meta($postid, 'bd_day1_status', $today_status);
                    update_post_meta($postid, 'bd_day1_search_string', 'search_'+$today_status);
                }
            }
        }
    }
}

closing_cron();