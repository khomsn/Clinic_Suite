<script>
$(document).ready(function(){
<?php 
for ($rawmat=1;$rawmat<=30;$rawmat++)
{
    echo "$('#rawmat".$rawmat."').autocomplete({source:'../../libs/rmlistin.php', minLength:1, autoFocus:true });\n";
}
?>
});
</script>
