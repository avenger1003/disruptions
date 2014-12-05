jQuery(document).ready(function($) {
    jQuery("#select-all:checked").each(function() {
        jQuery('#normalDiv').hide();
        jQuery('#advancedDiv').show();
        jQuery('#normalTime').show();
        jQuery('#advancedTime').hide();
    });

    /** IF DAY OFF  DISABLE **/
    if (jQuery("input#bname").attr("otime") == "") {
        jQuery("#todayChangeStatus").val("closed");
        jQuery("#todayChangeStatus").attr("disabled", true);
        jQuery("input#calldayclosed").attr("checked", "checked");
        jQuery("input#calldayclosed").attr("disabled", true);
        jQuery("#day1Croncheck").val("dayoff");
    }

    if (jQuery("input#bname").attr("totime") == "") {
        jQuery("#tomorrowChangeStatus").val("closed");
        jQuery("#tomorrowChangeStatus").attr("disabled", true);
        jQuery("input#talldayclosed").attr("checked", "checked");
        jQuery("input#talldayclosed").attr("disabled", true);
        jQuery("#day2Croncheck").val("dayoff");
    }

    jQuery('.status-container ul li:last-child').css('border-bottom', 'none');

    jQuery("#businessHoursDiv input:checked").each(function() {
        var obj = jQuery(this).val();
        jQuery('.' + obj + 'Select').show();
        //                    var start = jQuery('#'+obj+'from').val();
        //                    var end = jQuery('#'+obj+'to').val();
        //                    jQuery("#"+obj+"_starttime").val(start);
        //                    jQuery("#"+obj+"_endtime").val(end); 
    });


    /* select-all click */
    jQuery("#select-all").click(function() {
        if (jQuery("#select-all").length == jQuery("#select-all:checked").length) {
            jQuery(".check").attr("checked", "checked");
            jQuery('#advancedDiv').hide();
            jQuery('#normalDiv').hide();
            jQuery('#advancedTime').hide();
            jQuery('#normalTime').show();
            jQuery('#advancedDiv').hide();
            jQuery(".daycheck input").each(function() {
                jQuery(".daycheck input").attr('disabled', '');
                if (jQuery('#select-all').attr('checked')) {
                    jQuery('#checkmode').val('0');
                    jQuery('.normalOadvance').val('0');
                    jQuery('#normalDiv').hide();
                    jQuery('#advancedDiv').hide();
                    //jQuery('#normalTime').show();
                    jQuery('#advancedTime').hide();
                }
            });
        } else {
            jQuery(".check").removeAttr("checked");
            jQuery(".daycheck input").each(function() {
                jQuery(this).removeAttr('disabled');
            });
            jQuery('#normalDiv').hide();
            jQuery('#advancedDiv').show();
        }
    });

    /* day check click */
    jQuery(".daycheck").click(function() {
        var cclass = jQuery(this).find('input').val();
        var obj = jQuery(this).find('input');
        if (jQuery(obj).attr("checked")) {
            jQuery("." + cclass + "Select").show();
        } else {
            jQuery("." + cclass + "Select").hide();
        }
    });
    if (jQuery('.normalOadvance').val() == 1) {
        //  alert(jQuery('.normalOadvance').val());
        jQuery('#advancedTime').show();
        jQuery('#normalDiv').show();

        jQuery('#advancedDiv').hide();
        jQuery('#normalTime').hide();
    }
//                    }else{ 
//                                        alert(jQuery('.normalOadvance').val());
//                                        jQuery('#advancedTime').hide();
////                                        jQuery('#advancedDiv').hide();
//                    }                    
    if (jQuery('.normalOadvance').val() == 0) {
        // alert(jQuery('.normalOadvance').val());
        jQuery('#advancedTime').hide();
        jQuery('#normalDiv').hide();

        jQuery('#advancedDiv').show();
        jQuery('#normalTime').show();
    }

    if (jQuery('.checkAll').val() == 1) {
        // alert(jQuery('.normalOadvance').val());
        jQuery('#advancedDiv').hide();
        jQuery('#normalDiv').hide();
    }

    /* Advacned Div click */
    jQuery('#advancedDiv').click(function() {
        jQuery('#checkmode').val('1');
        jQuery('.normalOadvance').val('1');
        jQuery('.dayDiv').hide();
        jQuery("#businessHoursDiv input:checked").each(function() {
            var obj = jQuery(this).val();
            jQuery('.' + obj + 'Select').show();
        });

        jQuery('#normalDiv').show();
        jQuery('#advancedDiv').hide();
        jQuery('#normalTime').hide();
        jQuery('#advancedTime').show();
    });

    /* Normal Div click */
    jQuery('#normalDiv').click(function() {
        jQuery('#checkmode').val('0');
        jQuery('.normalOadvance').val('0');

        jQuery('#normalDiv').hide();
        jQuery('#advancedDiv').show();
        jQuery('#normalTime').show();
        jQuery('#advancedTime').hide();
    });


    jQuery("#send").click(function() {
        jQuery.ajax({
            type: "post", url: '/wp-admin/admin-ajax.php', data: {action: 'gethello'},
            success: function(html) { //so, if data is retrieved, store it in html
                alert(html);
            }
        }); //close jQuery.ajax(
    });


    /*  SEARCH PROVINCE */
    jQuery('#status').change(function() {
        var st = jQuery('select#status option:selected').val();
        jQuery('span.default-result, span.rmv').remove();
        jQuery('#termList').html('<span class="loader"><p>Looking for closings & delays...</p><img src="/wp-content/plugins/business-disruptions/includes/images/ajax-loader-listing.gif"  alt="Filter" /></span>');
        jQuery.ajax({
            type: 'POST',
            url: BD_Ajax.ajaxUrl,
            data: {action: 'status_search', status: st},
            success: function(result) {
                jQuery('#termList').html(result);
            }
        });
        return false;
    });

    /*  RESEND MAIL */
    jQuery('.b1dresendlink').click(function() {
        //                                        var  st =   jQuery( 'select#status option:selected').val();
        //                                        jQuery('span.default-result, span.rmv').remove();
        jQuery('#login-business').html('<span class="resend-loader"><p>Looking for resend mail..</p><img src="/wp-content/plugins/business-disruptions/includes/images/ajax-loader-listing.gif"  alt="Filter" /></span>');
        jQuery.ajax({
            type: 'POST',
            url: BD_Ajax.ajaxUrl,
            data: {action: 'bd_resendMail'},
            success: function(mail) {
                jQuery('#login-business, .success-message').html(mail);
            }
        });
        return false;
    });

    jQuery('.pllink').live("click", function() {
        var pagid = jQuery(this).attr('id');
        var strs = jQuery('input.business-search-text').val();
        if (strs == "Search by name or ZIP") {
            var str = jQuery('select.changeCats option:selected').val();
            var strcity = jQuery('select.changeCity option:selected').val();
            var statusid = jQuery('select.changeStatus option:selected').val();
        } else {
            var str = '';
            var strcity = '';
            var statusid = '';
            var cday = '';
        }

        jQuery('#termList').html('<span class="loader"><p>Looking for closings & delays...</p><img src="/wp-content/plugins/business-disruptions/includes/images/ajax-loader-listing.gif"  alt="Filter" /></span>');
        //jQuery('.pagination').hide();
        jQuery('.default-result').hide();
        jQuery.ajax({
            type: 'POST',
            url: BD_Ajax.ajaxUrl,
            data: {action: 'businessListing', page: pagid, bsearch: strs, catname: str, cityname: strcity, statusid: statusid, cday: cday},
            success: function(result) {
                jQuery('.status-container').html(result);
                jQuery('.default-result').show();
            }
        });
        return false;
    });

//                                        /**  RETURN 'S' FOR EMPTY SEARCH KEYUP **/
//                                        jQuery('input.business-search-text').live( "keyup", function() {
//                                        var  strs =  (  jQuery('input.business-search-text').val() ) ?  jQuery('input.business-search-text').val() : 's';
//                                                            jQuery.ajax( {
//                                                                                type: 'POST',
//                                                                                url: BD_Ajax.ajaxUrl,
//                                                                                data: {action: 'businessListing', bsearch: strs },
//                                                                                success: function( result ){ 
//                                                                                jQuery('.status-container').html( result );
//                                                                                }
//                                                             } );
//                                        return false;
//                                        });

    jQuery('.changeCats, .changeCity, .changeToday, .changeTomo, .changeStatus, .changeDate').live("change", function() {
        var str = jQuery('select.changeCats option:selected').val();
        var strcity = jQuery('select.changeCity option:selected').val();
        var strtoday = jQuery('input.changeToday:checked').val();
        var strtomo = jQuery('input.changeTomo:checked').val();
        var statusid = jQuery('select.changeStatus option:selected').val();
        var strs = jQuery('input.business-search-text').val();

        var cday = '';

        var postids = jQuery('#postids').val();
        if (strs && strs != 'Search by name or ZIP' && (!str || !strcity || !statusid)) {
            var hrefparams = 'bsearch=' + strs;
        } else if (str || strcity || statusid) {
            strs = 'Search by name or ZIP';
            var hrefparams = 'catname=' + str + '&cityname=' + strcity + '&statusid=' + statusid;
        }
        jQuery('#exportdiv a').attr('href', BD_Ajax.pluginurl + "excel_report.php?" + hrefparams);

        jQuery('span.default-result, span.rmv').remove();
        //alert(cday);
        jQuery('#termList').html('<span class="loader"><p>Looking for closings & delays...</p><img src="/wp-content/plugins/business-disruptions/includes/images/ajax-loader-listing.gif"  alt="Filter" /></span>');
        //jQuery('.pagination').hide();
        jQuery('.default-result').hide();
        jQuery.ajax({
            type: 'POST',
            url: BD_Ajax.ajaxUrl,
            data: {action: 'businessListing', catname: str, cityname: strcity, today: strtoday, tomo: strtomo, postids: postids, statusid: statusid, cday: cday, bsearch: strs},
            success: function(result) {
                jQuery('.status-container').html(result);
                jQuery('input.business-search-text').val('Search by name or ZIP');
                if (strcity != '') {
                    jQuery('select.changeCity option:selected').val(strcity);
                }
                if (str != '') {
                    jQuery('select.changeCats option:selected').val(str);
                }
                //jQuery('.pagination').show();
                jQuery('.default-result').show();
            }
        });
        return false;
    });

    /**  SEARCH BY BUSINESS OR CITY  **/
    jQuery('.business-keyword-search, .clickPlace').click(function() {
        var strs = jQuery('input.business-search-text').val();

        var strhref = jQuery('clickPlace').attr('href');

        var str1 = '';
        var strcity = '';
        var statusid = '';

        var str = (strs) ? strs : strhref;
        //alert(str);
        jQuery('span.default-result, span.rmv').remove();
        jQuery('#termList').html('<span class="loader"><p>Looking for closings & delays...</p><img src="/wp-content/plugins/business-disruptions/includes/images/ajax-loader-listing.gif"  alt="Filter" /></span>');
        // jQuery('.pagination').hide();
        jQuery('.default-result').hide();
        if (strs && strs != 'Search by name or ZIP' && (!str || !strcity || !statusid)) {
            var hrefparams = 'bsearch=' + strs;
        } else if (str || strcity || statusid) {
            strs = 'Search by name or ZIP';
            var hrefparams = 'catname=' + str1 + '&cityname=' + strcity + '&statusid=' + statusid;
        }
        jQuery('#exportdiv a').attr('href', BD_Ajax.pluginurl + "excel_report.php?" + hrefparams);
        jQuery.ajax({
            type: 'POST',
            url: BD_Ajax.ajaxUrl,
            data: {action: 'businessListing', bsearch: strs},
            success: function(result) {
                jQuery('.status-container').html(result);
                //jQuery('.pagination').show();
                jQuery('.default-result').show();
            }
        });
        return false;
    });


    jQuery('.business-search-text').keypress(function(event) {

        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var strs = (jQuery('input.business-search-text').val()) ? jQuery('input.business-search-text').val() : 'Search by name or ZIP';
            var str1 = jQuery('select.changeCats option:selected').val();
            var strcity = jQuery('select.changeCity option:selected').val();
            var statusid = jQuery('select.changeStatus option:selected').val();
            var strhref = jQuery('clickPlace').attr('href');
            var str = (strs) ? strs : strhref;
            //alert(str);
            jQuery('span.default-result, span.rmv').remove();
            if (strs && strs != 'Search by name or ZIP' && (!str || !strcity || !statusid)) {
                var hrefparams = 'bsearch=' + strs;
            } else if (str || strcity || statusid) {
                strs = 'Search by name or ZIP';
                var hrefparams = 'catname=' + str1 + '&cityname=' + strcity + '&statusid=' + statusid;
            }
            jQuery('#exportdiv a').attr('href', BD_Ajax.pluginurl + "excel_report.php?" + hrefparams);
            jQuery('#termList').html('<span class="loader"><p>Looking for closings & delays...</p><img src="/wp-content/plugins/business-disruptions/includes/images/ajax-loader-listing.gif"  alt="Filter" /></span>');
            jQuery.ajax({
                type: 'POST',
                url: BD_Ajax.ajaxUrl,
                data: {action: 'businessListing', bsearch: strs},
                success: function(result) {
                    jQuery('.status-container').html(result);
                }
            });
            return false;
        }
    });

    jQuery(".cat-drop-cont").hide();
    jQuery(".cat-wrap-rest").click(function() {
        jQuery(".cat-drop-cont").toggleClass("active").slideToggle("fast");
        return false;
    });
    jQuery('.business-search-text').blur(function() {
        if (this.value == '')
            this.value = 'Search by name or ZIP';
    });
    jQuery('.business-search-text').focus(function() {
        if (this.value == 'Search by name or ZIP')
            this.value = '';
    });


    /* Business Ajax Check */
    var validateUsername = jQuery('#validateUsername');
    jQuery("#user_login").blur(function() {
        var t = this;
        this.lastValue = '';
        var email = jQuery(this).val();
        if (this.timer)
            clearTimeout(this.timer);
        validateUsername.removeClass('error').html('checking availability...');
        this.timer = setTimeout(function() {
            jQuery.ajax({
                type: "post", url: BD_Ajax.ajaxUrl, data: {action: 'businesscheck', useremail: email},
                success: function(retval) { //so, if data is retrieved, store it in html
                    console.log(retval);
                    var myVar = retval.split(',');
                    validateUsername.html(myVar[1]);
                }
            });
        }, 200);
    });

    jQuery(".delete_post").live('click', function() {
        //alert("d");
        var pid = jQuery(this).attr('id');
        //alert(pid);
        if (confirm('Are you sure you want to delete this Business?')) {
            jQuery.ajax({
                type: "post", url: BD_Ajax.ajaxUrl, data: {action: 'deletepost', pid: pid},
                success: function(retval) { //so, if data is retrieved, store it in html
                    window.location.replace(BD_Ajax.manageurl);
                    // window.location.replace(BD_Ajax.manageurl);
                }
            });

        }
    });

    jQuery("#bname").change(function() {
        previewClosings();
    });


    jQuery("#tomorrowChangeStatus").change(function() {
        if (jQuery(this).val() == "delayed") {
            jQuery("#tomorrow_delayed_details,#tomorrow_closed_details,#tomorrow_dismissal_details").css("display", "none");
            jQuery("#tomorrow_delayed_details").css("display", "block");
            //jQuery("#tomorrow_delayed_details").css("display","block");
        } else if (jQuery(this).val() == "closed") {
            jQuery("#tomorrow_delayed_details,#tomorrow_closed_details,#tomorrow_dismissal_details").css("display", "none");
            jQuery("#tomorrow_closed_details").css("display", "block");
            //jQuery("#tomorrow_closed_details").css("display","block");
        } else if (jQuery(this).val() == "early_dismisal") {
            jQuery("#tomorrow_delayed_details,#tomorrow_closed_details,#tomorrow_dismissal_details").css("display", "none");
            jQuery("#tomorrow_dismissal_details").css("display", "block");
            //jQuery("#tomorrow_closed_details").css("display","block");
        } else {
            jQuery("#tomorrow_delayed_details,#tomorrow_closed_details,#tomorrow_dismissal_details").css("display", "none");
        }
        previewClosings();
    });

    jQuery("#cfromhr,#ctohr, #ctfromhr, #cttohr, #tomorrow_edtohr,#dtimehr,#dtimemin,#tomorrow_dtimehr, #tomorrow_dtimemin,#edtohr").change(function() {
        previewClosings();
    });

    jQuery("#todayChangeStatus").change(function() {
        if (jQuery(this).val() == "delayed") {
            jQuery("#today_delayed_details,#today_closed_details,#today_dismissal_details").css("display", "none");
            jQuery("#today_delayed_details").css("display", "block");
            //jQuery("#tomorrow_delayed_details").css("display","block");
        } else if (jQuery(this).val() == "closed") {
            jQuery("#today_delayed_details,#today_closed_details,#today_dismissal_details").css("display", "none");
            jQuery("#today_closed_details").css("display", "block");
            //jQuery("#tomorrow_closed_details").css("display","block");
        } else if (jQuery(this).val() == "early_dismisal") {
            jQuery("#today_delayed_details,#today_closed_details,#today_dismissal_details").css("display", "none");
            jQuery("#today_dismissal_details").css("display", "block");
            //jQuery("#tomorrow_closed_details").css("display","block");
        } else {
            jQuery("#today_delayed_details,#today_closed_details,#today_dismissal_details").css("display", "none");
        }
        previewClosings();
    });

    /* start today fields visible */
    if (jQuery("#todayChangeStatus").val() == "closed") {
        jQuery("#today_delayed_details,#today_closed_details,#today_dismissal_details").css("display", "none");
        jQuery("#today_closed_details").css("display", "block");
    } else if (jQuery("#todayChangeStatus").val() == "early_dismisal") {
        jQuery("#today_delayed_details,#today_closed_details,#today_dismissal_details").css("display", "none");
        jQuery("#today_dismissal_details").css("display", "block");
    } else if (jQuery("#todayChangeStatus").val() == "delayed") {
        jQuery("#today_delayed_details,#today_closed_details,#today_dismissal_details").css("display", "none");
        jQuery("#today_delayed_details").css("display", "block");
    } else if (jQuery("#todayChangeStatus").val() == "normal_hours") {
        jQuery("#today_delayed_details,#today_closed_details,#today_dismissal_details").css("display", "none");
    }

    /* end today fields */
    if (jQuery("#tomorrowChangeStatus").val() == "closed") {
        jQuery("#tomorrow_delayed_details,#tomorrow_closed_details,#tomorrow_dismissal_details").css("display", "none");
        jQuery("#tomorrow_closed_details").css("display", "block");
    } else if (jQuery("#tomorrowChangeStatus").val() == "early_dismisal") {
        jQuery("#tomorrow_delayed_details,#tomorrow_closed_details,#tomorrow_dismissal_details").css("display", "none");
        jQuery("#tomorrow_dismissal_details").css("display", "block");
    } else if (jQuery("#tomorrowChangeStatus").val() == "delayed") {
        jQuery("#tomorrow_delayed_details,#tomorrow_closed_details,#tomorrow_dismissal_details").css("display", "none");
        jQuery("#tomorrow_delayed_details").css("display", "block");
    } else if (jQuery("#tomorrowChangeStatus").val() == "normal_hours") {
        jQuery("#tomorrow_delayed_details,#tomorrow_closed_details,#tomorrow_dismissal_details").css("display", "none");
    }

    jQuery('#today_closed_details #calldayclosed').live("click", function() {
        if ("alldayclosed" == jQuery("#today_closed_details #calldayclosed:checked").val())
            jQuery("#ttodayselctholder").css("display", "none");
        else
            jQuery("#ttodayselctholder").css("display", "block");
        previewClosings();
    });

    jQuery('#tomorrow_closed_details #talldayclosed').live("click", function() {
        if ("alldayclosed" == jQuery("#tomorrow_closed_details #talldayclosed:checked").val())
            jQuery("#tomorrow_ttodayselctholder").css("display", "none");
        else
            jQuery("#tomorrow_ttodayselctholder").css("display", "block");
        previewClosings();
    });
    if ("alldayclosed" == jQuery("#today_closed_details #calldayclosed:checked").val())
        jQuery("#ttodayselctholder").css("display", "none");
    else
        jQuery("#ttodayselctholder").css("display", "block");

    if ("alldayclosed" == jQuery("#tomorrow_closed_details #talldayclosed:checked").val())
        jQuery("#tomorrow_ttodayselctholder").css("display", "none");
    else
        jQuery("#tomorrow_ttodayselctholder").css("display", "block");


    jQuery('.todayDiv textarea').keyup(function() {
        var charLength = $(this).val().length;
        // Displays count
        jQuery(this).next().html(charLength + ' of 200 characters used');
        // Alerts when 200 characters is reached
        if (jQuery(this).val().length > 200)
            jQuery(this).next().html('<span>You may only have up to 200 characters.</span>');
    });
    jQuery('.tomorrowDiv textarea').keyup(function() {
        var charLength = $(this).val().length;
        // Displays count
        jQuery(this).next().html(charLength + ' of 200 characters used');
        // Alerts when 200 characters is reached
        if (jQuery(this).val().length > 200)
            jQuery(this).next().html('<span>You may only have up to 200 characters.</span>');
    });

    jQuery("#bb").click(function() {
        previewClosings();
    });
    previewClosings();
});

