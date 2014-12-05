<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $wpdb, $blog_id, $current_user;
get_current_user();
$current_blog_id = $blog_id;
$user_id = $current_user->ID;
$wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
$wpdb->set_prefix( $wpdb->base_prefix );

if(isset($_POST[self::$form_submit]) && !empty(self::$completion_message)) {
    echo self::$completion_message;
    return null;
}
$is_registration = (self::$query_page_name == self::REGISTER_PAGE_NAME) ? true : false;
$is_add = (self::$query_page_name == self::ADD_PAGE_NAME) ? true : false;
$is_editable = false;
$submit = 'SUBMIT';

if($_GET[self::POSTID]){
    $options = self::load_business_data($_GET[self::POSTID]);
    if($options['is_owner']){
        $is_editable = true;
        $submit = 'SUBMIT CHANGES';
    }
} else {
    $options = self::assign_http_post($_POST);
}

/*
* Reset database pointer to current blog
*/
$wpdb->blogid = $current_blog_id;
$wpdb->set_prefix( $wpdb->base_prefix );

?>
<div id="addBusiness">
    <div class="ab_header">
        <h2><?php echo ($is_editable) ? 'EDIT' : 'ADD';?> YOUR BUSINESS </h2>
    </div>
    <?php if($is_registration){?>
    <p>Welcome! You can register your business by completing the simple form below. You will be able to easily submit closings/delays after you complete this quick registration process.</p>
    <?php }?>
    <form id="registration_form" class="<?php echo ($is_add) ? 'add-business-style' : '';?> login-registration validate clearfix" action="" method="post" enctype="multipart/form-data" onsubmit="javascript:void(0);">
        <?php
        wp_nonce_field(self::$nonce_name, self::$nonce_field);
        include_once(self::plugin_path('views/modules/input-name.php'));
        if($is_registration){
            include_once(self::plugin_path('views/modules/input-email.php'));
            include_once(self::plugin_path('views/modules/input-passwords.php'));
        }
        include_once(self::plugin_path('views/modules/input-address-city.php'));
        include_once(self::plugin_path('views/modules/select-state.php'));
        include_once(self::plugin_path('views/modules/input-zip.php'));
        include_once(self::plugin_path('views/modules/input-alternate.php'));
        ?>

        <div class="input clearfix"></div>

        <?php
        include_once(self::plugin_path('views/modules/select-taxonomy.php'));
        include_once(self::plugin_path('views/modules/input-days.php'));
        ?>

        <?php if($is_editable){?>
        <input id="editmode" type="hidden" value="1" type="text" name="<?php echo self::EDITMODE ;?>" />
        <input type="hidden" value="<?php echo $_GET[self::POSTID]; ?>" type="text" name="<?php echo self::POSTID; ?>" />
        <?php }?>

        <input id="checkmode" type="hidden" value="<?php echo $options[self::DIFFICULTY] ;?>" name="checkmode">
        <input class="normalOadvance" type="hidden" value="<?php echo $options[self::DIFFICULTY] ;?>" name="<?php echo self::DIFFICULTY; ?>" />

        <div id="timingDiv" class="input clearfix">
            <label class="opentimeTxt" for="opening-time">Opening Time<span>*</span></label>

            <div id="advancedDiv" class="clearfix" <?php self::hide($options[self::DIFFICULTY],'1'); ?>>Switch to Advanced</div>
            <div id="normalDiv" <?php self::display($options[self::DIFFICULTY],'1'); ?>>Switch to Normal</div>
            <?php include_once(self::plugin_path('views/modules/select-hours.php'));?>
            <div class="submitDiv">
                <input type="submit" id="regBusiness" class="button submit-button button-primary" value="<?php echo $submit; ?>" name="<?php echo self::$form_submit ;?>" tabindex="13"/>
                <span class="more_links"><a class="button submit-button button-primary" href="<?php echo self::closing_url(); ?>">CANCEL</a></span>
            </div>
        </div>
    </form>
</div>