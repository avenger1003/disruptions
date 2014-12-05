<?php


class Disruptions {

    var $version = '1.0';
    var $location;
    var $plugin_dir = '';
    var $plugin_url = '';
    var $centralBid = '';
    //var $centralBid = update_site_option( 'bd_centralBid', $value );
    //get_site_option( 'network_centralbid' );
    function Disruptions() {
        $this->__construct();
    }

    function __construct() {
        //setup our variables
        $this->init_vars();

        //install plugin
        register_activation_hook( __FILE__, array($this, 'install') );

        //load template functions
        require_once( $this->plugin_dir . 'template-functions.php' );

        $settings = get_option('bd_settings');
        add_action( 'init', array(&$this, 'register_custom_posts'), 0 ); //super high priority
        add_action( 'init', array(&$this, 'centralBid'), 0 ); // Assign central blogid
        add_action( 'wp', array(&$this, 'load_business_templates') );
        add_action( 'admin_menu', array(&$this, 'add_menu_items') );
        add_action( 'admin_menu', array(&$this, 'dashboard_redirect'));
        add_action( 'template_redirect', array(&$this, 'load_business_theme') );
        add_filter( 'rewrite_rules_array', array(&$this, 'add_rewrite_rules') );
        add_action( 'template_redirect', array(&$this, 'business_script') ); //only on front pages
        add_action( 'option_rewrite_rules', array(&$this, 'check_rewrite_rules') );
        add_action( 'admin_print_scripts' . $page, array(&$this, 'my_admin_scripts') );
        add_action( 'admin_print_styles' . $page, array(&$this, 'my_admin_styles') );
        add_filter( 'google_ads_gtype', array(&$this, 'google_ads_gtype') );
        add_filter( 'ts_force_sidebar_ids', array(&$this, 'ts_force_sidebar_ids') );

        add_action( 'wp_loaded', array(&$this, 'register_closing_sidebars') );

        add_action( 'wp_ajax_deletepost', 'get_deletepost' );
        add_action( 'wp_ajax_businesscheck', 'get_businesscheck' );
        add_action('wp_ajax_nopriv_businesscheck', 'get_businesscheck');
        add_action( 'wp_ajax_useremailcheck', 'get_usernamecheck' );

        add_action('wp_ajax_nopriv_businessListing', 'getBusinessResult');
        add_action( 'wp_ajax_businessListing', 'getBusinessResult' );

        add_action('wp_ajax_nopriv_new_search_province', 'new_search_province');
        add_action( 'wp_ajax_new_search_province', 'new_search_province' );

        add_action('wp_ajax_nopriv_businessSearch', 'businessSearch');
        add_action( 'wp_ajax_businessSearch', 'businessSearch' );


//                                        add_action('wp_ajax_nopriv_bd_resendMail', 'bd_resendMail');
//                                        add_action( 'wp_ajax_bd_resendMail', 'bd_resendMail' );
        $this->install();
    }
    /**
     * The post type defined by this class
     *
     * @param string $format Either 'id' (for the post type ID) or 'object' (for the WP post type object)
     * @return object|string
     */
    public function get_post_type( $format = 'id' ) {
        switch ( $format ) {
            case 'object':
                return get_post_type_object($this->post_type);
            default:
                return $this->post_type;
        }
    }

    /**
     * Add the page type to the google ads header
     *
     * @param string $gtype
     * @return string $gtype 'closings' if the current page is a loyalty page
     */
    public function google_ads_gtype( $gtype = null ) {

        $url = $_SERVER["REQUEST_URI"];
        $bus_id = strpos($url, 'businesses');
        $clo_id = strpos($url, 'closings');
        if ($bus_id!==false || $clo_id!==false){
            $gtype = 'closings';
        }
        return $gtype;
    }

    /**
     * Change sidebar for closings page to weather
     *
     * @param array $sidebar_id
     * @return array $sidebar_id setting first sidebar weather-page
     */
    public function ts_force_sidebar_ids($sidebar_ids){

        $url = $_SERVER["REQUEST_URI"];
        $bus_id = strpos($url, 'businesses');
        $clo_id = strpos($url, 'closings');
        if ( ($bus_id!==false || $clo_id!==false) && $sidebar_ids[0]=='single-page' ){
            $sidebar_ids = array('closings-page','weather-page','default','home-right');
        }
        return $sidebar_ids;
    }

