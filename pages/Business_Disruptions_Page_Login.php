<?php
/**
 * Business Disruptions Page Login
 */

class Business_Disruptions_Page_Login extends Business_Disruptions_Page {

    const QUERY_PAGE_NAME = 'login';
    const PAGE_TITLE_LABEL ='Closings - Login';

    const URL_BASE = 'businesses';
    const URL_PAGE_ID = self::QUERY_PAGE_NAME;

    const FORM_USER = 'log';
    const FORM_PASS = 'pwd';
    const FORM_SUBMIT = 'business_login_form_submit';
    const AUTH1 = 'Auth1';
    const AUTH2 = 'Auth2';
    const REMEMBER = 'remember';

    protected $activated_message = '';
    protected $login_message = '';
    protected $error_message = '';

    protected function __construct() {
        add_filter('template_redirect', array($this,'add_hooks'));
    }

    public function add_hooks(){
        if(get_query_var('pagename') == self::QUERY_PAGE_NAME){
            $this->pre_header_logic();
            $this->add_parent_hooks();
            add_filter('aioseop_title_page', array($this, 'aioseop_title_page'), 19, 3 );
            add_filter('the_content', array($this, 'the_content') );
            $this->load_template('page-template-login.php');
        }
    }

    protected function pre_header_logic(){

        if ( is_user_logged_in() ){
            wp_redirect(self::closing_url());
            exit;
        }
        global $wpdb, $blog_id, $current_user;
        get_current_user();
        $current_blog_id = $blog_id;
        $wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
        $wpdb->set_prefix( $wpdb->base_prefix );

        $username = $_POST[self::FORM_USER];
        $password = $_POST[self::FORM_PASS];

        /*
        * Only used if Legacy User is active on Blog
        * Check Legacy User
        * Wordpress custom login with authentication
        *
        * If user is a legacy user AND has not created an account TSM, redirect to /businesses/legacy/ page
        *
        */
        if ( get_option(Business_Disruptions_Site_Admin::LEGACY_OPTION_NAME) == 1 ) {
            $legacy_user = $wpdb->get_var( $wpdb->prepare( "SELECT UserName,  Password FROM  ts_legacy WHERE UserName='{$username}' AND Password='{$password}'") );
            $normal_user = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM ts_usermeta WHERE meta_key = 'legacy_user' AND meta_value ='{$username}'") );
            $legacyurl = self::legacy_url() . '?' . self::AUTH1 . '=' . self::encode_base64( $username ) . '&' . self::AUTH2 . '=' . self::encode_base64( $password );
            if( $legacy_user &&  !$normal_user  ){
                wp_redirect( $legacyurl );
                exit;
            }
        }

        /*
        * Only used if user not logged in
        * Set user information from $_POST form submit
        * User data already exists from registering on /businesses/registration/
        * Default confirmation is set to "pending"
        *
        */
        $creds = array();
        $user_id = get_user_id_from_string( $username );
        $confirmation = get_user_meta( $user_id, Business_Disruptions_Roles::ACTIVATION_SLUG, true );

        if(isset($_POST[self::FORM_SUBMIT])) {
            if( $normal_user ) {
                $this->error_message ='<span class="sanerror">Your account has been migrated, please use email to login.</span>';
            }elseif( !$normal_user ){
                $this->error_message ='<span class="sanerror">Sorry, the email / user ID or password that you entered didn\'t match our records.</span>';
            }
        }

        /*
        * Only used on $_POST form submit
        * If the user has been removed from business disruptions system
        * Assigning $_POST user/pass to credentials array
        *
        */
        if( isset( $_POST[self::FORM_SUBMIT] ) ){
            $creds['user_login'] = $_POST[self::FORM_USER];
            $creds['user_password'] = $_POST[self::FORM_PASS];
        }
        $creds['remember'] = true;

        /*
        * If use is using HTTP AUTH to register business
        * HTTP AUTH is access via business registration email
        *
        * Signs in user if data is correct
        * Sends confirmation email to user if data is correct
        *
        */
        if( $_GET[self::AUTH1] && $_GET[self::AUTH2] ){
            $user_id = get_user_id_from_string( self::decode_base64( $_GET[self::AUTH1] ) );
            $confirmation = get_user_meta(  $user_id, Business_Disruptions_Roles::ACTIVATION_SLUG , true );
            if( $confirmation == 'pending' ){
                $autocreds = array();
                $autocreds['user_login'] = self::decode_base64( $_GET[self::AUTH1] );
                $autocreds['user_password'] = self::decode_base64( $_GET[self::AUTH2] );
                update_user_meta(  $user_id, Business_Disruptions_Roles::ACTIVATION_SLUG, 'confirmed' );
                $activate = new WP_Query( 'post_type=' . Business_Disruptions_Post_Type::POST_TYPE_SLUG . '&post_status=draft&author=' . $user_id );

                foreach($activate->posts as $post){
                    $update_post = array(
                        'ID'            => $post->ID,
                        'post_status'	=> 'publish',
                        'post_type'	=> Business_Disruptions_Post_Type::POST_TYPE_SLUG
                    );
                    $post_id = wp_update_post( $update_post );
                }

                $confirm_msg =
                    '<p><b>Congratulations!</b></p>
                    <p>Your account for our new Closings and Delays alerts system has been successfully created. Whether it\'s snow or storm, with this tool you will be able to alert Internet users and listeners of our New Jersey radio stations of any changes in your business opening times.</p>
                    <br/>
                    <b>Your login details are listed below.</b>
                    <p>You will need them to update your closings information so please save them for future reference.<br/> You can send us feedback or request additional help at ' . townsquare_get_email() . '.</p>
                    <br/>
                    <b>Your Account Information</b>
                    <p>Your business settings: ' . self::get_business_page_url($post) . 'login'.'</p>
                    <p>All closings in your area: ' . self::closing_url() . '</p>
                    <p>User Name: ' . $autocreds['user_login'] . '</p>
                    <p>Password: ' . $autocreds['user_password'] . '</p>';

                // SEND MAIL ON CONFIRMATION.
                add_filter( 'wp_mail_content_type',create_function( '', 'return "text/html";' ) );
                $subject = 'Your Closings and Delay account information for ' . get_the_title( $post_id );
                wp_mail( $autocreds['user_login'], $subject, $confirm_msg );

                $autocreds['remember'] = true;
                $user = wp_signon( $autocreds, false );
                wp_set_current_user($user->ID);
                get_currentuserinfo();

                $activate = new WP_Query( 'post_type=' . Business_Disruptions_Post_Type::POST_TYPE_SLUG . '&post_status=publish&author=' . $user_id );

                if(  $activate->post_count  > 1 ){
                    wp_redirect( self::manage_url()  );
                }else if( $activate->post_count == 1){
                    foreach($activate->posts as $post) {
                        wp_redirect( self::closing_url() . '?p='.$post->ID.'&a=update' );
                    }
                }else if( $activate->post_count == 0 ) {
                    wp_redirect( self::closing_url() );
                }
            } else {
                $this->activated_message =  '<div class="bdlogin-alert">Your account has been already activated, please login.</div>';
            }
        }

        if( $_GET[self::AUTH1] && $_GET[self::AUTH2] && $confirmation != 'pending'){
            $this->activated_message =  '<div class="bdlogin-alert">Your account has been already activated, please login.</div>';
        }

        /*
        * User confirmation check
        * User status is still 'pending' because:
        * Regular $_POST form submit did not successfully log them in
        *
        */
        if( $confirmation == 'pending' ){
            $this->error_message = '<span class="sanerror">Kindly activate your business before you proceed.</span><br/>';
            $this->error_message .= '<span class="sanerror">Didn\'t receive an email? Check your spam folder or <a href="' . self::resend_mail_url() . '" class="bdresendlink">click here </a>to resend.</span>';
            $creds['user_login'] = '';
        }

        /*
        * Log user in with $_POST form submit and redirect to appropriate page
        * Otherwise generate wp_error message
        *
        */
        $wp_signon = wp_signon($creds,false);
        $this->login_message = get_login_message();
        if ( !is_wp_error($wp_signon) && isset( $_POST[self::FORM_SUBMIT] )){
            if($_GET['ref'] == Business_Disruptions_Page_Register_Add::ADD_PAGE_NAME){
                wp_redirect(self::add_url());
                exit;
            }
            $useremail = $_POST[self::FORM_USER];
            $user_id = get_user_id_from_string( $useremail );

            $activate = new WP_Query( 'post_type=' . Business_Disruptions_Post_Type::POST_TYPE_SLUG . '&post_status=publish&author=' . $user_id );

            if(  $activate->post_count  > 1 ){
                wp_redirect( self::manage_url()  );
                exit;
            }else if( $activate->post_count == 1){
                foreach($activate->posts as $post){
                    wp_redirect( self::submit_closing_url() . '?p='.$post->ID.'&a=update' );
                    exit;
                }
            }else if( $activate->post_count == 0 ) {
                wp_redirect( self::closing_url() );
                exit;
            }
        }

        if ( isset($_POST[self::FORM_SUBMIT]) && is_wp_error($wp_signon) && $confirmation != 'pending' ) {
            if( $wp_signon->get_error_message() ){
                $this->error_message = '<span class="sanerror">Sorry, the email / user ID or password that you entered didn\'t match our records.</span>';
            }
        }
        $wpdb->blogid = $current_blog_id;
        $wpdb->set_prefix( $wpdb->base_prefix );
    }

    public function aioseop_title_page(){
        return self::__(self::PAGE_TITLE_LABEL);
    }

    public function the_content($content){
        include_once(self::plugin_path('views/page-login.php'));
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