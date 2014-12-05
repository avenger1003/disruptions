<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

?>
<li>
    <?php if ($is_owner){ ?>
    <div class="edit-link">
        <a class="aleft editbusiness" href="<?php echo $business_data['link']; ?>" title="Change Status"></a>
        <a id="<?php echo $business_data['ID']; ?>" class="delete_post deletebusiness" title="Delete Business" href="javascript:void(0);"></a>
    </div>
    <?php } ?>
    <div class="blockinfo">
        <span class="locationblock">
            <h4 class="title font_face"><a href="<?php echo $business_data['link']; ?>"><?php echo $business_data['title'];?></a></h4>
            <p><?php echo $business_data['city']; ?></p>
        </span>
        <div class="statusblock">
            <span class="status1">
                <p><?php echo $business_data['today_status']; ?></p>
            </span>
            <span class="status1detail">
                <p><?php echo $business_data['today_message']; ?></p>
            </span>
            <span class="status1">
                <p><?php echo $business_data['tomorrow_status']; ?></p>
            </span>
            <span class="status1detail">
                <p><?php echo $business_data['tomorrow_message']; ?></p>
            </span>
        </div>
    </div>
</li>