    // Block Admin Panel if the user role is Business and redirect to home page
    function dashboard_redirect() {
        global $current_user, $wpdb, $wp_user_roles;
        if($current_user->roles[0]=='business'){
            $url = get_bloginfo('url').'/closings';
            wp_redirect( $url );
        }
    }
    function install() {
        $old_settings = get_option('bd_settings');
        $old_version = get_option('bd_version');

        //our default settings
        $default_settings = array (
            'base_country' => 'US',
            /* Translators: change default slugs here */
            'slugs' => array (
                'business' => __('businesses', 'bd'),
                'login' => __('login', 'bd'),
                'registration' => __('registration', 'bd'),
                'closings' => __('closings', 'bd'),
                'submit-closings' => __('submit-closings', 'bd'),
                'manage' => __('manage', 'bd'),
                'addbusiness' => __('add-business', 'bd'),
                'login' => __('login', 'bd'),
                'legacy' => __('legacy-migration', 'bd'),
                'resend-mail' => __('resend-mail', 'bd'),
                'category' => __('category', 'bd'),
                'tag' => __('tag', 'bd')
            ),
            'msg' => array (
                'business_list' => 'Business List'
            ),
        );
        //filter default settings
        $default_settings = apply_filters( 'bd_default_settings', $default_settings );
        $settings = wp_parse_args( (array)$old_settings, $default_settings );
        update_option( 'bd_settings', $default_settings );
        add_option( 'bd_business_page', '', '', 'no' );
        //create business & closings page
        add_action( 'admin_init', array(&$this, 'create_business_page') );
        add_action( 'admin_init', array(&$this, 'create_closings_page') );
        add_action( 'admin_init', array(&$this, 'create_categories') );
        add_action( 'admin_init', array(&$this, 'createRole') );
        add_action('init', array(&$this,'admin_bar_disabler_disable' ), 9);
        update_option( 'bd_version', $this->version );
    }

