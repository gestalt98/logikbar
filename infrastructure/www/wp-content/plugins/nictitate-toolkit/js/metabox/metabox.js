function on_change_icon(obj){    
    jQuery(obj).parent().parent().parent().find(".icon_class").val(jQuery(obj).attr("lang"));
    jQuery(".selected").removeClass("selected");
    jQuery(obj).parent().addClass("selected");   
   return false;
}