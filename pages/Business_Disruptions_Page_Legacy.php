<?php
/**
 * Business Disruptions Page Legacy
 */

class Business_Disruptions_Page_Legacy extends Business_Disruptions_Page {

    const QUERY_PAGE_NAME = 'legacy-migration';
    const PAGE_TITLE_LABEL ='Legacy';

    const URL_BASE = 'businesses';
    const URL_PAGE_ID = self::QUERY_PAGE_NAME;

    protected function __construct() {
        add_filter('template_redirect', array($this,'add_hooks'));
    }

    public function add_hooks(){
        if(get_query_var('pagename') == self::QUERY_PAGE_NAME){
            $this->add_parent_hooks();
            add_filter('aioseop_title_page', array($this, 'aioseop_title_page'));
            add_filter('the_content', array($this, 'the_content') );
            $this->load_template('page-template-login.php');
        }
    }

    protected static function pre_redirect(){
        //No Redirects
    }

    public function aioseop_title_page(){
        return self::__(self::PAGE_TITLE_LABEL);
    }

    public function the_content($content){
        include_once(self::plugin_path('views/page-legacy.php'));
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