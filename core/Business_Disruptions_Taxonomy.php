<?php
/**
 * Register Business Taxonomy
 */

class Business_Disruptions_Taxonomy extends Business_Disruptions_Plugin {

    const POST_TYPE_LABEL_SINGULAR = 'Business';
    const POST_TYPE_LABEL_PLURAL = 'Businesses';
    const TAXONOMY_SLUG = 'business_type';
    const TAXONOMY_TERM = 'business_type';
    const TAXONOMY_TAG = 'business_tag';
    const TAXONOMY_REWRITE = 'businesses/category';
    const TAXONOMY_ARGS_FILTER = 'bd_register_business_type';

    protected function __construct() {
        $this->add_hooks();
    }

    protected function add_hooks() {
        add_action('init', array($this, 'register_business_taxonomy'));
        add_action('init', array($this, 'register_business_categories'));
        add_action('admin_menu', array($this, 'remove_submenus'));
        add_filter('wp_title', array($this, 'wp_title_output'), 19, 3 );
    }

    /*
     * Regsistering Taxonomy
     * Taxonomy object type will be Business Custom Post Type
     *
     */
    public function register_business_taxonomy(){
        $taxonomy_labels = array(
            'name' => _x( Business_Disruptions_Post_Type::POST_TYPE_LABEL_PLURAL, 'taxonomy general name' ),
            'singular_name' => _x( Business_Disruptions_Post_Type::POST_TYPE_LABEL_SINGULAR, 'taxonomy singular name' ),
            'menu_name' => __( Business_Disruptions_Post_Type::POST_TYPE_LABEL_SINGULAR ),
        );
        $taxonomy_args = array(
            'hierarchical' => true,
            'labels' => $taxonomy_labels,
            'rewrite' => self::TAXONOMY_REWRITE
        );

        $taxonomy_args = apply_filters(self::TAXONOMY_ARGS_FILTER,$taxonomy_args);

        register_taxonomy(
            self::TAXONOMY_SLUG,
            Business_Disruptions_Post_Type::POST_TYPE_SLUG,
            $taxonomy_args
        );
    }

    public function register_business_categories(){
        $parent_term = term_exists(self::TAXONOMY_TERM, self::TAXONOMY_SLUG ); // array is returned if taxonomy is given
        $parent_term_id = $parent_term['term_id']; // get numeric term id
        $terms = array(
            'closings-schools' => 'Schools',
            'closings-government-offices' => 'Government',
            'closings-business' => 'Business',
            'closings-religious-organizations' => 'Religious Organizations',
            'closings-civic-organizations' => 'Civic Organizations',
            'closings-misc' => 'Miscellaneous',
        );
        foreach($terms as $term => $label){
            if(!term_exists($term)){
                wp_insert_term(
                    $label,
                    self::TAXONOMY_SLUG,
                    array(
                        'slug' => $term,
                        'parent'=> $parent_term_id
                    )
                );
            }
        }
    }

    public function remove_submenus(){
        if (get_current_blog_id() != Business_Disruptions_Network_Admin::get_central_blog_id()){
            remove_submenu_page('edit.php?post_type=business', 'edit-tags.php?taxonomy=business_type&amp;post_type=business');
        }
    }

    public function wp_title_output($title){
        $query_var = get_query_var('taxonomy');
        if($query_var == Business_Disruptions_Taxonomy::TAXONOMY_SLUG){
            $term = get_term_by('slug', $query_var, Business_Disruptions_Taxonomy::TAXONOMY_SLUG);
            $title = sprintf( self::__('%s'), $term->name );
        }

        //TODO Remove Tag Code - business_tag not declared anywhere in code
        if($query_var == Business_Disruptions_Taxonomy::TAXONOMY_TAG){
            $term = get_term_by('slug', $query_var, Business_Disruptions_Taxonomy::TAXONOMY_TAG);
            $title = sprintf( self::__('Business Tag: %s'), $term->name );
        }

        return $title;
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