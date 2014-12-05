<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

?>
<div id="formstate" class="clearfix">
    <label for="<?php echo self::STATE; ?>">State<span>*</span></label>
    <select type="text" name="<?php echo self::STATE; ?>" id="station-info-state" class="store-custom-select regselect">
        <option value="AL" <?php selected( $options[self::STATE], 'AL'); ?> >Alabama</option>
        <option value="AK" <?php selected( $options[self::STATE], 'AK'); ?> >Alaska</option>
        <option value="AZ" <?php selected( $options[self::STATE], 'AZ'); ?> >Arizona</option>
        <option value="AR" <?php selected( $options[self::STATE], 'AR'); ?> >Arkansas</option>
        <option value="CA" <?php selected( $options[self::STATE], 'CA'); ?> >California</option>
        <option value="CO" <?php selected( $options[self::STATE], 'CO'); ?> >Colorado</option>
        <option value="CT" <?php selected( $options[self::STATE], 'CT'); ?> >Connecticut</option>
        <option value="DE" <?php selected( $options[self::STATE], 'DE'); ?> >Delaware</option>
        <option value="DC" <?php selected( $options[self::STATE], 'DC'); ?> >District Of Columbia</option>
        <option value="FL" <?php selected( $options[self::STATE], 'FL'); ?> >Florida</option>
        <option value="GA" <?php selected( $options[self::STATE], 'GA'); ?> >Georgia</option>
        <option value="HI" <?php selected( $options[self::STATE], 'HI'); ?> >Hawaii</option>
        <option value="ID" <?php selected( $options[self::STATE], 'ID'); ?> >Idaho</option>
        <option value="IL" <?php selected( $options[self::STATE], 'IL'); ?> >Illinois</option>
        <option value="IN" <?php selected( $options[self::STATE], 'IN'); ?> >Indiana</option>
        <option value="IA" <?php selected( $options[self::STATE], 'IA'); ?> >Iowa</option>
        <option value="KS" <?php selected( $options[self::STATE], 'KS'); ?> >Kansas</option>
        <option value="KY" <?php selected( $options[self::STATE], 'KY'); ?> >Kentucky</option>
        <option value="LA" <?php selected( $options[self::STATE], 'LA'); ?> >Louisiana</option>
        <option value="ME" <?php selected( $options[self::STATE], 'ME'); ?> >Maine</option>
        <option value="MD" <?php selected( $options[self::STATE], 'MD'); ?> >Maryland</option>
        <option value="MA" <?php selected( $options[self::STATE], 'MA'); ?> >Massachusetts</option>
        <option value="MI" <?php selected( $options[self::STATE], 'MI'); ?> >Michigan</option>
        <option value="MN" <?php selected( $options[self::STATE], 'MN'); ?> >Minnesota</option>
        <option value="MS" <?php selected( $options[self::STATE], 'MS'); ?> >Mississippi</option>
        <option value="MO" <?php selected( $options[self::STATE], 'MO'); ?> >Missouri</option>
        <option value="MT" <?php selected( $options[self::STATE], 'MT'); ?> >Montana</option>
        <option value="NE" <?php selected( $options[self::STATE], 'NE'); ?> >Nebraska</option>
        <option value="NV" <?php selected( $options[self::STATE], 'NV'); ?> >Nevada</option>
        <option value="NH" <?php selected( $options[self::STATE], 'NH'); ?> >New Hampshire</option>
        <option value="NJ" <?php selected( $options[self::STATE], 'NJ'); ?> >New Jersey</option>
        <option value="NM" <?php selected( $options[self::STATE], 'NM'); ?> >New Mexico</option>
        <option value="NY" <?php selected( $options[self::STATE], 'NY'); ?> >New York</option>
        <option value="NC" <?php selected( $options[self::STATE], 'NC'); ?> >North Carolina</option>
        <option value="ND" <?php selected( $options[self::STATE], 'ND'); ?> >North Dakota</option>
        <option value="OH" <?php selected( $options[self::STATE], 'OH'); ?> >Ohio</option>
        <option value="OK" <?php selected( $options[self::STATE], 'OK'); ?> >Oklahoma</option>
        <option value="OR" <?php selected( $options[self::STATE], 'OR'); ?> >Oregon</option>
        <option value="PA" <?php selected( $options[self::STATE], 'PA'); ?> >Pennsylvania</option>
        <option value="RI" <?php selected( $options[self::STATE], 'RI'); ?> >Rhode Island</option>
        <option value="SC" <?php selected( $options[self::STATE], 'SC'); ?> >South Carolina</option>
        <option value="SD" <?php selected( $options[self::STATE], 'SD'); ?> >South Dakota</option>
        <option value="TN" <?php selected( $options[self::STATE], 'TN'); ?> >Tennessee</option>
        <option value="TX" <?php selected( $options[self::STATE], 'TX'); ?> >Texas</option>
        <option value="UT" <?php selected( $options[self::STATE], 'UT'); ?> >Utah</option>
        <option value="VT" <?php selected( $options[self::STATE], 'VT'); ?> >Vermont</option>
        <option value="VA" <?php selected( $options[self::STATE], 'VA'); ?> >Virginia</option>
        <option value="WA" <?php selected( $options[self::STATE], 'WA'); ?> >Washington</option>
        <option value="WV" <?php selected( $options[self::STATE], 'WV'); ?> >West Virginia</option>
        <option value="WI" <?php selected( $options[self::STATE], 'WI'); ?> >Wisconsin</option>
        <option value="WY" <?php selected( $options[self::STATE], 'WY'); ?> >Wyoming</option>
    </select>
</div>