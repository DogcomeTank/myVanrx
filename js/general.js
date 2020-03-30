jQuery(document).ready(function () {
    jQuery( ".lock" ).click(function() {
        jQuery(this).toggleClass('unlocked');
      });
    checkIfUserClockIn();

    // process improvment
    var sys1Arr = [];
    var sys2Arr = [];
    var sys3Arr = [];
    for (i = 0; i < 10; i++) {
        sys1Arr.push('WO100-' + i);
        sys2Arr.push('WO200-' + i);
        sys3Arr.push('WO300-' + i);
        if (i < 4) {
            jQuery('<div class="w3-panel w3-leftbar w3-border-blue w3-pale-blue" id="WO100-' + i + '">WO100-' + i + '</div>').appendTo("#workOrderOne");
        } else {
            jQuery('<div class="w3-panel w3-leftbar w3-border-red w3-pale-red" id="WO100-' + i + '">WO100-' + i + '</div>').appendTo("#workOrderOne");
        }

        if (i < 6) {
            jQuery('<div class="w3-panel w3-leftbar w3-border-blue w3-pale-blue" id="WO200-' + i + '">WO200-' + i + '</div>').appendTo("#workOrderTwo");
        } else {
            jQuery('<div class="w3-panel w3-leftbar w3-border-red w3-pale-red" id="WO200-' + i + '">WO200-' + i + '</div>').appendTo("#workOrderTwo");
        }

        if (i < 5) {
            jQuery('<div class="w3-panel w3-leftbar w3-border-blue w3-pale-blue" id="WO300-' + i + '">WO300-' + i + '</div>').appendTo("#workOrderThree");
        } else {
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

function checkIfUserClockIn() {
    jQuery.ajax({
        url: "/api/?action=isUserClockIn",
    }).done(function (da) {
        var data = JSON.parse(da);
        if (data.isClockIn) {

            // If user clock in
            if(jQuery('#btn-clock-in').length != 0){
                jQuery('#btn-clock-in').text('Clock Out');
                jQuery('#btn-clock-in').removeClass('w3-border-red').addClass('w3-border-green');
            }
            if(jQuery('#clock-in-to-last-work').length != 0){
                jQuery('#clock-in-to-last-work').text('Clock Out');
                jQuery('#clock-in-to-last-work').addClass('w3-border-green').removeClass('w3-border-red');
            }
        } else {
            
            if(jQuery('#btn-clock-in').length != 0){
                jQuery('#btn-clock-in').text('Clock In');
                jQuery('#btn-clock-in').removeClass('w3-border-green').addClass('w3-border-red');
            }
            if(jQuery('#clock-in-to-last-work').length != 0){
                jQuery('#clock-in-to-last-work').text('Clock In To Last Work Order');
                jQuery('#clock-in-to-last-work').removeClass('w3-border-green').addClass('w3-border-red');
            }
        }

    });
} //end



function isClockin() {
    var data;
    jQuery.ajax({
        url: "/api/?action=isUserClockIn",
        async: false,
    }).done(function (da) {
        data = JSON.parse(da);
    });
    return data
} //end

function getWorkType() {
    // empty work type list
    jQuery('#typeOfWorkList').empty();

    jQuery.ajax({
        url: "/api/?action=getWorkTypeList",
    }).done(function (da) {
        var data = JSON.parse(da);

        for (i = 0; i < data.length; i++) {
            if (i == 0) {
                var inputOption = '<input class="w3-radio" type="radio" name="work-type" value="' + data[i]
                    .id + '" checked><label class=" w3-margin-right">' + data[i].work_type + '</label>';
            } else {
                var inputOption = '<input class="w3-radio" type="radio" name="work-type" value="' + data[i]
                    .id + '"><label class="w3-margin-right">' + data[i].work_type + '</label>';
            }

            jQuery('#typeOfWorkList').append(inputOption);

        }
    });
} // End getWorkType()


function getBinLocationList() {
    // empty work type list
    jQuery('#typeOfWorkList').empty();

    jQuery.ajax({
        url: "/api/?action=getStorageLocation",
    }).done(function (da) {
        var data = JSON.parse(da);
        for (i = 0; i < data.length; i++) {
            if (i == 0) {
                var inputOption = '<input class="w3-radio" type="radio" name="storage_location" value="' + data[i]
                    .id + '" checked><label class=" w3-margin-right">' + data[i].location + '</label>';
            } else {
                var inputOption = '<input class="w3-radio" type="radio" name="storage_location" value="' + data[i]
                    .id + '"><label class="w3-margin-right">' + data[i].location + '</label>';
            }
            jQuery('#storageLocationList').append(inputOption);
        }
    });
} //end getBinLocationList

function getCurrentBinLocation(workOrderNumber) {
    //data will display in div #currentBinLocation

    var url = "/api/?action=getCurrentBinLocation&wonum=" + workOrderNumber;
    jQuery.ajax({
        url: "/api/?action=getCurrentBinLocation&wonum=" + workOrderNumber,
    }).done(function (da) {
        var data = JSON.parse(da);
        if (data.total == 0) {
            jQuery('#currentBinLocation').text("No Location. Maybe still in the warehouse.");
        } else {
            jQuery('#currentBinLocation').text(data.location);
        }
    });
}

function changBinLocation(newLocation, workOrderNumber) {
    jQuery.ajax({
      url: "/api/?action=changBinLocation&newLocation=" + newLocation + "&wonum=" + workOrderNumber,
    }).done(function (da) {
      var data = JSON.parse(da);
      getCurrentBinLocation(workOrderNumber);
      
    });
  } //end changBinLocation();


function getBinLocationList() {
    // empty work type list
    jQuery('#typeOfWorkList').empty();

    jQuery.ajax({
      url: "/api/?action=getStorageLocation",
    }).done(function (da) {
      var data = JSON.parse(da);
      for (i = 0; i < data.length; i++) {

          var inputOption = '<input class="w3-radio" type="radio" name="storage_location" value="' + data[i]
            .id + '"><label class="w3-margin-right">' + data[i].location + '</label>';
        
            jQuery('#storageLocationList').append(inputOption);
      }
    });
  } //end getBinLocationList


  function getEdrawingByWorkNum(drawingNumber) {

    jQuery.ajax({
      url: "/api/?action=getEdrawingByWorkNum&wonum="+drawingNumber,
    }).done(function (da) {
      var data = JSON.parse(da);
      var url = "https://swpdm001.ad.vanrx.com/SOLIDWORKSPDM/File/Search/Vanrx_Vault/?keyword=" + data[0]
        .assembly_id;
        
      jQuery("#e-drawing-btn").attr("href", url);

    //   <a id="e-drawing-btn" href="./work-order"><button class="w3-margin-bottom w3-btn w3-white w3-border w3-border-green w3-round-xlarge">E-Drawing</button></a>

    });
  }

