<?php
/**
 * Business Disruptions Page Submit Closing
 */

class Business_Disruptions_Page_Submit_Closing extends Business_Disruptions_Page {

    const QUERY_PAGE_NAME = 'submit-closings';
    const PAGE_TITLE_LABEL ='Submit Closings';

    const URL_BASE = 'businesses';
    const URL_PAGE_ID = self::QUERY_PAGE_NAME;

    const FORM_SUBMIT = 'submit_closing';
    const NONCE_NAME = 'business-disruptions-submit-closings';
    const NONCE_FIELD = 'business-disruptions-submit-closings-nonce';

    const UPDATE = 'a';
    const UPDATEVAL = 'update';

    protected static $status_labels = array(
        'normal_hours' => 'Normal Hours',
        'closed' => 'Closed',
        'delayed' => 'Delayed by',
        'early_dismissal' => 'Early Dismissal'
    );

    protected $business_data = array();

    protected function __construct() {
        add_filter('template_redirect', array($this,'add_hooks'));
    }

    public function add_hooks(){
        if(get_query_var('pagename') == self::QUERY_PAGE_NAME){
            $this->pre_header_logic();
            $this->add_parent_hooks();
            add_filter('aioseop_title_page', array($this, 'aioseop_title_page'));
            add_filter('the_content', array($this, 'the_content') );
            $this->load_template('page-template-general.php');
        }
    }

    protected static function pre_redirect(){
        if ( !is_user_logged_in() && !isset($_GET[self::POSTID]) ){
            wp_redirect(self::login_url());
            exit;
        }
    }

