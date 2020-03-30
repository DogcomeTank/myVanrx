jQuery(document).ready(function () {

    jQuery("#wo-history").empty();

    let searchParams = new URLSearchParams(window.location.search);
    var userLoginGroup = new Array();

    if(searchParams.has('wonum')){
        var wonum = searchParams.get('wonum');
        var ajaxURL = "/api/?action=getWorkOrderHistory&wonum="+wonum;
        // var userGroup = [""];
        jQuery.ajax({
            url: ajaxURL,
        }).done(function (da) {
            var daJSON = JSON.parse(da);
            
            var i;
            var daLen = daJSON.length;
            if(daLen >0){
                // if user found in clock in history
                for( i = 0; i < daLen; i++){
                    if(!userLoginGroup.includes(daJSON[i].display_name)){
                        userLoginGroup.push(daJSON[i].display_name);
                    }
                }
                var x;
                for(x = 0; x < userLoginGroup.length; x++){
                    var r= jQuery('<input class="w3-margin w3-button" type="button" value="'+userLoginGroup[x] +'"/>');
                     jQuery("#wo-history").append(r);
                }


            }else{
                var r= jQuery('<input class="w3-margin w3-button" type="button" value="No Clock In History"/>');
                jQuery("#wo-history").append(r);
            }

        

        });
    }
    

    // document ready end
});

