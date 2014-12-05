<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

?>

<div id="login-business">
    <div class="registration_wrap">
        <?php if(!empty($this->activated_message)) echo $this->activated_message; ?>
        <div class="title"><h2>Business Login</h2></div>
        <?php if(!empty($this->login_message)) echo $this->login_message; ?>
        <form id="login_form" class="login-registration validate" action="" method="post">
            <p class="site-line">Use your <?php echo get_bloginfo(name); ?> business account to log in</p>
            <div class="clearfix"></div>

            <?php if(!empty($this->error_message)) echo $this->error_message; ?>

            <div class="input clearfix">
                <label for="<?php echo self::FORM_USER; ?>">Email / Username</label>
                <input type="text" class="input validate valid-required" tabindex="2" size="22" value="" id="user_login" name="<?php echo self::FORM_USER; ?>" />
            </div><!-- // #.input -->

            <div class="input clearfix">
                <label for="<?php echo self::FORM_PASS; ?>">Password</label>
                <input type="password" class="input validate valid-required" tabindex="3" size="22" value="" id="user_pass" name="<?php echo self::FORM_PASS; ?>" />
            </div><!-- // #.input -->

            <div class="rememberme">
                <label  for="rememberme">Remember Me
                    <input id="rememberme" type="checkbox" name="rememberme" value="forever" tabindex="4" />
                </label>
            </div>
            <input type="submit" name="business_login_form_submit" id="login_form_submit" class="button-primary button submit-button" value="Log In" tabindex="5" />
        </form>
        <p><a href="<?php echo get_bloginfo('url') . '/login/?action=lost-password'; ?>">Forgot Password?</a></p>
        <p>Don\'t have an account? <a href="<?php echo self::registration_url() ;?>">Click here to Register</a></p>
    </div>
</div>