    protected function pre_header_logic(){

        $this->pre_redirect();

        $postid = $_GET[self::POSTID];

        global $wpdb, $blog_id;
        $current_blog_id = $blog_id;
        $wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
        $wpdb->set_prefix( $wpdb->base_prefix );

        // Load Existing Business Data
        $business_data = $this->business_data = self::load_business_data($postid);
        if( isset($_POST[self::FORM_SUBMIT]) ) {

            /*
             * Setting default time zone to business zip code timezone
             * All time generated will be based on business zip code
             * Cronjobs will convert time to GMT to process later
             * Reset at the end of process to restore timezone for rest of WP page
             */
            $timezone = self::get_user_time_zone($business_data[self::ZIP]);
            $default_timezone = date_default_timezone_get();
            date_default_timezone_set($timezone['timezone_desc']);
            $today_name = strtolower(date('l'));
            $tomorrow_name = strtolower(date('l', time()+86400));
            $today = get_post_meta($postid,'bd_'.$today_name,true);
            $tomorrow = get_post_meta($postid,'bd_'.$tomorrow_name,true);
            $end_of_today = Business_Disruptions_Plugin::convert_time_zone_to_zone(date('m/d/Y 23:55:00'),$timezone['timezone_desc'],$timezone['timezone_desc']);
            $end_of_tomorrow = Business_Disruptions_Plugin::convert_time_zone_to_zone(date('m/d/Y 23:55:00', time()+86400),$timezone['timezone_desc'],'GMT');

            /*
            * Initialize $_POST data into closings day arrays
            */
            $closing_day1 = array(
                'date' => $_POST['day1_date'],
                'time' => '',
                'expiry' => '',
                'cur_status' => '',
                'statusmsg' => $_POST['day1message'],
                'statusdesc' => $_POST['closingDetails_day1'],
                'statusslug' => $_POST['todayChangeStatus'],
            );
            $closing_day2 = array(
                'date' => $_POST['day2_date'],
                'time' => '',
                'expiry' => '',
                'cur_status' => '',
                'statusmsg' => $_POST['day2message'],
                'statusdesc' => $_POST['closingDetails_day2'],
                'statusslug' => $_POST['tomorrowChangeStatus'],
            );

            /*
             * Assign $_POST data into closings day arrays
             * Note: Saving expiry dates based on business zip code time zone
             * Cronjobs will convert expiry dates to GMT for processing
             */
            switch ($_POST['todayChangeStatus']){
                case 'closed':
                    $time = strtotime($_POST['ctohr'].':00:00');
                    $time = date('H:i:s', $time);
                    $time = ($time) ? $time : '00:00:00';
                    $date_time = ($_POST['ctohr']=='none') ? $end_of_today : date('m/d/Y '.$time);
                    $closing_day1['expiry'] = self::convert_time_zone_to_zone($date_time,$timezone['timezone_desc'],$timezone['timezone_desc']);
                    $closing_day1['time'] = array( 'closedallday' => $_POST['calldayclosed'],'starttime' => $_POST['cfromhr'],'endtime' => $_POST['ctohr'] );
                    $closing_day1['cur_status'] = 'Closed';
                    $closing_day1['statusslug'] = 'closed';
                    break;
                case 'delayed':
                    $date = strtotime($_POST['today_delayexpiry']);
                    $date_time = date('m/d/Y H:i:s', $date);
                    $closing_day1['expiry'] = self::convert_time_zone_to_zone($date_time,$timezone['timezone_desc'],$timezone['timezone_desc']);
                    $closing_day1['time'] = array( 'starttime'=>$_POST['dtimehr'], 'endtime' => $_POST['dtimemin'] );
                    $closing_day1['cur_status'] = 'Delayed';
                    $closing_day1['statusslug'] = 'delayed';
                    break;
                case 'early_dismissal':
                    $time = strtotime($_POST['edtohr'].':00:00');
                    $time = date('H:i:s', $time);
                    $time = ($time) ? $time : '00:00:00';
                    $date_time = ($_POST['edtohr']=='none') ? $end_of_today : date('m/d/Y '.$time);
                    $closing_day1['expiry'] = self::convert_time_zone_to_zone($date_time,$timezone['timezone_desc'],$timezone['timezone_desc']);
                    $closing_day1['time'] = array('starttime' => $_POST['edtohr']);
                    $closing_day1['cur_status'] = 'Early Dismissal';
                    $closing_day1['statusslug'] = 'early_dismissal';
                    break;
                case 'normal_hours':
                    $closing_day1['time'] = array('starttime' => '');
                    $closing_day1['cur_status'] = (empty($today)) ? 'Closed' : 'Normal Hours';
                    $closing_day1['statusslug'] = (empty($today)) ? 'closed' : 'normal_hours';
                    break;
            }
            switch ($_POST['tomorrowChangeStatus']){
                case 'closed':
                    $time = strtotime($_POST['cttohr'].':00:00');
                    $time = date('H:i:s', $time);
                    $time = ($time) ? $time : '00:00:00';
                    $date_time = ($_POST['cttohr']=='none') ? $end_of_tomorrow : date('m/d/Y '.$time);
                    $closing_day2['expiry'] = self::convert_time_zone_to_zone($date_time,$timezone['timezone_desc'],$timezone['timezone_desc']);
                    $closing_day2['time'] = array( 'closedallday' => $_POST['talldayclosed'],'starttime' => $_POST['ctfromhr'],'endtime' => $_POST['cttohr'] );
                    $closing_day2['cur_status'] = 'Closed';
                    $closing_day2['statusslug'] = 'closed';
                    break;
                case 'delayed':
                    $date = strtotime($_POST['tomo_delayexpiry']);
                    $date_time = date('m/d/Y H:i:s', $date);
                    $closing_day2['expiry'] = self::convert_time_zone_to_zone($date_time,$timezone['timezone_desc'],$timezone['timezone_desc']);
                    $closing_day2['time'] = array( 'starttime'=>$_POST['tomorrow_dtimehr'], 'endtime' => $_POST['tomorrow_dtimemin'] );
                    $closing_day2['cur_status'] = 'Delayed';
                    $closing_day2['statusslug'] = 'delayed';
                    break;
                case 'early_dismissal':
                    $time = strtotime($_POST['tomorrow_edtohr'].':00:00');
                    $time = date('H:i:s', $time);
                    $time = ($time) ? $time : '00:00:00';
                    $date_time = ($_POST['tomorrow_edtohr']=='none') ? $end_of_tomorrow : date('m/d/Y '.$time);
                    $closing_day2['expiry'] = self::convert_time_zone_to_zone($date_time,$timezone['timezone_desc'],$timezone['timezone_desc']);
                    $closing_day2['time'] = array('starttime' => $_POST['tomorrow_edtohr']);
                    $closing_day2['cur_status'] = 'Early Dismissal';
                    $closing_day2['statusslug'] = 'early_dismissal';
                    break;
                case 'normal_hours':
                    $closing_day2['time'] = array('starttime' => '');
                    $closing_day2['cur_status'] = (empty($tomorrow)) ? 'Closed' : 'Normal Hours';
                    $closing_day2['statusslug'] = (empty($tomorrow)) ? 'closed' : 'normal_hours';
                    break;
            }

            /*
             * Will not insert data to the business disruptions queue if status is scheduled "as is"
             * Delays and Early Dismissal will always be added to queue.
             * Normal Hours and Closed status will be checked against regular schedule             *
             */
            if( $_POST['todayChangeStatus'] == 'delayed' ||
                $_POST['todayChangeStatus'] == 'early_dismissal' ||
                (!empty($business_data['bd_'.$today_name]) && $_POST['todayChangeStatus'] == 'closed')
            ):      update_post_meta( $postid, self::CLOSING_DAY1, $closing_day1 );
            else:   update_post_meta( $postid, self::CLOSING_DAY1, '');
            endif;
            if( $_POST['tomorrowChangeStatus'] == 'delayed' ||
                $_POST['tomorrowChangeStatus'] == 'early_dismissal' ||
                (!empty($business_data['bd_'.$tomorrow_name]) && $_POST['tomorrowChangeStatus'] == 'closed')
            ):      update_post_meta( $postid, self::CLOSING_DAY2, $closing_day2 );
            else:   update_post_meta( $postid, self::CLOSING_DAY2, '');
            endif;

            update_post_meta( $postid, self::DAY1_STATUS, $_POST['todayChangeStatus'] );
            update_post_meta( $postid, self::DAY1_SEARCH, 'search_'.$_POST['todayChangeStatus'] );
            update_post_meta( $postid, self::DAY2_STATUS, $_POST['tomorrowChangeStatus'] );
            update_post_meta( $postid, self::DAY2_SEARCH, 'search_'.$_POST['tomorrowChangeStatus'] );

            // Reset timezone
            date_default_timezone_set($default_timezone);
            wp_redirect( self::get_business_page_url(get_post($postid)) );
        }

        // Reset database pointer to current blog
        $wpdb->blogid = $current_blog_id;
        $wpdb->set_prefix( $wpdb->base_prefix );
    }

    public function aioseop_title_page(){
        return self::__(self::PAGE_TITLE_LABEL);
    }

    public function the_content($content){
        include_once(self::plugin_path('views/page-submit-closings.php'));
        return null;
    }

    /* Start Singleton */
    private static $instance;
    public static function init() {
        self::$instance = self::get_instance();
    }
    public static function get_instance() {
        if ( !is_a(self::$instance, __CLASS__) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    final public function __clone() {
        trigger_error("No cloning allowed!", E_USER_ERROR);
    }
    final public function __sleep() {
        trigger_error("No serialization allowed!", E_USER_ERROR);
    }
    /* End Singleton */

}