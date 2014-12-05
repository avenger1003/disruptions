<?php

include_once "template-functions.php";
global $bd, $wpdb, $current_user;

if (is_user_logged_in()) {
    $userRole = ($current_user->data->wp_capabilities);
    $role = $userRole;
}
$errors = array();
$mesg = array();

if (!$_POST["business_name"]) {
    $errors[] = 'Business Name is required';
}
if (!is_user_logged_in() && !$_GET['pid']) {
    if (!$_POST['business_email']) {
        $errors[] = '* Email is required';
    } else {
        $validemail = is_valid_email($_POST['business_email']);
        if (!$validemail) {
            $errors[] = '* Not a valid Email';
        }
    }
    if (!$_POST["pass1"]) {
        $errors[] = 'Password is required';
    }
    if (!$_POST["pass2"]) {
        $errors[] = 'Confirm Password is required';
    }
    if ($_POST["pass1"] != $_POST["pass2"]) {
        $errors[] = 'Both Password and Cnform password should be same';
    }
}
if (!$_POST["business_address"]) {
    $errors[] = 'Address is required';
}
if (!$_POST["city"]) {
    $errors[] = 'City is required';
}
if (get_option('bd_statecheck') == 1) {
    $poststate = $_POST["defaultstate"];
} else {
    $poststate = $_POST["state"];
}
if (!$poststate) {
    $errors[] = 'State is required';
}
if (!$_POST["zip_code"]) {
    $errors[] = 'Zip Code is required';
}
if (!isset($_POST['check-all']) && !isset($_POST['check-mon']) && !isset($_POST['check-tue']) && !isset($_POST['check-wed']) && !isset($_POST['check-thu']) && !isset($_POST['check-fri']) && !isset($_POST['check-sat']) && !isset($_POST['check-sun'])) {
    $mesg['day'] = 'Select at least one day';
}

if (!is_user_logged_in() && !$_GET['pid'] && !$errors && !$mesg['day']) {
    $legacy = true;
    if ($_POST['business_email'] && $_POST["pass1"] && $_POST["pass2"]) {
        $newusername = $_POST['business_email'];
        $newpassword = $_POST["pass1"];
        $newemail = $_POST['business_email'];
        // Check that user doesn't already exist
        if (!username_exists($newusername) && !email_exists($newemail)) {
            // Create user and set role to business
            $user_id = wp_create_user($newusername, $newpassword, $newemail);
            // $user_id = wpmu_signup_user($user, $user_email, $meta);
            if (is_int($user_id)) {
                $wp_user_object = new WP_User($user_id);
                $wp_user_object->set_role('business');
                $blogs = $wpdb->get_results("SELECT * from ts_blogs");
                $role = 'business';
                $user_id = $user_id;
                foreach ($blogs as $blog) {
                    //echo $blog->blog_id;
                    $blog_id = $blog->blog_id;
                    add_user_to_blog($blog_id, $user_id, $role);
                }
                //  update_user_meta( $user_id, $wpdb->prefix.'user_level', 10 );
                //  update_options( $user_id, $wpdb->prefix . 'user_roles', 10 );
                //$errors[] = 'Successfully created new admin user. Now delete this file!';
            } else {
                $errors[] = 'Error with wp_insert_user. No users were created.';
            }
        } else {
//                                                                                                                        $errors[] = 'This user or email already exists.';
            $userid = get_user_id_from_string($_POST['business_email']);
            $blog_id = get_current_blog_id();
            add_user_to_blog($blog_id, $user_id, 'business');
        }
    } else {
        //$errors[] = 'Whoops, looks like you did not set a password, username, or email';
    }
} else {
    $usermeta = get_user_meta($current_user->ID, 'tsmuser');
    if (!$role == "business" || !$usermeta) {
        add_user_meta($current_user->ID, 'tsmuser', 1, true);
    }
}

if (!$_POST['business_email']) {
    $usermail = $current_user->user_email;
} else {
    $usermail = $_POST['business_email'];
}


$post_title = $_POST["business_name"];
$post_content = $_POST["business_name"];

$addr = $_POST["business_address"];
$city = $_POST["city"];
$state = $poststate;
$zipcode = $_POST["zip_code"];

if ($_POST['business_contact_person'])
    $contact = $_POST['business_contact_person'];
if ($_POST['business_phone_number'])
    $phone = $_POST['business_phone_number'];
if ($_POST['business_website'])
    $website = $_POST['business_website'];