    function init_vars() {
        //setup proper directories
        if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/business-disruptions/' . basename(__FILE__))) {
            $this->location = 'subfolder-plugins';
            $this->plugin_dir = WP_PLUGIN_DIR . '/business-disruptions/includes/';
            $this->plugin_url = WP_PLUGIN_URL . '/business-disruptions/includes/';
        } else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/' . basename(__FILE__))) {
            $this->location = 'plugins';
            $this->plugin_dir = WP_PLUGIN_DIR . '/includes/';
            $this->plugin_url = WP_PLUGIN_URL . '/includes/';
        } else if (is_multisite() && defined('WPMU_PLUGIN_URL') && defined('WPMU_PLUGIN_DIR') && file_exists(WPMU_PLUGIN_DIR . '/' . basename(__FILE__))) {
            $this->location = 'mu-plugins';
            $this->plugin_dir = WPMU_PLUGIN_DIR . '/includes/';
            $this->plugin_url = WPMU_PLUGIN_URL . '/includes/';
        } else {
            wp_die(__('There was an issue determining the location of the plugin. Please reinstall.', 'bd'));
        }
    }

    //Create/Update the Business page on plugin install
    function create_business_page($old_slug = false) {
        global $wpdb, $user_ID;
        $settings = get_option('bd_settings');

        //delete old page on update
        if ($old_slug && $old_slug != $settings['slugs']['business']) {
            $old_post_id = $wpdb->get_var("SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '$old_slug' AND post_type = 'page'");
            $old_post = get_post($old_post_id);
            $old_post->post_name = $settings['slugs']['business'];
            wp_update_post($old_post);
        }

        //Add Business page if not exists
        $page_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->posts . " WHERE post_name = '" . $settings['slugs']['business'] . "' AND post_type = 'page'");
        if ( !$page_count ) {
            $id = wp_insert_post( array('post_title' => __('Businesses', 'bd'), 'post_name' => $settings['slugs']['business'], 'post_status' => 'publish', 'post_type' => 'page', 'post_content' => $content ) );
            update_option('bd_business_page', $id);
        }
    }
    //Create/Update the 	 on plugin install
    function create_closings_page($old_slug = false) {
        global $wpdb, $user_ID;
        $settings = get_option('bd_settings');

        //delete old page if update
        if ($old_slug && $old_slug != $settings['slugs']['closings']) {
            $old_post_id = $wpdb->get_var("SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '$old_slug' AND post_type = 'page'");
            $old_post = get_post($old_post_id);
            $old_post->post_name = $settings['slugs']['closings'];
            wp_update_post($old_post);
        }

        //Add Closings page if not exists
        $page_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->posts . " WHERE post_name = '" . $settings['slugs']['closings'] . "' AND post_type = 'page'");
        if ( !$page_count ) {
            $id = wp_insert_post( array('post_title' => __('Closings', 'bd'), 'post_name' => $settings['slugs']['closings'], 'post_status' => 'publish', 'post_type' => 'page', 'post_content' => $content ) );
            update_option('bd_closings_page', $id);
        }
    }

    function register_custom_posts() {
        $settings = get_option('bd_settings');
        // Register custom taxonomy
        register_taxonomy( 'business_type', 'business', apply_filters( 'bd_register_business_type', array("hierarchical" => true, 'label' => __('Business Type', 'bd'), 'singular_label' => __('Business Type', 'bd'), 'rewrite' => array('slug' => $settings['slugs']['business'] . '/' . $settings['slugs']['category'])) ) );

        // Register custom closing post type
        $supports = array( 'title', 'editor', 'author', 'excerpt', 'revisions', 'thumbnail' );
        $args = array ( 'labels' => array('name' => __('Closings', 'bd'),
            'singular_name' => __('View Businesses', 'bd'),
            'add_new' => __('', 'bd'),
            'add_new_item' => __('Create New Business', 'bd'),
            'edit_item' => __('Edit Business', 'bd'),
            'edit' => __('Edit', 'bd'),
            'new_item' => __('New Business', 'bd'),
            'view_item' => __('View Business', 'bd'),
            'search_items' => __('Search Business', 'bd'),
            'not_found' => __('No Business Found', 'bd'),
            'not_found_in_trash' => __('No Business found in Trash', 'bd'),
            'view' => __('View Business', 'bd')
        ),
            'description' => __('Businesses for your Site.', 'bd'),
            'public' => true,
            'show_ui' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => array('slug' => $settings['slugs']['business']), // Permalinks format
            'query_var' => true,
            'supports' => $supports,
        );
        register_post_type( 'Business' , apply_filters( 'bd_register_post_type', $args ) );
    }

                    function register_closing_sidebars(){
                        register_sidebar(
                            array(
                                'name'          => 'Closings Page',
                                'id'            => 'closings-page',
                                'description'   => 'This sidebar is used for the closings page.',
                                'before_widget' => '<div id="%1$s" class="widget widget_bg %2$s clearfix"><div class="widget_int_wrap">',
                                'after_widget' => '<div class="widget_floor widget_bg">&nbsp;</div></div></div>',
                                'before_title' => '<div class="widget_header clearfix"><h4 class="widget_title font_face">',
                                'after_title' => '</h4></div>'
                            )
                        );
                    }

    function centralBid() {
        //$this->centralBid=get_site_option( 'network_centralbid' );
        $this->centralBid='449';
    }

    function add_rewrite_rules($rules){
        $settings = get_option('bd_settings');

        $new_rules = array();
        $new_rules[$settings['slugs']['closings'] . '/?$'] = 'index.php?pagename=product_list';
        $new_rules[$settings['slugs']['closings'] . '/page/?([0-9]{1,})/?$'] = 'index.php?pagename=product_list&paged=$matches[1]';
        $new_rules[$settings['slugs']['business'] . '/(.*)/?([0-9]{1,})/(.*)/?$'] = 'index.php?pagename=closing_list';
        $new_rules[$settings['slugs']['business'] . '/' . $settings['slugs']['submit-closings'] . '/?$'] = 'index.php?pagename=closing';
        $new_rules[$settings['slugs']['business'] . '/' . $settings['slugs']['registration'] . '/?$'] = 'index.php?pagename=registration';
        $new_rules[$settings['slugs']['business'] . '/' . $settings['slugs']['manage'] . '/?$'] = 'index.php?pagename=manage';
        $new_rules[$settings['slugs']['business'] . '/' . $settings['slugs']['addbusiness'] . '/?$'] = 'index.php?pagename=addbusiness';
        $new_rules[$settings['slugs']['business'] . '/' . $settings['slugs']['login'] . '/?$'] = 'index.php?pagename=login';
        $new_rules[$settings['slugs']['business'] . '/' . $settings['slugs']['legacy'] . '/?$'] = 'index.php?pagename=legacy';
        $new_rules[$settings['slugs']['business'] . '/' . $settings['slugs']['resend-mail'] . '/?$'] = 'index.php?pagename=resendmail';

        return array_merge($new_rules, $rules);
    }

    function create_categories(){
        $parent_term = term_exists( 'business_type', 'business_type' ); // array is returned if taxonomy is given
        $parent_term_id = $parent_term['term_id']; // get numeric term id
        wp_insert_term( 'Schools', 'business_type',array('slug' => 'schools','parent'=> $parent_term_id ));
        wp_insert_term( 'Government', 'business_type',array('slug' => 'government-offices','parent'=> $parent_term_id ));
        wp_insert_term( 'Business', 'business_type',array('slug' => 'business','parent'=> $parent_term_id ));
        wp_insert_term( 'Religious Organizations', 'business_type',array('slug' => 'religious-organizations','parent'=> $parent_term_id ));
        wp_insert_term( 'Civic Organizations', 'business_type',array('slug' => 'civic-organizations','parent'=> $parent_term_id ));
        wp_insert_term( 'Miscellaneous', 'business_type',array('slug' => 'misc','parent'=> $parent_term_id ));
    }

    function createRole() {
        global $wp_roles;
        if ( ! isset( $wp_roles ) )
            $wp_roles = new WP_Roles();
        //creating new role with admin capabilities. The business will be restricted access to wp-admin and will be to closings page using wp_redirect
        $business_caps = array(
            'read' => true, // True allows that capability
            'edit_posts' => true,
            'delete_posts' => true, // Use false to explicitly deny
            'switch_themes' =>false,
            'edit_themes' =>false,
            'edit_theme_options' =>false,
            'install_themes' =>false,
            'activate_plugins' =>false,
            'edit_plugins' =>false,
            'install_plugins' =>false,
            'edit_users' => false,
            'edit_files' => false,
            'manage_options' => false,
            'moderate_comments' => false,
            'manage_categories' => false,
            'manage_links' => false,
            'upload_files' => false,
            'import' => false,
            'unfiltered_html' => true,
            'edit_others_posts' => false,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'edit_pages' => true,
            'level_10' => true,
            'level_9' => true,
            'level_8' => true,
            'level_7' => true,
            'level_6' => true,
            'level_5' => true,
            'level_4' => true,
            'level_3' => true,
            'level_2' => true,
            'level_1' => true,
            'level_0' => true,
            'edit_others_pages' => false,
            'edit_published_pages' => true,
            'publish_pages' => true,
            'delete_pages' => true,
            'delete_others_pages' => false,
            'delete_published_pages' => true,
            'delete_posts' => true,
            'delete_others_posts' => false,
            'delete_published_posts' => true,
            'delete_private_posts' => true,
            'edit_private_posts' => true,
            'read_private_posts' => true,
            'delete_private_pages' => true,
            'edit_private_pages' => true,
            'read_private_pages' => true,
            'delete_users' => false,
            'create_users' => false,
            'unfiltered_upload' => false,
            'edit_dashboard' => false,
            'update_plugins' => false,
            'delete_plugins' => false,
            'install_plugins' => false,
            'update_themes' => false,
            'install_themes' => false,
            'update_core' => false,
            'list_users' => false,
            'remove_users' => false,
            'add_users' => false,
            'promote_users' => false,
            'edit_theme_options' => false,
            'delete_themes' => false,
            'export' => false
        );
        //Adding a 'new_role' with all admin caps
        $wp_roles->add_role('business', 'Business', $business_caps);
    }

    function admin_bar_disabler_disable (){
        global $current_user, $wpdb, $wp_user_roles;
        if($current_user->roles[0]=='business'){
            add_filter('show_admin_bar', '__return_false');
            add_action('admin_head', 'admin_bar_disabler_hide');
            remove_action('personal_options', '_admin_bar_preferences');
        }
    }

    function admin_bar_disabler_hide (){
        ?>
    <style type="text/css">
        .show-admin-bar { display: none; }
    </style>
    <?php
    }

    function my_admin_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
    }

    function my_admin_styles() {
        wp_enqueue_style('thickbox');
    }

    function add_menu_items() {
        $settings = get_option('bd_settings');
        if (get_current_blog_id() != $this->centralBid){
            remove_submenu_page('edit.php?post_type=business', 'edit-tags.php?taxonomy=business_type&amp;post_type=business');
        }
        remove_submenu_page('edit.php?post_type=business', 'edit.php?post_type=business');
        $page = add_submenu_page('edit.php?post_type=business', __('Settings', 'bd'), __('Settings', 'bd'), 'edit_others_posts', 'business', array(&$this, 'admin_page'));
    }

    function admin_page(){
        //save settings
        if (isset($_POST['business_settings'])) {
            $per_page = $_POST['per_page'];
            $legacycheck = $_POST['legacycheck'];
            $siteZipcodes = $_POST['siteZipcode'];

            update_option('bd_perpage', $per_page);
            update_option('bd_legacycheck', $legacycheck);
            update_option('bd_siteZipcode', $siteZipcodes);

            echo '<div class="updated fade">
                                                                                    <p>'.__('Settings saved.', 'mp').'</p>
                                                                            </div>';
        } ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            // LEGACY CHECK
            jQuery('.inside input.legacycheck').live('click',function() {
                if(jQuery(this).attr("checked")){
                    jQuery(this).val(1);
                }else{
                    jQuery(this).val(0);
                }
            });
        });
    </script>
    <?php
        //  ADMI SETTINGS
        $zipCodes =  get_option('bd_siteZipcode');
        if( get_site_option( 'network_centralbid' ) == get_current_blog_id() &&  isset( $_POST['business_settings'] ) ){
            $value  = ( $_POST['per_page'] ) ?  $_POST['per_page'] :  get_site_option( 'per_page' );
            update_site_option( 'per_page', $value );
        }
        $perpage =  ( get_site_option( 'per_page' ) ) ? get_site_option( 'per_page' ) : '5';
        $content.= '<h2>Business Settings</h2>';
        $content.= '<form id="bd-main-form" method="post" action="">
                                        <input type="hidden" name="business_settings" value="1" />
                                           <div class="postbox">
                                                            <h2><span><?php _e("Business Settings", "bd") ?></span></h2>
                                                            <div class="inside" style="margin:0 30px;">';
        if (get_option('bd_legacycheck') == 1){
            $content.='<div><input style="width:13px;" type="checkbox" class="legacycheck" checked="" value="1" name="legacycheck" />Enforce Legacy user check</div>';
        }else{
            $content.='<div><input style="width:13px;" type="checkbox" class="legacycheck" value="0" name="legacycheck" />Enforce Legacy user check</div>';
        }
        if( get_site_option( 'network_centralbid' ) == get_current_blog_id() ){
            $content.='<p><div>Number of closing listings  to show per page <input type="text" name="per_page" value="'.$perpage.'" size="5" /></div>
                                                                                </p>';
        }
        $content.='<p><div> Specify zipcodes for closing listings</div>
                                                                                <textarea name="siteZipcode" rows=3 cols=20 wrap=off>'.$zipCodes.'</textarea>';
        $content.='<p><input type="submit" class="button-primary" name="business_settings" value="submit"/></p></p>
                                                            </div>                             
                                        </div>
                    </form>';
        echo $content;
    }

    // This function clears the rewrite rules and forces them to be regenerated
    function flush_rewrite() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    //unfortunately some plugins flush rewrites before the init hook so they kill custom post type rewrites. This function verifies they are in the final array and flushes if not
    function check_rewrite_rules($value) {
        global $wpdb;
        $settings = get_option('bd_settings');

        //prevent an infinite loop by only
        if ( ! post_type_exists( 'businesses' ) )
            return $value;

        if ( is_array($value) && !in_array('index.php?businesses=$matches[1]&paged=$matches[2]', $value) ) {
            $this->flush_rewrite();
        } else {
            return $value;
        }
        $this->flush_rewrite();
    }

    //scans post type at template_redirect to apply custom themeing to products
    function load_business_templates() {
        global $wp_query;
        $settings = get_option('bd_settings');

        //load proper theme for registration page display
        if ($wp_query->query_vars['pagename'] == 'registration') {
            add_action('admin_print_scripts', 'my_admin_scripts');
            add_action('admin_print_styles', 'my_admin_styles');
            $wp_query->is_page = 1;
            $wp_query->is_singular = 1;
            $wp_query->is_404 = null;
            $wp_query->post_count = 1;
            add_filter( 'bp_page_title', array(&$this, 'bdpage_title_output'), 99 );
            add_filter( 'wp_title', array(&$this, 'wp_title_output'), 19, 3 );
            add_filter( 'the_content', array(&$this, 'registration_theme'), 99 );
        }

        //load proper theme for add/edit business
        if ($wp_query->query_vars['pagename'] == 'addbusiness') {
            $wp_query->is_page = 1;
            $wp_query->is_404 = null;
            $wp_query->post_count = 1;
            add_filter( 'bp_page_title', array(&$this, 'bdpage_title_output'), 99 );
            add_filter( 'wp_title', array(&$this, 'wp_title_output'), 19, 3 );
            add_filter( 'the_content', array(&$this, 'addbusiness_theme'), 99 );
        }

        //load proper theme for  submit closings
        if ($wp_query->query_vars['pagename'] == 'closing') {
            $wp_query->is_page = 1;
            $wp_query->is_404 = null;
            $wp_query->post_count = 1;
            add_filter( 'bp_page_title', array(&$this, 'bdpage_title_output'), 99 );
            add_filter( 'wp_title', array(&$this, 'wp_title_output'), 19, 3 );
            add_filter( 'the_content', array(&$this, 'closing_theme'), 99 );

        }

        //load proper theme for Legacy Migration
        if ($wp_query->query_vars['pagename'] == 'legacy') {
            $wp_query->is_page = 1;
            $wp_query->is_404 = null;
            $wp_query->post_count = 1;
            add_filter( 'bp_page_title', array(&$this, 'bdpage_title_output'), 99 );
            add_filter( 'wp_title', array(&$this, 'wp_title_output'), 19, 3 );
            add_filter( 'the_content', array(&$this, 'legacy_theme'), 99 );
        }

        //load proper theme for Legacy Migration
        if ($wp_query->query_vars['pagename'] == 'resendmail') {
            $wp_query->is_page = 1;
            $wp_query->is_404 = null;
            $wp_query->post_count = 1;
            add_filter( 'bp_page_title', array(&$this, 'bdpage_title_output'), 99 );
            add_filter( 'wp_title', array(&$this, 'wp_title_output'), 19, 3 );
            add_filter( 'the_content', array(&$this, 'resendmail_theme'), 99 );
        }

        //load proper theme for business listings
        if ($wp_query->query_vars['pagename'] == 'product_list') {
            $wp_query->is_page = 1;
            $wp_query->is_404 = null;
            $wp_query->post_count = 1;
            add_filter( 'bp_page_title', array(&$this, 'bdpage_title_output'), 99 );
            add_filter( 'wp_title', array(&$this, 'wp_title_output'), 19, 3 );
            add_filter( 'the_content', array(&$this, 'product_list_theme'), 99 );
        }

        //load proper theme for business listings
        if ($wp_query->query_vars['pagename'] == 'closing_list') {
            $wp_query->is_page = 1;
            $wp_query->is_404 = null;
            $wp_query->post_count = 1;
            add_filter( 'bp_page_title', array(&$this, 'bdpage_title_output'), 99 );
            add_filter( 'wp_title', array(&$this, 'wp_title_output'), 19, 3 );
            add_filter( 'the_content', array(&$this, 'closing_list_theme'), 99 );
        }


        //load proper theme for business listings
        if ($wp_query->query_vars['pagename'] == 'manage') {
            $wp_query->is_page = 1;
            $wp_query->is_404 = null;
            $wp_query->post_count = 1;
            add_filter( 'bp_page_title', array(&$this, 'bdpage_title_output'), 99 );
            add_filter( 'wp_title', array(&$this, 'wp_title_output'), 19, 3 );
            add_filter( 'the_content', array(&$this, 'manage_business_theme'), 99 );
            add_filter( 'the_excerpt', array(&$this, 'manage_business_theme'), 99 );
        }

        //load proper theme for business listings
        if ($wp_query->query_vars['pagename'] == 'login') { ?>
        <style>      #single_page_right_sidebar{ display: none; }
        #post-content- .the_content{ width: 960px!important; }
        .container .column_1_2{ width: 960px  !important; }
        </style>
        <?php
            $wp_query->is_page = 1;
            $wp_query->is_404 = null;
            $wp_query->post_count = 1;
            add_filter( 'bp_page_title', array(&$this, 'bdpage_title_output'), 99 );
            add_filter( 'wp_title', array(&$this, 'wp_title_output'), 19, 3 );
            add_filter( 'the_content', array(&$this, 'login_business_theme'), 99 );
            add_filter( 'the_excerpt', array(&$this, 'login_business_theme'), 99 );
        }

    }
    //loads the selected theme css files
    function load_business_theme() {
        $settings = get_option('bd_settings');
        wp_enqueue_style( 'db-business-theme', $this->plugin_url . 'css/styles.css', false, $this->version );
    }

    function wp_title_output($title, $sep, $seplocation) {
        // Determines position of the separator and direction of the breadcrumb
        if ( 'right' == $seplocation )
            return $this->bdpage_title_output($title, true) . " $sep ";
        else
            return " $sep " . $this->bdpage_title_output($title, true);
    }

    //filters the titles for our custom pages
    function bdpage_title_output($title, $id = false) {
        global $wp_query;
        //filter out nav titles
        if (!empty($title) && $id === false)
            return $title;

        if ($wp_query->query_vars['post_type'] == 'business'){
            return sprintf( __('Single Page: %s', 'bd'));
        }
        if ($wp_query->is_single && $wp_query->query_vars['post_type'] == 'business') {
            return sprintf( __('Single Pagess: %s', 'bd'));
        }

        //taxonomy pages
        if (($wp_query->query_vars['taxonomy'] == 'business_type' || $wp_query->query_vars['taxonomy'] == 'business_tag') && $wp_query->post->ID == $id) {
            if ($wp_query->query_vars['taxonomy'] == 'business_type') {
                $term = get_term_by('slug', get_query_var('business_type'), 'business_type');
                return sprintf( __(' %s', 'bd'), '' );
            } else if ($wp_query->query_vars['taxonomy'] == 'business_tag') {
                $term = get_term_by('slug', get_query_var('business_tag'), 'business_tag');
                return sprintf( __('Business Tag: %s', 'bd'), $term->name );
            }
        }

        switch ($wp_query->query_vars['pagename']) {
            case 'registration':
                return __('Business registration', 'bd');
                break;
            case 'addbusiness':
                return __('Add Business', 'bd');
                break;
            case 'closing':
                return __('Submit Closings', 'bd');
                break;
            case 'legacy':
                return __('Legacy', 'bd');
                break;
            case 'resendmail':
                return __('Resend Mail', 'bd');
                break;
            case 'product_list':
                return __('Business Disruptions', 'bd');
                break;
            case 'closing_list':
                return __('Closings', 'bd');
                break;
            case 'manage':
                return __('Manage your Businesses', 'bd');
                break;
            case 'login':
                return __('Closings - Login', 'bd');
                break;


            default:
                return $title;
        }

    }

    function product_list_theme($content) {
        //don't filter outside of the loop
        if ( !in_the_loop() )
            return $content;

        $settings = get_option('bd_settings');
        //$content .= $settings['msg']['product_list'];
        $content .= bd_business_list( true );
        return $content;
    }

    function closing_list_theme($content) {
        //don't filter outside of the loop
        if ( !in_the_loop() )
            return $content;

        $settings = get_option('bd_settings');
        $content .= $settings['msg']['product_list'];
        $content .= detail_page( true );
        return $content;
    }

    function manage_business_theme($content) {
        //don't filter outside of the loop
        if ( !in_the_loop() )
            return $content;

        $settings = get_option('bd_settings');
        $content .= bd_manage_business_theme(true);
        //  $content .= get_posts_nav_link();

        return $content;
    }

    function login_business_theme($content) {
        //don't filter outside of the loop
        if ( !in_the_loop() )
            return $content;

        $settings = get_option('bd_settings');
        $content .= bd_login_business_theme(true);

        return $content;
    }

    //this is the default theme added to single product listings
    function business_theme($content) {
        global $post;
        $settings = get_option('bd_settings');

        //don't filter outside of the loop
        if ( !in_the_loop() )
            // return $content;

            return $content;
    }

    //this is the default theme added to registration page
    function registration_theme($content) {
        global $post;

        //don't filter outside of the loop
        if ( !in_the_loop() )
            return $content;

        $single_content = bd_business_registration(true);


        return $single_content;
    }

    //this is the default theme added to registration page
    function addbusiness_theme($content) {
        global $post;
        //don't filter outside of the loop
        if ( !in_the_loop() )
            return $content;

        $single_content = bd_add_business(true);
        return $single_content;
    }

    //this is the default theme added to closing page
    function closing_theme($content) {
        global $post;
        //don't filter outside of the loop
        if ( !in_the_loop() )
            return $content;

        $single_content = bd_business_closing(true);
        return $single_content;
    }

    //this is the default theme added to closing page
    function legacy_theme($content) {
        global $post;
        //don't filter outside of the loop
        if ( !in_the_loop() )
            return $content;
        $content .= bd_legacy_account(true);
        return $content;
    }

    //this is the default theme added to closing page
    function resendmail_theme($content) {
        global $post;
        //don't filter outside of the loop
        if ( !in_the_loop() )
            return $content;
        $content .= bd_resendMail(true);
        return $content;
    }

    //ajax cart handling for business frontend
    function business_script() {
        wp_localize_script( 'bd-custom-js', 'BD_VAL',array('validate' => 'true') );
        wp_enqueue_script( 'bd-business-js', $this->plugin_url . 'js/business.js', array('jquery'), $this->version );
        // declare the variables we need to access in js
        wp_localize_script( 'bd-business-js', 'BD_Ajax', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ),'pluginurl' => $this->plugin_url, 'manageurl' => $this->manageurl , 'addburl' => $this->addburl  ) );
    }
} //end class

