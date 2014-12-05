<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

?>
<div class="input add-code clearfix">
    <label for="<?php echo self::ADDRESS ;?>">Address<span>*</span></label>
    <input type="text" name="<?php echo self::ADDRESS ;?>" id="bd_address" class="" value="<?php echo $options[self::ADDRESS]; ?>"  tabindex="5"/>
</div>
<div class="input add-code clearfix" id="formcity">
    <label for="<?php echo self::CITY ;?>">City<span>*</span></label>
    <input type="text" name="<?php echo self::CITY ;?>" class="" id="bd_city" value="<?php echo $options[self::CITY]; ?>" tabindex="6"/>
</div>