(function ($) {

    var bd = {

        options:{
            ajaxUrl:'',
            ajaxloaderhtml:$('#bd_disruptionsDiv .loader').clone(),
            registerform:$('#registration_form'),
            selectAll:$('#select-all'),
            normalText:$('#normalDiv'),
            normalTime:$('#normalTime'),
            advancedText:$('#advancedDiv'),
            advancedTime:$('#advancedTime'),
            difficulty:$('#registration_form .normalOadvance'),
            difficultyMode:$('#registration_form #checkmode'),
            normalDays:'',
            // Business Listings Selectors
            statuscontainer:$('.status-container'),
            send:$('#send'),
            status:$('#status'),
            spandefaultresult:$('span.default-result'),
            defaultresult:$('.default-result'),
            spanremove:$('span.rmv'),
            termlist:$('#termList'),
            plink:$('.plink'),
            inputbizsearch:$('input.business-search-test'),
            selectchgcat:$('select.changeCats'),
            selectchgcity:$('select.changeCity'),
            selectchgstatus:$('select.changeStatus'),

            end:'',
            interval:''
        },

        init:function () {
            var self = this,
                o = self.options;

            self.businessSettingEvents(self);
            self.validateBusinessForm(self);
            self.ajaxDeletePost(self);
            self.ajaxProductLists(self);
            self.ajaxProductSearch(self);
            self.ajaxExportExcel(self);
            self.submitClosingEvents(self);
            self.validateSubmitClosings(self);

        },
        businessSettingEvents:function (self) {
            var o = self.options;
            o.normalDays = o.registerform.find('.check').not('#select-all').filter(':checked');
            o.registerform
                .on('click', '#businessHoursregi .check, #advancedDiv, #normalDiv', function () {
                    var $this = $(this),
                        $parent = $this.closest('#registration_form'),
                        $allDays = $parent.find('#select-all'),
                        $days = $parent.find('.check').not('#select-all'),
                        $daysArray = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
                    switch (true) {
                        case $this.val() == 'all':
                            if ($allDays.is(':checked')) {
                                $days.attr('disabled', true);
                                o.normalDays = $days.filter(':checked');
                                $days.attr('checked', true);
                                self.allDaysMode(self);
                                o.registerform.find('#businessHoursregi').trigger('focus');
                            } else {
                                $days.removeAttr('disabled')
                                    .not(o.normalDays).attr('checked', false);
                                if (o.difficultyMode.val() == '1') {
                                    self.advancedMode(self);
                                } else {
                                    self.normalMode(self);
                                }
                            }
                            break;
                        case $daysArray.indexOf($this.val()) != -1:
                            var $dayTime = $parent.find('.' + $this.val() + 'Select');
                            if ($this.is(':checked')) {
                                $dayTime.show();
                            } else {
                                $dayTime.hide();
                                o.registerform.find('#businessHoursregi').trigger('focus');
                            }
                            break;
                        case $this.attr('id') == 'normalDiv':
                            self.normalMode(self);
                            break;
                        case $this.attr('id') == 'advancedDiv':
                            self.advancedMode(self);
                            break;
                    }
                });
        },
        advancedMode:function (self) {
            var o = self.options;
            o.difficulty.add(o.difficultyMode).val('1');
            o.advancedText.add(o.normalTime).hide();
            o.normalText.add(o.advancedTime).show();
        },
        normalMode:function (self) {
            var o = self.options;
            o.difficulty.add(o.difficultyMode).val('0');
            o.advancedText.add(o.normalTime).show();
            o.normalText.add(o.advancedTime).hide();
        },
        allDaysMode:function (self) {
            var o = self.options;
            o.difficulty.val('0');
            o.normalTime.show();
            o.normalText.add(o.advancedText).add(o.advancedTime).hide();
        },
        validateBusinessForm:function (self) {
            var o = self.options,
                email = o.registerform.find('#user_login').val(),
                status = o.registerform.find('#validateUsername');

            $('#registration_form')
                .bind('validationSafe', function (e, data) {
                    $(data.elem).siblings('#validateUsername').fadeOut('fast');
                })
                .add('#bd_resendmail_form, #login-business #login_form')
                .submitClickValidated();
            $('#bd_resend_usermail')
                .validate({
                    expression:"if (VAL) return true; else return false;",
                    message:"User name is required",
                    callback_success:function () {
                        status.html('checking availability...').fadeIn('fast');
                        self.ajaxEmailVerify(self);
                    }
                })
            $('#registration_form #user_login, #bd_resend_usermail')
                .validate({
                    expression:"if (VAL.match(/^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+@[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$/)) return true; else return false;",
                    message:"Not a valid email address",
                    callback_success:function () {
                        status.html('checking availability...').fadeIn('fast');
                        self.ajaxEmailVerify(self);
                    }
                })
            $('#login-business #user_login')
                .validate({
                    expression:"if (VAL) return true; else return false;",
                    message:"Email / Username is required"
                })
            $('#bname, #business_name')
                .validate({
                    expression:"if (VAL) return true; else return false;",
                    message:"Business Name is required"
                })
            $('#pass1, #login-business #user_pass')
                .validate({
                    expression:"if (VAL) return true; else return false;",
                    message:"Password is required"
                })
            $('#pass2')
                .validate({
                    expression:"if (VAL) return true; else return false;",
                    message:"Confirm password is required"
                })
            $('#bd_zipcode')
                .validate({
                    expression:"if (VAL.match(/^\\d{5}$/)) return true; else return false;",
                    message:"Should be a valid US zipcode",
                    callback_success:function () {
                        self.ajaxZipCode(self, $('#bd_zipcode').val());
                    }
                })
            $('#bd_address')
                .validate({
                    expression:"if (VAL) return true; else return false;",
                    message:"Address is required"
                })
            $('#bd_city')
                .validate({
                    expression:"if (VAL) return true; else return false;",
                    message:"City Name is required"
                })
            $('#businessHoursregi')
                .validate({
                    expression:"if ( (isNaN(VAL)?0:0) + ($('.daycheck input:checked, #select-all:checked').size())) return true; else return false;",
                    message:"Please check atleast one day",
                    error_field_class:''
                })
        },
        ajaxEmailVerify:function (self) {
            var o = self.options,
                email = o.registerform.find('#user_login').val(),
                status = o.registerform.find('#validateUsername');
            $.ajax({
                type:"GET", url:BD_Ajax.ajaxUrl, data:{ action:'business_email_verify', email:email },
                success:function (json) {
                    if (json.email) {
                        status.html(json.msg);
                        o.interval = setInterval(function () {
                            status.fadeOut()
                        }, 1000)
                    } else {
                        status.html(json.msg)
                    }
                }
            });
        },
        ajaxZipCode:function (self, zip) {
            var o = self.options;
            $.ajax({
                type:'GET',
                url:BD_Ajax.ajaxUrl,
                data:{
                    action:'business_zip_data',
                    zip:zip
                },
                success:function (data) {
                    if (!data.success) return;
                    o.registerform.find('#bd_city').trigger('focus').val(data.zip_data.city);
                    o.registerform.find('#station-info-state').trigger('focus').val(data.zip_data.state);
                }
            });
        },
        ajaxDeletePost:function (self) {
            $('#bd_manage_business_content, #bd_business_listing')
                .on('click', '.delete_post, .deletebusiness', function (e) {
                    var $target = $(e.target),
                        pid = $target.attr('id'),
                        $manage = $target.closest('#bd_manage_business'),
                        $listing = $target.closest('#bd_business_listing');
                    if (confirm('Are you sure you want to delete this Business?')) {
                        $.ajax({
                            type:"post", url:BD_Ajax.ajaxUrl, data:{ action:'business_delete_post', pid:pid },
                            success:function (retval) { //so, if data is retrieved, store it in html
                                if ($manage.size() > 0) {
                                    window.location.replace(BD_Ajax.manageurl);
                                } else if ($target.closest('#bd_business_listing').size() > 0) {
                                    $('.nobusiness').show();
                                }
                            }
                        });

                    }
                });
        },
        ajaxProductLists:function (self) {
            var o = self.options,
                $container = $('#bd_disruptionsDiv'),
                $productListWrap = $('#bd_disruptionsDiv #product_list_wrap'),
                $inputSearch = $('#bd_disruptionsDiv .business-search-text');
            $('#bd_disruptionsDiv .changeCat').on('change', 'select',function (e) {
                var business_type = $container.find('.changeCats').val(),
                    zip = $container.find('.changeCity').val(),
                    status = $container.find('.changeStatus').val(),
                    page = $container.find('#bd_product_list_page').val();
                $productListWrap.html(o.ajaxloaderhtml);
                $.ajax({
                    type:'GET',
                    url:BD_Ajax.ajaxUrl,
                    data:{
                        action:'business_product_lists',
                        business_type:business_type,
                        zip:zip,
                        status:status,
                        page:page
                    },
                    success:function (data) {
                        if (data.noresults) {
                            $productListWrap.html($('<span>').addClass('loader rmv').text('SORRY! NO RESULTS FOUND.'));
                        } else {
                            $productListWrap.html($('<ul>').html($(data.results)));
                        }
                    }
                });
            }).find('select').trigger('change');
        },
        ajaxProductSearch:function (self) {
            var o = self.options,
                $container = $('#bd_disruptionsDiv'),
                $productListWrap = $('#bd_disruptionsDiv #product_list_wrap'),
                $inputSearch = $('#bd_disruptionsDiv #business-search-text'),
                $searchButton = $('#bd_disruptionsDiv #searchBusiness');
            $inputSearch
                .on('focus', function () {
                    $(this).val('');
                })
                .on('defaultSearch', function () {
                    $(this).val('Search by name or ZIP');
                })
                .on('keypress', function (e) {
                    var code = e.keyCode || e.which;
                    if (code != 13) return;
                    $searchButton.trigger('click');
                });
            $('body').on('click', function (e) {
                var target = e.target || e.srcElement;
                if (target.id == 'business-search-text' || target.id == 'searchBusiness') {
                    return false;
                } else {
                    $inputSearch.trigger('defaultSearch');
                }
            });
            $searchButton.on('click', function (e) {
                var searchText = $.trim($inputSearch.val()),
                    page = $container.find('#bd_product_list_page').val(),
                    zip = '',
                    name = '';
                if (searchText.match(/^\d{5}$/)) {
                    zip = searchText;
                } else {
                    name = searchText;
                }
                $productListWrap.html(o.ajaxloaderhtml);
                $.ajax({
                    type:'GET',
                    url:BD_Ajax.ajaxUrl,
                    data:{
                        action:'business_product_lists',
                        zip:zip,
                        search:name
                    },
                    success:function (data) {
                        console.log('here');
                        console.log($productListWrap);
                        if (data.noresults) {
                            $productListWrap.html($('<span>').addClass('loader rmv').text('SORRY! NO RESULTS FOUND.'));
                        } else {
                            $productListWrap.html($('<ul>').html($(data.results)));
                        }
                    }
                });
            })
        },
        ajaxExportExcel:function (self) {
            var o = self.options,
                $exportButton = $('#exportdiv a');
                $exportButton.trigger('click');
//                alert("dd");
            $exportButton.on('click', function (e) {
               var  str =   jQuery('select.changeCats option:selected').val();
					var  strcity =   jQuery('select.changeCity option:selected').val();
					var  strtoday =   jQuery('input.changeToday:checked').val();
					var  strtomo=   jQuery('input.changeTomo:checked').val();
					var  statusid =   jQuery('select.changeStatus option:selected').val();
					var  strs =   jQuery('input.business-search-text').val();
					
					var cday = '';

					var  postids=   jQuery('#postids').val();
					if( strs && strs !='Search by name or ZIP' && ( !str || !strcity || !statusid ) ) {
					var hrefparams = 'bsearch='+strs;
					}else if( str || strcity || statusid ){
					strs = 'Search by name or ZIP';           
					var hrefparams = 'catname='+str+'&cityname='+strcity+'&statusid='+statusid;
					}
					alert(hrefparams);
					jQuery('#exportdiv a').attr('href', BD_Ajax.pluginurl+"excel_report.php?"+hrefparams);
            })
        },
        submitClosingEvents:function (self) {
            var o = self.options,
                details = $('.closingDetails');

            $('.closingDetails').on('keyup', function () {
                var $this = $(this),
                    $count = $this.val().length,
                    $counter = $this.siblings('.charCount')
                        .show()
                        .text($count + ' of 200 characters used');
                if ($count == 0) $counter.hide();
            });
            $('#todayAllFieldsHolder, #tomorrowAllFieldsHolder').on('change', 'input, select', function (e) {
                var $this = $(this),
                    $parent = $this.closest('#todayAllFieldsHolder, #tomorrowAllFieldsHolder'),
                    $status = $parent.find('#todayChangeStatus, #tomorrowChangeStatus'),
                    $preview = $parent.siblings('#cpreview1, #cpreview'),
                    $desc = $preview.find('.business-status'),
                    $dayMessage = $parent.siblings('#day1message, #day2message'),
                    $closedOptions = $parent.find('#today_closed_details, #tomorrow_closed_details').hide(),
                    $delayedOptions = $parent.find('#today_delayed_details, #tomorrow_delayed_details').hide(),
                    $dismissalOptions = $parent.find('#today_dismissal_details, #tomorrow_dismissal_details').hide(),
                    $timezone = $('#timezone').val(),
                    $expiry = $parent.find('#today_delayexpiry, #tomo_delayexpiry'),
                    $message = '';
                switch ($status.val()) {
                    case 'normal_hours':
                        $preview.show();
                        $message = ('Normal Hours');
                        break;
                    case 'closed':
                        var $closedAll = $closedOptions.find('#calldayclosed, #talldayclosed'),
                            $closedStart = $closedOptions.find('#ttodayselctholder .starttime, #tomorrow_ttodayselctholder .starttime'),
                            $closedEnd = $closedOptions.find('#ttodayselctholder .endtime, #tomorrow_ttodayselctholder .endtime'),
                            $selectors = $closedOptions.find('#ttodayselctholder, #tomorrow_ttodayselctholder');
                        $closedOptions.show();
                        if ($closedAll.is(':checked')) {
                            var $day = $.trim($parent.find('.cdate label strong').text().toLowerCase()),
                                $date = $parent.find('.cdate input').val().substring(0, 5);
                            $message = 'Closed ' + $day + ' (' + $date + ')';
                            $preview.show();
                            $selectors.hide();
                        } else {
                            if ($closedStart.val() != 'none' && $closedEnd.val() != 'none' && parseInt($closedStart.val()) < parseInt($closedEnd.val())) {
                                var $day = $.trim($parent.find('.cdate label strong').text().toLowerCase()),
                                    $date = $parent.find('.cdate input').val().substring(0, 5),
                                    $startText = $closedStart.find('option[value="' + $closedStart.val() + '"]').text(),
                                    $endText = $closedEnd.find('option[value="' + $closedEnd.val() + '"]').text();
                                $message = 'Closed ' + $day + ' (' + $date + ') for ' + ($closedEnd.val() - $closedStart.val()) + ' hr(s) from ' + $startText + ' to ' + $endText + ' (' + $timezone + ').';
                                $preview.show();
                            } else {
                                $preview.hide();
                            }
                            $selectors.show();
                        }
                        break;
                    case 'delayed':
                        var $hours = $delayedOptions.find('#dtimehr, #tomorrow_dtimehr'),
                            $mins = $delayedOptions.find('#dtimemin, #tomorrow_dtimemin');
                        $delayedOptions.show()
                        if ($hours.val() == '0' && $mins.val() == '00') {
                            $preview.hide();
                        } else {
                            var $day = $.trim($parent.find('.cdate label strong').text().toLowerCase()),
                                $date = $parent.find('.cdate input').val(),
                                $hour = $hours.val(),
                                $hourText = ($hour != '0') ? $hour + ' hr(s)' : '',
                                $min = ($mins.val() != '00') ? $mins.val() : '',
                                $minText = ($mins.val() != '00') ? $min + ' mins' : '',
                                $start = $parent.siblings('.daydefaultstart').val(),
                                $delayedHour = parseInt($start) + parseInt($hour);
                            $delayedHour = ($delayedHour > 23) ? $delayedHour - 24 : $delayedHour;
                            var $realDate = self.getCorrectedDelayedHour($date, parseInt($start), parseInt($hour)),
                                $delayedTime = $closedOptions.find('.starttime option[value="' + $delayedHour + '"]').text();
                            $delayedTime = ($mins.val() != '00') ? $delayedTime.replace(':00', ':' + $min) : $delayedTime
                            $expiry.val(self.getDelayedDate($date, parseInt($start), parseInt($hour), $mins.val()));
                            //$expiry.val($.trim($delayedTime.replace(/[PAM]/g,'')));
                            $message = 'Delayed ' + $day + ' (' + $realDate + ') by ' + $hourText + ' ' + $minText + ', opens at ' + $delayedTime + ' (' + $timezone + ').';
                            $preview.show();
                        }
                        break;
                    case 'early_dismissal':
                        var $end = $dismissalOptions.find('.endtime'),
                            $endVal = $end.val(),
                            $endText = $end.find(':selected').text();
                        $dismissalOptions.show();
                        if ($endVal != 'none') {
                            var $day = $.trim($parent.find('.cdate label strong').text().toLowerCase()),
                                $date = $parent.find('.cdate input').val().substring(0, 5);
                            $message = 'Closed ' + $day + ' by (' + $date + ') ' + $endText + ' (' + $timezone + ').';
                            $preview.show();
                        } else {
                            $preview.hide();
                        }
                        break;
                }
                $desc.text($message);
                $dayMessage.val($message);

            });

            $('#todayChangeStatus, #tomorrowChangeStatus').trigger('change');
        },
        validateSubmitClosings:function (self) {
            var o = self.options,
                $form = $('#closing_form');
            $form.find('.submit-button').on('click', function (e) {
                var $parent = $form.find('#todayAllFieldsHolder'),
                    $status = $parent.find('#todayChangeStatus'),
                    $closedOptions = $parent.find('#today_closed_details'),
                    $delayedOptions = $parent.find('#today_delayed_details'),
                    $dismissalOptions = $parent.find('#today_dismissal_details'),
                    $error = [];
                switch ($status.val()) {
                    case 'closed':
                        var $checkall = $closedOptions.find('#calldayclosed, #talldayclosed'),
                            $start = $closedOptions.find('#cfromhr, #ctfromhr'),
                            $end = $closedOptions.find('#ctohr, #cttohr'),
                            $day = $.trim($parent.find('.cdate label strong').text().toLowerCase());
                        if (!$checkall.is(':checked')) {
                            if ($start.val() == 'none' || $end.val() == 'none') {
                                $error.push('Please select a start and end time for ' + $day + '.');
                            } else if (parseInt($start.val()) > parseInt($end.val())) {
                                $error.push('Opening time should be greater than closing time for ' + $day + '.');
                            }
                        }
                        break;
                    case 'delayed':
                        var $hours = $delayedOptions.find('#dtimehr, #tomorrow_dtimehr'),
                            $mins = $delayedOptions.find('#dtimemin, #tomorrow_dtimemin'),
                            $day = $.trim($parent.find('.cdate label strong').text().toLowerCase());
                        if ($hours.val() == '0' && $mins.val() == '00') {
                            $error.push('Please check the delay time for ' + $day + '.');
                        }
                        break
                    case 'early_dismissal':
                        var $end = $dismissalOptions.find('.endtime'),
                            $day = $.trim($parent.find('.cdate label strong').text().toLowerCase());
                        if ($end.val() == 'none') {
                            $error.push('Please check the early dismissal time for ' + $day + '.');
                        }
                        break;
                }
                var $parent = $form.find('#tomorrowAllFieldsHolder'),
                    $status = $parent.find('#tomorrowChangeStatus'),
                    $closedOptions = $parent.find('#tomorrow_closed_details'),
                    $delayedOptions = $parent.find('#tomorrow_delayed_details'),
                    $dismissalOptions = $parent.find('#tomorrow_dismissal_details');
                switch ($status.val()) {
                    case 'closed':
                        var $checkall = $closedOptions.find('#calldayclosed, #talldayclosed'),
                            $start = $closedOptions.find('#cfromhr, #ctfromhr'),
                            $end = $closedOptions.find('#ctohr, #cttohr'),
                            $day = $.trim($parent.find('.cdate label strong').text().toLowerCase());
                        if (!$checkall.is(':checked')) {
                            if ($start.val() == 'none' || $end.val() == 'none') {
                                $error.push('Please select a start and end time for ' + $day + '.');
                            } else if (parseInt($start.val()) > parseInt($end.val())) {
                                $error.push('Opening time should be greater than closing time for ' + $day + '.');
                            }
                        }
                        break;
                    case 'delayed':
                        var $hours = $delayedOptions.find('#dtimehr, #tomorrow_dtimehr'),
                            $mins = $delayedOptions.find('#dtimemin, #tomorrow_dtimemin'),
                            $day = $.trim($parent.find('.cdate label strong').text().toLowerCase());
                        if ($hours.val() == '0' && $mins.val() == '00') {
                            $error.push('Please check the delay time for ' + $day + '.');
                        }
                        break
                    case 'early_dismissal':
                        var $end = $dismissalOptions.find('.endtime'),
                            $day = $.trim($parent.find('.cdate label strong').text().toLowerCase());
                        if ($end.val() == 'none') {
                            $error.push('Please check the early dismissal time for ' + $day + '.');
                        }
                        break;
                }
                if ($error.length) {
                    var $message = '';
                    $.each($error, function ($i, $v) {
                        $message += ($v + '\n');
                    });
                    alert($message);
                } else {
                    $form.find('input, select').removeAttr('disabled');
                    $form.submit(function () {
                        $(this).submit();
                    });
                }
            });
        },
        getCorrectedDelayedHour:function ($date, $start, $hour) {
            var $date = new Date($date + ' ' + $start + ':00:00'),
                $current_hours = $date.getHours();
            $date.setHours($current_hours + $hour);
            return $date.format('m/d');
        },
        getDelayedDate:function ($date, $start, $hour, $min) {
            var $date = new Date($date + ' ' + $start + ':00:00'),
                $current_hours = $date.getHours();
            $date.setHours($current_hours + $hour);
            return $date.format('m/d/Y H') + ':' + $min + ':00';
        }
    }


    // Document ready, fire it up!
    $(function () {
        // Set validate to silent mode

        // Initialize business disruption scripts
        bd.init();


    });
})(jQuery);