function previewClosings() {
    try {
        var statusStr = "";
        jQuery("#closingsPreview").css("display", "block");

        if (jQuery("#todayChangeStatus").val() == "closed" || jQuery("#tomorrowChangeStatus").val() == "closed") {

            todayStatusStr = "";
            tomorrowStatusStr = "";

            if (jQuery("#todayChangeStatus").val() == "closed") {

                if ("alldayclosed" == jQuery("#today_closed_details #calldayclosed:checked").val()) {

                    todayStatusStr = "Closed today";

                    //alert(todayStatusStr);

                } else {

                    todayClosedHrs = ((jQuery("#ctohr").val() == "none") || (jQuery("#cfromhr").val() == "none")) ? "none" : (parseInt(jQuery("#ctohr").val()) - parseInt(jQuery("#cfromhr").val()));

                    if (todayClosedHrs > 0) {


                        if ((jQuery("#cfromhr").val() + ":00").length <= 4)
                            fdate = GetNumber4("0" + jQuery("#cfromhr").val() + ":00");
                        else
                            fdate = GetNumber4(jQuery("#cfromhr").val() + ":00");


                        if ((jQuery("#ctohr").val() + ":00").length <= 4)
                            tdate = GetNumber4("0" + jQuery("#ctohr").val() + ":00");
                        else
                            tdate = GetNumber4(jQuery("#ctohr").val() + ":00");

                        //alert(tdate);

                        todayStatusStr = "Closed today for " + todayClosedHrs + " hr(s) from " + fdate + " to " + tdate;

                        //alert(todayStatusStr);
                    }
                }

            }

            if (jQuery("#tomorrowChangeStatus").val() == "closed") {

                if ("alldayclosed" == jQuery("#tomorrow_closed_details #talldayclosed:checked").val()) {
                    tomorrowStatusStr = "Closed tomorrow";
                    //alert(tomorrowStatusStr);
                } else {
                    tomorrowClosedHrs = ((jQuery("#cttohr").val() == "none") || (jQuery("#ctfromhr").val() == "none")) ? "none" : (parseInt(jQuery("#cttohr").val()) - parseInt(jQuery("#ctfromhr").val()));
                    if (tomorrowClosedHrs > 0) {
                        if ((jQuery("#ctfromhr").val() + ":00").length <= 4)
                            fdate = GetNumber4("0" + jQuery("#ctfromhr").val() + ":00");
                        else
                            fdate = GetNumber4(jQuery("#ctfromhr").val() + ":00");
                        if ((jQuery("#cttohr").val() + ":00").length <= 4)
                            tdate = GetNumber4("0" + jQuery("#cttohr").val() + ":00");
                        else
                            tdate = GetNumber4(jQuery("#cttohr").val() + ":00");
                        tomorrowStatusStr = "Closed tomorrow for " + tomorrowClosedHrs + " hr(s) from " + fdate + " to " + tdate;
                    }
                }
            }

            statusStr = todayStatusStr + tomorrowStatusStr;
        }
        if (jQuery("#todayChangeStatus").val() == "delayed" || jQuery("#tomorrowChangeStatus").val() == "delayed") {

            if (jQuery("#todayChangeStatus").val() == "delayed") {
                v = "";
                if (!isNaN(parseInt(jQuery("input#bname").attr("otime")))) {
                    v = parseInt(jQuery("input#bname").attr("otime"), 10);
                    v += parseInt(jQuery("#dtimehr").val(), 10);
                }
                m = jQuery("#dtimemin").val();

                v = v + ":" + m;
                if (v.length <= 4)
                    fdate_today = GetNumber4("0" + v);
                else
                    fdate_today = GetNumber4(v);

                if (jQuery("input#bname").attr("otime") != "") {
                    ostr = ", opens at " + fdate_today;
                }
                else {
                    ostr = "???";
                }
                shrs = smins = "";
                if (jQuery("#dtimemin").val() != "00")
                    smins = jQuery("#dtimemin").val() + " mins";

                if (jQuery("#dtimehr").val() != "00")
                    shrs = jQuery("#dtimehr").val() + " hr(s) ";

                if (shrs != "" || smins != "")
                    todayStatusStr = "Delayed today by " + shrs + smins + ostr;

                jQuery("input#today_delayexpiry").val(fdate_today);
            }

            if (jQuery("#tomorrowChangeStatus").val() == "delayed") {
                v = "";
                if (!isNaN(parseInt(jQuery("input#bname").attr("totime")))) {
                    v = parseInt(jQuery("input#bname").attr("totime"), 10);
                    v += parseInt(jQuery("#tomorrow_dtimehr").val(), 10);
                }
                m = jQuery("#tomorrow_dtimemin").val();
                v = v + ":" + m;

                if (v.length <= 4)
                    fdate_tomo = GetNumber4("0" + v);
                else
                    fdate_tomo = GetNumber4(v);

                if (jQuery("input#bname").attr("totime") != "")
                    tostr = ", opens at " + fdate_tomo;
                else
                    tostr = "???";

                shrs = smins = "";
                if (jQuery("#tomorrow_dtimemin").val() != "00")
                    smins = jQuery("#tomorrow_dtimemin").val() + " mins";

                if (jQuery("#tomorrow_dtimehr").val() != "00")
                    shrs = jQuery("#tomorrow_dtimehr").val() + " hr(s) ";

                if (shrs != "" || smins != "")
                    tomorrowStatusStr = "Delayed tomorrow by " + shrs + smins + tostr;

                jQuery("input#tomo_delayexpiry").val(fdate_tomo);
            }
        }

        if (jQuery("#todayChangeStatus").val() == "early_dismisal" || jQuery("#tomorrowChangeStatus").val() == "early_dismisal") {
            if (jQuery("#todayChangeStatus").val() == "early_dismisal") {

                if ((jQuery("#edtohr").val() + ":00").length <= 4)
                    fdate = GetNumber4("0" + jQuery("#edtohr").val() + ":00");
                else
                    fdate = GetNumber4(jQuery("#edtohr").val() + ":00");

                todayStatusStr = "Closed today by " + fdate;
            }

            if (jQuery("#tomorrowChangeStatus").val() == "early_dismisal") {

                if ((jQuery("#tomorrow_edtohr").val() + ":00").length <= 4)
                    fdate = GetNumber4("0" + jQuery("#tomorrow_edtohr").val() + ":00");
                else
                    fdate = GetNumber4(jQuery("#tomorrow_edtohr").val() + ":00");

                tomorrowStatusStr = "Closed tomorrow by " + fdate;
            }
        }

        if (jQuery("#todayChangeStatus").val() == "normal_hours") {
            todayStatusStr = "Normal Hours";
        }

        if (jQuery("#tomorrowChangeStatus").val() == "normal_hours") {
            tomorrowStatusStr = "Normal Hours";
        }

        statusStr = todayStatusStr + tomorrowStatusStr;
        //alert(todayStatusStr);
        if (todayStatusStr == "") {
            jQuery("#cpreview1").css("display", "none");
        } else {
            jQuery("#cpreview1").css("display", "block");
        }

        if (tomorrowStatusStr == "") {
            jQuery("#cpreview").css("display", "none");
        } else {
            jQuery("#cpreview").css("display", "block");
        }

        jQuery("#day2message").val(tomorrowStatusStr);
        jQuery("#cpreview").html('<div class="business-status" id="business-status">' + tomorrowStatusStr + '</div>');
        jQuery("#cpreview1").html('<div class="business-status" id="business-status">' + todayStatusStr + '</div>');
        jQuery("#day1message").val(todayStatusStr);

    } catch (e) {
    }
}