if ($_POST['checkmode']) {
    $checkmode = $_POST['checkmode'];
}
if ($_POST['nOa']) {
    $editmode = $_POST['nOa'];
}
echo "<pre>";
print_r($post_title);
echo "</pre>";
if (trim($checkmode) == "") {
    if ($_POST['check-all']) {
        $monday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        $tuesday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        $wednesday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        $thursday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        $friday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        $saturday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        $sunday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        $checkall = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        $commontime = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
    } else {
        if ($_POST['check-mon']) {
            $monday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        }
        if ($_POST['check-tue']) {
            $tuesday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        }
        if ($_POST['check-wed']) {
            $wednesday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        }
        if ($_POST['check-thu']) {
            $thursday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        }
        if ($_POST['check-fri']) {
            $friday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        }
        if ($_POST['check-sat']) {
            $saturday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        }
        if ($_POST['check-sun']) {
            $sunday = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
        }
        $commontime = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
    }
} else {
    if ($_POST['check-mon']) {
        $monday = Array('start' => $_POST['mon_fromhr'], 'end' => $_POST['mon_tohr']);
    }
    if ($_POST['check-tue']) {
        $tuesday = Array('start' => $_POST['tue_fromhr'], 'end' => $_POST['tue_tohr']);
    }
    if ($_POST['check-wed']) {
        $wednesday = Array('start' => $_POST['wed_fromhr'], 'end' => $_POST['wed_tohr']);
    }
    if ($_POST['check-thu']) {
        $thursday = Array('start' => $_POST['thu_fromhr'], 'end' => $_POST['thu_tohr']);
    }
    if ($_POST['check-fri']) {
        $friday = Array('start' => $_POST['fri_fromhr'], 'end' => $_POST['fri_tohr']);
    }
    if ($_POST['check-sat']) {
        $saturday = Array('start' => $_POST['sat_fromhr'], 'end' => $_POST['sat_tohr']);
    }
    if ($_POST['check-sun']) {
        $sunday = Array('start' => $_POST['sun_fromhr'], 'end' => $_POST['sun_tohr']);
    }
    $commontime = Array('start' => $_POST['fromhr'], 'end' => $_POST['tohr']);
}
$business_type = $_POST["business_type"];
/* Set $myBlogId to the ID of the site you want to query */
$wpdb->blogid = $bd->centralBid;
$wpdb->set_prefix($wpdb->base_prefix);
get_currentuserinfo();

