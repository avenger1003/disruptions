<?php
/**
 * Business Disruptions Site Admin
 */

class Business_Disruptions_Site_Admin extends Business_Disruptions_Plugin {

    const SETTINGS_MENU_SLUG = 'options-general.php';
    const POST_MENU_SLUG = 'edit.php?post_type=business';
    const SUBMENU_SLUG = 'business';
    const SETTINGS_SUBMENU_LABEL = 'Business Disruptions';
    const POST_SUBMENU_LABEL = 'Settings';
    const ADMIN_CAPABILITIES = 'edit_others_posts';

    const IS_ACTIVE_OPTION_NAME = 'bd_active';
    const ZIPCODE_OPTION_NAME = 'bd_siteZipcode';
    const LEGACY_OPTION_NAME = 'bd_legacycheck';

    protected function __construct() {
        $this->add_hooks();
    }

    protected function add_hooks() {
        add_action('admin_menu', array($this, 'register_site_admin_page') );
        add_action('admin_head', array($this, 'site_admin_icon') );
    }

    public function register_site_admin_page(){
        add_submenu_page(
            self::SETTINGS_MENU_SLUG,
            self::__(self::SETTINGS_SUBMENU_LABEL),
            self::__(self::SETTINGS_SUBMENU_LABEL),
            self::ADMIN_CAPABILITIES,
            self::SUBMENU_SLUG,
            array($this, 'display_settings_page')
        );
    }

    public function display_settings_page(){
          //Flush rewrites whenever plugin is activated or deactivated
//        if (isset($_POST[self::IS_ACTIVE_OPTION_NAME]) && $_POST[self::IS_ACTIVE_OPTION_NAME] == self::is_active()) {
//            Business_Disruptions_Redirects::flush_rewrite();
//        }
        include( self::plugin_path('views/settings-site.php') );
    }

    public function site_admin_icon(){
        include(self::plugin_path('views'.DIRECTORY_SEPARATOR.'admin_styles.php'));
    }

    public static function is_active(){
        return get_option(self::IS_ACTIVE_OPTION_NAME);
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