<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

?>
<div class="zipDiv clearfix" id="zipcode">
    <label for="<?php echo self::ZIP ;?>">Zip Code<span>*</span></label>
    <input type="text" name="<?php echo self::ZIP ;?>" class="zipcode"  id="bd_zipcode"  value="<?php echo $options[self::ZIP]; ?>" tabindex="8" />
</div>