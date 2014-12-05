<?php
/**
 * The base class for the Business Disruptions Plugin. All
 * classes in the plugin should inherit from this class,
 * and may share in its utility functions.
 */
class Business_Disruptions_Plugin {

    const TEXT_DOMAIN = 'Business_Disruptions_Plugin';
    const SETTINGS_OPTION_NAME = 'bd_settings';
    const BASE_PREFIX = 'ts_';

    const ZIPCODE_TABLE = 'ts_zipcodes';
    const ZIP_FIELD = 'zip';
    const TIMEZONE_TABLE = 'ts_timezone';
    const TIMEZONE_FIELD = 'timezone';

    const DEBUG = FALSE;

    /**
     * Return $string after translating it with the plugin's text domain
     * @static
     * @param string $string
     * @return string|void
     */
    protected static function __( $string ) {
        return __($string, self::TEXT_DOMAIN); exit();
    }

    /**
     * Echo $string after translating it with the plugin's text domain
     *
     * @static
     * @param string $string
     * @return void
     */
    protected static function _e( $string ) {
        _e($string, self::TEXT_DOMAIN);
    }

    /**
     * Get the absolute system path to the plugin directory, or a file therein
     * @static
     * @param string $path
     * @return string
     */
    protected static function plugin_path( $path ) {
        $base = dirname(__FILE__);
        if ( $path ) {
            return trailingslashit($base).$path;
        } else {
            return untrailingslashit($base);
        }
    }

    /**
     * Get the absolute URL to the plugin directory, or a file therein
     * @static
     * @param string $path
     * @param null $version
     * @return string
     */
    protected static function plugin_url( $path, $version = NULL ) {
        $path = plugins_url($path, __FILE__);
        if ( !is_null($version) ) {
            $path = add_query_arg(array('version' => $version), $path);
        }
        return $path;
    }

    public static function get_blog_list() {
        /** @var wpdb $wpdb */
        global $wpdb, $current_site;
        $query = "SELECT blog_id, domain, path FROM {$wpdb->blogs} ";
        if ( is_subdomain_install() ) {
            $query .= "ORDER BY domain";
        } else {
            $query .= "ORDER BY path";
        }
        $blogs = $wpdb->get_results($wpdb->prepare($query,$wpdb->blogs));
        $list = array();
        foreach ( $blogs as $blog ) {
            $blogname = is_subdomain_install() ? str_replace( '.'.$current_site->domain, '', $blog->domain ) : $blog->path;
            $list[$blog->blog_id] = $blogname;
        }
        return $list;
    }

    /**
     * Get the name of the blog
     *
     * @param int $blog_id
     * @return string
     */
    public static function get_blog_name( $blog_id ) {
        if ( function_exists('get_townsquare_callsign') ) {
            return get_townsquare_callsign($blog_id);
        }

        // fallback if the townsquare_callsign plugin isn't active
        static $stations = array();
        if ( isset($stations[$blog_id]) ) {
            return $stations[$blog_id];
        }
        $bloginfo = get_blog_details($blog_id);
        if ( !$bloginfo ) {
            $stations[$blog_id] = '';
        } else {
            $stations[$blog_id] = $bloginfo->blogname;
        }
        return $stations[$blog_id];
    }

    /**
     * Replace &amp; with &#038; in guids
     *
     * @static
     * @param string $guid
     * @return string
     */
    public static function normalize_guid_entities( $guid ) {
        $guid = str_replace('&amp;', '&#038;', $guid);
        return $guid;
    }
    /**
     * This function clears the rewrite rules and forces them to be regenerated
     *
     * @static
     */
    public static function flush_rewrite() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    public static function encode_base64($sData){
        $sBase64 = base64_encode($sData);
        return strtr($sBase64, '+/', '-_');
    }

    public static function decode_base64($sData){
        $sBase64 = strtr($sData, '-_', '+/');
        return base64_decode($sBase64);
    }

    /**
     * Get address data from a zip code
     *
     * Sample Query:
     *
     * SELECT * FROM
     * FROM ts_zipcodes
     * WHERE zip = '10003'
     *
     * @static
     * @param string $zip
     * @return array Returns an array with address information
     */
    public static function get_user_zip_data($zip){
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM ".self::ZIPCODE_TABLE." WHERE ".self::ZIP_FIELD."='".$zip."'",ARRAY_A);
    }

