<?php
/**
 * Business Disruptions Page Product_List
 */

class Business_Disruptions_Page_Product_List extends Business_Disruptions_Page {

    const QUERY_PAGE_NAME = 'product-list';
    const PAGE_TITLE_LABEL ='Closings';

    const URL_BASE = 'closings';
    const URL_PAGE_ID = '';
    const PREG_INDEX_PAGED = '/page/?([0-9]{1,})/?$';

    protected function __construct() {
        add_filter('template_redirect', array($this,'add_hooks'));
    }

    public function add_hooks(){
        if(get_query_var('pagename') == self::QUERY_PAGE_NAME){
            $this->add_parent_hooks();
            add_filter('aioseop_title_page', array($this, 'aioseop_title_page'));
            add_filter('the_content', array($this, 'the_content'));
            $this->load_template('page-template-general.php');
        }
    }

    protected static function pre_redirect(){
        //No Redirects
    }

    public function add_query_var($vars){
        $vars[] = 'paged';
        return $vars;
    }

    public function aioseop_title_page(){
        return self::__(self::PAGE_TITLE_LABEL);
    }

    public function the_content($content){
        include_once(self::plugin_path('views/page-products.php'));
        return null;
    }

    public static function get_filtered_results($array= array(), $businesstype = NULL, $city = NULL, $status = NULL, $serachkey = NULL, $postsPerpage = NULL){
        if(empty($businesstype)) $businesstype = array('business','civic-organization','government-offices','misc','religious-organizations','schools');
        if(empty($city)) $city = explode(',',get_option(Business_Disruptions_Site_Admin::ZIPCODE_OPTION_NAME));
        if(empty($status)) $status = array('search_normal_hours','search_closed','search_delayed','search_early_dismissal');
        if(empty($postsPerpage)) $postsPerpage = Business_Disruptions_Network_Admin::get_listings_per_page();


    }

    public static function get_business_result(){

    }

    public static function get_taxonomy_options($taxonomy){
        echo '<option value=""  class="seperator"> Business Type </option>';
        foreach( $taxonomy as $term ){
            echo '<option value="'.$term->term_id.'">'.$term->name.'</option>';
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