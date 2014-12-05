<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

$options = self::get_daily_operation_hours($_GET[self::POSTID],$options);

$mon = ($options[self::MON]) ? 'block' : 'none';
$tue = ($options[self::TUE]) ? 'block' : 'none';
$wed = ($options[self::WED]) ? 'block' : 'none';
$thu = ($options[self::THU]) ? 'block' : 'none';
$fri = ($options[self::FRI]) ? 'block' : 'none';
$sat = ($options[self::SAT]) ? 'block' : 'none';
$sun = ($options[self::SUN]) ? 'block' : 'none';

?>
<div id="normalTime" class="clearfix" <?php self::hide($options[self::DIFFICULTY],'1'); ?>>
    <select id="starttime" name="<?php echo self::START ;?>"><?php echo self::get_select_from($options[self::START]); ?></select> <span>TO</span>
    <select id="endtime" name="<?php echo self::END ;?>"><?php echo self::get_select_to($options[self::END]); ?></select>
</div>

<div id="advancedTime" <?php self::display($options[self::DIFFICULTY],'1'); ?>>
    <div class="monSelect dayDiv" style="display:<?php echo $mon; ?>"><label>Monday</label>
        <select id="mon_starttime" name="<?php echo self::MON_START ;?>"><?php echo self::get_select_from($options[self::MON_START]); ?></select> <span>TO</span>
        <select id="mon_endtime" name="<?php echo self::MON_END ;?>" ><?php echo self::get_select_to($options[self::MON_END]); ?></select>
    </div>
    <div class="tueSelect dayDiv" style="display:<?php echo $tue; ?>"><label>Tuesday</label>
        <select id="tue_starttime" name="<?php echo self::TUE_START ;?>"><?php echo self::get_select_from($options[self::TUE_START]); ?></select> <span>TO</span>
        <select id="tue_endtime" name="<?php echo self::TUE_END ;?>"><?php echo self::get_select_to($options[self::TUE_END]); ?></select>
    </div>
    <div class="wedSelect dayDiv" style="display:<?php echo $wed; ?>"><label>Wednesday</label>
        <select id="wed_starttime" name="<?php echo self::WED_START ;?>" ><?php echo self::get_select_from($options[self::WED_START]); ?></select> <span>TO</span>
        <select id="wed_endtime" name="<?php echo self::WED_END ;?>"><?php echo self::get_select_to($options[self::WED_END]); ?></select>
    </div>
    <div class="thuSelect dayDiv" style="display:<?php echo $thu; ?>"><label>Thursday</label>
        <select id="thu_starttime" name="<?php echo self::THU_START ;?>"><?php echo self::get_select_from($options[self::THU_START]); ?></select> <span>TO</span>
        <select id="thu_endtime" name="<?php echo self::THU_END ;?>" ><?php echo self::get_select_to($options[self::THU_END]); ?></select>
    </div>
    <div class="friSelect dayDiv" style="display:<?php echo $fri; ?>"><label>Friday</label>
        <select id="fri_starttime" name="<?php echo self::FRI_START ;?>" ><?php echo self::get_select_from($options[self::FRI_START]); ?></select> <span>TO</span>
        <select id="fri_endtime" name="<?php echo self::FRI_END ;?>" ><?php echo self::get_select_to($options[self::FRI_END]); ?></select></div>
    <div class="satSelect dayDiv" style="display:<?php echo $sat; ?>"><label>Saturday</label>
        <select id="sat_starttime" name="<?php echo self::SAT_START ;?>"><?php echo self::get_select_from($options[self::SAT_START]); ?></select> <span>TO</span>
        <select id="sat_endtime" name="<?php echo self::SAT_END ;?>"><?php echo self::get_select_to($options[self::SAT_END]); ?></select></div>
    <div class="sunSelect dayDiv" style="display:<?php echo $sun; ?>"><label>Sunday</label>
        <select id="sun_starttime" name="<?php echo self::SUN_START ;?>" ><?php echo self::get_select_from($options[self::SUN_START]); ?></select> <span>TO</span>
        <select id="sun_endtime" name="<?php echo self::SUN_END ;?>" ><?php echo self::get_select_to($options[self::SUN_END]); ?></select></div>
</div>