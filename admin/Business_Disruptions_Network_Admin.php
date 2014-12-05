<?php
/**
 * Business Disruptions Network Admin
 */

class Business_Disruptions_Network_Admin extends Business_Disruptions_Plugin {

    const SLUG = 'closings_network_settings';
    const NONCE_ACTION = 'save-closings-network-settings';
    const NONCE_NAME = 'save-closings-network-settings-nonce';
    const GENERAL_NETWORK_SETTINGS = 'closings_general_network_settings';
    const CENTRAL_BLOG_OPTION_NAME = 'bd_central_blog_id';
    const LISTINGS_PER_PAGE_OPTION_NAME = 'bd_listings_per_page';

    protected function __construct() {
        $this->add_hooks();
    }

    protected function add_hooks() {
        add_action('network_admin_menu', array($this, 'register_network_admin_page'), 10, 0);
        add_action('network_admin_edit_'.self::SLUG, array($this, 'save_network_settings_page'), 10, 0);
    }

    /**
     * Register the admin page and the settings to display on it
     *
     * @return void
     */
    public function register_network_admin_page() {
        add_menu_page(
            self::__('Business Disruptions Network Settings'),
            self::__('Business Settings'),
            Business_Disruptions_Roles::ROLE_SLUG,
            self::SLUG,
            array($this, 'display_network_settings_page'),
            self::plugin_url('resources/images/closed-network-icon.png')
        );

        add_settings_section(
            self::GENERAL_NETWORK_SETTINGS,
            self::__('General Settings'),
            array($this, 'display_settings_section'),
            self::SLUG
        );

        add_settings_field(
            self::CENTRAL_BLOG_OPTION_NAME,
            self::__('Closings Central Site'),
            array($this, 'display_central_blog_id_field'),
            self::SLUG,
            self::GENERAL_NETWORK_SETTINGS
        );

        add_settings_field(
            self::LISTINGS_PER_PAGE_OPTION_NAME,
            self::__('Closings Per Page'),
            array($this, 'display_per_page_field'),
            self::SLUG,
            self::GENERAL_NETWORK_SETTINGS
        );
    }


    /**
     * Display the general settings page
     *
     * @return void
     */
    public function display_network_settings_page() {
        $title = self::__('Business Disruptions Network Settings');
        include( self::plugin_path('views/settings-network.php') );
    }

    /**
     * Save the settings defined in register_network_admin_page()
     *
     * @see Business_Disruptions_Network_Admin::register_network_admin_page()
     * @return void
     */
    public function save_network_settings_page() {
        // settings API doesn't work at the network level, so we save it ourselves
        if ( !isset($_POST[self::NONCE_NAME]) || !wp_verify_nonce($_POST[self::NONCE_NAME], self::NONCE_ACTION) ) {
            return;
        }

        $this->save_network_settings_fields();

        wp_redirect(add_query_arg(array('page' => self::SLUG, 'updated' => 'true'), network_admin_url('admin.php')));
        exit();
    }

    /**
     * Display the prefix to a settings section.
     *
     * @see add_settings_section()
     * @return void
     */
    public function display_settings_section() {
        // We don't need to do anything here. add_settings_section() just requires a valid callback
    }

    /**
     * Display a select box to choose the central closings site
     *
     * @return void
     */
    public function display_central_blog_id_field() {
        $options = self::get_blog_list();
        $central_blog_id = self::get_central_blog_id();
        echo '<select name="'.self::CENTRAL_BLOG_OPTION_NAME.'">';
        foreach ( $options as $blog_id => $blog_label ) {
            echo '<option value="'.$blog_id.'"'.selected($blog_id, $central_blog_id, FALSE).'>'.$blog_label.'</option>';
        }
        echo '</select>';
        echo '<br />';
        echo '<small>'.self::__('Select the blog to use as the Central Closings Site.').'</small>';
    }

    /**
     * Display a input to set the number of business closings per page
     *
     * @return void
     */
    public function display_per_page_field() {
        $per_page = self::get_listings_per_page();
        echo '<input type="text" name="'.self::LISTINGS_PER_PAGE_OPTION_NAME.'" value="'.$per_page.'" size="5" />';
        echo '<br />';
        echo '<small>'.self::__('Number of closing listings  to show per page .').'</small>';
    }

    /**
     * Save the network settings fields
     *
     * @return void
     */
    private function save_network_settings_fields() {
        //Save central blog id
        $central_blog_id = (int)$_POST[self::CENTRAL_BLOG_OPTION_NAME];
        if($central_blog_id && get_blog_details($central_blog_id)) {
            update_blog_option(1, self::CENTRAL_BLOG_OPTION_NAME, $central_blog_id);
        }

        //Save listings per page
        if(is_numeric($_POST[self::LISTINGS_PER_PAGE_OPTION_NAME])) {
            update_blog_option(1, self::LISTINGS_PER_PAGE_OPTION_NAME, (int)$_POST[self::LISTINGS_PER_PAGE_OPTION_NAME]);
        }
    }

    public static function get_central_blog_id(){
         if(is_multisite()){
                 $path = get_blog_option(1,self::CENTRAL_BLOG_OPTION_NAME,false);
            }else{
                 $path = get_option(self::CENTRAL_BLOG_OPTION_NAME);
            }
        $central_blog_id =  $path;
        // $central_blog_id =  (get_blog_option(1, self::CENTRAL_BLOG_OPTION_NAME, false))? get_blog_option(1, self::CENTRAL_BLOG_OPTION_NAME, false) : 1;(get_blog_option(1, self::CENTRAL_BLOG_OPTION_NAME, false))? get_blog_option(1, self::CENTRAL_BLOG_OPTION_NAME, false) : 1;
        return $central_blog_id;
    }

    public function get_listings_per_page(){
        $per_page = (get_blog_option(1, self::LISTINGS_PER_PAGE_OPTION_NAME, false))? get_blog_option(1, self::LISTINGS_PER_PAGE_OPTION_NAME, false) : 5;
        return $per_page;
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