if (!$errors) {
    $post_author = $current_user->ID;
    //echo $post_author;
    //saves business as published
    if ($_POST['editmode']) {
        $update_post = array(
            'ID' => trim($_POST['postid']),
            //    'post_author'	=> $user_id,
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_status' => 'publish',
            'post_type' => 'business'
        );
        $post_id = wp_update_post($update_post);
    } else {
        if (is_user_logged_in()) {
            $status = 'publish';
        } else {
            $status = 'draft';
        }
        $post = array(
            'post_author' => $user_id,
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_status' => $status,
            'post_type' => 'business'
        );
        $post_id = wp_insert_post($post);
        $userid = get_user_id_from_string($newusername);
        /* New code */
        //update_post_meta($post_id, 'bd_activation','pending');
        update_user_meta($userid, 'bd_activation', 'pending', '');
        update_post_meta($post_id, 'bd_day1_status', 'normal_hours');
        update_post_meta($post_id, 'bd_day2_status', 'normal_hours');
        update_post_meta($post_id, 'bd_day1_search_string', 'search_normal_hours');
        update_post_meta($post_id, 'bd_day2_search_string', 'search_normal_hours');
        update_post_meta($post_id, 'bd_closing_dhistory_count', 0);
        $today_time = Array('starttime' => '');
        $closingsday1_details = Array(
            'date' => date("m/d/Y"),
            'time' => $today_time,
            'cur_status' => 'Normal Hours',
            'statusmsg' => 'Normal Hours',
            'statusdesc' => ''
        );
        $closingsday2_details = Array(
            'date' => date("m/d/Y", time() + 86400),
            'time' => $today_time,
            'cur_status' => 'Normal Hours',
            'statusmsg' => 'Normal Hours',
            'statusdesc' => ''
        );
        update_post_meta($post_id, 'bd_closing_day1', $closingsday1_details, true);
        update_post_meta($post_id, 'bd_closing_day2', $closingsday2_details, true);
        update_user_meta($user_id, 'bdpwd', encode_base64($newpassword), '');
        update_user_meta($user_id, 'user_member_of', 'business_listings');
        /* New code */
    }

    //sets category
    if ($business_type)
        wp_set_post_terms($post_id, $business_type, 'business_type', false);

    //adds postmeta
    if (!$_POST['editmode'])
        update_post_meta($post_id, 'bd_user_mail', $usermail);

    update_post_meta($post_id, 'bd_cat', $business_type);
    update_post_meta($post_id, 'bd_bname', $post_title);
    update_post_meta($post_id, 'bd_address', $addr);
    update_post_meta($post_id, 'bd_city', $city);
    update_post_meta($post_id, 'bd_state', $state);
    update_post_meta($post_id, 'bd_zipcode', $zipcode);
    update_post_meta($post_id, 'bd_contact', $contact);
    update_post_meta($post_id, 'bd_phone', $phone);
    update_post_meta($post_id, 'bd_website', $website);
    update_post_meta($post_id, 'bd_checkall', $checkall);
    update_post_meta($post_id, 'bd_checkmode', $checkmode);
    update_post_meta($post_id, 'bd_monday', $monday);
    update_post_meta($post_id, 'bd_tuesday', $tuesday);
    update_post_meta($post_id, 'bd_wednesday', $wednesday);
    update_post_meta($post_id, 'bd_thursday', $thursday);
    update_post_meta($post_id, 'bd_friday', $friday);
    update_post_meta($post_id, 'bd_saturday', $saturday);
    update_post_meta($post_id, 'bd_sunday', $sunday);
    update_post_meta($post_id, 'bd_editmode', $editmode);
    update_post_meta($post_id, 'bd_commontime', $commontime);

    $settings = get_option('bd_settings');
    if (!is_user_logged_in() || $existing) {
        $userRole = ($current_user->data->wp_capabilities);
        $role = $userRole;
    }

    // SEND MAIL ON REGISTRATION  
    if (isset($_POST['register_bform_submit'])) {
        echo '<div class="success-message"><p>Thank you for registering, your Closings and Delays account has been created but your email address needs to be verified before your account becomes fully active. Please check your mail.</p><br/>
                                                                                <p>Didn\'t receive an email? Check your spam folder or <a href="' . get_option('resendmailurl') . '" class="bdresendlink">click here </a>to resend.</p></div>';
        $mnewemail = $_POST['business_email'];
        $mpwd = $_POST["pass1"];

        // SEND MAIL ON REGISTRATION.                                                            
        $msg .= '<p>Hello ' . $_POST['business_email'] . ', <b>you are almost done!</b><p/>';
        $msg .= '<p>Your Closings and Delays account has been created but your email address needs to be verified before your account becomes fully active.</p>';
        $msg .='<p><a href="' . get_option("businessurl") . 'login/?Auth1=' . encode_base64($mnewemail) . '&Auth2=' . encode_base64($mpwd) . '">Click here to activate your business</a>';
        $msg .=' or copy and paste this web address into your browser to confirm your email address ' . get_option("businessurl") . 'login/?Auth1=' . encode_base64($mnewemail) . '&Auth2=' . encode_base64($mpwd) . '.</p>';
        //$msg .='<a href="'.get_option("businessurl").'login/?auth1='.$user_name.'&auth2='.$password.'&auth3='.$post_id.'">Click here to activate your business</a>';
        add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
        $subject = 'Your Closings and Delay account information for ' . get_the_title($post_id);
        //luca@townsquaremedia.com
        //wp_mail( 'sandeep@inkoniq.com, nirali@inkoniq.com, madhu@inkoniq.com, luca@townsquaremedia.com', iconv_mime_decode($subject,2,'utf8'),  $msg  );
        wp_mail($mnewemail, iconv_mime_decode($subject, 2, 'utf8'), $msg);
        die();
    }
    if ($_POST['editmode']) {
        //$elink = get_permalink($post_id);
        $zipcode = get_post_meta($post_id, 'bd_zipcode', true);
        $cityy = get_post_meta($post_id, 'bd_city', true);
        $city = str_replace(' ', '-', $cityy);
        $clsg = get_post($post_id);
        $grl = $clsg->post_name;
        $link = get_option('businessurl') . $city . '/' . $zipcode . '/' . $grl;
    } else {
        $link = get_option('closingurl') . '?p=' . $post_id;
    }
    if (!$mesg) {
        wp_redirect($link);
    }
} else {
    $err = tsm_error_msg($errors);
    $arr = array($err, $success);
    echo implode(',', $arr);
    //echo print_r($error);
}
