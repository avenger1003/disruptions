<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

?>
<div class="input clearfix">
    <label for="<?php echo self::EMAIL ;?>">Business Email<span>*</span></label>
    <input autocomplete="off" type="text" name="<?php echo self::EMAIL ;?>" id="user_login" class="" value="<?php echo $options[self::EMAIL]; ?>" tabindex="2"/>
    <span id="validateUsername"><?php if (!empty(self::$errors[self::EMAIL])) { echo self::$errors[self::EMAIL]; } ?></span>
</div>