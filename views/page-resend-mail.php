<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }


?>
<div id="login-business">
    <div class="title"><h2>Resend business registration email</h2></div>

    <?php
    if( isset( $_POST[self::FORM_SUBMIT] ) ) {
        global $wpdb, $blog_id;
        $current_blog_id = $blog_id;
        $wpdb->blogid = Business_Disruptions_Network_Admin::get_central_blog_id();
        $wpdb->set_prefix( $wpdb->base_prefix );

        $user_name =  $_POST[self::USER_LOGIN];
        if( username_exists( $user_name ) ){
            $user_id = get_user_id_from_string( $user_name );
            $password = get_user_meta( $user_id, Business_Disruptions_Roles::PASS_SLUG, true);
            $query = new WP_Query( array(
                    'author' => $user_id,
                    'post_status' => array('publish','draft'),
                    'post_type' => Business_Disruptions_Post_Type::POST_TYPE_SLUG
                )
            );
            $post_id = $query->post->ID;

            $msg = '<p>Hello '.$user_name.', you are almost done!</p>';
            $msg .= '<p>Your '.get_bloginfo('name').' Closings and Delays account has been created but your email address needs to be verified before your account becomes fully active.</p>';
            $msg .= '<p><a href="'.get_option( "businessurl" ).'login/?' . Business_Disruptions_Page_Login::AUTH1 . '='.self::encode_base64( $user_name ).'&' . Business_Disruptions_Page_Login::AUTH2 . '='. $password .'">Click here to activate your business</a>';
            $msg .= ' or copy and paste this web address into your browser to confirm your email address '.get_option( "businessurl" ).'login/?' . Business_Disruptions_Page_Login::AUTH1 . '='.self::encode_base64( $user_name ).'&' . Business_Disruptions_Page_Login::AUTH2 . '='. $password .'</p>';
            $subject = 'Confirm your Closings and Delay account for '.get_post_meta( $post_id, self::NAME, true);
            add_filter( 'wp_mail_content_type',create_function( '', 'return "text/html";' ) );

            wp_mail( $user_name, $subject, $msg  );
            echo '<div class="bd-success-message">Activation link has been resent successfully.</div>';
        } else {
            echo '<div class="sanerror">Maybe you made a typo when entering your email address. Please contact ' . townsquare_get_email() . ' to resolve this issue.</div>';
        }
        /*
         * Reset database pointer to current blog
         */
        $wpdb->blogid = $current_blog_id;
        $wpdb->set_prefix( $wpdb->base_prefix );

    }
    ?>
    <form id="bd_resendmail_form" class="resendmail" action="" method="post">
        <?php wp_nonce_field(self::NONCE_NAME, self::NONCE_FIELD); ?>
        <p class="bdresend-site-line">Please enter the User id / email  you used to register your business and we will resend the registration email.</p>
        <div class="clearfix"></div>
        <?php
        if ( is_wp_error($user) || $confirmation == 'pending' ){
            if( $user->get_error_message() ){
                echo '<span class="sanerror">Kindly activate your business before you proceed.</span><br/>';
            }
        }
        ?>
        <div class="input clearfix">
            <label for="<?php echo self::USER_LOGIN; ?>">Email / Username</label>
            <input type="text" class="input validate valid-required" size="22" value="" id="bd_resend_usermail" name="<?php echo self::USER_LOGIN; ?>" />
            <input type="submit" name="<?php echo self::FORM_SUBMIT; ?>" id="resendmail_form_submit" class="button-primary button submit-button" value="Resend Mail" />
        </div><!-- // #.input -->
    </form>
</div>