global $bd;
$bd = new Disruptions();

// END DISRUPTIONS CLASS
function username_taken($user_email){
    $status = false;
    global $wpdb, $bd, $current_user;
    $result = $wpdb->get_var( $wpdb->prepare( "SELECT * FROM ts_usermeta AS L LEFT JOIN ts_users AS M ON( L.user_id = M.ID ) WHERE M.user_email='".$user_email."' AND (`meta_key`='".$wpdb->prefix."capabilities' )" ) );

    if ($result){
        $status = true;
    }else{
        $status = false;
    }

    return $status;
}

function get_businesscheck(){
    global $bd;
    $useremail = trim($_POST["useremail"]);
    // if the username is blank
    if (!$useremail) {
        $reponse = false;
        // $msg = "Please specify an email address";
        // this would live in an external library just to check if the username is taken
    } else if (username_taken($useremail)) {
        $response = false;
        $msg = 'This email is already registered. Want to <a href="'.get_bloginfo('url').'/businesses/login">login</a> ?';

        // this would live in an external library just to check if the username is taken
    } else if (!is_valid_email($useremail)) {
        $response = false;
        $msg = "Doesn't look like a valid email";
        // it's all good
    } else if( !username_taken($useremail) ) {
        $response = true;
        $msg = "<span style='color:#008000;'>Selected email address is available</span>";
    }
    $arr = array( $response,$msg);
    echo implode(',', $arr);
    die();
}

