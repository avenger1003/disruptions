<?php
/**
 * Business Disruptions User Admin
 */

class Business_Disruptions_User_Admin extends Business_Disruptions_Plugin {

    const DASHBOARD_REWRITE = '/closings/';

    protected function __construct() {
        $this->add_hooks();
    }

    protected function add_hooks() {
        add_action('init', array($this,'admin_bar_disabler_disable'), 9);
        add_action('admin_menu', array($this,'dashboard_redirect'));
    }

    /*
     * Remove Admin Bar for business users
     *
     */
    public function admin_bar_disabler_disable(){
        global $current_user;
        if($current_user->roles[0]=='business'){
            show_admin_bar( false );
            remove_action('wp_head','_admin_bar_bump_cb');
        }
    }

    /*
     * Block Admin Panel if the user role is Business and redirect to home page
     *
     */
    public function dashboard_redirect(){
        global $current_user;
        if($current_user->roles[0]=='business'){
            wp_redirect( get_bloginfo('url') . self::DASHBOARD_REWRITE );
        }
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