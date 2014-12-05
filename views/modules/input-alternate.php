<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

?>
<div class="">
    <label for="<?php echo self::CONTACT ;?>">Contact Person</label>
    <input type="text" name="<?php echo self::CONTACT ;?>"  value="<?php echo $options[self::CONTACT]; ?>" tabindex="9"/>
</div>
<div class="input clearfix">
    <label for="<?php echo self::PHONE ;?>">Phone Number</label>
    <input type="text" name="<?php echo self::PHONE ;?>"  id="bd_phone" value="<?php echo $options[self::PHONE]; ?>" tabindex="10"/>
</div>
<div class="input clearfix">
    <label for="<?php echo self::SITE ;?>">Website</label>
    <input type="text" name="<?php echo self::SITE ;?>" id="bd_website" value="<?php echo $options[self::SITE]; ?>" tabindex="11"/>
</div>