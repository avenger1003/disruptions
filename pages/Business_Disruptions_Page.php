<?php
/**
 * Business Disruptions Page
 */

class Business_Disruptions_Page extends Business_Disruptions_Plugin {

    const GOOGLE_ATTR_TYPE = 'closings';
    const ADMIN_JS_SLUG = 'bd-business-js';
    const ADMIN_CSS_SLUG = 'db-business-theme';

    const URL_BASE = '';
    const URL_PAGE_ID = '';
    const PREG_INDEX = '/?$';
    const PREG_INDEX_PAGED = '';

    /*
     * Form CONST vars
     */
    const NONCE_NAME = 'business-disruptions-general';
    const NONCE_FIELD = 'business-disruptions-general-nonce';
    const NAME = 'bd_bname';
    const EMAIL = 'bd_user_mail';
    const PASS1 = 'pass1';
    const PASS2 = 'pass2';
    const XPASS = 'error_pass';
    const ADDRESS = 'bd_address';
    const CITY = 'bd_city';
    const STATE = 'bd_state';
    const ZIP = 'bd_zipcode';
    const CONTACT = 'bd_contact';
    const PHONE = 'bd_phone';
    const SITE = 'bd_website';
    const TAXONOMY = 'bd_cat';
    const CHECKMODE = 'checkmode';
    const EDITMODE = 'editmode';
//    const POSTID = 'p';
    const POSTID = 'business_id';
    const DIFFICULTY = 'nOa';
    const EVERYDAY = 'check-all';
    const XDAY = 'error_day';
    const START = 'fromhr';
    const END = 'tohr';
    const MON = 'bd_monday';
    const TUE = 'bd_tuesday';
    const WED = 'bd_wednesday';
    const THU = 'bd_thursday';
    const FRI = 'bd_friday';
    const SAT = 'bd_saturday';
    const SUN = 'bd_sunday';
    const MON_START = 'mon_fromhr';
    const MON_END = 'mon_tohr';
    const TUE_START = 'tue_fromhr';
    const TUE_END = 'tue_tohr';
    const WED_START = 'wed_fromhr';
    const WED_END = 'wed_tohr';
    const THU_START = 'thu_fromhr';
    const THU_END = 'thu_tohr';
    const FRI_START = 'fri_fromhr';
    const FRI_END = 'fri_tohr';
    const SAT_START = 'sat_fromhr';
    const SAT_END = 'sat_tohr';
    const SUN_START = 'sun_fromhr';
    const SUN_END = 'sun_tohr';
    const COMMON_TIME = 'bd_commontime';

    const DELAYED_HOURS = 'delayed';
    const EARLY_DISMISSAL = 'early_dismissal';
    const CLOSED_HOURS = 'closed';
    const NORMAL_HOURS = 'normal_hours';
    const SEARCH_NORMAL_HOURS = 'search_normal_hours';
    const SEARCH_PREFIX = 'search_';
    const DAY1_STATUS = 'bd_day1_status';
    const DAY2_STATUS = 'bd_day2_status';
    const DAY1_SEARCH = 'bd_day1_search_string';
    const DAY2_SEARCH = 'bd_day2_search_string';
    const DAY1_CRON = 'bd_day1Cronchek';
    const DAY2_CRON = 'bd_day2Cronchek';
    const CLOSING_HISTORY = 'bd_closing_dhistory_count';
    const HISTORY_PREFIX = 'bd_closing_dhistory_';
    const CLOSING_DAY1 = 'bd_closing_day1';
    const CLOSING_DAY2 = 'bd_closing_day2';
    const DAY1_SCHEDULED_CLOSED = 'bd_day1_scheduled_closed';
    const DAY2_SCHEDULED_CLOSED = 'bd_day2_scheduled_closed';


    public $normal_day = array(
        'cur_status' => 'Normal Hours',
        'statusmsg' =>'Normal Hours',
        'statusslug' => 'normal_hours',
    );

    public $closed_day = array(
        'cur_status' => 'Closed',
        'statusmsg' =>'Closed',
        'statusslug' => 'closed',
    );

    protected static function set_user_role(){
        Business_Disruptions_Roles::add_role();
    }

    protected function add_parent_hooks(){
        add_action('wp_enqueue_scripts', array($this, 'business_admin_enqueue') );
        add_filter('google_ads_gtype', array($this, 'google_ads_gtype') );
    }

