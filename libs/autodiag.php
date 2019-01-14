<script>
$(document).ready(function(){
<?php
for($d=1;$d<10;$d++)
{
    echo "$('#diag".$d."').autocomplete({
        position:{collision:\"fit flip\"},
        source:'../../libs/diaglist.php',
        minLength:1,
        autoFocus:true
    }); ";
}
?>
});
</script>
