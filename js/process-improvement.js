
  jQuery(document).ready(function () {

    // process improvment
    var sys1Arr = [];
    var sys2Arr = [];
    var sys3Arr = [];
    for (i = 0; i < 20; i++) {
        sys1Arr.push('WO100-' + i);
        sys2Arr.push('WO200-' + i);
        sys3Arr.push('WO300-' + i);
        if (i < 4) {
            jQuery('<div class="w3-panel w3-leftbar w3-border-grey w3-pale-grey" id="WO100-' + i + '">WO100-' + i + '</div>').appendTo("#workOrderOne");
        } else  if( i>=4 && i < 8){
            jQuery('<div class="w3-panel w3-leftbar w3-border-blue w3-pale-blue" id="WO100-' + i + '">WO100-' + i + '</div>').appendTo("#workOrderOne");
        }else{
            jQuery('<div class="w3-panel w3-leftbar w3-border-red w3-pale-red" id="WO100-' + i + '">WO100-' + i + '</div>').appendTo("#workOrderOne");
        }

        if (i < 6) {
            jQuery('<div class="w3-panel w3-leftbar w3-border-grey w3-pale-grey" id="WO200-' + i + '">WO200-' + i + '</div>').appendTo("#workOrderTwo");
        } else  if( i>=6 && i < 12){
            jQuery('<div class="w3-panel w3-leftbar w3-border-blue w3-pale-blue" id="WO200-' + i + '">WO200-' + i + '</div>').appendTo("#workOrderTwo");
        }else{
            jQuery('<div class="w3-panel w3-leftbar w3-border-red w3-pale-red" id="WO200-' + i + '">WO200-' + i + '</div>').appendTo("#workOrderTwo");

        }

        if (i < 4) {
            jQuery('<div class="w3-panel w3-leftbar w3-border-grey w3-pale-grey" id="WO300-' + i + '">WO300-' + i + '</div>').appendTo("#workOrderThree");
        } else  if( i>=4 && i < 6){
            jQuery('<div class="w3-panel w3-leftbar w3-border-blue w3-pale-blue" id="WO300-' + i + '">WO300-' + i + '</div>').appendTo("#workOrderThree");
        }else{
            jQuery('<div class="w3-panel w3-leftbar w3-border-red w3-pale-red" id="WO300-' + i + '">WO300-' + i + '</div>').appendTo("#workOrderThree");

        }

    }

    jQuery('#postpone-form').submit(function (e) {
        e.preventDefault();
        var postponeWO = jQuery('#postpone-wo-input').val().trim();
        jQuery.each(sys1Arr, function (index, value) {
            // Postpone Work order
            if (value == postponeWO) {
                if (jQuery('#' + value).hasClass('w3-border-blue')) {
                    jQuery('#' + value).slideUp('500').remove('501');
                    jQuery('<div class="w3-panel w3-leftbar w3-border-red w3-pale-red" id="WO100-' + index + '">WO100-' + index + '</div>').appendTo("#workOrderOne").slideDown('500');
                }
            }
        });
        jQuery.each(sys2Arr, function (index, value) {
            // Postpone Work order
            if (value == postponeWO) {
                if (jQuery('#' + value).hasClass('w3-border-blue')) {
                    jQuery('#' + value).slideUp('500').remove('501');
                    jQuery('<div class="w3-panel w3-leftbar w3-border-red w3-pale-red" id="WO200-' + index + '">WO200-' + index + '</div>').appendTo("#workOrderTwo").slideDown('500');
                }
            }
        });
        jQuery.each(sys3Arr, function (index, value) {
            // Postpone Work order
            if (value == postponeWO) {
                if (jQuery('#' + value).hasClass('w3-border-blue')) {
                    jQuery('#' + value).slideUp('500').remove('501');
                    jQuery('<div class="w3-panel w3-leftbar w3-border-red w3-pale-red" id="WO300-' + index + '">WO300-' + index + '</div>').appendTo("#workOrderThree").slideDown('500');
                }
            }
        });
    });

    jQuery('#prioritize-form').submit(function (e) {
        e.preventDefault();
        var postponeWO = jQuery('#prio-wo-input').val().trim();
        jQuery.each(sys1Arr, function (index, value) {
            // Postpone Work order
            if (value == postponeWO) {
                if (jQuery('#' + value).hasClass('w3-border-red')) {
                    jQuery('#' + value).slideUp('500').remove('501');
                    jQuery.each(sys1Arr, function (i, val) {
                        if (jQuery('#' + val).hasClass('w3-border-red')) {
                            
                            jQuery('#' + val).before('<div class="w3-panel w3-leftbar w3-border-blue w3-pale-blue" id="WO100-' + index + '">WO100-' + index + '</div>');
                            return false;
 
                        }
                    });
                }
            }
        });
        jQuery.each(sys2Arr, function (index, value) {
            // Postpone Work order
            if (value == postponeWO) {
                if (jQuery('#' + value).hasClass('w3-border-red')) {
                    jQuery('#' + value).slideUp('500').remove('501');
                    jQuery.each(sys2Arr, function (i, val) {
                        if (jQuery('#' + val).hasClass('w3-border-red')) {
                            
                            jQuery('#' + val).before('<div class="w3-panel w3-leftbar w3-border-blue w3-pale-blue" id="WO200-' + index + '">WO200-' + index + '</div>').slideDown('500');
                            return false;
 
                        }
                    });
                }
            }
        });        jQuery.each(sys3Arr, function (index, value) {
            // Postpone Work order
            if (value == postponeWO) {
                if (jQuery('#' + value).hasClass('w3-border-red')) {
                    jQuery('#' + value).slideUp('500').remove('501');
                    jQuery.each(sys3Arr, function (i, val) {
                        if (jQuery('#' + val).hasClass('w3-border-red')) {
                            
                            jQuery('#' + val).before('<div class="w3-panel w3-leftbar w3-border-blue w3-pale-blue" id="WO300-' + index + '">WO300-' + index + '</div>');
                            return false;
 
                        }
                    });
                }
            }
        });
    });

    // End Process Improvement
    

  });