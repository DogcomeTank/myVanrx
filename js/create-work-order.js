jQuery(document).ready(function () {

    // jQuery("#work-order-converted").empty();
    // jQuery("#work-order-upload").css("display","none");

    // jQuery("#work-order-list-input").change(function(){

    //     jQuery("#work-order-converted").empty();
    //     jQuery("#work-order-upload").css("display","block");

    //     var textIn = jQuery('#work-order-list-input').val();
    //     try {
    //         var workOrderObj = JSON.parse(textIn);
    //     } catch(e) {
    //         jQuery("#work-order-upload").css("display","none");
    //     }
    //     var totalWorkImport = workOrderObj.length;
        

    //     var table = jQuery('<table>').addClass('w3-margin');

    //     for(x = 0; x < totalWorkImport; x++){
    //         jQuery("<tr><td>"+workOrderObj[x].ItemNumber+"</td><td>"+workOrderObj[x].Description+"</td><td>"+workOrderObj[x]["Assembly Number"]+"</td></tr>").appendTo(table);
    //     }
    //     jQuery("<h3 class='w3-margin'>Totoal Items: "+ totalWorkImport +"</h3>").appendTo("#work-order-converted");
    //     table.appendTo("#work-order-converted");
      
    //   });
    //   end #work-order-list-input change

    

    // jQuery("#work-order-upload").click(function(){


    //     var workOrderList = jQuery('#work-order-list-input').val();
    //     var ajaxURL = "/api/?action=uploadWorkOrder";
    //     jQuery.ajax({
    //         // type: 'POST',
    //         url : ajaxURL,
    //         // dataType: "json",
    //         // data: {name:"name"},
    //     }).done(function (da) {
    //         console.log(da);
    //     }).fail(function(e) {
    //         console.log("error: "+e);
    //     });


    // });

    // var ws = "\x09\x0A\x0B\x0C\x0D\x20\xA0\u1680\u180E\u2000\u2001\u2002\u2003" + "\u2004\u2005\u2006\u2007\u2008\u2009\u200A\u202F\u205F\u3000\u2028" + "\u2029\uFEFF";
    // if (!String.prototype.trim || ws.trim()) {
    //     ws = "[" + ws + "]";
    //     var trimBeginRegexp = new RegExp("^" + ws + ws + "*"),
    //         trimEndRegexp = new RegExp(ws + ws + "*$");
    //     String.prototype.trim = function trim() {
    //         if (this === undefined || this === null) {
    //             throw new TypeError("can't convert " + this + " to object");
    //         }
    //         return String(this).replace(trimBeginRegexp, "").replace(trimEndRegexp, "");
    //     };
    // }

    
});    // document ready end

// function trimArrayAndRemoveSpace(item, index, arr){
//     arr[index] = item.trim();
//     if(arr[index] == "" || arr[index] == "↵"){
//         arr.splice(index,1);
//     }
// }