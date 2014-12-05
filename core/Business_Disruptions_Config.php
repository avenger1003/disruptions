<?php
/**
 * Business Disruptions Config
 */

class Business_Disruptions_Config extends Business_Disruptions_Plugin {

    const VERSION_OPTION_NAME = 'bd_version';
    const VERSION = '2';
    const SIDEBAR_SLUG = 'closings-page';
    const SIDEBAR_LABEL = 'Closings Pages';


    protected function __construct() {
        $this->add_hooks();
        $this->configure();
    }

    protected function add_hooks() {
        add_action('wp_loaded', array($this, 'register_sidebars') );
    }

    public function register_sidebars(){
        register_sidebar(
            array(
                'name'          => self::SIDEBAR_LABEL,
                'id'            => self::SIDEBAR_SLUG,
                'description'   => 'This sidebar is used for the closings page.',
                'before_widget' => '<div id="%1$s" class="widget widget_bg %2$s clearfix"><div class="widget_int_wrap">',
                'after_widget' => '<div class="widget_floor widget_bg">&nbsp;</div></div></div>',
                'before_title' => '<div class="widget_header clearfix"><h4 class="widget_title font_face">',
                'after_title' => '</h4></div>'
            )
        );
    }

    public function configure(){
            if(is_multisite()){
                 $path = get_blog_option(1,self::VERSION_OPTION_NAME,false);
            }else{
                 $path = get_option(self::VERSION_OPTION_NAME);
            }
        $current_version = $path;
        // if(!$current_version || $current_version != self::VERSION){
        //     update_blog_option(1,self::VERSION_OPTION_NAME,self::VERSION);
        // }
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