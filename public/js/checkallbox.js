$(document).ready(function() {
    //check all checkboxes
    $("#check_all_boxes").click(function() {
        $(".mycheckbox").each(function(i){
            $(this).attr("checked","checked");
        });
    });
 
    //uncheck all checkboxes
    $("#uncheck_all_boxes").click(function() {
        $(".mycheckbox").each(function(i){
            $(this).removeAttr("checked");
        });
    });
 
    //reset the search form
    $("#reset_button").click(function() {
        $("#search_form input").each(function(i) {
            //alert($(this).html());
 
        });
    });
 
});