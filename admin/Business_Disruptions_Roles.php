<?php
/**
 * Business Disruptions Roles
 */

class Business_Disruptions_Roles extends Business_Disruptions_Plugin {

    const ROLE_SLUG = 'business';
    const ROLE_LABEL = 'Business';

    const ACTIVATION_SLUG = 'bd_activation';
    const PASS_SLUG = 'bdpwd';
    const MEMBER_OF_SLUG = 'user_member_of';
    const MEMBER_ID = 'business';

    protected function __construct() {
        $this->add_hooks();
    }

    protected function add_hooks() {
        add_action('admin_init', array($this, 'register_user_role'));
    }

    public function register_user_role(){
        global $wp_roles;
        if ( ! isset( $wp_roles ) )
            $wp_roles = new WP_Roles();
        /*
         * Creating new role with admin capabilities.
         * The business will be restricted access to wp-admin and will be to closings page using wp_redirect
         */
        $business_capabilities = array(
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

        $wp_roles->add_role(self::ROLE_SLUG, self::ROLE_LABEL, $business_capabilities);
    }

    public static function add_role(){
        global $current_user;
        get_currentuserinfo();
        if( is_user_logged_in()){
            $memberof = get_user_meta($current_user->ID, self::MEMBER_OF_SLUG, true);
            if(in_array(self::MEMBER_ID,(array)$memberof)){
                $current_user->add_role(self::ROLE_SLUG);
                return true;
            }
        }
        return false;
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