    /**
     * Get timezone data from a zip code
     *
     * Sample Query:
     *
     * SELECT ts_timezone.*
     * FROM ts_zipcodes
     * LEFT JOIN ts_timezone
     * ON ts_zipcodes.timezone = ts_timezone.timezone
     * WHERE ts_zipcodes.zip = '10003'
     *
     * @static
     * @param string $zip
     * @return array Returns an array with timezone data
     */
    public static function get_user_time_zone($zip){
        global $wpdb;
        return $wpdb->get_row("SELECT ".self::TIMEZONE_TABLE.".* FROM ".self::ZIPCODE_TABLE." LEFT JOIN ".self::TIMEZONE_TABLE. " ON ".self::ZIPCODE_TABLE.".".self::TIMEZONE_FIELD."=".self::TIMEZONE_TABLE.".".self::TIMEZONE_FIELD." WHERE ".self::ZIPCODE_TABLE.".".self::ZIP_FIELD."='".$zip."'",ARRAY_A);
    }

    public static function get_city_list($zips){
        if(!is_array ($zips)) return false;
        $zips = implode(',', $zips);
        global $wpdb;
        return $wpdb->get_results("SELECT ".self::ZIPCODE_TABLE.".zip,".self::ZIPCODE_TABLE.".city FROM ".self::ZIPCODE_TABLE." WHERE ".self::ZIPCODE_TABLE.".zip IN (".$zips.") ORDER BY ".self::ZIPCODE_TABLE.".city ASC",ARRAY_A);
    }

    public static function get_taxonomy_terms(){
        global $wpdb,$blog_id;
        $current_blog_id = $blog_id;
        $wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
        $wpdb->set_prefix( $wpdb->base_prefix );

        $termtable = "SELECT DISTINCT * FROM " . $wpdb->prefix . "terms LEFT JOIN " . $wpdb->prefix . "term_taxonomy ON ( " . $wpdb->prefix . "terms.term_id = " . $wpdb->prefix . "term_taxonomy.term_id ) WHERE taxonomy=%s ORDER BY " . $wpdb->prefix . "terms.name  ASC";
        $taxonomy = $wpdb->get_results($wpdb->prepare($termtable,'business_type'),ARRAY_A);

        $wpdb->blogid = $current_blog_id;
        $wpdb->set_prefix( $wpdb->base_prefix );

        return $taxonomy;
    }

    /*
     * Converts one time zone to another
     *
     * @static
     * @param string $date_time the date and time
     * @param string $timezone_a The timezone a
     * @param string $timezone_b The timezone b
     * @return string Returns timestamp converted to timezone b
     */
    public static function convert_time_zone_to_zone($date_time,$timezone_a,$timezone_b){

        $system_timezone = date_default_timezone_get();

        date_default_timezone_set($timezone_a);
        $date_a = date("m/d/Y h:i:s A");

        date_default_timezone_set($timezone_b);
        $date_b = date("m/d/Y h:i:s A");

        date_default_timezone_set("GMT");
        $GMT = date("m/d/Y h:i:s A");

        date_default_timezone_set($system_timezone);

        $diff1 = (strtotime($GMT) - strtotime($date_a));
        $diff2 = (strtotime($date_b) - strtotime($GMT));

        $date_time = new DateTime($date_time);
        $date_time->modify("+$diff1 seconds");
        $date_time->modify("+$diff2 seconds");
        $date_time = $date_time->format("m/d/Y H:i:s");
        return $date_time;
    }

    public static function display($val,$match){
        $match = explode(',',$match);
        if(in_array($val,$match)) {
            echo ' style="display:block"';
        } else {
            echo ' style="display:none"';
        }
    }

    public static function hide($val,$match){
        $match = explode(',',$match);
        if(in_array($val,$match)) {
            echo ' style="display:none"';
        } else {
            echo ' style="display:block"';
        }
    }

    public static function truncate($text,$limit,$append='',$elipse='...') {
        $chars_limit = $limit;
        $chars_text = strlen($text);
        $text .= ' ';
        $text = substr($text,0,$chars_limit);
        $text = substr($text,0,strrpos($text,' '));

        if ($chars_text > $chars_limit) $text .= $elipse;
        if ($chars_text > $chars_limit && !empty($append)) $text .= '<br/>'.$append;
        return $text;
    }
}
