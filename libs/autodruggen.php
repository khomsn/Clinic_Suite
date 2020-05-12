<script>
$(document).ready(function(){
<?php
for($d=1;$d<=14;$d++)
{
echo "$('#dgname".$d."').autocomplete({ source:'../../libs/dglist.php', minLength:1, autoFocus:true});\n";
}
for($d=1;$d<=14;$d++)
{
echo "$('#dggsname".$d."').autocomplete({ source:'../../libs/dgglist.php', minLength:1, autoFocus:true}); \n";
}
for($d=1;$d<=5;$d++)
{
echo "$('#chroil".$d."').autocomplete({source:'../../libs/illlist.php', minLength:1, autoFocus:true}); \n";
}
?>
$("#alldrugname").autocomplete({ source:"../libs/drugdblist.php", minLength:1, autoFocus:true});
$("#drugsize").autocomplete({ source:"../libs/sizelist.php", minLength:1, autoFocus:true});
});
</script>