function get_deletepost(){
    global $wpdb, $bd, $post, $wp_query;
    /* Set $myBlogId to the ID of the site you want to query */
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    $pid= $_POST['pid'];
    wp_delete_post($pid);
    die();
}

/** DISRUPTIONS PLUGIN SETTINGS ICON **/
function business_icon() { ?>
<style type="text/css" media="screen">
    #menu-posts-business .wp-menu-image {
        background: url('<?php echo plugin_dir_url( __FILE__."business-disruptions" ); ?>/includes/images/closed-icon.png') no-repeat 6px -26px !important;
    }
    #menu-posts-business:hover .wp-menu-image, #menu-posts-business.wp-has-current-submenu .wp-menu-image {
        background-position:6px 6px !important;
    }
</style>
<?php }
// #icon-edit.icon32-posts--business {background: url(<?php echo plugin_dir_url( __FILE__ ); images/portfolio-32x32.png) no-repeat;}
add_action( 'admin_head',  'business_icon' );

/** NETWORK  SETTINGS ADMIN MENU **/
function bd_network_pages() {
    add_menu_page( 'Business Settings', __('Business Settings', 'bd'), 'business', 'business_settings', 'business_settings', plugin_dir_url( __FILE__."business-disruptions" ).'/includes/images/closed-network-icon.png' );
}
add_action( 'network_admin_menu', 'bd_network_pages' );