jQuery(document).ready(function() {
    if (jQuery('#editmode').val() != 1) {
        if (jQuery('input#businessHours')) {
            jQuery(".check").attr("checked", "checked");
            jQuery("#select-all").removeAttr('checked');
            jQuery(".check-sat").removeAttr('checked');
            jQuery(".check-sun").removeAttr('checked');
        }
    }
});

function validateClosingsForm() {
    checkStatusStr = "";
    checkFlag = false;
    if (jQuery("#todayChangeStatus").val() == "closed") {
        if (!jQuery("input#calldayclosed:checked").val()) {
            //alert('I`m here');	
            if ((jQuery("#ctohr").val() != "none") && (jQuery("#cfromhr").val() != "none")) {
                if (((parseInt(jQuery("#ctohr").val()) - parseInt(jQuery("#cfromhr").val())) <= 0)) {
                    checkStatusStr += "Opening time should be greater than closing time for today.\n";
                    checkFlag = true;
                }
            } else if ((jQuery("#ctohr").val() == "none") || (jQuery("#cfromhr").val() == "none")) {
                //alert('I`m here......');	
                //if(jQuery("#ctohr").val() != jQuery("#cfromhr").val()){
                checkStatusStr += "Please check  start time and end time for today.\n";
                checkFlag = true;
            }
        }
    }

    if (jQuery("#tomorrowChangeStatus").val() == "closed") {
        if (!jQuery("input#talldayclosed:checked").val() && jQuery("#tomorrowChangeStatus").val() != "normal_hours") {
            if ((jQuery("#cttohr").val() != "none") && (jQuery("#ctfromhr").val() != "none")) {
                if (((parseInt(jQuery("#cttohr").val()) - parseInt(jQuery("#ctfromhr").val())) <= 0)) {
                    checkStatusStr += "Opening time should be greater than closing time for tomorrow.\n";
                    checkFlag = true;
                }
            } else if ((jQuery("#cttohr").val() == "none") || (jQuery("#ctfromhr").val() == "none")) {
                checkStatusStr += "Please check  start time and end time for tomorrow.\n";
                checkFlag = true;
            }
        }
    }

    if (jQuery("#todayChangeStatus").val() == "delayed") {
        if (jQuery("#dtimehr").val() == "00" && jQuery("#dtimemin").val() == "00") {
            checkStatusStr += "Please check the delay time for today \n";
            checkFlag = true;
        }
    }
    if (jQuery("#tomorrowChangeStatus").val() == "delayed") {
        if (jQuery("#tomorrow_dtimehr").val() == "00" && jQuery("#tomorrow_dtimemin").val() == "00") {
            checkStatusStr += "Please check the delay time for tomorrow \n";
            checkFlag = true;
        }
    }
    if (jQuery("#todayChangeStatus").val() == "early_dismisal") {
        if (jQuery("#edtohr").val() == "none") {
            checkStatusStr += "Please check the early dismissal time for today \n";
            checkFlag = true;
        }
    }
    if (jQuery("#tomorrowChangeStatus").val() == "early_dismisal") {
        if (jQuery("#tomorrow_edtohr").val() == "none") {
            checkStatusStr += "Please check the early dismissal time for tomorrow \n";
            checkFlag = true;
        }
    }
    if (checkFlag)
        alert(checkStatusStr);
    return !checkFlag;
}

