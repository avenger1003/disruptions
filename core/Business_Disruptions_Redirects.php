<?php
/**
 * Business Disruptions Redirects
 */

class Business_Disruptions_Redirects extends Business_Disruptions_Plugin {

    protected $business_pages = array();

    protected function __construct() {
        $this->add_hooks();
    }

    protected function add_hooks() {
        add_filter('generate_rewrite_rules', array($this,'add_rewrite_rules') );
    }

    public function add_rewrite_rules($wp_rewrite){

        $new_rules = array();

        $new_rules['closings/?$'] = 'index.php?&pagename=product-list';
        $new_rules['closings/page/?([0-9]{1,})/?$'] = 'index.php?&pagename=product-list&paged=$matches[1]';
        $new_rules['businesses/(.*)/?([0-9]{1,})/(.*)/?$'] = 'index.php?&pagename=closing-list&slug=$matches[3]';
        $new_rules['businesses/add-business/?$'] = 'index.php?&pagename=add-business';
        $new_rules['businesses/legacy-migration/?$'] = 'index.php?&pagename=legacy-migration';
        $new_rules['businesses/login/?$'] = 'index.php?&pagename=login';
        $new_rules['businesses/resend-mail/?$'] = 'index.php?&pagename=resend-mail';
        $new_rules['businesses/manage/?$'] = 'index.php?&pagename=manage';
        $new_rules['businesses/registration/?$'] = 'index.php?&pagename=registration';
        $new_rules['businesses/submit-closings/?$'] = 'index.php?&pagename=submit-closings';

        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
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