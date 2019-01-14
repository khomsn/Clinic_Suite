<script>
$(document).ready(function(){
$("#drug").autocomplete({
    position:{collision:"fit flip"},
    source:'../../libs/druglistin.php',
    minLength:1,
    autoFocus:true
});
});
</script>
