<?php
global $squery;
function update_options(){
    global $bd;
    $settings = get_option('bd_settings');
    $businessurl = home_url( $settings['slugs']['business'] .'/');
    update_option('businessurl',$businessurl);
    $bclosingsurl = home_url( $settings['slugs']['closings'] .'/');
    update_option('bclosingsurl',$bclosingsurl);
    $closingurl = home_url( $settings['slugs']['business'] . '/' . $settings['slugs']['submit-closings'].'/');
    update_option('closingurl',  $closingurl);
    $resendmailurl = home_url( $settings['slugs']['business'] . '/' . $settings['slugs']['resend-mail'].'/');
    update_option('resendmailurl',  $resendmailurl);
    $regurl = home_url( $settings['slugs']['business'] . '/' . $settings['slugs']['registration'].'/');
    update_option('regurl',  $regurl);
    $legacyRegurl = home_url( $settings['slugs']['business'] . '/' . $settings['slugs']['legacy'].'/');
    update_option('legacyRegurl',  $legacyRegurl);
    $manageurl = home_url( $settings['slugs']['business'] . '/' . $settings['slugs']['manage'].'/');
    update_option('manageurl',  $manageurl);
    $addburl = home_url( $settings['slugs']['business'] . '/' . $settings['slugs']['addbusiness'].'/');
    update_option('addburl',  $addburl);
    $loginurl = home_url( $settings['slugs']['business'] . '/' . $settings['slugs']['login'].'/');
    update_option('loginurl',  $loginurl);
    $categoryurl = home_url( $settings['slugs']['business'] . '/' . $settings['slugs']['category'].'/');
    update_option('categoryurl',  $categoryurl);
}
update_options();

function bd_business_registration($echo=true){
    global $wpdb, $bd, $current_user;
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    get_currentuserinfo();

    if ( is_user_logged_in() ){
        wp_redirect(get_option('addburl'));
    }

    if(isset($_POST['register_bform_submit'])){
        require_once('bd_save_regsitration.php');
    }
    ?>
<div id="addBusiness">
    <div class="ab_header"><h2>ADD YOUR BUSINESS </h2>
        <p>Welcome! You can register your business by completing the simple form below. You will be able to easily submit closings/delays after you complete this quick registration process.</p>
    </div>
    <form id="registration_form" class="login-registration validate clearfix" action="" method="post" enctype="multipart/form-data" onsubmit="return validatebusinessForm();">
        <div class="input clearfix"><label for="display_name">Business Name<span>*</span></label><input type="text" name="business_name" id="business_name" class="" value="<?php echo $post_title ?>"  tabindex="1"/></div>
        <div class="input clearfix"><label for="user_email">Business Email<span>*</span></label><input autocomplete="off" type="text" name="business_email" id="user_login" class="" value="<?php echo $usermail ?>" tabindex="2"/><span id="validateUsername"><?php if ($error) { echo $error["msg"]; } ?></span></div>
        <div class="input clearfix"><label for="pass1">Password<span>*</span></label><input autocomplete="off" type="password" name="pass1" id="pass1" class="validate valid-required pass1" value="" tabindex="3"/></div>
        <div class="input clearfix"><label for="pass2">Confirm Password<span>*</span></label><input autocomplete="off" type="password" name="pass2" id="pass2" class="validate valid-required" value="" tabindex="4"/></div>
        <div id="pass-strength-result">Strength indicator</div><p class="description indicator-hint">Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ & )</p>
        <div class="input add-code clearfix"><label for="business_address">Address<span>*</span></label><input type="text" name="business_address" id="bd_address" class="" value="<?php echo $addr ?>"  tabindex="5"/></div>
        <div class="input add-code clearfix" id="formcity"><label for="city">City<span>*</span></label><input type="text" name="city" class="" id="bd_city" value="<?php echo $city ?>" tabindex="6"/></div>

        <div id="formstate" class="clearfix"><label for="city">State<span>*</span></label><select type="text" name="state" id="station-info-state" class="store-custom-select regselect">
            <option value="AL" <?php echo ( $poststate =="AL" )? "selected='selected'" :" "; ?>>Alabama</option>
            <option value="AK" <?php echo ( $poststate =="AK" )? "selected='selected'" :" "; ?>>Alaska</option>
            <option value="AZ" <?php echo ( $poststate =="AZ" )? "selected='selected'" : " "; ?>>Arizona</option>
            <option value="AR" <?php echo ( $poststate =="AR" )? "selected='selected'" : " "; ?>>Arkansas</option>
            <option value="CA" <?php echo ( $poststate =="CA" )? "selected='selected'" : " "; ?>>California</option>
            <option value="CO" <?php echo ( $poststate =="CO" )? "selected='selected'" : " "; ?>>Colorado</option>
            <option value="CT" <?php echo ( $poststate =="CT" )? "selected='selected'" : " "; ?>>Connecticut</option>
            <option value="DE" <?php echo ( $poststate =="DE" )? "selected='selected'" : " "; ?>>Delaware</option>
            <option value="DC" <?php echo ( $poststate =="DC" )? "selected='selected'" : " "; ?>>District Of Columbia</option>
            <option value="FL" <?php echo ( $poststate =="FL" )? "selected='selected'" : " "; ?>>Florida</option>
            <option value="GA" <?php echo ( $poststate =="GA" )? "selected='selected'" : " "; ?>>Georgia</option>
            <option value="HI" <?php echo ( $poststate =="HI" )? "selected='selected'" : " "; ?>>Hawaii</option>
            <option value="ID" <?php echo ( $poststate =="ID" )? "selected='selected'" : " "; ?>>Idaho</option>
            <option value="IL" <?php echo ( $poststate =="IL" )? "selecte='selected'" : " "; ?>>Illinois</option>
            <option value="IN" <?php echo ( $poststate =="IN" )? "selected='selected'" : " "; ?>>Indiana</option>
            <option value="IA" <?php echo ( $poststate =="IA" )? "selected='selected'" : " "; ?>>Iowa</option>
            <option value="KS" <?php echo ( $poststate =="KS" )? "selected='selected'" :" "; ?>>Kansas</option>
            <option value="KY" <?php echo ( $poststate =="KY" )? "selected='selected'" : " "; ?>>Kentucky</option>
            <option value="LA" <?php echo ( $poststate =="LA" )? "selected='selected'" : " "; ?>>Louisiana</option>
            <option value="ME" <?php echo ( $poststate =="ME" )? "selected='selected'"  : " "; ?>>Maine</option>
            <option value="MD" <?php echo ( $poststate =="MD" )? "selected='selected'" : " "; ?>>Maryland</option>
            <option value="MA" <?php echo ( $poststate =="MA" )? "selected='selected'" : " "; ?>>Massachusetts</option>
            <option value="MI" <?php echo ( $poststate =="MI" )? "selected='selected'" : " "; ?>>Michigan</option>
            <option value="MN" <?php echo ( $poststate =="MN" )? "selected='selected'": " "; ?>>Minnesota</option>
            <option value="MS" <?php echo ( $poststate =="MS" )? "selected='selected'" : " "; ?>>Mississippi</option>
            <option value="MO" <?php echo ( $poststate =="MO" )? "selected='selected'" : " "; ?>>Missouri</option>
            <option value="MT" <?php echo ( $poststate =="MT" )? "selected='selected'" : " "; ?>>Montana</option>
            <option value="NE" <?php echo ( $poststate =="NE" )? "selected='selected'" : " "; ?>>Nebraska</option>
            <option value="NV" <?php echo ( $poststate =="NV" )? "selected='selected'" : " "; ?>>Nevada</option>
            <option value="NH" <?php echo ( $poststate =="NH" )? "selected='selected'" : " "; ?>>New Hampshire</option>
            <option value="NJ" <?php echo ( $poststate =="NJ" )? "selected='selected'" : " "; ?>>New Jersey</option>
            <option value="NM" <?php echo ( $poststate =="NM" )? "selected='selected'": " "; ?>>New Mexico</option>
            <option value="NY" <?php echo ( $poststate =="NY" )? "selected='selected'" : " "; ?>>New York</option>
            <option value="NC" <?php echo ( $poststate =="NC" )? "selected='selected'" : " "; ?>>North Carolina</option>
            <option value="ND" <?php echo ( $poststate =="ND" )? "selected='selected'" : " "; ?>>North Dakota</option>
            <option value="OH" <?php echo ( $poststate =="OH" )? "selected='selected'" : " "; ?>>Ohio</option>
            <option value="OK" <?php echo ( $poststate =="OK" )? "selected='selected'" : " "; ?>>Oklahoma</option>
            <option value="OR" <?php echo ( $poststate =="OR" )? "selected='selected'" : " "; ?>>Oregon</option>
            <option value="PA" <?php echo ( $poststate =="PA" )? "selected='selected'" : " "; ?>>Pennsylvania</option>
            <option value="RI" <?php echo ( $poststate =="RI" )? "selected='selected'" : " "; ?>>Rhode Island</option>
            <option value="SC" <?php echo ( $poststate =="SC" )? "selected='selected'" : " "; ?>>South Carolina</option>
            <option value="SD" <?php echo ( $poststate =="SD" )? "selected:'selected'"  : " "; ?>>South Dakota</option>
            <option value="TN" <?php echo ( $poststate =="TN" )? "selected='selected'" : " "; ?>>Tennessee</option>
            <option value="TX" <?php echo ( $poststate =="TX" )? "selected='selected'" : " "; ?>>Texas</option>
            <option value="UT" <?php echo ( $poststate =="UT" )? "selecte='selected'": " "; ?>>Utah</option>
            <option value="VT" <?php echo ( $poststate =="VT" )? "selected='selected'" : " "; ?>>Vermont</option>
            <option value="VA" <?php echo ( $poststate =="VA" )? "selected='selected'" : " "; ?>>Virginia</option>
            <option value="WA" <?php echo ( $poststate =="WA" )? "selected='selected'" : " "; ?>>Washington</option>
            <option value="WV" <?php echo ( $poststate =="WV" )? "selected='selected'" : " "; ?>>West Virginia</option>
            <option value="WI" <?php echo ( $poststate =="WI" )? "selected='selected'" : " "; ?>>Wisconsin</option>
            <option value="WY" <?php echo ( $poststate =="WY" )? "selected ='selected'" : " "; ?>>Wyoming</option>
        </select>
        </div>

        <div class="zipDiv clearfix" id="zipcode"><label for="zip_code">Zip Code<span>*</span></label><input type="text" name="zip_code" class="zipcode"  id="bd_zipcode"  value="<?php echo $zipcode ?>" tabindex="8" /></div>
        <div class=""><label for="business_contact_person">Contact Person</label><input type="text" name="business_contact_person"  value="<?php echo $contact ?>" tabindex="9"/></div>
        <div class="input clearfix"><label for="business_phone_number">Phone Number</label><input type="text" name="business_phone_number"  id="bd_phone" value="<?php echo $phone ?>" tabindex="10"/></div>
        <div class="input clearfix"><label for="business_website">Website</label><input type="text" name="business_website" id="bd_website" value="<?php echo $website ?>" tabindex="11"/></div>
        <div class="input clearfix"></div>
        <div class="input clearfix"><label for="business_org_type">Organization Type<span>*</span></label>

            <select name="business_type" data-customid="my_products_catlist" class="store-custom-select regselect">

                <?php        $termtable =  "SELECT DISTINCT * FROM ".$wpdb->prefix."terms LEFT JOIN ".$wpdb->prefix."term_taxonomy ON ( ".$wpdb->prefix."terms.term_id = ".$wpdb->prefix."term_taxonomy.term_id ) WHERE taxonomy='business_type' ORDER BY ".$wpdb->prefix."terms.name  ASC";
                $table_taxo =   $wpdb->get_results( $wpdb->prepare( $termtable ) );
                if( count( $table_taxo)>=0 ) {
                    foreach( $table_taxo as $taxo ) {
                        ?>
                        <option value="<?php echo $taxo->term_id ?>" class="level-0" <?php echo ( $taxo->term_id == $business_type ) ? "selected='selected'" : " "; ?> ><?php echo $taxo->name ?></option>
                        <?php  }
                } ?>
            </select>
            <?php $category; ?>
        </div>
        <div id="businessHoursDiv" class="input checkbox"><label for="daysopen">Days Open<span>*</span>         <?php  echo '<div class="errormsg">'.$mesg['day'].'</div>';  ?></label>
            <div id="businessHoursregi">
                <div class="day"><input type="checkbox" class="check" name="check-all" id="select-all" value="all" /><span>All</span></div>
                <div class="day daycheck"><input type="checkbox" value="mon" class="check check-mon" name="check-mon" /><span>Mon</span></div>
                <div class="day daycheck"><input type="checkbox" value="tue" class="check check-tue" name="check-tue" /><span>Tue</span></div>
                <div class="day daycheck"><input type="checkbox" value="wed" class="check check-wed" name="check-wed" /><span>Wed</span></div>
                <div class="day daycheck"><input type="checkbox" value="thu" class="check check-thu" name="check-thu"  /><span>Thu</span></div>
                <div class="day daycheck"><input type="checkbox" value="fri" class="check check-fri" name="check-fri" /><span>Fri</span></div>
                <div class="day daycheck"><input type="checkbox" value="sat" class="check check-sat" name="check-sat" /><span>Sat</span></div>
                <div class="day daycheck"><input type="checkbox" value="sun" class="check check-sun" name="check-sun" /><span>Sun</span></div>
            </div>
        </div>
        <input id="checkmode" type="hidden" value="0" name="checkmode">
        <?php
        $defvalue = ( get_post_meta( $postid, 'bd_editmode', true ) ) ?  get_post_meta( $postid, 'bd_editmode', true ): "0"; ?>
        <input class="normalOadvance" type="hidden" value="<?php echo $defvalue; ?>" name="nOa" />
        <?php
        $r = range(0, 23);
        //    if( $defvalue=='0' ) {
        $selectfrom = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectfrom .= "<option value=\"$hour\"";
            $selectfrom .= ($hour=='9') ? ' selected="selected"' : "";
            $selectfrom .= ">".date('g:i A', $start)."</option>\n";
        }
        $selectTo = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectTo .= "<option value=\"$hour\"";
            $selectTo .= ($hour=='17') ? ' selected="selected"' : "";
            $selectTo .= ">".date('g:i A', $start)."</option>\n";
        }
        //  }
//                                        $select = "<option value=\"none\">Select</option>";
//                                                            foreach ($r as $hour){
//                                                                                $start = strtotime($hour.":00");
//                                                                                $select .= "<option value=\"$hour\"";
//                                                                                //$select .= ($hour=='9') ? ' selected="selected"' : "";
//                                                                                $select .= ">".date('g:i A', $start)."</option>\n";
//                                                            }
        ?>
        <div id="timingDiv" class="input clearfix"><label class="opentimeTxt" for="opening-time">Opening Time<span>*</span></label>

            <div id="advancedDiv" class="clearfix">Advanced</div><div id="normalDiv">Normal</div>

            <div id="normalTime" class="clearfix">
                <select id="starttime" name="fromhr"><?php echo $selectfrom ?>></select> <span>TO</span>
                <select id="endtime" name="tohr"><?php echo $selectTo ?></select>
            </div>

            <div id="advancedTime">
                <div class="monSelect dayDiv"><label>Monday</label>
                    <select id="mon_starttime" name="mon_fromhr"><?php echo $selectfrom ?></select> <span>TO</span>
                    <select id="mon_endtime" name="mon_tohr" ><?php echo $selectTo ?></select>
                </div>

                <div class="tueSelect dayDiv"><label>Tuesday</label>
                    <select id="tue_starttime" name="tue_fromhr"><?php echo $selectfrom ?></select> <span>TO</span>
                    <select id="tue_endtime" name="tue_tohr"><?php echo $selectTo ?></select>
                </div>

                <div class="wedSelect dayDiv"><label>Wednesday</label>
                    <select id="wed_starttime" name="wed_fromhr" ><?php echo $selectfrom ?></select> <span>TO</span>
                    <select id="wed_endtime" name="wed_tohr"><?php echo $selectTo ?></select>
                </div>

                <div class="thuSelect dayDiv"><label>Thursday</label>
                    <select id="thu_starttime" name="thu_fromhr"><?php echo $selectfrom ?></select> <span>TO</span>
                    <select id="thu_endtime" name="thu_tohr" ><?php echo $selectTo ?></select>
                </div>

                <div class="friSelect dayDiv"><label>Friday</label>
                    <select id="fri_starttime" name="fri_fromhr" ><?php echo $selectfrom ?></select> <span>TO</span>
                    <select id="fri_endtime" name="fri_tohr" ><?php echo $selectTo ?></select></div>
                <div class="satSelect dayDiv"><label>Saturday</label>
                    <select id="sat_starttime" name="sat_fromhr"><?php echo $selectfrom ?></select> <span>TO</span>
                    <select id="sat_endtime" name="sat_tohr"><?php echo $selectTo ?></select></div>
                <div class="sunSelect dayDiv"><label>Sunday</label>
                    <select id="sun_starttime" name="sun_fromhr" ><?php echo $selectfrom ?></select> <span>TO</span>
                    <select id="sun_endtime" name="sun_tohr" ><?php echo $selectTo ?></select></div>
            </div>

            <div class="submitDiv">
                <?php wp_nonce_field("register", "register_nonce"); ?><input type="submit" id="regBusiness" class="button submit-button button-primary" value="SUBMIT" name="register_bform_submit" tabindex="13"/>
                <span class="more_links"><a class="button submit-button button-primary" href="<?php echo get_option('bclosingsurl'); ?>">CANCEL</a></span>
            </div>
        </div>
    </form>
