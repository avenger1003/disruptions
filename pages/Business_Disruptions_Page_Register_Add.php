<?php
/**
 * Business Disruptions Page Registration
 */

class Business_Disruptions_Page_Register_Add extends Business_Disruptions_Page {
    const REGISTER_PAGE_NAME = 'registration';
    const ADD_PAGE_NAME = 'add-business';


    protected static $query_page_name = '';
    protected static $page_title_label = '';
    protected static $url_base = '';
    protected static $url_id = '';
    protected static $form_submit = '';
    protected static $nonce_name = '';
    protected static $nonce_field = '';
    protected static $default_post_status = '';

    protected static $completion_message = '';
    protected static $errors = array();
    protected static $mesg = array();

    protected function __construct() {
        add_filter('template_redirect', array($this,'add_hooks'));
    }

    public function add_hooks(){
        $pagename = get_query_var('pagename');
        if($pagename == 'registration' || $pagename == 'add-business'){
            $this->set_default_properties($pagename);
            $this->pre_header_logic();
            $this->add_parent_hooks();
            add_filter('aioseop_title_page', array($this, 'aioseop_title_page'));
            add_filter('the_content', array($this, 'the_content') );
            $this->load_template('page-template-general.php');
        }
    }

    protected function set_default_properties($pagename){
        if($pagename == self::REGISTER_PAGE_NAME){
            self::$query_page_name = self::REGISTER_PAGE_NAME;
            self::$page_title_label = 'Business Registration';
            self::$url_base = 'businesses';
            self::$url_id = self::REGISTER_PAGE_NAME;
            self::$form_submit = 'register_bform_submit';
            self::$nonce_name = 'business-disruptions-registration';
            self::$nonce_field = 'business-disruptions-registration-nonce';
            self::$default_post_status = 'draft';
        } elseif ($pagename == self::ADD_PAGE_NAME) {
            self::$query_page_name = self::ADD_PAGE_NAME;
            self::$page_title_label = 'Add Business';
            self::$url_base = 'businesses';
            self::$url_id = self::ADD_PAGE_NAME;
            self::$form_submit = 'add_bform_submit';
            self::$nonce_name = 'business-disruptions-add';
            self::$nonce_field = 'business-disruptions-add-nonce';
            self::$default_post_status = 'publish';
        }

    }

    protected static function pre_redirect(){
        if ( is_user_logged_in() && self::$query_page_name == self::REGISTER_PAGE_NAME){
            wp_redirect(self::add_url());
            exit;
        } elseif ( !is_user_logged_in() && self::$query_page_name == self::ADD_PAGE_NAME){
            wp_redirect(self::login_url() . '?ref=' . self::ADD_PAGE_NAME);
            exit;
        }
    }
    
