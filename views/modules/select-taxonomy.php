<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $wpdb;
$taxonomy= self::get_taxonomy_terms();
?>

<div class="input clearfix">
    <label for="<?php echo self::TAXONOMY; ?>">Organization Type<span>*</span></label>
    <select name="<?php echo self::TAXONOMY; ?>" data-customid="my_products_catlist" class="store-custom-select regselect">
        <?php foreach($taxonomy as $term){ ?>
        <option value="<?php echo $term['term_id'] ?>" class="level-0" <?php selected($options[self::TAXONOMY],$term['term_id']); ?> ><?php echo $term['name'] ?></option>
        <?php } ?>
    </select>
</div>