</div>
<?php
}

function bd_add_business( $echo=true ) {
    global $bd,$current_user;
    global $wpdb;

    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    get_currentuserinfo();

    if (!is_user_logged_in()){
        $login_link = get_option('loginurl').'?ref=add-business';
        wp_redirect($login_link);
    }

    if( isset( $_POST['add_bform_submit'] ) ) {
        require_once('bd_save_regsitration.php');
    }
    if ( $_GET['edit'] ) {
        $postid = $_GET['edit'];
        $cat = get_post_meta( $postid, 'bd_cat', true );
        $name = get_post_meta( $postid, 'bd_bname', true );
        $addr =   get_post_meta( $postid, 'bd_address', true);
        $city =     get_post_meta($postid, 'bd_city', true);
        $state = get_post_meta($postid, 'bd_state', true);
        $zipcode = get_post_meta($postid, 'bd_zipcode', true);
        $contact = get_post_meta($postid, 'bd_contact', true);
        $phone = get_post_meta($postid, 'bd_phone', true);
        $website = get_post_meta($postid, 'bd_website', true);
    }else{
        $name='';$addr='';$city='';$zipcode='';$contact='';$phone='';$website='';
    }

    if ($_GET['edit']){
        $content .='<div id="addBusiness"><div class="ab_header"><h2>EDIT YOUR BUSINESS</h2>';
    }else{
        $content .='<div id="addBusiness"><div class="ab_header"><h2>ADD YOUR BUSINESS</h2>';
    }
    $content.='</div>';
    //$content.=the_breadcrumb();
    $content.='<form id="registration_form" onsubmit="return validatebusinessForm();" class="login-registration add-business-style  validate clearfix" action="" method="post" enctype="multipart/form-data" >';
    $content.='<div class="input clearfix"><label for="display_name">Business Name<span>*</span></label><input type="text" name="business_name" id="business_name" class="" value="'.$name.'"  tabindex="1"/></div>';
    $content .='<div class="input add-code clearfix"><label for="business_address">Address<span>*</span></label><input type="text" name="business_address" id="bd_address" class="" value="'.$addr.'"  tabindex="5"/></div>';
    $content .='<div class="input add-code clearfix" id="formcity"><label for="city">City<span>*</span></label><input type="text" name="city" class="" id="bd_city" value="'.$city.'" tabindex="6"/></div>';

    $content.='<div id="formstate" class="clearfix"><label for="city">State<span>*</span></label><select type="text" name="state" id="station-info-state" class="store-custom-select regselect">';
    $content.='<option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="DC">District Of Columbia</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD">Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                        <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                    </select></div>';

    if ($_GET['edit']){
        $content .='<input id="editmode" type="hidden" value="1" name="editmode">';
        $content .='<input id="postid" type="hidden" value="'.$_GET['edit'].'" name="postid">';
    }else{
        $content .='<input id="editmode" type="hidden" value="0" name="editmode">';
    }

    $defvalue = ( get_post_meta( $postid, 'bd_editmode', true ) ) ?  get_post_meta( $postid, 'bd_editmode', true ): "0";
    $content .='<input class="normalOadvance" type="hidden" value="'.$defvalue.'" name="nOa">';

    $defvaluecheckall = ( get_post_meta( $postid, 'bd_checkall', true ) ) ? "1": "0";
    $content .='<input class="checkAll" type="hidden" value="'.$defvaluecheckall.'" name="checkAll">';

    $content.='<div class="zipDiv clearfix" id="loggedin"><label for="zip_code">Zip Code<span>*</span></label><input type="text" name="zip_code" class="loggedin"  id="bd_zipcode"  value="'.$zipcode.'" tabindex="8" /></div>';
    //$content.='<div class="zipDiv clearfix" id="'.$state.'"><label for="zip_code">Zip Code<span>*</span></label><input type="text" name="zip_code" class=""  id="add-input"  value="'.$zipcode.'" tabindex="8"/></div>';
    $content.='<div class=""><label for="business_contact_person">Contact Person</label><input type="text" name="business_contact_person"  value="'.$contact.'" tabindex="9"/></div>';
    $content.='<div class="input clearfix"><label for="business_phone_number">Phone Number</label><input type="text" name="business_phone_number"  id="bd_phone" value="'.$phone.'" tabindex="10"/></div>';
    $content.='<div class="input clearfix"><label for="business_website">Website</label><input type="text" name="business_website" id="bd_website" value="'.$website.'" tabindex="11"/></div>';
    $content.='<div class="input clearfix"></div><div class="input clearfix"><label for="business_org_type">Organization Type<span>*</span></label>';

    $category .= '<select name="business_type" data-customid="my_products_catlist" class="store-custom-select regselect">';
    $table_taxo = get_terms( 'business_type', 'order=ASC&hide_empty=0' );
    if( count($table_taxo)>=0 ) {
        foreach( $table_taxo as $taxo ){
            $category .='<option value="'.$taxo->term_id.'" class="level-0">'.$taxo->name.'</option>';
        }
    }
    $category .= '</select>';
    $content .= $category;
    $content .='</div>
                    <div id="businessHoursDiv" class="input checkbox clearfix"><label for="daysopen">Days Open<span>*</span> <div class="errormsg">'.$mesg['day'].'</div></label>
                    <div id="businessHours">';
    if ( $_GET['edit'] ){
        $bdcat = get_post_meta($postid, 'bd_cat',true);
        $content .='<script>jQuery(document).ready(function($) { var bdcat = "'.$bdcat.'"; jQuery(".store-custom-select ").val(bdcat);var st = "'.$state.'"; jQuery("#station-info-state").val(st);});</script>';
    }

    if ( get_post_meta( $postid, 'bd_checkall', true ) ){
        $content .='<div class="day"><input type="checkbox" class="check" name="check-all" id="select-all" value="all" checked="true"/><span>All</span></div>';
    }else{
        $content .='<div class="day"><input type="checkbox" class="check" name="check-all" id="select-all" value="all"/><span>All</span></div>';
    }

    if ( get_post_meta( $postid, 'bd_checkmode', true ) ) {
        $content .='<input id="checkmode" type="hidden" value="1" name="checkmode">';
    }else{
        $content .='<input id="checkmode" type="hidden" value="0" name="checkmode">';
    }

    if ( get_post_meta( $postid, 'bd_monday', true )  || !$_GET['edit'] ) {
        $content .='<div class="day daycheck"><input type="checkbox" name="check-mon" class="check check-mon" value="mon" checked="true"/><span>Mon</span></div>';
    }else{
        $content .='<div class="day daycheck"><input type="checkbox" name="check-mon" class="check check-mon" value="mon"/><span>Mon</span></div>';
    }

    if ( get_post_meta( $postid, 'bd_tuesday', true ) || !$_GET['edit'] ) {
        $content .='<div class="day daycheck"><input type="checkbox" name="check-tue" class="check check-tue" value="tue" checked="true"/><span>Tue</span></div>';
    }else{
        $content .='<div class="day daycheck"><input type="checkbox" name="check-tue" class="check check-tue" value="tue"/><span>Tue</span></div>';
    }

    if ( get_post_meta( $postid, 'bd_wednesday', true ) || !$_GET['edit'] ) {
        $content .='<div class="day daycheck"><input type="checkbox" name="check-wed" class="check check-wed" value="wed" checked="true"/><span>Wed</span></div>';
    }else{
        $content .='<div class="day daycheck"><input type="checkbox" name="check-wed" class="check check-wed" value="wed"/><span>Wed</span></div>';
    }

    if ( get_post_meta( $postid, 'bd_thursday', true ) || !$_GET['edit'] ) {
        $content .='<div class="day daycheck"><input type="checkbox" name="check-thu" class="check check-thu" value="thu" checked="true"/><span>Thu</span></div>';
    }else{
        $content .='<div class="day daycheck"><input type="checkbox" name="check-thu" class="check check-thu" value="thu"/><span>Thu</span></div>';
    }

    if ( get_post_meta($postid, 'bd_friday', true) || !$_GET['edit'] ) {
        $content .='<div class="day daycheck"><input type="checkbox" name="check-fri" class="check check-fri" value="fri" checked="true"/><span>Fri</span></div>';
    }else{
        $content .='<div class="day daycheck"><input type="checkbox" name="check-fri" class="check check-fri" value="fri"/><span>Fri</span></div>';
    }

    if ( get_post_meta($postid, 'bd_saturday', true) ) {
        $content .='<div class="day daycheck"><input type="checkbox" name="check-sat" class="check check-sat" value="sat" checked="true"/><span>Sat</span></div>';
    }else{
        $content .='<div class="day daycheck"><input type="checkbox" name="check-sat" class="check check-sat" value="sat"/><span>Sat</span></div>';
    }

    if ( get_post_meta( $postid, 'bd_sunday', true ) ) {
        $content .='<div class="day daycheck"><input type="checkbox" name="check-sun" class="check check-sun" value="sun" checked="true"/><span>Sun</span></div>';
    }else{
        $content .='<div class="day daycheck"><input type="checkbox" name="check-sun" class="check check-sun" value="sun"/><span>Sun</span></div>';
    }
    $content .='</div>';

    $r = range(0, 23);
    $selected = is_null($selected) ? date('h') : $selected;

    $editmode =  get_post_meta( $postid, 'bd_editmode', true );

    if( !$_GET['edit'] ) {
        $selectcommon = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectcommon .= "<option value=\"$hour\"";
            $selectcommon .= ($hour=='9') ? ' selected="selected"' : "";
            $selectcommon .= ">".date('g:i A', $start)."</option>\n";
        }
        $selectcommonTo = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectcommonTo .= "<option value=\"$hour\"";
            $selectcommonTo .= ($hour=='17') ? ' selected="selected"' : "";
            $selectcommonTo .= ">".date('g:i A', $start)."</option>\n";
        }
    }
    $common = get_post_meta($postid, 'bd_commontime', true);

    // FOR CHECK ALL MODE
    if( $defvaluecheckall ) {
        $common = get_post_meta( $postid, 'bd_commontime', true );
        $selectcommon = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectcommon .= "<option value=\"$hour\"";
            $selectcommon .= ($hour==$common['start']) ? ' selected="selected"' : "";
            $selectcommon .= ">".date('g:i A', $start)."</option>\n";
        }
        $selectcommonTo = "<option value=\"none\">Select</option>";
        foreach ( $r as $hour ) {
            $start = strtotime($hour.":00");
            $selectcommonTo .= "<option value=\"$hour\"";
            $selectcommonTo .= ($hour==$common['end']) ? ' selected="selected"' : "";
            $selectcommonTo .= ">".date('g:i A', $start)."</option>\n";
        }
    }

    // FOR NORMAL MODE
    if( $_GET['edit'] && $defvalue=='0' || $defvalue=='1' ) {
        $common = get_post_meta( $postid, 'bd_commontime', true );
        $selectcommon = "<option value=\"none\">Select</option>";
        foreach ( $r as $hour ) {
            $start = strtotime( $hour.":00" );
            $selectcommon .= "<option value=\"$hour\"";
            $selectcommon .= ($hour==$common['start']) ? ' selected="selected"' : "";
            $selectcommon .= ">".date('g:i A', $start)."</option>\n";
        }
        $selectcommonTo = "<option value=\"none\">Select</option>";
        foreach ( $r as $hour ) {
            $start = strtotime( $hour.":00");
            $selectcommonTo .= "<option value=\"$hour\"";
            $selectcommonTo .= ($hour==$common['end']) ? ' selected="selected"' : "";
            $selectcommonTo .= ">".date('g:i A', $start)."</option>\n";
        }
    }

    // FOR ADVANCED MODE
    if(  $_GET['edit'] && $defvalue=='1' ) {
        $mon = get_post_meta( $postid, 'bd_monday', true );
        $selectMon = "<option value=\"none\">Select</option>";
        foreach ( $r as $hour ) {
            $start = strtotime($hour.":00");
            $selectMon .= "<option value=\"$hour\"";
            $selectMon .= ($hour==$mon['start']) ? ' selected="selected"' : "";
            $selectMon .= ">".date('g:i A', $start)."</option>\n";
        }
        $selectMonTo = "<option value=\"none\">Select</option>";
        foreach ( $r as $hour ) {
            $start = strtotime($hour.":00");
            $selectMonTo .= "<option value=\"$hour\"";
            $selectMonTo .= ($hour==$mon['end']) ? ' selected="selected"' : "";
            $selectMonTo .= ">".date( 'g:i A', $start )."</option>\n";
        }

        $tue = get_post_meta( $postid, 'bd_tuesday', true );
        $selectTue = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectTue .= "<option value=\"$hour\"";
            $selectTue .= ($hour==$tue['start']) ? ' selected="selected"' : "";
            $selectTue .= ">".date('g:i A', $start)."</option>\n";
        }

        $selectTueTo = "<option value=\"none\">Select</option>";
        foreach ( $r as $hour ) {
            $start = strtotime( $hour.":00" );
            $selectTueTo .= "<option value=\"$hour\"";
            $selectTueTo .= ( $hour==$tue['end'] ) ? ' selected="selected"' : "";
            $selectTueTo .= ">".date( 'g:i A', $start )."</option>\n";
        }

        $wed = get_post_meta( $postid, 'bd_wednesday', true );
        $selectWed = "<option value=\"none\">Select</option>";
        foreach ( $r as $hour ) {
            $start = strtotime($hour.":00");
            $selectWed .= "<option value=\"$hour\"";
            $selectWed .= ( $hour==$wed['start'] )  ?  ' selected="selected"' : "";
            $selectWed .= ">".date('g:i A', $start)."</option>\n";
        }

        $selectWedTo = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectWedTo .= "<option value=\"$hour\"";
            $selectWedTo .= ($hour==$wed['end']) ? ' selected="selected"' : "";
            $selectWedTo .= ">".date('g:i A', $start)."</option>\n";
        }

        $thu = get_post_meta($postid, 'bd_thursday', true);
        $selectThu = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectThu .= "<option value=\"$hour\"";
            $selectThu .= ($hour==$thu['start']) ? ' selected="selected"' : "";
            $selectThu .= ">".date('g:i A', $start)."</option>\n";
        }

        $selectThuTo = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectThuTo .= "<option value=\"$hour\"";
            $selectThuTo .= ($hour==$thu['end']) ? ' selected="selected"' : "";
            $selectThuTo .= ">".date('g:i A', $start)."</option>\n";
        }
        $fri = get_post_meta($postid, 'bd_friday', true);
        $selectFri = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectFri .= "<option value=\"$hour\"";
            $selectFri .= ($hour==$fri['start']) ? ' selected="selected"' : "";
            $selectFri .= ">".date('g:i A', $start)."</option>\n";
        }

        $selectFriTo = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectFriTo .= "<option value=\"$hour\"";
            $selectFriTo .= ($hour==$fri['end']) ? ' selected="selected"' : "";
            $selectFriTo .= ">".date('g:i A', $start)."</option>\n";
        }
        $sat = get_post_meta($postid, 'bd_saturday', true);
        $selectSat = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectSat .= "<option value=\"$hour\"";
            $selectSat .= ($hour==$sat['start']) ? ' selected="selected"' : "";
            $selectSat .= ">".date('g:i A', $start)."</option>\n";
        }

        $selectSatTo = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectSatTo .= "<option value=\"$hour\"";
            $selectSatTo .= ($hour==$sat['end']) ? ' selected="selected"' : "";
            $selectSatTo .= ">".date('g:i A', $start)."</option>\n";
        }
        $sun = get_post_meta($postid, 'bd_sunday', true);
        $selectSun = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectSun .= "<option value=\"$hour\"";
            $selectSun .= ($hour==$sun['start']) ? ' selected="selected"' : "";
            $selectSun .= ">".date('g:i A', $start)."</option>\n";
        }

        $selectSunTo = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectSunTo .= "<option value=\"$hour\"";
            $selectSunTo .= ($hour==$sun['end']) ? ' selected="selected"' : "";
            $selectSunTo .= ">".date('g:i A', $start)."</option>\n";
        }
    }

    $content .='</div>
                            <div id="timingDiv" class="input clearfix">
                                        <label class="opentimeTxt" for="opening-time">
                                                            Opening Time<span>*</span>
                                        </label>';

    $content.='<a id="advancedDiv" class="clearfix">Advanced</a>';
    $content.='<a id="normalDiv" >Normal</a>';

    $content.='<div id="normalTime" class="clearfix"><select id="starttime" name="fromhr">'.$selectcommon.'</select> <span>TO</span>';
    $content.='<select id="endtime" name="tohr">'.$selectcommonTo.'</select></div>';

    if( $defvalue =="1" || $defvalue =="0" ){
        $noday = get_post_meta($postid, 'bd_monday', true);
        $select = "<option value=\"none\">Select</option>";
        $selectfrom = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectfrom .= "<option value=\"$hour\"";
            $selectfrom .= ($hour=='9') ? ' selected="selected"' : "";
            $selectfrom .= ">".date('g:i A', $start)."</option>\n";
        }
        $selectTo = "<option value=\"none\">Select</option>";
        foreach ($r as $hour){
            $start = strtotime($hour.":00");
            $selectTo .= "<option value=\"$hour\"";
            $selectTo .= ($hour=='17') ? ' selected="selected"' : "";
            $selectTo .= ">".date('g:i A', $start)."</option>\n";
        }
        $content.='<div id="advancedTime">';
        if ($mon){ $content.='<div class="monSelect dayDiv"><label>Monday</label><select class="timestart" id="mon_starttime" name="mon_fromhr">'.$selectMon.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="mon_endtime" name="mon_tohr">'.$selectMonTo.'</select></div>';
        }else{$content.='<div class="monSelect dayDiv"><label>Monday</label><select class="timestart" id="mon_starttime" name="mon_fromhr">'.$selectfrom.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="mon_endtime" name="mon_tohr">'.$selectTo.'</select></div>';
        }
        if($tue){$content.='<div class="tueSelect dayDiv"><label>Tuesday</label><select class="timestart" id="tue_starttime" name="tue_fromhr">'.$selectTue.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="tue_endtime" name="tue_tohr">'.$selectTueTo.'</select></div>';
        }else{$content.='<div class="tueSelect dayDiv"><label>Tuesday</label><select class="timestart" id="tue_starttime" name="tue_fromhr">'.$selectfrom.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="tue_endtime" name="tue_tohr">'.$selectTo.'</select></div>';
        }
        if($wed){$content.='<div class="wedSelect dayDiv"><label>Wednesday</label><select class="timestart" id="wed_starttime" name="wed_fromhr">'.$selectWed.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="wed_endtime" name="wed_tohr">'.$selectWedTo.'</select></div>';
        }else{$content.='<div class="wedSelect dayDiv"><label>Wednesday</label><select class="timestart" id="wed_starttime" name="wed_fromhr">'.$selectfrom.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="wed_endtime" name="wed_tohr">'.$selectTo.'</select></div>';
        }
        if($thu){$content.='<div class="thuSelect dayDiv"><label>Thursday</label><select class="timestart" id="thu_starttime" name="thu_fromhr">'.$selectThu.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="thu_endtime" name="thu_tohr">'.$selectThuTo.'</select></div>';
        }else{$content.='<div class="thuSelect dayDiv"><label>Thursday</label><select class="timestart" id="thu_starttime" name="thu_fromhr">'.$selectfrom.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="thu_endtime" name="thu_tohr">'.$selectTo.'</select></div>';
        }
        if($fri){$content.='<div class="friSelect dayDiv"><label>Friday</label><select class="timestart" id="fri_starttime" name="fri_fromhr">'.$selectFri.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="fri_endtime" name="fri_tohr">'.$selectFriTo.'</select></div>';
        }else{$content.='<div class="friSelect dayDiv"><label>Friday</label><select class="timestart" id="fri_starttime" name="fri_fromhr">'.$selectfrom.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="fri_endtime" name="fri_tohr">'.$selectTo.'</select></div>';
        }
        if($sat){$content.='<div class="satSelect dayDiv"><label>Saturday</label><select class="timestart" id="sat_starttime" name="sat_fromhr">'.$selectSat.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="sat_endtime" name="sat_tohr">'.$selectSatTo.'</select></div>';
        }else{$content.='<div class="satSelect dayDiv"><label>Saturday</label><select class="timestart" id="sat_starttime" name="sat_fromhr">'.$selectfrom.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="sat_endtime" name="sat_tohr">'.$selectTo.'</select></div>';
        }
        if($sun){$content.='<div class="sunSelect dayDiv"><label>Sunday</label><select class="timestart" id="sun_starttime" name="sun_fromhr">'.$selectSun.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="sun_endtime" name="sun_tohr">'.$selectSunTo.'</select></div>';
        }else{
            $content.='<div class="sunSelect dayDiv"><label>Sunday</label><select class="timestart" id="sun_starttime" name="sun_fromhr">'.$selectfrom.'</select> <span>TO</span>';
            $content.='<select class="timeend" id="sun_endtime" name="sun_tohr">'.$selectTo.'</select></div>';
        }
        $content .='</div>';
    }if ($_GET['edit']){
        $content.='<div class="submitDiv"><?php wp_nonce_field("register", "register_nonce"); ?><input type="submit" id="regBusiness" class="button submit-button button-primary" value="Update" name="add_bform_submit" tabindex="13"/>';
    }else{
        $content.='<div class="submitDiv"><?php wp_nonce_field("register", "register_nonce"); ?><input type="submit" id="regBusiness" class="button submit-button button-primary" value="Submit" name="add_bform_submit" tabindex="13"/>';
    }
    $content.='<span class="more_links"><a class="button submit-button button-primary" href="'.get_option('bclosingsurl').'">CANCEL</a></span></div></form></div></div>';

    if ($echo)
        echo $content;
    else
        return $content;
}

