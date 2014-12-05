<?php
/**
 * Business Disruptions Page Mail
 */

class Business_Disruptions_Page_Mail extends Business_Disruptions_Page {

    const QUERY_PAGE_NAME = 'resend-mail';
    const PAGE_TITLE_LABEL ='Resend Mail';

    const URL_BASE = 'businesses';
    const URL_PAGE_ID = self::QUERY_PAGE_NAME;

    const FORM_SUBMIT = 'resendmail_bform_submit';
    const NONCE_NAME = 'business-disruptions-resendmail';
    const NONCE_FIELD = 'business-disruptions-resendmail-nonce';
    const USER_LOGIN = 'user_login';

    protected function __construct() {
        add_filter('template_redirect', array($this,'add_hooks'));
    }

    public function add_hooks(){
        if(get_query_var('pagename') == self::QUERY_PAGE_NAME){
            $this->pre_header_logic();
            $this->add_parent_hooks();
            add_filter('aioseop_title_page', array($this, 'aioseop_title_page'), 19, 3 );
            add_filter('the_content', array($this, 'the_content') );
            $this->load_template('page-template-general.php');
        }
    }

    public function pre_header_logic(){
        if ( is_user_logged_in() ){
            wp_redirect(self::closing_url());
            exit;
        }
    }

    public function aioseop_title_page(){
        return self::__(self::PAGE_TITLE_LABEL);
    }

    public function the_content($content){
        include_once(self::plugin_path('views/page-resend-mail.php'));
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