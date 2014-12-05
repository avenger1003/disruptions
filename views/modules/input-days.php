<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

$options = self::get_daily_operation_hours($_GET[self::POSTID],$options);

if(!isset($_POST[self::$form_submit]) && !isset($_GET[self::POSTID])){
    $options[self::MON] = 'mon';
    $options[self::TUE] = 'tue';
    $options[self::WED] = 'wed';
    $options[self::THU] = 'thu';
    $options[self::FRI] = 'fri';
}
?>
<div id="businessHoursDiv" class="input checkbox">
    <label for="daysopen">Days Open<span>*</span>
        <?php  if (self::$mesg[self::XDAY]) { echo '<div class="errormsg">'.self::$mesg[self::XDAY].'</div>'; } ?>
    </label>
    <div id="businessHoursregi">
        <div class="day">
            <input type="checkbox" class="check" name="<?php echo self::EVERYDAY ;?>" id="select-all" value="all" <?php checked($options[self::EVERYDAY],'all'); ?> /><span>All</span>
        </div>
        <div class="day daycheck"><input type="checkbox" value="mon" class="check check-mon" name="<?php echo self::MON ;?>" <?php checked($options[self::MON],'mon'); disabled($options[self::EVERYDAY],'all');?> /><span>Mon</span></div>
        <div class="day daycheck"><input type="checkbox" value="tue" class="check check-tue" name="<?php echo self::TUE ;?>" <?php checked($options[self::TUE],'tue'); disabled($options[self::EVERYDAY],'all');?> /><span>Tue</span></div>
        <div class="day daycheck"><input type="checkbox" value="wed" class="check check-wed" name="<?php echo self::WED ;?>" <?php checked($options[self::WED],'wed'); disabled($options[self::EVERYDAY],'all');?> /><span>Wed</span></div>
        <div class="day daycheck"><input type="checkbox" value="thu" class="check check-thu" name="<?php echo self::THU ;?>" <?php checked($options[self::THU],'thu'); disabled($options[self::EVERYDAY],'all');?> /><span>Thu</span></div>
        <div class="day daycheck"><input type="checkbox" value="fri" class="check check-fri" name="<?php echo self::FRI ;?>" <?php checked($options[self::FRI],'fri'); disabled($options[self::EVERYDAY],'all');?> /><span>Fri</span></div>
        <div class="day daycheck"><input type="checkbox" value="sat" class="check check-sat" name="<?php echo self::SAT ;?>" <?php checked($options[self::SAT],'sat'); disabled($options[self::EVERYDAY],'all');?> /><span>Sat</span></div>
        <div class="day daycheck"><input type="checkbox" value="sun" class="check check-sun" name="<?php echo self::SUN ;?>" <?php checked($options[self::SUN],'sun'); disabled($options[self::EVERYDAY],'all');?> /><span>Sun</span></div>
        <input id="checkmode" type="hidden" value="<?php echo $options[self::DIFFICULTY] ;?>" type="text" name="<?php echo self::CHECKMODE ;?>" />
    </div>
</div>