function bd_category_list( $business_id = false, $before = '', $sep = ', ', $after = '' ) {
    $terms = get_the_term_list( $business_id, 'business_type', $before, $sep, $after );
    if ( $terms )
        return $terms;
    else
        return __( 'Uncatagorized', 'mp' );
}

function tsm_error_msg($error_msg) {
    $msg_string = '';
    foreach ($error_msg as $value) {
        if(!empty($value)) {
            $msg_string = $msg_string . '' . $msg_string = $value.'  ';
        }
    }
    return $msg_string;
}

function is_valid_email($email) {
    $result = TRUE;
    if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
        $result = FALSE;
    }
    return $result;
}

function bd_login_business_theme($echo=true){
    global $bd,$wpdb, $wp_query,$bd;
    $settings = get_option('bd_settings');
    if (!is_user_logged_in()){

        $content .= ' <div id="login-business">
                                                                                <div class="registration_wrap">';
        //  Check Lagacy User
        //  Wordpress custom login with authentication
        $username = $_POST['log'];
        $password = $_POST['pwd'];
        if ( get_option( 'bd_legacycheck' ) == 1 ) {
            $legacy_user = $wpdb->get_var( $wpdb->prepare( "SELECT UserName,  Password FROM  ts_legacy WHERE UserName='".$username."' AND Password='".$password."'") );
            $normal_user = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM ts_usermeta WHERE `meta_key`='legacy_user' AND `meta_value`='".$username."'") );
            $legacyurl = get_option( 'legacyRegurl' )."?auth1=".encode_base64( $username )."&auth2=".encode_base64( $password );
            if( $legacy_user &&  !$normal_user  ){
                wp_redirect( $legacyurl );
                die;
            }
        }
        $userid = get_user_id_from_string( $username );
        $confirmation = get_user_meta( $userid, 'bd_activation', true );
        $creds = array();
        $bid = get_current_blog_id();
        $business = ( get_user_meta( $userid, $wpdb->prefix.'capabilities', true ) ) ? get_user_meta( $userid, $wpdb->prefix.'capabilities', true ) : $creds;
        $businessrole = key( $business );

        /* Set $myBlogId to the ID of the site you want to query */
        $wpdb->blogid = $bd->centralBid;
        $wpdb->set_prefix( $wpdb->base_prefix );

        if( $normal_user ) {
            $loginerror ='<span class="sanerror">Your accont has been migrated, please use email-id to login.</span>';
        }else if( !$normal_user ){
            $loginerror ='<span class="sanerror">Sorry, the email / user ID or password that you entered didn\'t match our records.</span>';
        }

        // IF THE USER HAS BEEN REMOVED FROM DISRUPTIONS SYSTEM
        if(  isset( $_POST['business_login_form_submit'] ) ){
            // if( !empty($businessrole) ){
//                                                                                                                                           if ( $businessrole !='business' && !is_super_admin() ){
//                                                                                                                                                                $creds['user_login'] = '12@';
//                                                                                                                                                                $creds['user_password'] = '@12@';
//                                                                                                                                                                $user = wp_signon( $creds, false );
//                                                                                                                                                         //       $loginerror ='<span class="sanerror">You seems that you are not member of Disruptions, kindly register to continue.</span>';      
//                                                                                                                                        //     exit;
//                                                                                                                                           }else{
            //}else{
            $creds['user_login'] = $_POST['log'];
            $creds['user_password'] =  $_POST['pwd'];
            //}
        }

        if( $_GET['Auth1'] && $_GET['Auth2'] ){
            $uid = get_user_id_from_string( decode_base64( $_GET['Auth1'] ) );
            $confirmation = get_user_meta(  $uid, 'bd_activation', true );
            if( $confirmation =='pending' ){
                $autocreds = array();
                $autocreds['user_login'] =   decode_base64( $_GET['Auth1'] );
                $autocreds['user_password'] = decode_base64(  $_GET['Auth2'] );

                update_user_meta(  $uid, 'bd_activation', 'confirmed' );
                $activate = new WP_Query( 'post_type=business&post_status=draft&author='.$uid.'' );

                foreach($activate->posts as $post){
                    $update_post = array(
                        'ID'            => $post->ID,
                        'post_status'	=> 'publish',
                        'post_type'	=> 'business'
                    );
                    $post_id = wp_update_post( $update_post );
                }

                $confrim_msg .= '<p><b>Congratulations! </b></p>
                                                                                                                                            <p>Your account for our new Closings and Delays alerts system has been successfully created. Whether it\'s snow or storm, with this tool you will be able to alert Internet users and listeners of our New Jersey radio stations of any changes in your business opening times.</p>
                                                                                                                                            <br/>
                                                                                                                                            <b>Your login details are listed below.</b>
                                                                                                                                            <p>You will need them to update your closings information so please save them for future reference.<br/> You can send us feedback or request additional help at '.townsquare_get_email().'.</p>
                                                                                                                                            <br/>
                                                                                                                                            <b>Your Account Information</b>
                                                                                                                                            <p>Your business settings: '.get_option( "businessurl" ).'login'.'</p>
                                                                                                                                            <p>All closings in your area: '.get_option( "bclosingsurl" ).'</p> 
                                                                                                                                            <p>User Name: '.$autocreds['user_login'].'</p>
                                                                                                                                            <p>Password: '.$autocreds['user_password'].'</p>';

                // SEND MAIL ON CONFIRMATION.
                add_filter( 'wp_mail_content_type',create_function( '', 'return "text/html";' ) );
                $subject = 'Your Closings and Delay account information for '.get_the_title( $post_id );
                //   wp_mail( 'sandeep@inkoniq.com, nirali@inkoniq.com, madhu@inkoniq.com, luca@townsquaremedia.com', iconv_mime_decode($subject,2,'utf8'), $confrim_msg );
                wp_mail( $autocreds['user_login'], iconv_mime_decode($subject,2,'utf8'), $confrim_msg );

                $autocreds['remember'] = true;
                $user = wp_signon( $autocreds, false );

                $activate = new WP_Query( 'post_type=business&post_status=publish&author='.$uid );
                echo "<pre>";
                print_r($post);
                echo "</pre>";
                if(  $activate->post_count  > 1 ){
                    wp_redirect( get_option( 'manageurl' )  );
                }else if( $activate->post_count  == 1){
                    foreach($activate->posts as $post)
//                        wp_redirect( get_option( 'closingurl' ).'?p='.$post->ID.'&a=update' );
                        echo 'ss';
                }else if( $activate->post_count == 0 ) {
                    wp_redirect( get_option( 'bclosingsurl' ) );
                }
            }else{
                echo  '<div class="bdlogin-alert">Your account has been already activated, please login.</div>';
            }
        }

        $creds['remember'] = true;

        // USER CONFIRMATION CHECK
        if( $confirmation=='pending' ){
            $loginerror ='<span class="sanerror">Kindly activate your business before you proceed.</span><br/>';
            $loginerror .='<span class="sanerror">Didn\'t receive an email? Check your spam folder or <a href="'.get_option( 'resendmailurl').'" class="bdresendlink">click here </a>to resend.</span>';
            $creds['user_login']='';
        }

        $user = wp_signon( $creds, false );


        $content .='<div class="title"><h2>Business Login</h2></div>';
        the_login_message();
        if ( !is_wp_error($user) ){
            if( isset( $_POST['business_login_form_submit'] )  ){
                $useremail = $_POST['log'];
                $userid = get_user_id_from_string( $useremail );
                global $wpdb;
                $activate = new WP_Query( 'post_type=business&post_status=publish&author='.$userid );

                if(  $activate->post_count  > 1 ){
                    wp_redirect( get_option( 'manageurl' )  );
                }else if(  $activate->post_count  == 1 ){
                    foreach($activate->posts as $post)
//                        wp_redirect( get_option( 'closingurl' ).'?p='.$post->ID.'&a=update' );
                        echo ">>>";
                }else if( $activate->post_count == 0 ) {
                    wp_redirect( get_option( 'bclosingsurl' ) );
                }
            }
        }
        $content.='<form id="login_form" class="login-registration validate" action="" method="post">
                                                                                                                                          <p class="site-line">Use your '.  get_bloginfo(name).' business account to log in</p>
                                                                                                                                          <div class="clearfix"></div>';
        if ( is_wp_error($user) ||  $confirmation == 'pending' )
            if( $user->get_error_message() ){
                $content .= $loginerror;
            }
        $content.='<div class="input clearfix">
                                                                                                                                                                                                                          <label for="user_login">Email / Username</label>
                                                                                                                                                                                                                          <input type="text" class="input validate valid-required" tabindex="2" size="22" value="" id="user_login" name="log" />
                                                                                                                                                                                                        </div><!-- // #.input -->

                                                                                                                                                                                                      <div class="input clearfix">
                                                                                                                                                                                                                          <label for="user_pass">Password</label>
                                                                                                                                                                                                                          <input type="password" class="input validate valid-required" tabindex="3" size="22" value="" id="user_pass" name="pwd" />
                                                                                                                                                                                                      </div><!-- // #.input -->

                                                                                                                                                                                                      <div class="rememberme">
                                                                                                                                                                                                                          <label  for="rememberme">Remember Me
                                                                                                                                                                                                                                                <input id="rememberme" type="checkbox" name="rememberme" value="forever" tabindex="4" />
                                                                                                                                                                                                                          </label>
                                                                                                                                                                                                      </div>
                                                                                                                                                                <input type="submit" name="business_login_form_submit" id="login_form_submit" class="button-primary button submit-button" value="Log In" tabindex="5" />
                                                                                                                        </form>';
        $content.='<p><a href="'. get_bloginfo('url'). '/login/?action=lost-password">Forgot Password?</a></p>';
        $content.='<p>Don\'t have an account? <a href="'.get_option('regurl').'">Click here to Register</a></p>
                                                                                                    </div>
                                                                                </div>';
    }elseif( is_user_logged_in() ){
        $location = get_option('bclosingsurl');
        wp_redirect($location);
    }
    if ($echo)
        echo $content;
    else
        return $content;
}