function validatebusinessForm() {
    checkStatusStr = "";
    checkFlag = false;
    if (jQuery('.normalOadvance').val() == 0) {
        if ((jQuery("#starttime").val() != "none") && (jQuery("#endtime").val() != "none")) {
            //                                                            alert( jQuery( "#starttime" ).val() );
            //                                                            alert( jQuery( "#endtime" ).val() );

            if (((parseInt(jQuery("#starttime").val()) - parseInt(jQuery("#endtime").val())) >= 0)) {
                checkStatusStr += "Opening time should be greater than closing time.\n";
                checkFlag = true;
            }
        } else if ((jQuery("#starttime").val() == "none") || (jQuery("#endtime").val() == "none")) {
            //alert('I`m here......');	
            //if(jQuery("#ctohr").val() != jQuery("#cfromhr").val()){
            checkStatusStr += "Please check  start time and end time.\n";
            checkFlag = true;
        }
    } else if (jQuery('.normalOadvance').val() == 1) {

        if (jQuery('.check-mon').attr('checked')) {
            if ((jQuery("#mon_starttime").val() != "none") && (jQuery("#mon_endtime").val() != "none")) {
                if (((parseInt(jQuery("#mon_starttime").val()) - parseInt(jQuery("#mon_endtime").val())) >= 0)) {
                    checkStatusStr += "Opening time should be greater than closing time for Monday.\n";
                    checkFlag = true;
                }
            } else if ((jQuery("#mon_starttime").val() == "none") || (jQuery("#mon_endtime").val() == "none")) {
                checkStatusStr += "Please check  start time and end time for Monday.\n";
                checkFlag = true;
            }
        }

        if (jQuery('.check-tue').attr('checked')) {
            if ((jQuery("#tue_starttime").val() != "none") && (jQuery("#tue_endtime").val() != "none")) {
                if (((parseInt(jQuery("#tue_starttime").val()) - parseInt(jQuery("#tue_endtime").val())) >= 0)) {
                    checkStatusStr += "Opening time should be greater than closing time for Tuesday.\n";
                    checkFlag = true;
                }
            } else if ((jQuery("#tue_starttime").val() == "none") || (jQuery("#tue_endtime").val() == "none")) {
                checkStatusStr += "Please check  start time and end time for Tuesday.\n";
                checkFlag = true;
            }
        }

        if (jQuery('.check-wed').attr('checked')) {
            if ((jQuery("#wed_starttime").val() != "none") && (jQuery("#wed_endtime").val() != "none")) {
                if (((parseInt(jQuery("#wed_starttime").val()) - parseInt(jQuery("#wed_endtime").val())) >= 0)) {
                    checkStatusStr += "Opening time should be greater than closing time for Wednesday.\n";
                    checkFlag = true;
                }
            } else if ((jQuery("#wed_starttime").val() == "none") || (jQuery("#wed_endtime").val() == "none")) {
                checkStatusStr += "Please check  start time and end time for Wednesday.\n";
                checkFlag = true;
            }
        }

        if (jQuery('.check-thu').attr('checked')) {
            if ((jQuery("#thu_starttime").val() != "none") && (jQuery("#thu_endtime").val() != "none")) {
                if (((parseInt(jQuery("#thu_starttime").val()) - parseInt(jQuery("#thu_endtime").val())) >= 0)) {
                    checkStatusStr += "Opening time should be greater than closing time for Thursday.\n";
                    checkFlag = true;
                }
            } else if ((jQuery("#thu_starttime").val() == "none") || (jQuery("#thu_endtime").val() == "none")) {
                checkStatusStr += "Please check  start time and end time for Thursday.\n";
                checkFlag = true;
            }
        }

        if (jQuery('.check-fri').attr('checked')) {
            if ((jQuery("#fri_starttime").val() != "none") && (jQuery("#fri_endtime").val() != "none")) {
                if (((parseInt(jQuery("#fri_starttime").val()) - parseInt(jQuery("#fri_endtime").val())) >= 0)) {
                    checkStatusStr += "Opening time should be greater than closing time for Friday.\n";
                    checkFlag = true;
                }
            } else if ((jQuery("#fri_starttime").val() == "none") || (jQuery("#fri_endtime").val() == "none")) {
                checkStatusStr += "Please check  start time and end time for Friday.\n";
                checkFlag = true;
            }
        }

        if (jQuery('.check-sat').attr('checked')) {
            if ((jQuery("#sat_starttime").val() != "none") && (jQuery("#sat_endtime").val() != "none")) {
                if (((parseInt(jQuery("#sat_starttime").val()) - parseInt(jQuery("#sat_endtime").val())) >= 0)) {
                    checkStatusStr += "Opening time should be greater than closing time for Saturday.\n";
                    checkFlag = true;
                }
            } else if ((jQuery("#sat_starttime").val() == "none") || (jQuery("#sat_endtime").val() == "none")) {
                checkStatusStr += "Please check  start time and end time for Saturday.\n";
                checkFlag = true;
            }
        }

        if (jQuery('.check-sun').attr('checked')) {
            if ((jQuery("#sun_starttime").val() != "none") && (jQuery("#sun_endtime").val() != "none")) {
                if (((parseInt(jQuery("#sun_starttime").val()) - parseInt(jQuery("#sun_endtime").val())) >= 0)) {
                    checkStatusStr += "Opening time should be greater than closing time for Sunday.\n";
                    checkFlag = true;
                }
            } else if ((jQuery("#sun_starttime").val() == "none") || (jQuery("#sun_endtime").val() == "none")) {
                checkStatusStr += "Please check  start time and end time for Sunday.\n";
                checkFlag = true;
            }
        }
    }
    if (checkFlag)
        alert(checkStatusStr);
    return !checkFlag;
}

