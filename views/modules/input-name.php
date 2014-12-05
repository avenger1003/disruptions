<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

?>
<div class="input clearfix">
    <label for="<?php echo self::NAME ;?>">Business Name<span>*</span></label>
    <input type="text" name="<?php echo self::NAME ;?>" id="bname" class="" value="<?php echo $options[self::NAME]; ?>"  tabindex="1"/>
</div>