function bd_manage_business_theme( $echo=true ) {
    global $current_user, $bd, $wpdb;
    /* Set $myBlogId to the ID of the site you want to query */
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    $settings = get_option('bd_settings');
    get_currentuserinfo();
    $userid = intval($current_user->ID);
    $content .='<div id="bd_manage_business" class="'.$current_user->ID.'">';
    if( is_user_logged_in() ) {
        $content .='<div class="title"><h2>MANAGE YOUR BUSINESS</h2></div>
                                                                                <div id="bd_manage_business_content">';
        $manage_query = new WP_Query('post_type=business&post_status=publish&author='.$userid.'');
        foreach($manage_query->posts as $post){
            $link =  get_option('addburl').'?edit='.$post->ID;
            $bd_email =  get_post_meta($post->ID, 'bd_user_mail', true);
            $zipcode = get_post_meta( $post->ID, 'bd_zipcode', true);
            $cityy = strtolower(get_post_meta( $post->ID, 'bd_city', true));
            $city = str_replace( ' ', '-', $cityy );
            $state = get_post_meta( $post->ID, 'bd_state', true);
            $day = get_post_meta( $post->ID, 'bd_commontime', true);
            $address = strtolower(get_post_meta( $post->ID, 'bd_address', true));
            $clsg = get_post( $post->ID );
            $grl = $clsg->post_name;
            $inqlink = get_option('businessurl').$city.'/'.$zipcode.'/'.$grl;
            $title = get_the_title($post->ID);
            $resttile = ( strlen( $title ) > 40 ) ? substr($title, 0, 40).' ...' : $title;
            $content .='<div id="bd_business_listing">
                                                                                                                                                                <div class="block1">
                                                                                                                                                                                    <h1 class="font_face"><a  href="'.$inqlink.'">'.$resttile.'</a></h1>';
            if ( is_user_logged_in() && ( $current_user->user_email == $bd_email ) ) {
                $content.='<div class="edit-link">
                                                                                                                                                                                                                   <a class="aleft editbusiness" href="'.$link.'" title="Edit Business"></a>
                                                                                                                                                                                                                   <a id="'.$post->ID.'" href="javascript:void(0);" class="delete_post deletebusiness" title="Delete Business"></a>
                                                                                                                                                                                                           </div>';
            }
            $content .='</div>
                                                                                                                                                                
                                                                                                                                                                <div class="block2">';
            $content.='<div class="stauts-alert">
                                                                                                                                                                                                                            <div class="sl_left">';
            if ( is_user_logged_in() && ( $current_user->user_email == $bd_email ) ) {
                $statuschk = ( !$zipcode || !$city || !$grl || !$address || !$state || !$day ) ? '<span><a class="" href="'.$link.'">Update missing fields in your business to Change Status</a></span>' : '<span><a class="" href="'.get_option('closingurl').'?p='.$post->ID.'&a=update">Change</a></span>';
                $content.='<h4>Current Status '.$statuschk.'</h4>';
            }else{
                $content.='<h4>Current Status</h4>';
            }
            $day1 = get_post_meta( $post->ID, 'bd_closing_day1', true);
            $day2 = get_post_meta( $post->ID, 'bd_closing_day2', true);
            $todaymessage = $day1['statusmsg'];
            $todaydesc = $day1['statusdesc'];
            $tomorrowmessage = $day2['statusmsg'];
            $tomorrowdesc = $day2['statusdesc'];
            $content.="<p>Today: ".$day1['cur_status']."</p>";
            $content.="<p>Tomorrow: ".$day2['cur_status']."</p>";
            $content.='</div>
                                                                                                                                                                                    </div>                                                                                                                                                             
                                                                                                                                                                </div>
                                                                                                                                           </div>';
        }
        $bcount = $manage_query->post_count;
        if( $bcount < 1 ){
            $content .='<div class="nobusiness">
                                                                                                                                            <strong>You don\'t have a business listing, please use add business</strong>
                                                                                                                            </div>';
        }
        $creds = array();
        $role =  ( get_user_meta( $current_user->ID, $wpdb->prefix.'capabilities', true ) ) ? get_user_meta( $current_user->ID, $wpdb->prefix.'capabilities', true ) : $creds;
        $key = key( $role );
        if ( $key == "business" || ( is_super_admin() ) ) {

            $content .='<div class="sl_right more_links manage-business">
                                                                                                                                                                <a class="button submit-button button-primary" href="'.get_option('addburl').'">ADD NEW BUSINESS</a>
                                                                                                                                            </div>';
            $content .='<div class="sl_right more_links manage-business">
                                                                                                                                                                <a class="button submit-button button-primary" href="'.get_option('bclosingsurl').'">VIEW CLOSINGS</a>
                                                                                                                                            </div>';
        }
        $content .='</div>';
    }else {
        $content .=  '<div class="success-message">Please <a href="'.get_option('loginurl').'">Login</a> to manage your businesses</div>';
    }
    $content .='</div>';
    if ($echo)
        echo $content;
    else
        return $content;
}

function getCurrentCatID(){
    global $wp_query;
    if(is_category() || is_single()){
        $cat_ID = get_query_var('cat');
    }
    return $cat_ID;
}

function searchword( $title ) {
    $stitle = explode( ' ', $title );
    $stitle = sanitize_title( ( $stitle[0] ) );
    return $stitle;
}

function string_in_array($string, $array) {
    $array = implode(" ", $array);
    if (strrpos($array, $string)) {
        // String is found
        return true;
    } else {
        // String was NOT found
        return false;
    }
}

function getBdFilteredResult( $array= array(),  $businesstype = NULL, $city = NULL, $status = NULL, $serachkey = NULL, $postsPerpage = NULL ) {
    $key1 = 'bcat';
    $key2 = 'city';
    $key3 = 'todayStatus';
    $key4 = 'tmrstatus';
    $filter = array();

    foreach ( $array as $subarray ) {

        /** GLOBAL SEARCH **/
        if($serachkey=="Search by name or ZIP"){
            $serachkey = NULL;
        }
        if( $businesstype || $city || $status ){
            $serachkey = NULL;
        }

        if( $serachkey != NULL ) {
            $serachkey =  sanitize_title($serachkey);
            if( !is_super_admin() ) {
                if( $subarray[$key3]!='search_normal_hours' ||  $subarray[$key4]!='search_normal_hours' ) {
                    $sval = string_in_array($serachkey,$subarray);
                    if($sval){
                        $filter[] =       $subarray;
                    }
                }
            }else{
                $sval = string_in_array($serachkey,$subarray);
                if($sval){
                    $filter[] =       $subarray;
                }
            }
        }else{
            /** FOR BUSINESS USER **/
            if( !is_super_admin() )  {

                if( $subarray[$key3]!='search_normal_hours' ||  $subarray[$key4]!='search_normal_hours' ) {
                    if( $city != NULL &&  $businesstype == NULL ) {
                        if( $subarray[$key2] == $city ){
                            $filter[] =     $subarray;
                        }
                    }
                    if( $businesstype != NULL  && $city ==  NULL ) {
                        if( $subarray[$key1] == $businesstype ){
                            $filter[] =       $subarray;
                        }
                    }
                    if( $businesstype != NULL && $city != NULL ) {
                        if(  $subarray[$key1] == $businesstype && $subarray[$key2] == $city ) {
                            $filter[] =     $subarray;
                        }
                    }
                    if( $businesstype == NULL && $city == NULL ) {
                        $filter[] =     $subarray;
                    }
                }
                /** FOR SUPER ADMIN USER **/
            }else if( is_super_admin() ){

                if( $businesstype !=NULL && $city !=NULL && $status != NULL ) {
                    if( $subarray[$key1] == $businesstype && $subarray[$key2] == $city && ( $subarray[$key3] == $status || $subarray[$key4] == $status ) ) {
                        $filter[] =     $subarray;
                    }
                }
                if( $businesstype == NULL && $city == NULL && $status != NULL ) {
                    if( ( $subarray[$key3] == $status || $subarray[$key4] == $status ) ) {
                        $filter[] =     $subarray;
                    }
                }
                if( $businesstype == NULL && $city != NULL && $status == NULL ) {
                    if( $subarray[$key2] == $city ) {
                        $filter[] =     $subarray;
                    }
                }

                if( $businesstype !=NULL && $city == NULL && $status == NULL  ) {
                    if( $subarray[$key1] == $businesstype ) {
                        $filter[] =     $subarray;
                    }
                }

                if( $businesstype != NULL && $city != NULL && $status == NULL ) {
                    if( $subarray[$key1] == $businesstype &&  $subarray[$key2] == $city ) {
                        $filter[] =     $subarray;
                    }
                }
                if( $businesstype == NULL && $city != NULL && $status != NULL ) {
                    if(  $subarray[$key2] == $city && ( $subarray[$key3] == $status || $subarray[$key4] == $status ) ) {
                        $filter[] =     $subarray;
                    }
                }
                if( $businesstype != NULL && $city == NULL && $status != NULL ) {
                    if(  $subarray[$key1] == $businesstype && ( $subarray[$key3] == $status || $subarray[$key4] == $status ) ) {
                        $filter[] =     $subarray;
                    }
                }
                if( $businesstype ==NULL && $city ==NULL && $status == NULL ) {
                    $filter[] =     $subarray;
                }
            }
        }
    }

    $Num_Rows = count( $filter );
    $pageid = $_POST['page'] - 1;
    $startnumber = $pageid * $postsPerpage;
    if ( $_POST['page']=='' ){
        $strPage = '1';
    }
    else{
        $strPage = $_POST['page'];
    }
    // Records Per Page
    $Per_Page = $postsPerpage;
    $Page = $strPage;
    if( !$strPage ) {
        $Page=1;
    }

    $Prev_Page = $Page-1;
    $Next_Page = $Page+1;
    $Page_Start = ( ( $Per_Page*$Page ) - $Per_Page  );

    if( $Num_Rows <= $Per_Page ){
        $Num_Pages =1;
    }
    else if(  @( $Num_Rows % $Per_Page) == 0 ){
        $Num_Pages = @( $Num_Rows / $Per_Page ) ;
    }else{
        $Num_Pages =( $Num_Rows / $Per_Page ) + 1;
        $Num_Pages = ( int )$Num_Pages;
    }

    //echo $Num_Pages;
    $param = $Page_Start.','.$Per_Page.'<br/>';
    $pageend =  $Per_Page*$Page-1;
    $Num_Rows;


    for( $Page_Start; $Page_Start <= $pageend;  $Page_Start++ ) {
        $rs[] =  $filter[$Page_Start];
    }

    $current = ( $_POST['page'] ) ? $_POST['page']: 1;

    if ( $Num_Pages > 1 ){

        $index_limit = 3;
        $page_size = $postsPerpage;
        $total = $Num_Rows;

        $total_pages=ceil($total/$page_size);
        $start=max($current-intval($index_limit/2), 1);
        $end=$start+$index_limit-1;

        $content.= '<div class="resultbg pagination top"><ul>';

        if($current==1) {
            //             $content.= '<li class="btn_dis first">&laquo; Prev</span> </li>';
        } else {
            $i = $current-1;
            $content.= '<li class="pbutton"><span class="pllink" id="'.$i.'">&laquo; Prev</span> </li>';
        }

        if($start > 1) {
            $i = 1;
            $content.= '<li> <span class="pllink" id="'.$i.'">'.$i.'</span> </li>';
            if( $start && $current-1 != 2 ){
                $content.='<li class="dot">....</li>';
            }
        }

        for ( $i = $start; $i <= $end && $i <= $total_pages; $i++ ) {
            if( $i==$current ) {
                $content.= '<li class="currentpage"><span>'.$i.'</span></li>';
            } else {
                $content.= '<li> <span class="pllink" id="'.$i.'">'.$i.'</span> </li>';
            }
        }

        if( $total_pages > $end ) {
            $i = $total_pages;
            if( $total_pages != 4 ) {
                if( $current < ( $total_pages-2 ) ) {
                    $content.='<li class="dot">....</li>';
                }
            }
            $content.= '<li> <span class="pllink" id="'.$i.'">'.$i.'</span> </li>';
        }

        if($current < $total_pages) {
            $i = $current+1;
            $content.= '<li class="pbutton"><span class="pllink" id="'.$i.'">Next &raquo;</span> </li>';
        } else {
            // $content.= '<li class="btn_dis">Next &raquo;</span> </li>';
        }

        $content.='</ul></div>';
    }
    echo $content;
    $content1 .='<div class="status_header">
                                                                                                    <div class="location"><h4> Name </h4></div>
                                                                                                    <div class="status"><h4>Current Status </h4></div>
                                                                                                    <div class="details"><h4> Details </h4></div>
                                                                                </div>';
    if( empty($filter) ) {
        $content1.='<div id="termList"></div>';
        $content1.= '<span class="loader rmv">';
        $content1.= 'SORRY! NO RESULTS FOUND.';
        $content1.= '</span>';

//                                           if ( is_user_logged_in() ||  is_super_admin() ) {
//                                                               $content1 .='<div class="sl_right more_links manage-business"><a class="button submit-button button-primary" href="'.get_option('addburl').'">ADD NEW BUSINESS</a></div>';
//                                                               $content1 .='<div class="sl_right more_links manage-business"><a class="button submit-button button-primary" href="'.get_option('manageurl').'">MANAGE BUSINESSES</a></div>';
//                                        }
//                                                            echo $content1;
//                                                          // exit;
//                                                                                ?>
    <style type="text/css"> .default-result{ border: none; }</style>
    <?php }

    echo $content1;
    // exit;
    return $rs;
}