function GetNumber4(h) {
    var sAve = 0
    var ez1 = ""
    var ml3 = "00"
    var resultTime = ""
    var pw3 = parseFloat(CleanBad(h));
    var pm4 = CleanBad(h);
    var pm5 = CleanBad(h);
    var len = pm4.length
    if (len != 4) {
        resultTime = "???"
    }
    else if (pw3 > 2401) {
        resultTime = "???"
    }
    else if (pw3 > 1259) {
        pw3 = pw3 - 1200

        var pm4 = String(pw3)

        var len = pm4.length
        if (len < 4) {
            pm4 = "0" + pm4
        }

        if (pm4.substring(0, 1) == "0") {
            var hours = pm4.substring(1, 2)
        }
        else {
            var hours = pm4.substring(0, 2)
        }
        var mins = pm4.substring(2, 4)
        ez1 = " P.M."
        if (hours == "12")
            ez1 = " A.M."
        if (mins > "59")
            mins = "???"
        resultTime = hours + ":" + mins + ez1
    }

    else {
        if (pm4.substring(0, 1) == "0") {
            var hours = pm4.substring(1, 2)
        }
        else {
            var hours = pm4.substring(0, 2)
        }
        var mins = pm4.substring(2, 4)
        ez1 = " A.M."
        if (hours == "12")
            ez1 = " P.M."
        if (pm5.substring(0, 2) == "00")
            hours = "12"
        if (mins > "59")
            mins = "???"

        resultTime = hours + ":" + mins + ez1
    }

    return resultTime;
}

