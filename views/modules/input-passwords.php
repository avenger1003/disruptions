<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

?>
<div class="input clearfix">
    <label for="<?php echo self::PASS1 ;?>">Password<span>*</span></label>
    <input autocomplete="off" type="password" name="<?php echo self::PASS1 ;?>" id="pass1" class="validate valid-required pass1" value="" tabindex="3"/>
</div>
<div class="input clearfix">
    <label for="<?php echo self::PASS2 ;?>">Confirm Password<span>*</span></label>
    <input autocomplete="off" type="password" name="<?php echo self::PASS2 ;?>" id="pass2" class="validate valid-required" value="" tabindex="4"/>
</div>
<div id="pass-strength-result">Strength indicator</div>
<p class="description indicator-hint">Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ & )</p>