function getBusinessResult() {
    global $wp_query, $wpdb, $bd, $current_user;
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    $settings = get_option('bd_settings');
    $bid = get_current_blog_id();
    $key = $bid.'_bd_cache';
    //  $result = wp_cache_get( $key );

    $m = new Memcache();
    //    foreach(preg_split("/,/", MEMCACHED_HOSTS) as $mchost){
    foreach(preg_split( "/,/", MEMCACHED_HOSTS_NOFLUSH ) as $mchost ){
        list($server,$port)=preg_split("/:/",$mchost);
        $m->addServer($server, $port);
    }

    $result=$m->get($key);

    //  $result =  get_transient( $key );
    $theCatId = get_term_by( 'slug', $_POST['catname'], 'business_type' );
    $theCatId = $theCatId->term_id;

    if ( false === ( $value = $result ) ){
        $content.='<input type="hidden" value="'.$key.'Not found" />';

        get_currentuserinfo();
        $userRole = ($current_user->data->wp_capabilities);
        $role = $userRole;
        $zip =  get_option('bd_siteZipcode');
        $temp = explode( ',',$zip );
        $zipcode_val = array();
        for ( $k=0; $k < count( $temp ); $k++ ){
            array_push( $zipcode_val, trim($temp[ $k ]) );
        }
        $filters[] = array( 'key' => 'bd_zipcode', 'value' => $zipcode_val,'compare' => 'IN' );
        $posts_per_page = 1;
        $pageid = $_POST['page']-1;
        $offset = $pageid * $posts_per_page;
        $args = array(
            'post_type'=>'business',
            'post_status'=>'publish',
            'meta_query' => $filters,
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'

        );

        $the_query = new WP_Query( $args );
        if ( $the_query->post_count > 0 ) {
            $bd_cache= array();
            $result= array();

            foreach( $the_query->posts as $post ){
                $mpostid = $post->ID;
                $day1 = get_post_meta( $mpostid, 'bd_closing_day1', true);
                $day1Cstatus = searchword( $day1['cur_status'] );
                $day2 = get_post_meta( $mpostid, 'bd_closing_day2', true);
                $day2Cstatus = searchword( $day2['cur_status'] );
                $bname = ShortenText( get_the_title( $mpostid ), 45 );
                $searchtitle = searchword( get_the_title( $mpostid ) );
                $zipcode = get_post_meta( $mpostid, 'bd_zipcode', true );
                $bd_email = get_post_meta( $mpostid, 'bd_user_mail', true );
                $todayStatus = get_post_meta( $mpostid, 'bd_day1_search_string', true );
                $tmrstatus = get_post_meta( $mpostid, 'bd_day2_search_string',true );
                $todaymessage = $day1['statusmsg'];
                $todaydesc = $day1['statusdesc'];
                $tomorrowmessage = $day2['statusmsg'];
                $tomorrowdesc = $day2['statusdesc'];
                $bcat = get_post_meta( $mpostid, 'bd_cat',true );
                $bcity = get_post_meta( $mpostid, 'bd_city',true );
                $cityy = strtolower( $bcity );
                $city = str_replace( ' ', '-', $cityy );
                $clsg = get_post( $mpostid );
                $grl = $clsg->post_name;
                $inqlink = get_option( 'businessurl' ).$city.'/'.$zipcode.'/'.$grl;
                $link = get_option('closingurl').'?p='.$mpostid.'&a=update';
                $day1status = get_post_meta( $mpostid, 'bd_day1_status',true );
                $day2status = get_post_meta( $mpostid, 'bd_day2_status',true );


                $bd_cache['postid']                  =     $mpostid;
                $bd_cache['day1']                      =     $day1;
                $bd_cache['day2']                      =     $day2;
                $bd_cache['day1Cstatus']       =     $day1Cstatus;
                $bd_cache['day2Cstatus']       =     $day2Cstatus;
                $bd_cache['bname']                  =     $bname;
                $bd_cache['searchtitle']           =     $searchtitle;
                $bd_cache['zipcode']                =     $zipcode;
                $bd_cache['city']                         =     $cityy;
                $bd_cache['todayStatus']        =     $todayStatus;
                $bd_cache['tmrstatus']             =     $tmrstatus;
                $bd_cache['todaymessage']   =     $todaymessage;
                $bd_cache['todaydesc']           =     $todaydesc;
                $bd_cache['tomorrowmessage']           =     $tomorrowmessage;
                $bd_cache['tomorrowdesc']           =     $tomorrowdesc;
                $bd_cache['bcat']           =     $bcat;
                $bd_cache['link']           =     $link;
                $bd_cache['bd_email']           =     $bd_email;
                $bd_cache['inqlink']      =     $inqlink;
                $bd_cache['day1status']      =     $day1status;
                $bd_cache['day2status']      =     $day2status;
                $result[]  = $bd_cache;
            }
        }
        echo $content;
//                                                            $wpdb->blogid = $bid;
//                                                            $wpdb->seta_prefix( $wpdb->base_prefix );
        $key = $bid.'_bd_cache';
        //$m->set($key, $result);
        if ( !defined('CLOSINGS_EXPIRE_TIME') ){
            define('CLOSINGS_EXPIRE_TIME', 3600);
        }
        $m->set( $key, $result, 0, CLOSINGS_EXPIRE_TIME );

        //  wp_cache_set( $key, $result );

        //set_transient( $bid.'_bd_cache2', $result,  60*60*12  );
    }else{
        $content.='<input type="hidden" value="'.$key.'"  Cached Result" />';
    }
    $perpage =  ( get_site_option( 'per_page' ) ) ? get_site_option( 'per_page' ) : '10';
    $filteredresults = @getBdFilteredResult( $result, $theCatId, strtolower( $_POST['cityname'] ),  $_POST['statusid'], $_POST['bsearch'], $perpage );
//                    $content.= '<div class="status_header">
//                                                            <div class="location"><h4> Name </h4></div>
//                                                            <div class="status"><h4>Current Status </h4></div>
//                                                            <div class="details"><h4> Details </h4></div>
//                                        </div>';

    $content.='<div id="termList"></div>';
    $content.= '<span class="default-result"><ul>';
    foreach( $filteredresults as $filteredresult ) {
        if(  !empty( $filteredresult ) ) {
            $content.= '<li>';
            if ( ( $current_user->user_email == $filteredresult['bd_email'] ) ||  is_super_admin() ) {
                $content.= '<div class="edit-link">
                                                                                                                                       <a class="aleft editbusiness" href="'.$filteredresult['link'].'" title="Change Status"></a>
                                                                                                                                       <a id="'.$filteredresult['postid'].'" class="delete_post deletebusiness" title="Delete Business" href="javascript:void(0);"></a>
                                                                                                                           </div>';
            }
            $content.= '<div class="blockinfo">
                                                                                                                        <span class="locationblock">
                                                                                                                        <h4 class="title font_face"><a  href="'.$filteredresult['inqlink'].'">'.$filteredresult['bname'].'</a></h4>';
            $content.= '<p>'.$filteredresult['city'].'</p>
                                                                                                                       </span> 
                                                                                                                          <div class="statusblock">
                                                                                                     
                                                                                                                        <span class="status1">';
            if ( $filteredresult['day1status'] == 'normal_hours' ) {
                $content .='<p>Normal Hours</p>';
            }
            if ( $filteredresult['day1status'] == 'early_dismisal' ) {
                $content .='<p>Early Dismissal</p>';
            }
            if ( $filteredresult['day1status'] != 'normal_hours' && $filteredresult['day1status'] != 'early_dismisal' ) {
                $content .='<p>'.$filteredresult['day1status'].'</p>';
            }
            $content .='</span>
                                                                                                                                             
                                                                                                                         <span class="status1detail">';
            $content .='<p>'. $filteredresult['day1']['statusmsg'].'</p>';
            $content .='</span>
                                                                                                                                              
                                                                                                                           <span class="status1">';
            if ( $filteredresult['day2status']  == 'normal_hours' ) {
                $content .='<p>Normal Hours</p>';
            }
            if ( $filteredresult['day2status']  == 'early_dismisal' ) {
                $content .='<p>Early Dismissal</p>';
            }
            if ( $filteredresult['day2status'] != 'normal_hours' && $filteredresult['day2status'] != 'early_dismisal' ) {
                $content .='<p>'.$filteredresult['day2status'].'</p>';
            }
            $content .='</span>
                                                                                                                                             
                                                                                                                        <span class="status1detail">';
            $content .='<p>'. $filteredresult['day2']['statusmsg'].'</p>';

            $content.= '</span>
                                                                                                      </div></div>';
            $content.= '</li>';
        }
    }
    $content.= '</ul></span>';
    //  if ( ( $current_user->user_email == $filteredresults[0]['bd_email'] ) || ( is_super_admin() ) ){
    if ( is_user_logged_in() ||  is_super_admin() ) {
        $content .='<div class="sl_right more_links manage-business"><a class="button submit-button button-primary" href="'.get_option('addburl').'">ADD NEW BUSINESS</a></div>';
        $content .='<div class="sl_right more_links manage-business"><a class="button submit-button button-primary" href="'.get_option('manageurl').'">MANAGE BUSINESSES</a></div>';
    }
    echo $content;

    if( $_POST['bsearch'] )
        die();
}

function bd_business_list($echo=true) {
    global $wp_query, $wpdb, $bd, $current_user;
    get_currentuserinfo();
    $san =  get_option('bd_siteZipcode');
    $temp = explode(',',$san);
    $zipcode_val1 = array();
    for ( $k=0;$k<count($temp);$k++ ){
        array_push( $zipcode_val1, trim( $temp[$k] ) );
    }
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    $settings = get_option('bd_settings'); ?>

                    <div id="bd_disruptionsDiv">
                                        <div class="title">
                                            <div class="wrap-left">
                                                <h1 class="heading title font_face">Closings & Delays</h1>
                                                <p>Local alerts for the next 48 hours</p>
                                            </div>

                                            <div class="input clearfix changeCat bdsearch">
                                                <div class="business-search">
                                                    <input type="text" name="business-search-text" class="business-search-text" value="Search by name or ZIP" />
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
                                                <?php $tms = get_terms( 'business_type', 'hide_empty=0'); ?>
                                                <select name="cats" class="changeCats">
                                                    <option value=""  class="seperator"> Business Type </option>
                                                    <?php   foreach( $tms as $tm ){ ?>
                                                    <option value="<?php echo $tm->slug; ?>">
                                                        <?php echo $tm->name; ?>
                                                    </option>
                                                    <?php   }  ?>
                                                </select>
                                                <?php
                                                $filters[] = array( 'key' => 'bd_zipcode', 'value' => $zipcode_val1, 'type'=>'numeric', 'compare' => 'IN' );
                                                $args = array(
                                                    'post_type'=>'business',
                                                    'post_status'=>'publish',
                                                    'meta_query' => $filters,
                                                    'posts_per_page' => -1,
                                                    'orderby' => 'title',
                                                    'order' => 'ASC'
                                                );
                                                $the_query = new WP_Query( $args);
                                                while ( $the_query->have_posts() ) : $the_query->the_post();
                                                    $cityselect[] = strtolower( get_post_meta( $the_query->post->ID, 'bd_city', true ) );
                                                endwhile;
                                                if ( $the_query->post_count > 0 ) {
                                                    ?>
                                                    <select name="cat" class="changeCity">
                                                        <option value=""  class="seperator"> City </option>
                                                        <?php
                                                        $city_result  = array_unique($cityselect);
                                                        foreach( $city_result as $uniqcity ) {
                                                            ?>
                                                            <option value='<?php echo $uniqcity; ?>'><?php echo $uniqcity; ?></option>
                                                            <?php  }
                                                        $cityarray[] = $uniqcity;
                                                        ?>
                                                    </select>
                                                    <?php
                                                    ?>
                                                    <?php } if( is_super_admin() ) { ?>
                                                <select name="status" class="changeStatus">
                                                    <option value=""  class="seperator"> Status </option>
                                                    <option value='search_normal_hours'>Normal Hours</option>
                                                    <option value='search_early_dismisal'>Early Dismisal</option>
                                                    <option value='search_delayed'>Delayed</option>
                                                    <option value='search_closed'>Closed</option>
                                                </select>
                                                <select name="cdate" class="changeDate">
                                                    <option value="" class="today"> Next 48 Hours </option>
                                                    <option value="today" class="today"> Today </option>
                                                    <option value='tomorrow'>Tomorrow</option>
                                                </select>
                                                <?php  } ?>
                                            </div>
                                        </div>
    <?php
    //closingExpiry();
    echo '<div class="status-container">';
    getBusinessResult();
    echo '</div></div>';
}

/** TIME ZONE CONVERTION FROM CURRENT TO REQUIRED **/
function ConvertOneTimezoneToAnotherTimezone($time,$currentTimezone,$timezoneRequired){
    $system_timezone = date_default_timezone_get();
    $local_timezone = $currentTimezone;
    date_default_timezone_set($local_timezone);
    $local = date("m/d/Y h:i:s A");

    date_default_timezone_set("GMT");
    $gmt = date("m/d/Y h:i:s A");

    $require_timezone = $timezoneRequired;
    date_default_timezone_set($require_timezone);
    $required = date("m/d/Y h:i:s A");

    date_default_timezone_set($system_timezone);

    $diff1 = (strtotime($gmt) - strtotime($local));
    $diff2 = (strtotime($required) - strtotime($gmt));

    $date = new DateTime($time);
    $date->modify("+$diff1 seconds");
    $date->modify("+$diff2 seconds");
    $timestamp = $date->format("m/d/Y H:i:s");
    return $timestamp;
}