function CleanBad(string) {
    for (var i = 0, output = '', valid = "eE-0123456789."; i < string.length; i++)
        if (valid.indexOf(string.charAt(i)) != -1)
            output += string.charAt(i)
    return output;
}

/* Validation */

jQuery(document).ready(function($) {
    /* Jquery validation */
    (function(jQuery) {
        var ValidationErrors = new Array();
        jQuery.fn.validate = function(options) {
            options = jQuery.extend({
                expression: "return true;",
                message: "",
                error_class: "ValidationErrors",
                error_field_class: "ErrorField",
                live: true
            }, options);
            var SelfID = jQuery(this).attr("id");
            var unix_time = new Date();
            unix_time = parseInt(unix_time.getTime() / 1000);
            if (!jQuery(this).parents('form:first').attr("id")) {
                jQuery(this).parents('form:first').attr("id", "Form_" + unix_time);
            }
            var FormID = jQuery(this).parents('form:first').attr("id");
            if (!((typeof (ValidationErrors[FormID]) == 'object') && (ValidationErrors[FormID] instanceof Array))) {
                ValidationErrors[FormID] = new Array();
            }
            if (options['live']) {
                if (jQuery(this).find('input').length > 0) {
                    jQuery(this).find('input').bind('blur', function() {
                        if (bdvalidate_field("#" + SelfID, options)) {
                            if (options.callback_success)
                                options.callback_success(this);
                        } else {
                            if (options.callback_failure)
                                options.callback_failure(this);
                        }
                    });
                    jQuery(this).find('input').bind('focus keypress click', function() {
                        jQuery("#" + SelfID).next('.' + options['error_class']).remove();
                        jQuery("#" + SelfID).removeClass(options['error_field_class']);
                    });
                } else {
                    jQuery(this).bind('blur', function() {
                        bdvalidate_field(this);
                    });
                    jQuery(this).bind('focus keypress', function() {
                        jQuery(this).next('.' + options['error_class']).fadeOut("fast", function() {
                            jQuery(this).remove();
                        });
                        jQuery(this).removeClass(options['error_field_class']);
                    });
                }
            }
            jQuery(this).parents("form").submit(function() {
                if (bdvalidate_field('#' + SelfID))
                    return true;
                else
                    return false;
            });
            function bdvalidate_field(id) {
                var self = jQuery(id).attr("id");
                console.log(self);
                var expression = 'function Validate(){' + options['expression'].replace(/VAL/g, 'jQuery(\'#' + self + '\').val()') + '} Validate()';
                var validation_state = eval(expression);
                if (!validation_state) {
                    if (jQuery(id).next('.' + options['error_class']).length == 0) {
                        jQuery(id).after('<span class="' + options['error_class'] + '">' + options['message'] + '</span>');
                        jQuery(id).addClass(options['error_field_class']);
                    }
                    if (ValidationErrors[FormID].join("|").search(id) == -1)
                        ValidationErrors[FormID].push(id);
                    return false;
                } else {
                    for (var i = 0; i < ValidationErrors[FormID].length; i++) {
                        if (ValidationErrors[FormID][i] == id)
                            ValidationErrors[FormID].splice(i, 1);
                    }
                    return true;
                }
            }
        };
        jQuery.fn.validated = function(callback) {
            jQuery(this).each(function() {
                if (this.tagName == "FORM") {
                    jQuery(this).submit(function() {
                        if (ValidationErrors[jQuery(this).attr("id")].length == 0)
                            callback();
                        return false;
                    });
                }
            });
        };
    })(jQuery);

    function isChecked(id) {
        var ReturnVal = false;
        $("#" + id).find('input[type="radio"]').each(function() {
            if ($(this).is(":checked"))
                ReturnVal = true;
        });
        $("#" + id).find('input[type="checkbox"]').each(function() {
            if ($(this).is(":checked"))
                ReturnVal = true;
        });
        return ReturnVal;
    }

    jQuery(function() {
        jQuery("#business_name").validate({
            expression: "if (VAL) return true; else return false;",
            message: "Business Name is required"
        });
        jQuery("#legacy-form #pass1, .login-registration #pass1").validate({
            expression: "if (VAL) return true; else return false;",
            message: "Password is required"
        });
        jQuery("#legacy-form #pass2, .login-registration #pass2").validate({
            expression: "if (VAL) return true; else return false;",
            message: "Confirm password is required"
        });
        jQuery("#bd_zipcode").validate({
            expression: "if (VAL.match(/^([0-9]{5})(?:[-\s]*([0-9]{4}))?$/)) return true; else return false;",
            message: "Should be a valid US zipcode"
        });
        jQuery("#bd_address").validate({
            expression: "if (VAL) return true; else return false;",
            message: "Address is required"
        });
        jQuery("#bd_city").validate({
            expression: "if (VAL) return true; else return false;",
            message: "City is required"
        });
        jQuery("#bd_phone").validate({
            expression: "if (VAL=='' || VAL.match(/^\\(?(\\d{3})\\)?[- ]?(\\d{3})[- ]?(\\d{4})$/)) return true; else return false;",
            message: "Should be valid phone number"
        });
        jQuery("#bd_website").validate({
            expression: "if (VAL=='' || VAL.match(/^([a-z0-9_-]+\.)*[a-z0-9_-]+(\.[a-z]{2,6}){1,2}$/)) return true; else return false;",
            message: "Should be valid website URL"
        });
        /*
         jQuery("#bd_website").validate({
         expression: "if (VAL=='' || VAL.match(/^http\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(/\S*)?$/)) return true; else return false;",
         message: "Should be valid website URL"
         });
         */
        //Login Validation
        jQuery("#login-business #user_login, .login-registration #user_login").validate({
            expression: "if (VAL) return true; else return false;",
            message: "User name is required"
        });
        jQuery("#bd_resend_usermail").validate({
            expression: "if (VAL) return true; else return false;",
            message: "User name is required"
        });
        jQuery("#login-business #user_pass").validate({
            expression: "if (VAL) return true; else return false;",
            message: "Password is required"
        });

        jQuery("#businessHours, #businessHoursregi").validate({
            expression: "if (isChecked(SelfID)) return true; else return false;",
            message: "Please check atleast one day"
        });
    });
});

/* Export Callback url */
jQuery(document).ready(function($) {
    var hrefparams = "catname=''&cityname=''&statusid=''&bsearch=''";
    jQuery('#exportdiv a').attr('href', BD_Ajax.pluginurl + "excel_report.php");

});              