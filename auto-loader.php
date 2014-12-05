<?php
/*
Plugin Name: Business Disruptions
Version: 1.0
Description: Business Disruptions Plugin to create and Maintain disruptions for the business.
Author: S KUMAR
Contributors: S KUMAR
Author URI: http://sandeepthemaster.wordpress.com
*/


if(!function_exists('business_disruptions_class_autoloader')){

    function business_disruptions_class_autoloader($class){
        $this_dir = dirname(__FILE__);
        $dirs = array(
            $this_dir,
            $this_dir.DIRECTORY_SEPARATOR.'admin',
            $this_dir.DIRECTORY_SEPARATOR.'core',
            $this_dir.DIRECTORY_SEPARATOR.'pages',
        );
        foreach ( $dirs as $dir ) {
            if ( file_exists($dir.DIRECTORY_SEPARATOR.$class.'.php') ) {
				
                include_once($dir.DIRECTORY_SEPARATOR.$class.'.php');
                break;
            }
        }
		
        if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.$class.'.php')){
            require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.$class.'.php');
        }
    }

    spl_autoload_register('business_disruptions_class_autoloader');

    Business_Disruptions_Config::init();
    Business_Disruptions_Roles::init();
    Business_Disruptions_Network_Admin::init();
    Business_Disruptions_Site_Admin::init();
    Business_Disruptions_Taxonomy::init();
    if(get_current_blog_id() == Business_Disruptions_Network_Admin::get_central_blog_id()){
        Business_Disruptions_Post_Type::init();
    }
    if(Business_Disruptions_Site_Admin::is_active()){
        Business_Disruptions_Redirects::init();
        Business_Disruptions_Ajax::init();
        Business_Disruptions_User_Admin::init();
        Business_Disruptions_Page_Register_Add::init();
        Business_Disruptions_Page_Mail::init();
        Business_Disruptions_Page_Login::init();
        Business_Disruptions_Page_Manage::init();
        Business_Disruptions_Page_Submit_Closing::init();
        Business_Disruptions_Page_Product_List::init();
        Business_Disruptions_Page_Closing_List::init();
    }
}







