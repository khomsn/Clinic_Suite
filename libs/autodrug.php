<script>
$(document).ready(function(){
    $("#drug").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
    $("#drug1").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
    $("#drug2").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
    $("#drug3").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
    $("#drug4").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
    $("#drug5").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
    $("#drug6").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
    $("#drug7").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
    $("#drug8").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
    $("#drug9").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
    $("#drug10").autocomplete({
        source:"../../libs/druglist.php?ptid=<?php echo $_SESSION['patdesk'];?>&catc=<?php echo $_SESSION['catc'];?>&ddil=<?php if($_SESSION['ddiltemp_'.$id]>$_SESSION['ddil']) echo $_SESSION['ddiltemp_'.$id];  else echo $_SESSION['ddil'];  ?>&dg1=<?php echo $_SESSION['dgname1'];?>&dg2=<?php echo $_SESSION['dgname2'];?>&dg3=<?php echo $_SESSION['dgname3'];?>&dg4=<?php echo $_SESSION['dgname4'];?>&dg5=<?php echo $_SESSION['dgname5'];?>&dg6=<?php echo $_SESSION['dgname6'];?>&dg7=<?php echo $_SESSION['dgname7'];?>&dg8=<?php echo $_SESSION['dgname8'];?>&dg9=<?php echo $_SESSION['dgname9'];?>&dg10=<?php echo $_SESSION['dgname10'];?>",
        minLength:1,
        autoFocus:true
    });
	$("#alldrug").autocomplete({
        source:"../../libs/alldruglist.php",
        minLength:1,
        autoFocus:true
    });
});
</script>