    public function load_template($template){
        global $wp_query;
        $wp_query->is_404 = 0;
        $wp_query->is_page = 1;
        load_template(self::plugin_path('views' . DIRECTORY_SEPARATOR . $template));
        exit;
    }

    public function google_ads_gtype( $gtype ) {
        $gtype = self::GOOGLE_ATTR_TYPE;
        return $gtype;
    }

    public function business_admin_enqueue(){
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_register_style(self::ADMIN_CSS_SLUG, self::plugin_url('resources/css/styles.css'), false, 1);
        wp_enqueue_style(self::ADMIN_CSS_SLUG);

        wp_localize_script(self::ADMIN_JS_SLUG, 'BD_VAL',array('validate' => 'true') );
        wp_register_script('jquery.validate.js', self::plugin_url('resources/js/jquery.validate.js'), array('jquery'), 1,true);
        wp_enqueue_script('jquery.validate.js');
        wp_register_script(self::ADMIN_JS_SLUG, self::plugin_url('resources/js/business.js'), array('jquery','jquery.validate.js'), 1,true);
        wp_enqueue_script(self::ADMIN_JS_SLUG);

        // declare the variables we need to access in js
        $js_path_array = array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'pluginurl' => self::plugin_url('resources/'),
            'manageurl' => self::manage_url(),
            'addburl' => self::add_url()
        );
        wp_localize_script(self::ADMIN_JS_SLUG,'BD_Ajax',$js_path_array);
    }

    protected function pre_header_logic(){
        // Silence is golden
    }

    protected static function pre_redirect(){
        if ( is_user_logged_in() ){
            wp_redirect(self::close_url());
            exit;
        }
    }

    public static function registration_url(){
        return home_url('businesses/registration');
    }

    public static function add_url(){
        return home_url('businesses/add-business');
    }

    public static function business_url(){
        return home_url( Business_Disruptions_Page_Closing_List::URL_BASE . '/' . Business_Disruptions_Page_Closing_List::URL_PAGE_ID );
    }

    public static function closing_url(){
        return home_url( Business_Disruptions_Page_Product_List::URL_BASE . '/' . Business_Disruptions_Page_Product_List::URL_PAGE_ID );
    }

    public static function submit_closing_url(){
        return home_url( Business_Disruptions_Page_Submit_Closing::URL_BASE . '/' . Business_Disruptions_Page_Submit_Closing::URL_PAGE_ID );
    }

    public static function resend_mail_url(){
        return home_url( Business_Disruptions_Page_Mail::URL_BASE . '/' . Business_Disruptions_Page_Mail::URL_PAGE_ID );
    }

    public static function legacy_url(){
        return home_url( Business_Disruptions_Page_Legacy::URL_BASE . '/' . Business_Disruptions_Page_Legacy::URL_PAGE_ID );
    }

    public static function manage_url(){
        return home_url( Business_Disruptions_Page_Manage::URL_BASE . '/' . Business_Disruptions_Page_Manage::URL_PAGE_ID );
    }

    public static function login_url(){
        return home_url( Business_Disruptions_Page_Login::URL_BASE . '/' . Business_Disruptions_Page_Login::URL_PAGE_ID );
    }

    public static function category_url(){
        return home_url( Business_Disruptions_Category::URL_BASE . '/' . Business_Disruptions_Category::URL_PAGE_ID );
    }

    /**
     * Used for Business Edits
     * Preloads form with Business data
     * Important that the current $wpdb->prefix is set to central blog id
     *
     * @static
     * @param string $id The ID of the Business
     * @param array $options You can pass in an array to add the data to
     * @return array The options array which holds all the Business data
     */
    public static function load_business_data($id,$options = array()){

        $options[self::NAME] = get_post_meta($id, self::NAME, true);
        $options[self::EMAIL] = get_post_meta($id, self::EMAIL, true);
        $options[self::ADDRESS] = get_post_meta($id, self::ADDRESS, true);
        $options[self::CITY] = get_post_meta($id, self::CITY, true);
        $options[self::STATE] = get_post_meta($id, self::STATE, true);
        $options[self::ZIP] = get_post_meta($id, self::ZIP, true);
        $options[self::CONTACT] = get_post_meta($id, self::CONTACT, true);
        $options[self::PHONE] = get_post_meta($id, self::PHONE, true);
        $options[self::SITE] = get_post_meta($id, self::SITE, true);
        $options[self::TAXONOMY] = get_post_meta($id, self::TAXONOMY, true);
        $options[self::CHECKMODE] = get_post_meta($id, self::CHECKMODE, true);
        $options[self::EDITMODE] = get_post_meta($id, self::EDITMODE, true);
        $options[self::POSTID] = get_post_meta($id, self::POSTID, true);
        $options[self::DIFFICULTY] = get_post_meta($id, self::DIFFICULTY, true);
        $options[self::EVERYDAY] = get_post_meta($id, self::EVERYDAY, true);
        $options[self::COMMON_TIME] = get_post_meta($id, self::COMMON_TIME, true);
        $options[self::MON] = get_post_meta($id, self::MON, true);
        $options[self::TUE] = get_post_meta($id, self::TUE, true);
        $options[self::WED] = get_post_meta($id, self::WED, true);
        $options[self::THU] = get_post_meta($id, self::THU, true);
        $options[self::FRI] = get_post_meta($id, self::FRI, true);
        $options[self::SAT] = get_post_meta($id, self::SAT, true);
        $options[self::SUN] = get_post_meta($id, self::SUN, true);
        $options[self::START] = $options[self::COMMON_TIME]['start'];
        $options[self::END] = $options[self::COMMON_TIME]['end'];

        $options[self::NORMAL_HOURS] = get_post_meta($id, self::NORMAL_HOURS, true);
        $options[self::SEARCH_NORMAL_HOURS] = get_post_meta($id, self::SEARCH_NORMAL_HOURS, true);
        $options[self::DAY1_STATUS] = get_post_meta($id, self::DAY1_STATUS, true);
        $options[self::DAY2_STATUS] = get_post_meta($id, self::DAY2_STATUS, true);
        $options[self::DAY1_SEARCH] = get_post_meta($id, self::DAY1_SEARCH, true);
        $options[self::DAY2_SEARCH] = get_post_meta($id, self::DAY2_SEARCH, true);
        $options[self::CLOSING_HISTORY] = get_post_meta($id, self::CLOSING_HISTORY, true);
        $options[self::CLOSING_DAY1] = get_post_meta($id, self::CLOSING_DAY1, true);
        $options[self::CLOSING_DAY2] = get_post_meta($id, self::CLOSING_DAY2, true);
        $options[self::DAY1_CRON] = get_post_meta($id, self::DAY1_CRON, true);
        $options[self::DAY2_CRON] = get_post_meta($id, self::DAY2_CRON, true);

        $options['is_owner'] = self::is_owner($id);

        return $options;
    }

    public static function load_business_history($id,$options = array()){

        $options[self::CLOSING_HISTORY] = get_post_meta($id, self::CLOSING_HISTORY, true);
        for($i=1; $i<=$options[self::CLOSING_HISTORY] && $i < 6; $i++){
            $options['history'][self::HISTORY_PREFIX.$i] = get_post_meta($id, self::HISTORY_PREFIX.$i, true);
        }

        return $options;
    }

    protected static function get_daily_operation_hours($id,$options=array()){
        // $options[self::COMMON_TIME] should already set
        $options = self::get_hours_open_per_day($options,$id,self::MON, 'mon');
        $options = self::get_hours_open_per_day($options,$id,self::TUE, 'tue');
        $options = self::get_hours_open_per_day($options,$id,self::WED, 'wed');
        $options = self::get_hours_open_per_day($options,$id,self::THU, 'thu');
        $options = self::get_hours_open_per_day($options,$id,self::FRI, 'fri');
        $options = self::get_hours_open_per_day($options,$id,self::SAT, 'sat');
        $options = self::get_hours_open_per_day($options,$id,self::SUN, 'sun');
        return $options;
    }
    /*
     *
     */
    protected static function get_hours_open_per_day($options, $id, $slug, $val, $start = '_fromhr', $end = '_tohr'){
        if($options[self::EVERYDAY] == 'all'){
            $options[$slug] = $val;
        } else {
            $post_meta = get_post_meta($id, $slug, true);
            if(!empty($post_meta)){
                $options[$slug] = $val;
                $options[$val . $start] =  $post_meta['start'];
                $options[$val . $end] =  $post_meta['end'];
            }
        }
        return $options;
    }

    public static function assign_http_post($post){
        $options = array();
        foreach($post as $key => $value){
            $options[$key] = $value;
        }
        return $options;
    }

    public static function get_select_from($value = '9'){
        if($value == '' || $value == 'none') $value = '9';
        $range = range(0, 23);
        $options = "<option value=\"none\">Select</option>";
        foreach ($range as $hour){
            $options .= '<option value="'.$hour.'" '.selected($value,$hour,false).'>'.date('g:i A', strtotime($hour.":00")).'</option>';
        }
        return $options;
    }

    public static function get_select_to($value = '17'){
        if($value == '' || $value == 'none') $value = '17';
        $range = range(0, 23);
        $options = "<option value=\"none\">Select</option>";
        foreach ($range as $hour){
            $options .= '<option value="'.$hour.'" '.selected($value,$hour,false).'>'.date('g:i A', strtotime($hour.":00")).'</option>';
        }
        return $options;
    }

    /*
     * Get Select Hours Dropdown for Delayed Closings
     */
    public static function get_select_hours_delay($value) {
        $range = array( "00","01","02","03","04","05","06","07","08" );
        $options = '';
        foreach ($range as $hour){
            $hour_label = ( $hour == '00') ? '00' : $hour;
            $hour = substr($hour,1,1);
            $options .= '<option value="'.$hour.'" '.selected($value,$hour,false).'>'.$hour_label.'</option>';
        }
        return $options;
    }

    /*
    * Get Select Minutes Dropdown for Delayed Closings
    */
    public static function get_select_mins($value) {
        $range = array( "00","15","30","45" );
        $options = '';
        foreach ($range as $min){
            $options .= '<option value="'.$min.'" '.selected($value,$min,false).'>'.$min.'</option>';
        }
        return $options;
    }

    public static function is_valid_email($email) {
        $result = TRUE;
        if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
            $result = FALSE;
        }
        return $result;
    }

    public static function the_status_label($post,$day,$echo = true){
        if($day == 'today') $status = self::DAY1_STATUS;
        if($day == 'tomorrow') $status = self::DAY2_STATUS;
        $status = get_post_meta($post->ID,$status,true);
        $status_label = '';
        switch ($status):
            case 'normal_hours':
                $status_label = 'Normal Hours';
                break;
            case 'early_dismissal':
                $status_label = 'Early Dismissal';
                break;
            case 'closed':
                $status_label = 'Closed';
                break;
            case 'delayed':
                $status_label = 'Delayed';
                break;
        endswitch;
        if($echo){
            echo $status_label;
        }else{
            return $status_label;
        }
    }

    public static function the_status_message($post,$day,$echo = true){
        if($day == 'today') $closing = self::CLOSING_DAY1;
        if($day == 'tomorrow') $closing = self::CLOSING_DAY2;
        $closing = get_post_meta($post->ID,$closing,true);
        if($day == 'today') $status = self::DAY1_STATUS;
        if($day == 'tomorrow') $status = self::DAY2_STATUS;
        $status = get_post_meta($post->ID,$status,true);
        $status_message = '';
        switch ($status):
            case 'normal_hours':
                $status_message = 'Normal Hours';
                break;
            case 'closed':
                if(empty($closing)){
                    $status_message = 'Closed '.day.' '.date('m/d');
                }else{
                    $status_message = $closing['statusmsg'];
                }
                break;
            case 'early_dismissal':
                $status_message = $closing['statusmsg'];
                break;
            case 'delayed':
                $status_message = $closing['statusmsg'];
                break;
        endswitch;
        if($echo){
            echo $status_message;
        }else{
            return $status_message;
        }
    }
    public static function get_business_page_url($post){

        $zip = get_post_meta($post->ID, self::ZIP, true);
        $city = str_replace(' ', '-', strtolower(get_post_meta($post->ID, self::CITY, true)));

        return self::business_url() . $city . '/' . $zip . '/' . $post->post_name;
    }

    /**
     * Checks if user is the owner of the post id given
     *
     * @static
     * @param string $post_id Post ID of business to check
     * @param string $post_registered_email Post ID of business to check
     * @return bool true if user is owner of business
     */
    public static function is_owner($post_id,$post_registered_email = ''){
        global $current_user;
        get_current_user();
        $user_email = $current_user->user_email;
        if(!$post_registered_email) $post_registered_email = get_post_meta($post_id, self::EMAIL, true);
        return ($user_email == $post_registered_email || is_super_admin());
    }
}