/** NETWORK  ADMIN SETTINGS **/
function business_settings() {

    if (isset($_POST['business_settings'])) {
        $value  = $_POST['network_centralbid'];
        update_site_option( 'network_centralbid', $value );
    }

    $content.= '<h2>Business Settings</h2>';
    $content.= '<form id="bd-main-form" method="post" action="">
                                                                                <input type="hidden" name="business_settings" value="1" />
                                                                                   <div class="postbox">
                                                                                                    <h2><span><?php _e("Business Settings", "bd") ?></span></h2>
                                                                                                    <div class="inside" style="margin:0 30px;">';
    $content.='<div> Specify central blog id </div>';
    $content.='<p><input type="text" name="network_centralbid" value="'.get_site_option( 'network_centralbid' ).'"  size="5" /></p>';
    $content.='<p><input type="submit" class="button-primary" name="business_settings" value="submit"/></p>
                                                                                                    </div>                             
                                                                                </div>
                                                            </form>';
    echo $content;
}
add_action( 'network_admin_menu', 'bd_network_icon' );

function bd_network_icon() {
    ?>
<style type="text/css" media="screen">
    li#toplevel_page_business_settings.current  div.wp-menu-image{
        background: url('<?php echo plugin_dir_url( __FILE__."business-disruptions" ); ?>/includes/images/closed-icon.png') no-repeat 6px 6px !important;
    }
    #adminmenu .toplevel_page_business_settings .wp-menu-image img {
        padding: 3px 0 0 1px;
    }
</style>
<?php } ?>