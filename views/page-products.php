<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $wpdb, $blog_id, $current_user;
get_current_user();
$current_blog_id = $blog_id;
$user_id = $current_user->ID;

$site_zipcodes = get_option(Business_Disruptions_Site_Admin::ZIPCODE_OPTION_NAME);
$site_zipcodes = explode(',',$site_zipcodes);
$city_list = self::get_city_list($site_zipcodes);
$cities = array();
// Merging zips on same city names ie. New York
foreach($city_list as $city){
    if(array_key_exists($city['city'],$cities)){
        $cities[$city['city']] = $cities[$city['city']].','.$city['zip'];
    } else {
        $cities[$city['city']] = $city['zip'];
    }
}

$wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
$wpdb->set_prefix($wpdb->base_prefix);
$taxonomy = get_terms('business_type');

$is_editable = is_user_logged_in() ||  is_super_admin();
?>

<div id="bd_disruptionsDiv">
    <div class="title">
        <div class="wrap-left">
            <h1 class="heading title font_face">Closings & Delays</h1>
            <p>Local alerts for the next 48 hours</p>
        </div>
        <div class="input clearfix changeCat bdsearch">
            <div class="business-search">
                <input type="text" name="business-search-text" class="business-search-text" id="business-search-text" value="Search by name or ZIP" />
                <input type="submit" id="searchBusiness" class="button submit-button button-primary business-keyword-search" value="" name="business-search-button" />
            </div>
        </div>
        <div class="clearfix"></div>
        <?php if( is_super_admin() ){ ?>
        <div class="export-option" id="exportdiv">
            <a href="" class="export" title="Export to excel">Export</a>
            <!-- <a href="#" onclick="window.print(); return false;" title="Print businesses" class="print" >Print</a>   -->
        </div>
        <?php } ?>
        <div class="input clearfix changeCat">
            <span class="single_title font_face">Filter By</span>
            <select name="cats" class="changeCats">
                <?php self::get_taxonomy_options($taxonomy)?>
            </select>
            <?php if (sizeof($city_list)) { ?>
            <select name="cat" class="changeCity">
                <option value=""  class="seperator"> City </option>
                <?php foreach( $cities as $city => $zip ) { ?>
                <option value="<?php echo $zip; ?>"><?php echo $city; ?></option>
                <?php  } ?>
            </select>
            <?php }?>
            <?php if( is_super_admin() ) { ?>
            <select name="status" class="changeStatus">
                <option value=""  class="seperator"> Status </option>
                <option value='search_normal_hours'>Normal Hours</option>
                <option value='search_early_dismissal'>Early Dismisal</option>
                <option value='search_delayed'>Delayed</option>
                <option value='search_closed'>Closed</option>
            </select>
            <select name="cdate" class="changeDate">
                <option value="" class="today"> Next 48 Hours </option>
                <option value="today" class="today"> Today </option>
                <option value='tomorrow'>Tomorrow</option>
            </select>
            <?php } ?>
        </div>
    </div>
    <div class="status-container">
        <div class="status-container">
            <div class="status_header">
                <div class="location"><h4>Name</h4></div>
                <div class="status"><h4>Current Status</h4></div>
                <div class="details"><h4>Details</h4></div>
            </div>
            <span id="product_list_wrap" class="default-result">
                <span class="loader"><p>Looking for closings & delays...</p><img src="<?php echo self::plugin_url('resources/images/ajax-loader-listing.gif'); ?>"  alt="Filter" /></span>
            </span>
            <input type="hidden" id="bd_product_list_page" value="<?php echo get_query_var('paged'); ?>" />
            <?php if($is_editable) { ?>
            <div class="sl_right more_links manage-business"><a class="button submit-button button-primary" href="<?php echo self::add_url() ?>">ADD NEW BUSINESS</a></div>
            <div class="sl_right more_links manage-business"><a class="button submit-button button-primary" href="<?php echo self::manage_url() ?>">MANAGE BUSINESSES</a></div>
            <?php } ?>
        </div>
    </div>
</div>
<?php

/*
* Reset database pointer to current blog
*/
$wpdb->blogid = $current_blog_id;
$wpdb->set_prefix( $wpdb->base_prefix );
