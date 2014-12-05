<?php
/**
 * Business Disruptions Ajax
 */

class Business_Disruptions_Ajax extends Business_Disruptions_Plugin {

    const EMAIL_VERIFY = 'business_email_verify';
    const ZIP_DATA = 'business_zip_data';
    const DELETE_POST = 'business_delete_post';
    const PRODUCT_LISTS = 'business_product_lists';

    protected function __construct() {
        $this->add_hooks();
    }

    protected function add_hooks() {
        add_action('wp_ajax_'.self::EMAIL_VERIFY, array($this, 'email_verify'));
        add_action('wp_ajax_nopriv_'.self::EMAIL_VERIFY, array($this, 'email_verify'));
        add_action('wp_ajax_'.self::ZIP_DATA, array($this, 'get_zip_data'));
        add_action('wp_ajax_nopriv_'.self::ZIP_DATA, array($this, 'get_zip_data'));
        add_action('wp_ajax_'.self::DELETE_POST, array($this, 'delete_post'));
        add_action('wp_ajax_'.self::PRODUCT_LISTS, array($this, 'get_product_lists'));
        add_action('wp_ajax_nopriv_'.self::PRODUCT_LISTS, array($this, 'get_product_lists'));

        // TODO Remove when going live
        add_action('wp_ajax_cronjob', array($this, 'run_cron'));
        add_action('wp_ajax_nopriv_cronjob', array($this, 'run_cron'));
    }

    // TODO Remove when going live
    public function run_cron(){
        include_once(self::plugin_path('resources/cronjob.php'));
    }

    public function no_priv(){
        $response = array( 'success' => false );

        header( "Content-Type: application/json" );
        $response = json_encode( $response );
        echo $response;
        exit;
    }

    public function email_verify(){
        if(isset($_GET['email']) && !email_exists($_GET['email'])){
            $response['email'] = true;
            $response['msg'] = '<span style=\'color:#008000\'>Selected email address is available</span>';
        } else {
            $response['email'] = false;
            $response['msg'] = 'This email is already registered. Want to <a href="' . Business_Disruptions_Page::login_url() . '">login</a> ?';
        }

        header( "Content-Type: application/json" );
        $response = json_encode( $response );
        echo $response;
        exit;
    }

    public function delete_post() {
        global $wpdb, $blog_id;
        $current_blog_id = $blog_id;
        /* Set $myBlogId to the ID of the site you want to query */
        $wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
        $wpdb->set_prefix( $wpdb->base_prefix );
        wp_delete_post($_POST['pid']);
        $wpdb->blogid = $current_blog_id;
        $wpdb->set_prefix( $wpdb->base_prefix );
        die();
    }

    public function get_zip_data(){
        $response = array();
        if(isset($_GET['zip'])){
            $response['zip_data'] = self::get_user_zip_data(substr($_GET['zip'], 0, 5));
            $response['success'] = !empty($response['zip_data']) ? true : false;
        } else {
            $response['success'] = false;
        }

        header( "Content-Type: application/json" );
        $response = json_encode( $response );
        echo $response;
        exit;
    }

    public function get_product_lists(){
        $response = $filters = array();

        global $wpdb, $blog_id;
        $current_blog_id = $blog_id;
        $wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
        $wpdb->set_prefix( $wpdb->base_prefix );
        $meta_keys['zip'] = Business_Disruptions_Page::ZIP;
        $meta_keys['taxonomy'] = Business_Disruptions_Page::TAXONOMY;
        $meta_keys['status1'] = Business_Disruptions_Page::DAY1_SEARCH;
        $meta_keys['status2'] = Business_Disruptions_Page::DAY2_SEARCH;
        $meta_keys['search'] = Business_Disruptions_Page::NAME;
        $zipcodes = (!empty($_GET['zip'])) ? $_GET['zip'] : get_option(Business_Disruptions_Site_Admin::ZIPCODE_OPTION_NAME);
        $per_page = intval(Business_Disruptions_Network_Admin::get_listings_per_page());
        $page = (isset($_GET['page'])) ? intval($_GET['page'])*$per_page : 0;

        $select =       "SELECT SQL_CALC_FOUND_ROWS DISTINCT
                                            post.post_id
                         FROM               {$wpdb->postmeta} post
                         INNER JOIN         {$wpdb->postmeta} zip
                                                                ON zip.post_id = post.post_id
                                                                AND zip.meta_key = '{$meta_keys['zip']}'
                                                                AND zip.meta_value IN ({$zipcodes})".PHP_EOL;
        $where =        "";
        $orderby =      "ORDER BY post.post_id".PHP_EOL;
        $limit =        "LIMIT {$page},{$per_page}".PHP_EOL;
        if(!empty($_GET['business_type'])){
            $select .=  "INNER JOIN         {$wpdb->postmeta} taxonomy
						                    ON taxonomy.post_id = post.post_id
                                            AND taxonomy.meta_key = '{$meta_keys['taxonomy']}'
                                            AND taxonomy.meta_value = '{$_GET['business_type']}'".PHP_EOL;
        }
        if(!empty($_GET['status'])){
            $where  .=  "WHERE              (post.meta_key = '{$meta_keys['status1']}' AND post.meta_value = '{$_GET['status']}')
						                    OR
						                    (post.meta_key = '{$meta_keys['status2']}' AND post.meta_value = '{$_GET['status']}')".PHP_EOL;
        } else {
            $where  .=  "WHERE              (post.meta_key = '{$meta_keys['status1']}' AND post.meta_value IN ('search_closed','search_delayed','search_early_dismissal'))
						                    OR
						                    (post.meta_key = '{$meta_keys['status2']}' AND post.meta_value IN ('search_closed','search_delayed','search_early_dismissal'))".PHP_EOL;
        }
        if(!empty($_GET['search'])){
            $where  .=  "WHERE              post.meta_key = '{$meta_keys['search']}' AND post.meta_value LIKE '%%{$_GET['search']}%%'".PHP_EOL;
        }

        $query = $select . $where . $orderby . $limit;

        $post_ids = $wpdb->get_col($wpdb->prepare($query));

        ob_start();
        if($post_ids){
            foreach($post_ids as $id){
                $post = get_post(intval($id));
                setup_postdata($post);
                $business_data = array();
                $business_data['ID'] = $post->ID;
                $business_data['title'] = self::truncate($post->post_title,40);
                $business_data['link'] = Business_Disruptions_Page::get_business_page_url($post);
                $business_data['more'] = '<span><a href="'.$business_data['link'].'">More details</a></span>';
                $business_data['city'] = get_post_meta($post->ID,Business_Disruptions_Page::CITY,true);
                $business_data['today_status'] = Business_Disruptions_Page::the_status_label($post,'today',false);
                $business_data['tomorrow_status'] = Business_Disruptions_Page::the_status_label($post,'tomorrow',false);
                $business_data['today_message'] = self::truncate(Business_Disruptions_Page::the_status_message($post,'today',false),50,$business_data['more']);
                $business_data['tomorrow_message'] = self::truncate(Business_Disruptions_Page::the_status_message($post,'tomorrow',false),50,$business_data['more']);
                $is_owner = Business_Disruptions_Page::is_owner($post->ID);
                include(self::plugin_path('views/modules/ajax-products.php'));
            }
            $response['results'] = ob_get_clean();
        } else {
            $response['noresults'] = true;
        } $wpdb->flush();

        $wpdb->blogid = $current_blog_id;
        $wpdb->set_prefix( $wpdb->base_prefix );

        header( "Content-Type: application/json" );
        $response = json_encode( $response );
        echo $response;
        ob_end_flush();
        exit;
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