    protected function pre_header_logic(){

        /*
         * Redirect - Dependent Current Page
         */
        self::pre_redirect();

        /*
         * Sets database pointer to access posts from central database
         * Store current blog id to reset database pointer at the end of the function
         */
        global $wpdb, $blog_id, $current_user;
        get_current_user();
        $current_blog_id = $blog_id;
        $user_id = $current_user->ID;
        $wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
        $wpdb->set_prefix( $wpdb->base_prefix );

        /*
         * Grants Business Role Capabilities if user is a member of business_listings
         * Returns true if user is member
         */
        $is_member = self::set_user_role();

        /*
         * Sets variables for when the page is: (Also sets default post status respectively)
         * 1. Adding
         * 2. Editing
         */
//        $is_add = (self::$query_page_name == self::ADD_PAGE_NAME);
//        $is_edit = (isset($_POST[self::EDITMODE]));
        
        $is_add = (self::$query_page_name == self::ADD_PAGE_NAME) ? true : false;
        $is_edit = (isset($_POST[self::EDITMODE]))? true : false;
        
        $status = self::$default_post_status;

        /*
         * Assigns $_POST to $options variable
         */
        $options = self::assign_http_post($_POST);

        if(isset($_POST[self::$form_submit])){

            if(!wp_verify_nonce($_POST[self::$nonce_field],self::$nonce_name)){
                echo '<p>Sorry, your nonce did not verify.</p>';
                exit;
            }

            /*
             * Check for missing required fields
             * Add errors to error array
             * Edit and Add mode do not have email or password inputs
             */
            if(!$is_edit && !$is_add){
                if(!$options[self::EMAIL]){
                    self::$errors[self::EMAIL] = '* Email is required';
                }else{
                    $validemail = self::is_valid_email($options[self::EMAIL]);
                    if (!$validemail) self::$errors[self::EMAIL] = '* Not a valid Email';
                }
                if(!$options[self::PASS1]) self::$errors[self::PASS1] = 'Password is required';
                if(!$options[self::PASS2]) self::$errors[self::PASS2] = 'Confirm Password is required';
                if($options[self::PASS1] != $options[self::PASS2]) self::$errors[self::XPASS] = 'Both Password and Confirm password should be same';
            }
            if(!$options[self::NAME]) self::$errors[self::NAME] = 'Business Name is required';
            if(!$options[self::ADDRESS]) self::$errors[self::ADDRESS] = 'Address is required';
            if(!$options[self::CITY]) self::$errors[self::CITY] = 'City is required';
            if(!$options[self::STATE]) self::$errors[self::STATE] = 'State is required';
            if(!$options[self::ZIP]) self::$errors[self::ZIP] = 'Zip Code is required';
            if (!isset( $options[self::EVERYDAY] ) &&
                !isset( $options[self::MON] ) &&
                !isset( $options[self::TUE] ) &&
                !isset( $options[self::WED] ) &&
                !isset( $options[self::THU] ) &&
                !isset( $options[self::FRI] ) &&
                !isset( $options[self::SAT] ) &&
                !isset( $options[self::SUN] )
            ) self::$mesg[self::XDAY] = 'Select at least one day';

            /*
             * Registration Mode : Create new user
             * Add Mode : Update user as member of business
             */
            if ( !self::$errors  && !self::$mesg[self::XDAY] && !$is_add && !$is_edit){
                if($options[self::EMAIL] && $options[self::PASS1] && $options[self::PASS2]){
                    // Check that user doesn't already exist
                    if ( !username_exists($options[self::EMAIL]) && !email_exists($options[self::EMAIL]) ){
                        // Create user and set role to business
                        $user_id = wp_create_user( $options[self::EMAIL], trim($options[self::PASS1]), $options[self::EMAIL]);
                        if ( is_int($user_id) ){
                            $wp_user_object = new WP_User($user_id);
                            $wp_user_object->set_role(Business_Disruptions_Roles::ROLE_SLUG);
                            $blogs = $wpdb->get_results($wpdb->prepare("SELECT * from ts_blogs"));
                            foreach ( $blogs as $blog ){
                                $blog_id = $blog->blog_id;
                                add_user_to_blog( $blog_id, $user_id, Business_Disruptions_Roles::ROLE_SLUG );
                            }
                            update_user_meta($user_id, Business_Disruptions_Roles::ACTIVATION_SLUG, 'pending');
                            update_user_meta($user_id, Business_Disruptions_Roles::PASS_SLUG, self::encode_base64($options[self::PASS1]));
                            update_user_meta($user_id, Business_Disruptions_Roles::MEMBER_OF_SLUG, array(Business_Disruptions_Roles::MEMBER_ID));
                        }else {
                            self::$errors['wp_insert_user'] = 'Error with wp_insert_user. No users were created.';
                        }
                    } else {
                        self::$errors[self::EMAIL] = 'This user or email already exists.';
                    }
                }
            } elseif ($is_add && !$is_member) {
                $memberof = get_user_meta($current_user->ID, Business_Disruptions_Roles::MEMBER_OF_SLUG, true);
                $memberof[] = Business_Disruptions_Roles::MEMBER_ID;
                update_user_meta($current_user->ID, Business_Disruptions_Roles::MEMBER_OF_SLUG, $memberof);
            }

            /*
             * Setup Email for post_meta
             * Setup and assign days and hours to be saved to post_meta
             * If Difficulty is advanced, save individual day times if checked
             * Else set common time for all days where checked
             *
             */
            if($is_add || $is_edit) $options[self::EMAIL] = $current_user->user_email;
            if($options[self::DIFFICULTY]){
                $options[self::MON] = ($options[self::MON]) ? array( 'start' => $options[self::MON_START],'end' => $options[self::MON_END]) : '';
                $options[self::TUE] = ($options[self::TUE]) ? array( 'start' => $options[self::TUE_START],'end' => $options[self::TUE_END]) : '';
                $options[self::WED] = ($options[self::WED]) ? array( 'start' => $options[self::WED_START],'end' => $options[self::WED_END]) : '';
                $options[self::THU] = ($options[self::THU]) ? array( 'start' => $options[self::THU_START],'end' => $options[self::THU_END]) : '';
                $options[self::FRI] = ($options[self::FRI]) ? array( 'start' => $options[self::FRI_START],'end' => $options[self::FRI_END]) : '';
                $options[self::SAT] = ($options[self::SAT]) ? array( 'start' => $options[self::SAT_START],'end' => $options[self::SAT_END]) : '';
                $options[self::SUN] = ($options[self::SUN]) ? array( 'start' => $options[self::SUN_START],'end' => $options[self::SUN_END]) : '';
            }else{
                if ( $options[self::EVERYDAY] ){
                    $options[self::MON] =
                    $options[self::TUE] =
                    $options[self::WED] =
                    $options[self::THU] =
                    $options[self::FRI] =
                    $options[self::SAT] =
                    $options[self::MON] =
                    $options[self::SUN] = array( 'start' => $options[self::START],'end' => $options[self::END]);
                }else{
                    $options[self::MON] = ($options[self::MON]) ? array( 'start' => $options[self::START],'end' => $options[self::END]) : '';
                    $options[self::TUE] = ($options[self::TUE]) ? array( 'start' => $options[self::START],'end' => $options[self::END]) : '';
                    $options[self::WED] = ($options[self::WED]) ? array( 'start' => $options[self::START],'end' => $options[self::END]) : '';
                    $options[self::THU] = ($options[self::THU]) ? array( 'start' => $options[self::START],'end' => $options[self::END]) : '';
                    $options[self::FRI] = ($options[self::FRI]) ? array( 'start' => $options[self::START],'end' => $options[self::END]) : '';
                    $options[self::SAT] = ($options[self::SAT]) ? array( 'start' => $options[self::START],'end' => $options[self::END]) : '';
                    $options[self::SUN] = ($options[self::SUN]) ? array( 'start' => $options[self::START],'end' => $options[self::END]) : '';
                }
            }
            $options[self::COMMON_TIME] = array( 'start' => $options[self::START],'end' => $options[self::END]);

            /*
             * If no errors then process $_POST Data
             * Edit Mode : Updates Post
             * Add Mode : Add new post published
             * Registration Mode : Add new post draft & send activation email
             */
            if (!self::$errors) {
                if($is_edit){
                    echo 'Edit block';
                    if(self::is_owner($_POST[self::POSTID]))
                        $update_post = array(
                            'ID'            => trim($_POST[self::POSTID]),
                            'post_title'	=> $options[self::NAME],
                            'post_content'	=> $options[self::NAME],
                            'post_status'	=> 'publish',
                            'post_type'	=> Business_Disruptions_Post_Type::POST_TYPE_SLUG
                        );
                    if(self::is_owner($options[self::POSTID])) $post_id = wp_update_post($update_post);
                } else {
                    echo 'add block';
                    $user_id = ($is_add) ? $current_user->ID : get_user_id_from_string( $options[self::EMAIL] );
                    $post = array(
                        'post_author'	=> $user_id,
                        'post_title'	=> $options[self::NAME],
                        'post_content'	=> $options[self::NAME],
                        'post_status'	=> $status,
                        'post_type'	    => Business_Disruptions_Post_Type::POST_TYPE_SLUG
                    );
                    $post_id = wp_insert_post($post);

                    update_post_meta($post_id, self::CLOSING_HISTORY, 0);
                    update_post_meta($post_id, self::CLOSING_DAY1, '');
                    update_post_meta($post_id, self::CLOSING_DAY2, '');
                }
                // Timezone information
                $timezone = self::get_user_time_zone($options[self::ZIP]);
                // Get name of day (ex. Monday, Tuesday, etc ...
                $default_timezone = date_default_timezone_get();
                date_default_timezone_set($timezone['timezone_desc']);
                $today['slug'] = strtolower( date( "l" ) );
                $tomorrow['slug'] = strtolower(  date( "l", time()+86400 ) );
                $today['status'] = (empty($options['bd_'.$today['slug']])) ? self::CLOSED_HOURS : self::NORMAL_HOURS;
                $tomorrow['status'] = (empty($options['bd_'.$tomorrow['slug']])) ? self::CLOSED_HOURS : self::NORMAL_HOURS;
                date_default_timezone_set($default_timezone);

                $closing_day1 = get_post_meta($post_id, self::CLOSING_DAY1, true);
                $closing_day2 = get_post_meta($post_id, self::CLOSING_DAY2, true);
                // Setting default scheduled status and search terms when there are no business disruptions
                if(empty($closing_day1)) {
                    update_post_meta($post_id, self::DAY1_STATUS,$today['status']);
                    update_post_meta($post_id, self::DAY1_SEARCH,self::SEARCH_PREFIX.$today['status']);
                }
                if(empty($closing_day2)){
                    update_post_meta($post_id, self::DAY2_STATUS,$tomorrow['status']);
                    update_post_meta($post_id, self::DAY2_SEARCH,self::SEARCH_PREFIX.$tomorrow['status']);
                }

                /*
                 * Set Category and Add post_meta
                 */
                wp_set_post_terms($post_id, $options[self::TAXONOMY], Business_Disruptions_Taxonomy::TAXONOMY_SLUG, false);
                update_post_meta($post_id, self::EMAIL, $options[self::EMAIL]);
                update_post_meta($post_id, self::TAXONOMY, $options[self::TAXONOMY]);
                update_post_meta($post_id, self::NAME, $options[self::NAME]);
                update_post_meta($post_id, self::ADDRESS, $options[self::ADDRESS]);
                update_post_meta($post_id, self::CITY, $options[self::CITY]);
                update_post_meta($post_id, self::STATE, $options[self::STATE]);
                update_post_meta($post_id, self::ZIP, $options[self::ZIP]);
                update_post_meta($post_id, self::CONTACT, $options[self::CONTACT]);
                update_post_meta($post_id, self::PHONE, $options[self::PHONE]);
                update_post_meta($post_id, self::SITE, $options[self::SITE]);
                update_post_meta($post_id, self::EVERYDAY, $options[self::EVERYDAY]);
                update_post_meta($post_id, self::CHECKMODE, $options[self::CHECKMODE]);
                update_post_meta($post_id, self::MON, $options[self::MON]);
                update_post_meta($post_id, self::TUE, $options[self::TUE]);
                update_post_meta($post_id, self::WED, $options[self::WED]);
                update_post_meta($post_id, self::THU, $options[self::THU]);
                update_post_meta($post_id, self::FRI, $options[self::FRI]);
                update_post_meta($post_id, self::SAT, $options[self::SAT]);
                update_post_meta($post_id, self::SUN, $options[self::SUN]);
                update_post_meta($post_id, self::EDITMODE, $options[self::EDITMODE]);
                update_post_meta($post_id, self::DIFFICULTY, $options[self::DIFFICULTY]);
                update_post_meta($post_id, self::COMMON_TIME, $options[self::COMMON_TIME]);

                /*
                 * Registration Mode: Send activation email
                 * Edit / Add Mode: Redirect to respective urls
                 */
                if( isset( $_POST[self::$form_submit] ) && !$is_edit && !$is_add){
                    $encoded_email = self::encode_base64($options[self::EMAIL]);
                    $encoded_pass = self::encode_base64($options[self::PASS1]);

                    self::$completion_message = '<div class="success-message"><p>Thank you for registering, your Closings and Delays account has been created but your email address needs to be verified before your account becomes fully active. Please check your mail.</p><br/>
                    <p>Didn\'t receive an email? Check your spam folder or <a href="' . self::resend_mail_url() . '" class="bdresendlink">click here </a>to resend.</p></div>';

                    // SEND MAIL ON REGISTRATION.
                    $msg = '<p>Hello ' . $options[self::EMAIL] . ', <b>you are almost done!</b><p/>';
                    $msg .= '<p>Your Closings and Delays account has been created but your email address needs to be verified before your account becomes fully active.</p>';
                    $msg .= '<p><a href="' . self::business_url() . 'login/?Auth1=' . $encoded_email . '&Auth2=' . $encoded_pass . '">Click here to activate your business</a>';
                    $msg .=' or copy and paste this web address into your browser to confirm your email address ' . self::business_url() . 'login/?Auth1=' . $encoded_email . '&Auth2=' . $encoded_pass . '.</p>';
                    $subject = 'Your Closings and Delay account information for ' . get_the_title($post_id);
                    add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

                    wp_mail($options[self::EMAIL], $subject,  $msg  );
                } else {
                    if($is_edit || $is_add) {
                        $link = (!self::is_owner($_POST[self::POSTID])) ? self::closing_url() : self::submit_closing_url() . '?' . self::POSTID . '=' . $post_id . '&a=update';
                    } else {
                        $link = self::closing_url() . '?' . self::POSTID . '=' . $post_id;
                    }
                    if(!self::$mesg){
                        //wp_redirect(self::add_url().'?p='.$post_id);
                       wp_redirect($link);
                      //  echo $link;
                    }
                }
            }
        }

        /*
         * Reset database pointer to current blog
         */
        $wpdb->blogid = $current_blog_id;
        $wpdb->set_prefix( $wpdb->base_prefix );

    }

    public function aioseop_title_page(){
        return self::__(self::$page_title_label);
    }

    public function the_content($content){
        include_once(self::plugin_path('views/page-register-add.php'));
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