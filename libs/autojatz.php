<script type="text/javascript">
$(document).ready(function(){
 
    // this is how to add autocomplete functionality in a textbox
    // source:'results.php' - where it will pass the search term and generates the JSON data
    // minLength:1 - how many characters user enters in order to start search
    $("#jname").autocomplete({
        source:'../../libs/jlist.php',
        minLength:1,
        autoFocus:true
    });
    $("#aname").autocomplete({
        source:'../../libs/alist.php',
        minLength:1,
        autoFocus:true
    });
    $('#tname').autocomplete({
        source:'../../libs/tlist.php',
        minLength:1,
        autoFocus:true
        /*
        select: function(event, ui){
 
            // just in case you want to see the ID
            var accountVal = ui.item.value;
            console.log(accountVal);
 
            // now set the label in the textbox
            var accountText = ui.item.label;
            $('#tname').val(accountText);
 
            return false;
        },
        focus: function( event, ui ) {
            // this is to prevent showing an ID in the textbox instead of name
            // when the user tries to select using the up/down arrow of his keyboard
            $( "#tname" ).val( ui.item.label );
            return false;
        },
 */
    });
    $("#zip").autocomplete({
        source:'../../libs/ziplist.php',
        minLength:1,
        autoFocus:true
    });
});
</script>