/** BUSINESS CLOSING **/
function bd_business_closing($echo=true){
    global $wp_query, $bd,$wpdb ;

    /* Set $myBlogId to the ID of the site you want to query */
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    $settings = get_option('bd_settings');
    $zipCode = get_post_meta( $_GET['p'], 'bd_zipcode', true);
    $retrieveTimzone =  "SELECT timezone FROM ts_zipcode WHERE zipcode='$zipCode'";
    $termresult = $wpdb->get_var( $retrieveTimzone );
    $timeZone = $termresult;

    //echo $timeZone;
    //  $timeZone = 'GMT';
    switch( $timeZone ){
        case 'Pacific':
            $userZone = "America/Los_Angeles";
            date_default_timezone_set( "America/Los_Angeles" );
            $timzondate = date("m/d/Y H:i:s A");
            break;
        case 'Eastern':
            $userZone = "America/New_York";
            date_default_timezone_set( "America/New_York" );
            $timzondate = date("m/d/Y H:i:s A");
            break;
        case 'Mountain':
            $userZone = "America/Phoenix";
            date_default_timezone_set( "America/Phoenix" );
            $timzondate = date("m/d/Y H:i:s A");
            break;
        case 'Central':
            $userZone = "America/Chicago";
            date_default_timezone_set( "America/Chicago" );
            $timzondate = date("m/d/Y H:i:s A");
            break;
        case 'Hawaii':
            $userZone = "Pacific/Honolulu";
            date_default_timezone_set( "Pacific/Honolulu" );
            $timzondate = date("m/d/Y H:i:s A");
            break;
        case 'Alaska':
            $userZone = "America/Anchorage";
            date_default_timezone_set( "America/Anchorage" );
            $timzondate = date("m/d/Y H:i:s A");
            break;
        default:
            $userZone = 'GMT';
            $timzondate = date("m/d/Y H:i:s A");
            break;
    }

    echo '<div id="bd_closings_alerts">
                                            <div class="backDiv"><a href="'.get_option('bclosingsurl').'">&laquo; Back to Closings</a></div>';
    if ($_GET['p'] != "firsttime")
        echo '<h1 class="font_face legcymig closingt">Submit Closing Alert</h1><span><a href="'.get_option('manageurl').'" title="Add and manage businesses">Add and manage businesses</a></span>';

    if ($_GET['p'] == "firsttime"){
        $content .='<div class="success-message">Your Business has been registered. Please <a href="'.get_option('loginurl').'">login</a> to submit disruptions</div>';
    }else{
        if (!is_user_logged_in()){
            wp_redirect(get_option('loginurl'));
        }

        $postid = $_GET['p'];
        $todayDetails1 = get_post_meta($postid, 'bd_closing_day1', true);
        $tomorrowDetails1 = get_post_meta($postid, 'bd_closing_day2', true);
        //echo $tomorrowDetails1['cur_status'];

        if($_GET["a"] =="update" ) {
            $todayStatus = get_post_meta($postid, 'bd_day1_status', true);
            $tomorrowStatus = get_post_meta($postid, 'bd_day2_status', true);
            $todayDetails = get_post_meta($postid, 'bd_closing_day1', true);

            $tomorrowDetails = get_post_meta($postid, 'bd_closing_day2', true);
            $todaydesc = $todayDetails['statusdesc'];
            $tomorrowdesc = $tomorrowDetails['statusdesc'];
            //print_r($todayDetails);

            if ( strtolower($todayStatus) == "closed"){
                $cfromhr = $todayDetails['time']['starttime'];
                $ctohr = $todayDetails['time']['endtime'];
                $calldayclosed = $todayDetails['time']['closedallday'];
            }
            if ( strtolower($tomorrowStatus) == "closed"){

                $ctfromhr = $tomorrowDetails['time']['starttime'];
                $cttohr = $tomorrowDetails['time']['endtime'];
                $talldayclosed = $tomorrowDetails['time']['closedallday'];
            }
            if ( strtolower($todayStatus) == "delayed"){
                $dtimehr = $todayDetails['time']['starttime'];
                $dtimemin = $todayDetails['time']['endtime'];
            }
            if (strtolower($tomorrowStatus) == "delayed"){
                $tomorrow_dtimehr = $tomorrowDetails['time']['starttime'];
                $tomorrow_dtimemin = $tomorrowDetails['time']['endtime'];
            }
            if (strtolower($todayStatus) == "early_dismisal"){
                $edtohr = $todayDetails['time']['starttime'];
            }
            if (strtolower($tomorrowStatus) == "early_dismisal"){
                $tomorrow_edtohr = $tomorrowDetails['time']['starttime'];
            }
        }

        if( isset( $_POST['submit_closing'] ) ) {

            if( $_POST['day1Croncheck'] =='dayoff' ) {
                $_POST['todayChangeStatus']='closed';
                $_POST['calldayclosed']='alldayclosed';
                $expiry =date("m/d/Y 23:50:00");
            }
            if( $_POST['day2Croncheck'] =='dayoff' ) {
                $_POST['tomorrowChangeStatus']='closed';
                $_POST['talldayclosed']='alldayclosed';
                $expiry_tom0 =date("m/d/Y 23:50:00", time()+86400);
            }

            update_post_meta( $postid, 'bd_day1Cronchek', $_POST['day1Croncheck'] );
            update_post_meta( $postid, 'bd_day2Cronchek', $_POST['day2Croncheck'] );
            if ( $_POST['todayChangeStatus'] == "closed" ) {
                $statusTxt = "Closed";
                // formating to pass ConvertOneTimezoneToAnotherTimezone methhod
                $_POST['ctohr'];
                $hd = strtotime( $_POST['ctohr'].':00:00' );
                // converting to hour min sec format
                //  echo date('T');
                $tm =  date( 'H:i:s',$hd );
                $h = ( $tm ) ? $tm :'00:00:00';
                // convering to date and time fomat
                $tm = ($_POST['ctohr']=='none')?date( 'm/d/Y 23:55:00'):date( 'm/d/Y '.$h );
                $gmtbasedtime = ConvertOneTimezoneToAnotherTimezone($tm,$userZone,$userZone);
                // $date = date( 'm/d/Y H:i:s', strtotime( $gmtbasedtime ) );
                $today_time = Array( 'closedallday' => $_POST['calldayclosed'],'starttime' => $_POST['cfromhr'],'endtime' => $_POST['ctohr'] );
                $expiry = $gmtbasedtime;

            }else if ($_POST['todayChangeStatus'] == "delayed") {
                $statusTxt = "Delayed";
                // formating to pass ConvertOneTimezoneToAnotherTimezone methhod
                // $_POST['today_delayexpiry'];
                $hd = strtotime( $_POST['today_delayexpiry']);
                // converting to hour min sec format
                //echo $timzondate;
                $tm = date( 'H:i:s',$hd );
                $h = ( $tm != '???' ) ? $tm :'00:00:00';
                // convering to date and time fomat
                $tm = date( 'm/d/Y '.$h );
                $gmtbasedtime = ConvertOneTimezoneToAnotherTimezone( $tm, $userZone, $userZone );
                // echo $date = date( 'm/d/Y H:i:s', strtotime( $gmtbasedtime ) );
                $today_time = Array( 'starttime'=>$_POST['dtimehr'], 'endtime' => $_POST['dtimemin'] );
                $expiry = $gmtbasedtime;
            }else if ($_POST['todayChangeStatus'] == "early_dismisal"){
                $statusTxt = "Early Dismissal";
                // formating to pass ConvertOneTimezoneToAnotherTimezone methhod
                $hd = strtotime( $_POST['edtohr'].':00:00' );
                // converting to hour min sec format
                $tm =  date( 'H:i:s',$hd );
                $h = ( $tm ) ? $tm :'00:00:00';
                // convering to date and time fomat
                $tm = date( 'm/d/Y '.$h );
                $gmtbasedtime = ConvertOneTimezoneToAnotherTimezone($tm,$userZone,$userZone);
                $today_time = Array( 'starttime' => $_POST['edtohr'] );
                $expiry = $gmtbasedtime;
            }
            else if ($_POST['todayChangeStatus'] == "normal_hours"){
                $statusTxt = "Normal Hours";
                $today_time = Array('starttime' => '');
            }

            if ($_POST['tomorrowChangeStatus'] == "closed"){
                $statusTxt2 = "Closed";
                // formating to pass ConvertOneTimezoneToAnotherTimezone methhod
                $hd = strtotime( $_POST['cttohr'].':00:00' );
                // converting to hour min sec format
                $tm =  date( 'H:i:s',$hd );
                $h = ( $tm ) ? $tm :'00:00:00';
                // convering to date and time fomat
                $day = date("d")+1;
                $tm = ($_POST['cttohr']=='none') ? date( 'm/'.$day.'/Y 23:55:00') :date( 'm/'.$day.'/Y '.$h );
                //$tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
                date("m/d/y", $tomorrow);
                $gmtbasedtime_tomo = ConvertOneTimezoneToAnotherTimezone($tm,$userZone,$userZone);
                $time_tomo = Array( 'closedallday' => $_POST['talldayclosed'],'starttime' => $_POST['ctfromhr'],'endtime' => $_POST['cttohr'] );
                $expiry_tom0 = $gmtbasedtime_tomo;

            }
            else if ($_POST['tomorrowChangeStatus'] == "delayed"){
                $statusTxt2 = "Delayed";
                // $_POST['tomo_delayexpiry'];
                // formating to pass ConvertOneTimezoneToAnotherTimezone methhod
                $hd = strtotime( $_POST['tomo_delayexpiry'] );
                // converting to hour min sec format
                $tm =  date( 'H:i:s',$hd );
                $h = ( $tm ) ? $tm :'00:00:00';
                // convering to date and time fomat
                $day = date("d")+1;
                $tm = date( 'm/'.$day.'/Y '.$h );
                $gmtbasedtime_tomo = ConvertOneTimezoneToAnotherTimezone($tm,$userZone,$userZone);
                $time_tomo = Array( 'starttime' => $_POST['tomorrow_dtimehr'],'endtime' => $_POST['tomorrow_dtimemin'] );
                $expiry_tom0 = $gmtbasedtime_tomo;
            }
            else if ($_POST['tomorrowChangeStatus'] == "early_dismisal"){
                $statusTxt2 = "Early Dismissal";
                // formating to pass ConvertOneTimezoneToAnotherTimezone methhod
                $hd = strtotime( $_POST['tomorrow_edtohr'].':00:00' );
                // converting to hour min sec format
                $tm =  date( 'H:i:s',$hd );
                $h = ( $tm ) ? $tm :'00:00:00';
                // convering to date and time fomat
                $day = date("d")+1;
                $tm = date( 'm/'.$day.'/Y '.$h );
                $gmtbasedtime_tomo = ConvertOneTimezoneToAnotherTimezone($tm,$userZone,$userZone);
                $time_tomo = Array('starttime' => $_POST['tomorrow_edtohr'] );
                $expiry_tom0 = $gmtbasedtime_tomo;
            }
            else if ($_POST['tomorrowChangeStatus'] == "normal_hours"){
                $statusTxt2 = "Normal Hours";
                $time_tomo = Array('starttime' => '');
            }

            $closingsday1_details = Array(
                'date' => $_POST['day1_date'],
                'time' => $today_time,
                'expiry' => $expiry,
                'cur_status' => $statusTxt,
                'statusmsg' =>$_POST['day1message'],
                'statusdesc' =>$_POST['closingDetails_day1'],
            );
            $closingsday2_details = Array(
                'date' => $_POST['day2_date'],
                'time' => $time_tomo,
                'expiry' => $expiry_tom0,
                'cur_status' => $statusTxt2,
                'statusmsg' =>$_POST['day2message'],
                'statusdesc' =>$_POST['closingDetails']
            );

            $dh_count = get_post_meta($postid, 'bd_closing_dhistory_count');
            $kk=$dh_count[0];


            if ( isset( $_POST['todayChangeStatus'] ) ){
                delete_post_meta( $postid, 'bd_closing_day1' );
                if ( $todayDetails1['cur_status']!="Normal Hours" && $todayDetails1['statusmsg']!=$closingsday1_details['statusmsg'] ){
                    $kk = $kk+1;
                    update_post_meta( $postid, 'bd_closing_dhistory_'.$kk.'', $todayDetails1 );
                }
                update_post_meta( $postid, 'bd_closing_day1', $closingsday1_details );
                update_post_meta( $postid, 'bd_day1_status', $_POST['todayChangeStatus'] );

                update_post_meta( $postid, 'bd_day1_search_string','search_'.$_POST['todayChangeStatus'].'' );
            }

            if ( isset( $_POST['tomorrowChangeStatus'] ) ){

                delete_post_meta( $postid, 'bd_closing_day2' );
                if ( $tomorrowDetails1['cur_status']!="Normal Hours" && $tomorrowDetails1['statusmsg']!=$closingsday2_details['statusmsg'] ){
                    $kk = $kk+1;
                    update_post_meta( $postid, 'bd_closing_dhistory_'.$kk.'', $tomorrowDetails1 );
                }
                update_post_meta( $postid, 'bd_closing_day2', $closingsday2_details );
                update_post_meta( $postid, 'bd_day2_status', $_POST['tomorrowChangeStatus'] );

                update_post_meta( $postid, 'bd_day2_search_string','search_'.$_POST['tomorrowChangeStatus'].'' );
            }

            update_post_meta( $postid, 'bd_closing_dhistory_count', $kk );
            $zipcode = get_post_meta( $postid, 'bd_zipcode', true );
            $cityy = strtolower( get_post_meta( $postid, 'bd_city', true ) );
            $city = str_replace( ' ', '-', $cityy );
            $clsg = get_post( $postid );
            $grl = $clsg->post_name;
            $inqlink = get_option( 'businessurl' ).$city.'/'.$zipcode.'/'.$grl;
            wp_redirect( $inqlink );
        }

        global $current_user, $wpdb;
        /* Set $myBlogId to the ID of the site you want to query */
        $wpdb->blogid = $bd->centralBid;
        $wpdb->set_prefix( $wpdb->base_prefix );
        get_currentuserinfo();
        $userid = $current_user->ID;
        $content .='<form enctype="multipart/form-data" method="POST" action="" class="login-registration validate clearfix" id="closing_form" onsubmit="return validateClosingsForm();">
                                        <div class="input  clearfix">';
        $custom_query = new WP_Query( 'post_type=business&post_status=publish&author='.$userid );
        $post_id = $_GET["p"];
        $postname = get_post( $post_id );
        $title = $postname->post_title;
        $resttile = ( strlen( $title ) > 43 ) ? substr( $title, 0, 43 ).' ...' : $title;

        $tday =  strtolower( date( "l" ) );
        $tomorrowday =  strtolower( date( "l", time()+86400 ) );

        $businessTimingsToday = get_post_meta( $postid, 'bd_'.$tday );


        $businessTimingsTomorrow = get_post_meta( $postid, 'bd_'.$tomorrowday );

        $businessStartTime = $businessTimingsToday[0]['start'];
        $businessEndTime = $businessTimingsToday[0]['end'];

        $businessStartTimeTmr = $businessTimingsTomorrow[0]['start'];
        $businessEndTimeTmr = $businessTimingsTomorrow[0]['end'];

        $select = getTimeListForSelect( $businessStartTime,$businessEndTime );
        $selectTorrow = getTimeListForSelect( $businessStartTimeTmr,$businessEndTimeTmr );

        // IF DAY OFF SET DEFAULT RESULT TO CLOSE FULL DAY
        $dname = strtolower(date('l'));
        $tomodname = strtolower(date('l', time()+86400));
        $day1off =  ( !get_post_meta( $post_id, 'bd_'.$dname, true ) )?'dayoff':'';
        $day2off =  ( !get_post_meta( $post_id, 'bd_'.$tomodname, true ) )?'dayoff':'';

        $content.='<label for="closingBname"><strong>Business Name</strong></label>
                                                                                      <input type="text" disabled="disabled" name="bname" id="bname" value="'.$resttile.'" rel="'.get_post_meta($post->ID, 'bd_city',true).'" otime="'.$businessStartTime.'" totime="'.$businessStartTimeTmr.'"/>';


        $content .='<div class="clearfix"></div>
                                        <div class="todayDiv">
                                                                               <div id="todayAllFieldsHolder">
                                                                                                   <div class="cdate">
                                                                                                                         <label><strong>Today&nbsp;</strong> ('.date("m/d/Y" ).')</label>
                                                                                                                         <input type="hidden" value="'.date("m/d/Y").'" name="day1_date"/>
                                                                                                                        <select class="todayChangeStatus" name="todayChangeStatus" id="todayChangeStatus">
                                                                                                                                            <option value="normal_hours">Normal Hours</option>
                                                                                                                                            <option value="closed">Closed</option>
                                                                                                                                            <option value="delayed">Delayed by</option>
                                                                                                                                            <option value="early_dismisal">Early Dismissal</option>
                                                                                                                        </select> 
                                                                                                    </div>
                                                                                                    <input type="hidden" value="" name="today_delayexpiry"  id="today_delayexpiry" />
                                                                                                    <input type="hidden" value="" name="tomo_delayexpiry"  id="tomo_delayexpiry" />
                                                                                                    <div id="today_closed_details" class="gholder">
                                                                                                                        <div class="allday">
                                                                                                                                            <input type="checkbox" name="calldayclosed" id="calldayclosed" '.(($calldayclosed!="")?'checked="checked"':"").' value="alldayclosed" /> 
                                                                                                                                            <span><strong>Closed all day</strong></span>
                                                                                                                        </div>
                                                                                                                        <div id="ttodayselctholder">
                                                                                                                                            <div class="otime">
                                                                                                                                                                <label><strong>From</strong></label>
                                                                                                                                                                <select name="cfromhr" id="cfromhr" class="starttime">'.$select.'</select>
                                                                                                                                            </div>
                                                                                                                                            <div class="ctime">
                                                                                                                                                                <label><strong>To</strong></label>
                                                                                                                                                                <select name="ctohr" id="ctohr" class="endtime">'.$select.'</select>
                                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div id="today_delayed_details" class="gholder">
                                                                                                                        <div class="gholder">
                                                                                                                                            <div class="otime">
                                                                                                                                                                <label><strong>Hours</strong></label>
                                                                                                                                                                <select name="dtimehr" id="dtimehr" class="starttime delaytime">'.getHoursAndMinsForList().'</select>
                                                                                                                                            </div>
                                                                                                                                            <div class="ctime">
                                                                                                                                                                <label><strong>Mins</strong></label>
                                                                                                                                                                <select name="dtimemin" id="dtimemin" class="starttime delaytime"><option value="00">00</option><option value="15">15</option><option value="30">30</option><option value="45">45</option></select>
                                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div id="today_dismissal_details" class="gholder">
                                                                                                                        <div class="gholder">
                                                                                                                                            <div class="ctime">
                                                                                                                                                                <label><strong>Closing Time</strong></label>
                                                                                                                                                                <select name="edtohr" id="edtohr" class="endtime">'.$select.'</select>
                                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" name="day1message" id="day1message" val=""/>
                                                                                <input type="hidden" name="day1Croncheck" id="day1Croncheck" val="'.$day1off.'"/>
                                                                                <input type="hidden" name="day2Croncheck" id="day2Croncheck" val="'.$day2off.'"/>
                                                                                <div id="cpreview1"></div>
                                                                                 <label for="closingDetials" class="detailsDiv"><strong>Details</strong><em class="subtxt"> (Max 200 chars)</em></label>
                                                                                                    <textarea tabindex="9" cols="10" rows="2" maxlength="201" name="closingDetails_day1" id="closingDetails">'.$todaydesc.'</textarea><span id="charCount"></span><br/>
                                                            </div>
                                                              <div class="tomorrowDiv">
                                                                                <div id="tomorrowAllFieldsHolder">
                                                                                                    <div class="cdate">
                                                                                                                        <label><strong>Tomorrow&nbsp;</strong> ('.date("m/d/Y", time()+86400).')</label>
                                                                                                                        <input type="hidden" value="'.date("m/d/Y", time()+86400).'" name="day2_date"/>
                                                                                                                        <select class="tomorrowChangeStatus" name="tomorrowChangeStatus" id="tomorrowChangeStatus">
                                                                                                                                            <option value="normal_hours">Normal Hours</option>
                                                                                                                                            <option value="closed">Closed</option>
                                                                                                                                            <option value="delayed">Delayed by</option>
                                                                                                                                            <option value="early_dismisal">Early Dismissal</option>
                                                                                                                        </select> 
                                                                                                    </div>
                                                                                                    <div id="tomorrow_closed_details" class="gholder">
                                                                                                                        <div class="allday">
                                                                                                                                            <input type="checkbox" name="talldayclosed" id="talldayclosed" '.(($talldayclosed!="")?'checked="checked"':"").' value="alldayclosed" /> 
                                                                                                                                            <span><strong>Closed all day</strong></span>
                                                                                                                        </div>
                                                                                                                        <div id="tomorrow_ttodayselctholder">
                                                                                                                                            <div class="otime">
                                                                                                                                                                <label><strong>From</strong></label>
                                                                                                                                                                <select name="ctfromhr" id="ctfromhr" class="starttime">'.$selectTorrow.'</select>
                                                                                                                                            </div>
                                                                                                                                            <div class="ctime">
                                                                                                                                                                <label><strong>To</strong></label>
                                                                                                                                                                <select name="cttohr" id="cttohr" class="endtime">'.$selectTorrow.'</select>
                                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div id="tomorrow_delayed_details" class="gholder">
                                                                                                                        <div class="gholder">
                                                                                                                                            <div class="otime">
                                                                                                                                                                <label><strong>Hours</strong></label>
                                                                                                                                                                <select name="tomorrow_dtimehr" id="tomorrow_dtimehr" class="starttime delaytime">'.getHoursAndMinsForList().'</select>
                                                                                                                                            </div>
                                                                                                                                            <div class="ctime">
                                                                                                                                                                <label><strong>Mins</strong></label>
                                                                                                                                                                <select name="tomorrow_dtimemin" id="tomorrow_dtimemin" class="starttime delaytime"><option value="00">00</option><option value="15">15</option><option value="30">30</option><option value="45">45</option></select>
                                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div id="tomorrow_dismissal_details" class="gholder">
                                                                                                                        <div class="gholder">
                                                                                                                                            <div class="ctime">
                                                                                                                                                                <label><strong>Closing Time</strong></label>
                                                                                                                                                                <select name="tomorrow_edtohr" id="tomorrow_edtohr" class="endtime">'.$select.'</select>
                                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                    </div>
                                                                                </div>

                                                                                        <script type="text/javascript">
                                                                                        
                                                                                                    jQuery("#cfromhr").val('.(($cfromhr!="")?('"'.$cfromhr.'"'):"\"none\"").');
                                                                                                    jQuery("#ctohr").val('.(($ctohr!="")?('"'.$ctohr.'"'):"\"none\"").');
                                                                                                    jQuery("#ctfromhr").val('.(($ctfromhr!="")?('"'.$ctfromhr.'"'):"\"none\"").');
                                                                                                    jQuery("#cttohr").val('.(($cttohr!="")?('"'.$cttohr.'"'):"\"none\"").');

                                                                                                
                                                                                                    jQuery("#todayChangeStatus").val('.(($todayStatus!="")?('"'.$todayStatus.'"'):"\"none\"").');
                                                                                                    jQuery("#tomorrowChangeStatus").val('.(($tomorrowStatus!="")?('"'.$tomorrowStatus.'"'):"\"none\"").');

                                                                                                    jQuery("#dtimehr").val('.(($dtimehr!="")?('"'.$dtimehr.'"'):"\"none\"").');
                                                                                                    jQuery("#dtimemin").val('.(($dtimemin!="")?('"'.$dtimemin.'"'):"\"none\"").');

                                                                                                    jQuery("#tomorrow_dtimehr").val('.(($tomorrow_dtimehr!="")?('"'.$tomorrow_dtimehr.'"'):"\"none\"").');
                                                                                                    jQuery("#tomorrow_dtimemin").val('.(($tomorrow_dtimemin!="")?('"'.$tomorrow_dtimemin.'"'):"\"none\"").');

                                                                                                    jQuery("#edtohr").val('.(($edtohr!="")?('"'.$edtohr.'"'):"\"none\"").');
                                                                                                    jQuery("#tomorrow_edtohr").val('.(($tomorrow_edtohr!="")?('"'.$tomorrow_edtohr.'"'):"\"none\"").');

                                                                                        </script>';

        $content .='<div id="statusHolder"></div>
                                                                                                                        <div class="clearfix"></div>
                                                                                                                        <input type="hidden" name="day2message" id="day2message" val=""/>
                                                                                                                        <div id="cpreview"></div>
                                                                                        <label for="closingDetials"><strong>Details</strong><em class="subtxt"> (Max 200 chars)</em></label>
                                                                                                    <textarea tabindex="9" cols="10" rows="2" maxlength="201" name="closingDetails" id="closingDetails">'.$tomorrowdesc.'</textarea><span id="charCount"></span>
                                                                                        </div>
                                                                                        </div>
                                                                                        <p class="closing-submit">
                                                                                                            <input type="hidden" value="4ad7cdd067" name="register_nonce" id="register_nonce">
                                                                                                            <input type="hidden" value="/closing/" name="_wp_http_referer">
                                                                                                            <input type="submit" tabindex="13" name="submit_closing" value="Submit" class="button submit-button button-primary">
                                                                                                            <input type="hidden" name="closings_action" value="'.$_REQUEST["a"].'" >
                                                                                                            <span class="more_links"><a class="button submit-button button-primary" href="'.get_option('bclosingsurl').'">CANCEL</a></span>
                                                                                                            
                                                                                </p>';
        $content .='<div class="clearfix"></div>
                                                                                                    <p>To ensure that the information is updated, we request that you update it at least every 48 hours. If the information is not updated for 48 hours, the business will automatically be shown as "Normal Hours".</p>
                                                                  </form>';
        $content.='<div id="statusTemplate">
                                                                                <div id="closed"></div>
                                                            <div id="delayed">
                                                                                <div class="input clearfix time-input">
                                                                                                    <div class="cdate"><label><strong>Today</strong></label><input name="dtoday" id="dtoday" tabindex="7" type="text" readonly="readonly"  value='.date("m/d/Y").'></div>
                                                                                                    <div class="cfrom">
                                                                                                                        <div class="time-format"><span>Hours:</span><sapn>Mins:</strong></span></div> 
                                                                                                                        <select name="dtimehr" id="dtimehr" class="starttime">'.getHoursAndMinsForList().'</select>
                                                                                                                        <select name="dtimemin" id="dtimemin" class="starttime"><option value="15">15</option><option value="30">30</option><option value="45">45</option></select>
                                                                                                    </div>
                                                                                </div>
                                                                                <script type="text/javascript">
                                                                                                    jQuery("#dtimehr").val('.(($dtimehr!="")?('"'.$dtimehr.'"'):"\"none\"").');
                                                                                                    jQuery("#dtimemin").val('.(($dtimemin!="")?('"'.$dtimemin.'"'):"\"none\"").');
                                                                                </script>		
                                                         </div>
                                                            <div id="earlydismissal">
                                                                                <div class="input clearfix time-input">	
                                                                                                    <div class="cTo">
                                                                                                                        <label><strong>TO</strong></label>
                                                                                                                        <select name="edtohr" id="edtohr" class="endtime">'.$select.'</select>
                                                                                                    </div>
                                                                                </div>
                                                                                <script>
                                                                                                    jQuery("#edtohr").val('.(($edtohr!="")?('"'.$edtohr.'"'):"\"none\"").');
                                                                                </script>
                                                            </div>
                                        </div>';
    }
    $content .='</div>';
    if ($echo)
        echo $content;
    else
        return $content;
}

