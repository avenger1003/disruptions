<?php
/**
 * Register Business Post Type
 */

class Business_Disruptions_Post_Type extends Business_Disruptions_Plugin {

    const POST_TYPE_SLUG = 'business';
    const POST_TYPE_LABEL_SINGULAR = 'Business';
    const POST_TYPE_LABEL_PLURAL = 'Businesses';
    const POST_TYPE_ARGS_FILTER = 'bd_register_post_type';

    private static $post_type_support = array(
        'title',
        'editor',
        'author',
        'excerpt',
        'revisions',
        'thumbnail'
    );

    protected function __construct() {
        $this->add_hooks();
    }

    protected function add_hooks() {
        add_action('init', array($this, 'register_business_post_type'));
        add_action('admin_menu', array($this, 'unset_business_submenus'));
    }

    public function register_business_post_type(){
        $settings = get_option(Business_Disruptions_Plugin::SETTINGS_OPTION_NAME);

        // Register custom closing post type
        $args = array (
            'labels' => array('name' => __(self::POST_TYPE_LABEL_PLURAL, self::TEXT_DOMAIN),
                'singular_name' => __('View '.self::POST_TYPE_LABEL_SINGULAR, self::TEXT_DOMAIN),
                'add_new' => __('Add New '.self::POST_TYPE_LABEL_SINGULAR, self::TEXT_DOMAIN),
                'add_new_item' => __('Create New '.self::POST_TYPE_LABEL_SINGULAR, self::TEXT_DOMAIN),
                'edit_item' => __('Edit '.self::POST_TYPE_LABEL_SINGULAR, self::TEXT_DOMAIN),
                'edit' => __('Edit', self::TEXT_DOMAIN),
                'new_item' => __('New '.self::POST_TYPE_LABEL_SINGULAR, self::TEXT_DOMAIN),
                'view_item' => __('View '.self::POST_TYPE_LABEL_SINGULAR, self::TEXT_DOMAIN),
                'search_items' => __('Search '.self::POST_TYPE_LABEL_PLURAL, self::TEXT_DOMAIN),
                'not_found' => __('No '.self::POST_TYPE_LABEL_SINGULAR.' Found', self::TEXT_DOMAIN),
                'not_found_in_trash' => __('No '.self::POST_TYPE_LABEL_SINGULAR.' Found in Trash', self::TEXT_DOMAIN),
                'view' => __('View '.self::POST_TYPE_LABEL_SINGULAR, self::TEXT_DOMAIN)
            ),
            'description' => __(self::POST_TYPE_LABEL_PLURAL.' for your Site.', self::TEXT_DOMAIN),
            'public' => true,
            'show_ui' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => array('slug' => $settings['slugs']['business']),
            'query_var' => true,
            'supports' => self::$post_type_support,
        );
        register_post_type(self::POST_TYPE_SLUG, apply_filters(self::POST_TYPE_ARGS_FILTER, $args ) );
    }

    public function unset_business_submenus(){
        global $submenu;
        unset($submenu['edit.php?post_type='.self::POST_TYPE_SLUG][5]);
        unset($submenu['edit.php?post_type='.self::POST_TYPE_SLUG][10]);
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