function getTimeListForSelect( $r1,$r2 ) {
    global $wpdb,$bd;
    /* Set $myBlogId to the ID of the site you want to query */
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    $r = range(0,23);
    $selected = is_null($selected) ? date('h') : $selected;
    $select = "<option value=\"none\">Select</option>";
    foreach ($r as $hour){
        $start = strtotime($hour.":00");
        $select .= "<option value=\"$hour\"";
        //$select .= ($hour==$selected) ? ' selected="selected"' : '';
        $select .= ">".date('g:i A', $start)."</option>\n";
    }
    return $select;
}

function getHoursAndMinsForList() {
    global $wpdb;
    /* Set $myBlogId to the ID of the site you want to query */
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    $r = array( "00","01","02","03","04","05","06","07","08" );
    //       $m = array(15,30,45);
    foreach ($r as $hour){
        $hur = ( str_replace( '0', '', $hour) );
        $hurs = ( $hour == '00') ? '00' : $hur;
        $select .= '<option value="'.$hurs.'">'.$hour.'</option>';
    }
    return $select;
}

function validate_user(){
    global $wpdb; // this is how you get access to the database
    $whatever = intval( $_POST['whatever'] );
    $whatever += 10;
    echo $whatever;
    die(); // this is required to return a proper result
}

/** LEGACY ACCOUNT MIGRATION**/
function bd_legacy_account() {
    global $wpdb, $bd, $enc;
    /* Set $myBlogId to the ID of the site you want to query */
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    if( !isset($_POST['legacy_migration_form_submit']) ){
        $legacyUserName =  decode_base64($_GET['auth1']);
        $legacypwd = decode_base64($_GET['auth2']);
        $legacyUser =  "SELECT * FROM  ts_legacy WHERE UserName='".$legacyUserName."' AND Password='".$legacypwd."'";
        $legacyIds = $wpdb->get_results( $legacyUser );
        foreach( $legacyIds as $lid ) {
            $legacyId      = $lid->UserName;
            $legacyEmail = $lid->Email;
        }  ?>
    <h1 class="font_face legcymig">Business Account Migration</h1>

    <p class="business-desc">
    <h4> Welcome to our new weather closings management platform</h4>
    Please update your contact details so that the radio staff can contact you easily if necessary.
    You will be able to submit your closing in just a minute. Thanks!
    </p>

    <div id="legacy-form">

        <form  id="registration_form" class="login-registration legacy-registration validate clearfix"   action="" method="post" enctype="multipart/form-data">

            <div class="input clearfix">
                <label for="display_name">Current User ID</label>
                <input type="text" name="business_name" id="display_name" class="validate valid-required legacy-name" value="<?php echo $legacyId; ?>" disabled tabindex="1" />
            </div>
            <!-- // #.input -->

            <div class="input clearfix">
                <label for="user_email">Business Email<span>*</span></label>
                <input autocomplete="off" type="text" name="business_email"  id="user_login" class="validate valid-email"  value="<?php echo $legacyEmail; ?>" tabindex="2" />
                <p class="description indicator-hint">Please verify / provide an valid e-mail id to facilitate your account migration.
                    <strong> This will be used as your business login</strong></p>
            </div>
            <!-- // #.input -->

            <div class="input clearfix">
                <label for="pass1">Password<span>*</span></label>
                <input autocomplete="off" type="password" name="pass1" id="pass1" class="validate valid-required pass1" value="" tabindex="3" />
            </div>
            <!-- // #.input -->

            <div class="input clearfix">
                <label for="pass2">Confirm Password<span>*</span></label>
                <input autocomplete="off" type="password" name="pass2" id="pass2" class="validate valid-required" value="" tabindex="4" />
            </div>
            <!-- // #.input -->

            <div id="pass-strength-result">Strength indicator</div>
            <p class="description indicator-hint">Hint: The password should be at least seven characters long. To make it stronger, use upper and lower
                case letters, numbers and symbols like ! " ? $ % ^ & )</p>

            <p class="submit"><?php wp_nonce_field("register", "register_nonce"); ?>
                <input type="submit" class="button submit-button button-primary"  value="Submit" name="legacy_migration_form_submit" tabindex="5" />
                <span class="more_links"><a class="button submit-button button-primary" href="<?php echo get_option('bclosingsurl'); ?>">CANCEL</a></span></span>

        </form>
    </div>

    <?php   } if( $_POST['legacy_migration_form_submit'] ) {
        $user_name = $_POST['business_email'];
        $password   = $_POST['pass1'];
        $usermail   = $_POST['business_email'];
        $legacy_migrated_ID = decode_base64($_GET['auth1']);

        $user_id = username_exists( $user_name );
        if ( !$user_id ) {
            $user_id = wp_create_user( $user_name, $password, $usermail );
            $wp_user_object = new WP_User($user_id);
            $wp_user_object->set_role('business');
            $role = 'business';
            $blogs = $wpdb->get_results("SELECT * from ts_blogs");
            foreach ( $blogs as $blog ){
                $blog_id = $blog->blog_id;
                add_user_to_blog( $blog_id, $user_id, $role );
            }
            update_user_meta( $user_id, $wpdb->prefix . 'user_level', 10 );
            update_user_meta( $user_id, 'legacy_user', $legacy_migrated_ID, '' );
            update_user_meta( $user_id, 'bdpwd', encode_base64($password), '' );
            update_user_meta( $user_id, 'user_member_of', 'business_listings', '' );
//                                        } else {
//                                                          //  echo  '<div class="success-message">Your account with all the details has been already migrated to our new system. Please <a href="'.get_option('loginurl').'">Login</a> with your email to continue</div>';
//                                                           // echo  '<div class="success-message">Once you confirm, you will have full access to your businesses and all future notifications will be sent to same email address.</div>';
//                                                            echo  '<div class="success-message"><p>Your '.bloginfo('name').' Closings and Delays account has been created but your email address needs to be verified before your account becomes fully active. Please check your mail.</p><br />
//                                                            <p>Didn\'t receive an email? Check your spam folder or <a href="'.get_option( 'resendmail').'" class="bdresendlink">click here </a>to resend.</p></div>';
//                                                            
        }
        echo  '<div class="success-message"><p>Your '.bloginfo('name').' Closings and Delays account has been created but your email address needs to be verified before your account becomes fully active. Please check your mail.</p><br/>
                                      <p>Didn\'t receive an email? Check your spam folder or <a href="'.get_option( 'resendmailurl').'" class="bdresendlink">click here </a>to resend.</p></div>';
        $legacyUserName = decode_base64($_GET['auth1']);
        $legacypwd = decode_base64($_GET['auth2']);
        $legacyUser =  "SELECT * FROM  ts_legacy WHERE UserName='".$legacyUserName."' AND Password='".$legacypwd."'";
        $legacyIds = $wpdb->get_results( $legacyUser );
        foreach( $legacyIds as $lid ){
            $result = $lid->OrgCategoriesRevised;
            $catname = strtolower( str_replace( ' ', '-', $result ) );
            global $wpdb;
            /* Set $myBlogId to the ID of the site you want to query */
            $wpdb->blogid = $bd->centralBid;
            $wpdb->set_prefix( $wpdb->base_prefix );
            $termtable =  "SELECT term_id FROM ".$wpdb->prefix."terms WHERE slug='".$catname."'";
            $termresult = $wpdb->get_results( $termtable );
            $customTermid =$termresult[0]->term_id;

            $lauthor     =  $user_id;
            $post_title =  $lid->orgName;
            $business_type = $customTermid;
            $addr        = $lid->orgAddress;
            $city          =  $lid->orgCity;
            $zipcode  = $lid->orgZip;
            $state       = $lid->orgState;
            $phone    = $lid->orgPhone;
            $contact  = $lid->orgContactName;
            $sShore  = $lid->sShore;
            $sTrenton = $lid->sTrenton;
            $sSJ = $lid->sSJ;
            $post_content = ' ';
            //$post_status= ( $pstatus=='pending' ) ? 'draft' : 'publish';
            $addLegacyBusiness = array(
                'post_author'   => $lauthor,
                'post_title'         => $lid->orgName,
                'post_content' => $post_content,
                'post_status'    => 'draft',
                'post_type'       => 'business'
            );

            $post_id = wp_insert_post( $addLegacyBusiness );
            if($business_type) wp_set_post_terms( $post_id, $business_type, 'business_type', false);
            update_post_meta($post_id, 'bd_bname', $post_title);
            update_post_meta($post_id, 'bd_cat', $business_type);
            update_post_meta($post_id, 'bd_user_mail', $usermail);
            update_post_meta($post_id, 'bd_address', $addr);
            update_post_meta($post_id, 'bd_city', $city);
            update_post_meta($post_id, 'bd_zipcode', $zipcode);
            update_post_meta($post_id, 'bd_state', $state);
            update_post_meta($post_id, 'bd_phone', $phone);
            update_post_meta($post_id, 'bd_contact', $contact);
            update_post_meta($post_id, 'sShore', $sShore);
            update_post_meta($post_id, 'sTrenton', $sTrenton);
            update_post_meta($post_id, 'sSJ', $sSJ);

            /* New code */
            update_post_meta($post_id, 'bd_day1_status','normal_hours');
            update_post_meta($post_id, 'bd_day2_status','normal_hours');
            update_post_meta($post_id, 'bd_day1_search_string','search_normal_hours');
            update_post_meta($post_id, 'bd_day2_search_string','search_normal_hours');
            update_post_meta($post_id, 'bd_closing_dhistory_count',0);
            update_user_meta( $user_id, 'bd_activation', 'pending', '' );
            $today_time = Array('starttime' => '');
            $closingsday1_details = Array(
                'date' => date("m/d/Y"),
                'time' => $today_time,
                'cur_status' => 'Normal Hours',
                'statusmsg' =>'Normal Hours',
                'statusdesc' =>''
            );
            $closingsday2_details = Array(
                'date' => date("m/d/Y", time()+86400),
                'time' => $today_time,
                'cur_status' => 'Normal Hours',
                'statusmsg' =>'Normal Hours',
                'statusdesc' =>''
            );
            update_post_meta($post_id, 'bd_closing_day1', $closingsday1_details, true);
            update_post_meta($post_id, 'bd_closing_day2', $closingsday2_details, true);

            /** DEFAULT START TIME, END TIME **/
            $monday = Array( 'start' => 9,'end' => 17 );
            $tuesday = Array( 'start' => 9,'end' => 17 );
            $wednesday = Array( 'start' => 9,'end' => 17 );
            $thursday = Array( 'start' => 9,'end' => 17 );
            $friday = Array( 'start' => 9,'end' => 17 );
            $commontime = Array( 'start' => 9,'end' => 17 );
            update_post_meta($post_id, 'bd_monday', $monday);
            update_post_meta($post_id, 'bd_tuesday', $tuesday);
            update_post_meta($post_id, 'bd_wednesday', $wednesday);
            update_post_meta($post_id, 'bd_thursday', $thursday);
            update_post_meta($post_id, 'bd_friday', $friday);
            update_post_meta($post_id, 'bd_commontime', $commontime);
            /* New code */
        }

        /** SEND MAIL ON MIGRATION. **/
        $msg .= '<p>Hello '.$user_name.', you are almost done!</p>';
        $msg .= '<p>Your '.get_bloginfo('name').' Closings and Delays account has been created but your email address needs to be verified before your account becomes fully active.</p>';
        $msg .='<p><a href="'.get_option( "businessurl" ).'login/?Auth1='.encode_base64( $user_name ).'&Auth2='.encode_base64( $password ).'">Click here to activate your business</a>';
        $msg .=' or copy and paste this web address into your browser to confirm your email address '.get_option( "businessurl" ).'login/?Auth1='.encode_base64( $user_name ).'&Auth2='.encode_base64( $password ).'.</p>';

        add_filter( 'wp_mail_content_type',create_function( '', 'return "text/html";' ) );
        $subject='Confirm your Closings and Delay account for '.get_the_title( $post_id );
        //  wp_mail( 'sandeep@inkoniq.com, nirali@inkoniq.com, madhu@inkoniq.com, luca@townsquaremedia.com', iconv_mime_decode($subject,2,'utf8'), $msg  );
        wp_mail( $user_name, iconv_mime_decode($subject,2,'utf8'), $msg  );
    }
}

/** DETAIL BUSINESS PAGE **/
function detail_page( $echo=true ){
    global $wpdb, $bd, $current_user;
    /* Set $myBlogId to the ID of the site you want to query */
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    get_currentuserinfo();

//                                        if( wp_cache_get( 'detail_page' ) ){
//                                                    echo "1".wp_cache_get( 'detail_page' );        
//                                        }else{

    $slug = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    $searchQuery =  "SELECT  ID FROM ".$wpdb->prefix."posts WHERE post_name='$slug'";
    $posts = $wpdb->get_results( $searchQuery );

    foreach( $posts as $post ){
        $settings = get_option('bd_settings');
        $link =  get_option('addburl').'?edit='.$post->ID;
        $meta = get_post_custom($post->ID);
        //unserialize
        foreach ($meta as $key => $val) {
            $meta[$key] = maybe_unserialize($val[0]);
            $meta[$key] = array($meta[$key]);
        }

        $bd_email =  get_post_meta( $post->ID, 'bd_user_mail', true );

        $content .='<div id="bd_business_listing">
                                                              <div class="backDiv"><a href="'.get_option('bclosingsurl').'">&laquo; Back to Closings</a></div>
                                                              <div class="block1">
                                                                               <h1 class="font_face">'. get_the_title($post->ID).'</h1>';
        if ( ( $current_user->user_email == $bd_email ) || ( is_super_admin() ) ) {
            $content.='<div class="edit-link">
                                                                                                                                           <a class="aleft editbusiness" href="'.$link.'" title="Edit Business"></a>
                                                                                                                                           <a id="'.$post->ID.'" href="javascript:void(0);" class="delete_post deletebusiness" title="Delete Business"></a>
                                                                                                                       </div>';
        }
        $content .='</div>
                                                                               
                                                                               <div class="block2">';
        $content.='<div class="bd_list_addr">';
        $content.='<ul class="detail-ads">
                                                                                                                                           <li>'.$meta['bd_address'][0].', '.$meta['bd_city'][0].', '.$meta['bd_state'][0].', '.$meta['bd_zipcode'][0].'</li>';
        if ( $meta['bd_website'][0]!='' ){
            $content.='<li><a href="http://'.$meta['bd_website'][0].'" target="_blank">'.$meta['bd_website'][0].'</a></li>';
        }
        if ( $meta['bd_contact'][0]!='' && ($current_user->user_email == $bd_email )){
            $content.='<br/><li><label>Contact Person: </label><span>'.$meta['bd_contact'][0].'</span></li>';
        }
        if ( $meta['bd_phone'][0]!='' && ($current_user->user_email == $bd_email )){
            $content.='<li><label>Phone: </label><span>'.$meta['bd_phone'][0].'</span></li>';
        }
        if ( ( $current_user->user_email == $bd_email ) ){
            $content.='<li><span><a href="mailto:'.$meta['bd_user_mail'][0].'" target="_blank">'.$meta['bd_user_mail'][0].'</a></span></li>';
        }
        $timings = $meta['bd_checkall'];
        $content.='</ul>
                                                                                                   <div class="stauts-alert">
                                                                                                                       <div class="sl_left">';
        if ( ($current_user->user_email == $bd_email) || ( is_super_admin()) ){
            $content.='<h4>Current Status <span><a class="" href="'.get_option('closingurl').'?p='.$post->ID.'&a=update">Change</a></span></h4>';
        }else{
            $content.='<h4>Current Status</h4>';
        }
        $day1 = get_post_meta( $post->ID, 'bd_closing_day1', true );
        $day2 = get_post_meta( $post->ID, 'bd_closing_day2', true );
        $content.="<p class='todaystatus'>Today: ".$day1['statusmsg']."</p>";
        if ( $day1['statusdesc'] ){
            $content.="<p class='detailsb'>Details: ".$day1['statusdesc']."</p>";
        }
        $content.="<p class='tomostatus'>Tomorrow: ".$day2['statusmsg']."</p>";
        if ( $day2['statusdesc'] ){
            $content.="<p class='detailsb'>Details: ".$day2['statusdesc']."</p>";
        }
        $content.='</div>';

        if($timings[0] != ""){
            $fdateFormat = date( 'g:i A',strtotime( $timings[0]['start'].":00" ) );
            $tdateFormat = date( 'g:i A',strtotime( $timings[0]['end'].":00" ) );
            $content.='<div class="detail-ads"><h4>Business Hours</h4>
                                                                                                                                                               <div class="days-of-operation">Sun - Sat '.$fdateFormat.' - '.$tdateFormat.'</div>
                                                                                                                                           </div>';
        }else{
            $odays = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            $wdays = "";
            foreach ( $odays as $key=>$dayValue ) {
                $timings = $meta['bd_'.$dayValue];
                if( $key != ( count( $odays ) - 1 ) ){
                    if($timings[0]['start'] != null){
                        $wdays.= $dayValue.", ";
                        $fdateFormat = date('g:i A',strtotime($timings[0]['start'].":00"));
                        $tdateFormat = date('g:i A',strtotime($timings[0]['end'].":00"));
                        $whours .= "<li>".ucfirst(substr($dayValue,0,3))." ".$fdateFormat.' - '.$tdateFormat."</li>";
                    }
                }else{
                    if($timings[0]['start'] != null){
                        $wdays.= $dayValue;
                        $fdateFormat = date('g:i A',strtotime($timings[0]['start'].":00"));
                        $tdateFormat = date('g:i A',strtotime($timings[0]['end'].":00"));
                        $whours .= "<li>".ucfirst(substr($dayValue,0,3))." ".$fdateFormat.' - '.$tdateFormat."</li>";
                    }
                }
            }

            $content.='<div class="detail-ads">
                                                                                                                                                                <h4>Business Hours</h4>
                                                                                                                                                                <div class="clearfix"></div>
                                                                                                                                                                <div><ul>'.$whours.'</ul></div>
                                                                                                                                            </div>';
        }
        $content .='</div>
                                                            </div>
                                        </div>';
        $content.='<div class="bd_list_map"></div></div>';
    }

    $history_count = get_post_meta($post->ID, 'bd_closing_dhistory_count', true);
    if ( $history_count > 0 ){
        $content .='<div class="bd_business_history" id="'.$history_count.'">';
        $content .='<h1>Closing History</h1>
                                                                                                            <div class="bd_business_history_header">
                                                                                                                               <span class="date">Date</span>
                                                                                                                               <span class="status">Status</span>
                                                                                                                               <span class="details">Details</span>
                                                                                                                               <div class="clear"></div>
                                                                                                            </div>';

        // FOR LATEST FIVE
        $inq=0;
        for ( $history_count; $history_count>$i=0; $history_count-- ){

            // Disply latest five
            if( $inq < 5  ){
                $history =  get_post_meta($post->ID, 'bd_closing_dhistory_'.$history_count.'', true);
                if( $history ) {
                    $content.='<div class="business-status">
                                                                                                                                                       <span class="date">'.$history['date'].'</span>                                          
                                                                                                                                                       <div class="status">'.$history['cur_status'].'</div>
                                                                                                                                                       <div class="details">'.$history['statusdesc'].'</div>
                                                                                                                                            </div>';
                }
            }
            $inq++;
        }
        $content .='</div>';
    }
    if( !$posts ){
        $content.="<div class='nobusiness'>
                                                                                                    <strong>Your business has been successfully deleted, <a href='".get_option('manageurl')."'>Back to manage business</a></strong>
                                                                                   </div>";
    }
    if ( ( $current_user->user_email == $bd_email) || ( is_super_admin() ) ){
        $content .='<div class="sl_right more_links manage-business"><a class="button submit-button button-primary" href="'.get_option('addburl').'">ADD NEW BUSINESS</a></div>';
        $content .='<div class="sl_right more_links manage-business"><a class="button submit-button button-primary" href="'.get_option('manageurl').'">MANAGE BUSINESSES</a></div>';
    }

    echo $content;
    //    wp_cache_set( 'detail_page',$content );
    //}
}


function ShortenText($text, $limit) {
    //// Function name ShortenText
    $chars_limit = $limit; // Character length
    $chars_text = strlen($text);
    $text = $text." ";
    $text = substr($text,0,$chars_limit);
    $text = substr($text,0,strrpos($text,' '));

    if ($chars_text > $chars_limit)
    { $text = $text."..."; } // Ellipsis
    return $text;
}

/** FUNCTION TO ENCRYPT USER LINK FROM BASE64**/
function encrypt($sData, $sKey='mysecretkey'){
    $sResult = '';
    for($i=0;$i<strlen($sData);$i++){
        $sChar    = substr($sData, $i, 1);
        $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
        $sChar    = chr(ord($sChar) + ord($sKeyChar));
        $sResult .= $sChar;
    }
    return encode_base64($sResult);
}

/** FUNCTION TO DECRYPT USER LINK FROM BASE64 **/
function decrypt($sData, $sKey='mysecretkey'){
    $sResult = '';
    $sData   = decode_base64($sData);
    for($i=0;$i<strlen($sData);$i++){
        $sChar    = substr($sData, $i, 1);
        $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
        $sChar    = chr(ord($sChar) - ord($sKeyChar));
        $sResult .= $sChar;
    }
    return $sResult;
}

function encode_base64($sData){
    $sBase64 = base64_encode($sData);
    return strtr($sBase64, '+/', '-_');
}
//echo $encode_output;
function decode_base64($sData){
    $sBase64 = strtr($sData, '-_', '+/');
    return base64_decode($sBase64);
}

/** FUNCTION TO REIRECT AFTER BUSINESS USER LOGOUT **/
function bd_logout_redirection(){
    global $wpdb, $bd, $current_user;
    $creds = array();
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );
    $business = ( get_user_meta( $current_user->ID, $wpdb->prefix.'capabilities', true ) ) ? get_user_meta( $current_user->ID, $wpdb->prefix.'capabilities', true ) : $creds;
    $businessrole = key( $business );
    if( $businessrole == 'business' ){
        wp_redirect( get_option('businessurl').'login');
        exit;
    }
}
//hook function  to wp_logout action
add_action('wp_logout','bd_logout_redirection');

function the_breadcrumb() {
    echo '<a href="';
    echo get_option('bclosingsurl');
    echo '">Closings';
    echo "</a>";
    if (is_page()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        echo the_title();
    }
}

// add_action('wp_content','display_breadcrumbs');

/** RESEND MAIL FUNCTION **/
function bd_resendMail() {

    global $wpdb, $bd, $current_user;
    /* Set $myBlogId to the ID of the site you want to query */
    $wpdb->blogid = $bd->centralBid;
    $wpdb->set_prefix( $wpdb->base_prefix );

    $content.='<div id="login-business">
                                                                                <div class="title"><h2>Resend business registration email</h2></div>';
    if( isset( $_POST['sbmtResendmail'] ) ) {
        $user_name =  $_POST['resendmail'];
        if( username_exists( $user_name ) ){

            $userid = get_user_id_from_string( $user_name );
            $bduserdata = get_userdata( $userid );
            $password = $bduserdata->user_pass;
            $password =  decode_base64( get_user_meta( $userid, 'bdpwd', true ) );
            $query = new WP_Query( 'author='.$userid.'&post_status='.array('published','draft').'&post_type=business' );
            $post_id = $query->post->ID;

            $msg .= '<p>Hello '.$user_name.', you are almost done!</p>';
            $msg .= '<p>Your '.get_bloginfo('name').' Closings and Delays account has been created but your email address needs to be verified before your account becomes fully active.</p>';
            $msg .='<p><a href="'.get_option( "businessurl" ).'login/?Auth1='.encode_base64( $user_name ).'&Auth2='.encode_base64( $password ).'">Click here to activate your business</a>';
            $msg .=' or copy and paste this web address into your browser to confirm your email address '.get_option( "businessurl" ).'login/?Auth1='.encode_base64( $user_name ).'&Auth2='.encode_base64( $password ).'</p>';
            add_filter( 'wp_mail_content_type',create_function( '', 'return "text/html";' ) );
            $subject='Confirm your Closings and Delay account for '.get_the_title( $post_id );
            // wp_mail( 'sandeep@inkoniq.com, nirali@inkoniq.com, madhu@inkoniq.com, luca@townsquaremedia.com', iconv_mime_decode($subject,2,'utf8'), $msg  );
            wp_mail( $user_name, iconv_mime_decode($subject,2,'utf8'), $msg  );
            echo '<div class="bd-success-message">Activation link has been resent successfully.</div>';
        }else{
            echo '<div class="sanerror">Maybe you made a typo when entering your email address. Please contact '.townsquare_get_email().' to resolve this issue.</div>';
        }
    }

    $content.='<form id="bd_resendmail_form" class="resendmail" action="" method="post">
                                                                                                    <p class="bdresend-site-line">Please enter the User id /email  you used to register your business and we will resend the registration email.</p>
                                                                                                    <div class="clearfix"></div>';
    if ( is_wp_error($user) ||  $confirmation == 'pending' )
        if( $user->get_error_message() ){
            $content .= $loginerror;
        }
    $content.='<div class="input clearfix">
                                                                                                    <label for="user_login">Email / Username</label>
                                                                                                    <input type="text" class="input validate valid-required" size="22" value="" id="bd_resend_usermail" name="resendmail" />
                                                                                                    <input type="submit" name="sbmtResendmail" id="resendmail_form_submit" class="button-primary button submit-button" value="Resend Mail" />
                                                                                                    </div><!-- // #.input -->
                                                                                </form>
                                                            </div>';
    echo $content;
    //die();
}