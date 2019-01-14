<?php 
include '../../config/dbc.php';
page_protect();
include '../../libs/resizeimage.php';

$fulluri = $_SERVER['REQUEST_URI'];

$id = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);
$err = array();
$msg = array();

$pdir = "../".DRUGIMAGE_PATH;
        
if($_POST['submit'] == 'Upload') 
{ 
       $drugid = $_POST['ptid']; 
        
    if (is_dir($pdir) && is_writable($pdir))
    {
        $target_file = $pdir ."drug_". $drugid . ".jpg";
        
        include '../../libs/uploadimage.php';
    }
    else
    {
        
        $err[] = FEEDBACK_AVATAR_FOLDER_NOT_WRITEABLE;
        
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf8mb4" http-equiv="content-type">
<script language="JavaScript" type="text/javascript" src="../../jscss/js/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="../../jscss/css/styles.css">
<script>
$(function () {
    var dropZoneId = "drop-zone";
    var buttonId = "clickHere";
    var mouseOverClass = "mouse-over";

    var dropZone = $("#" + dropZoneId);
    var ooleft = dropZone.offset().left;
    var ooright = dropZone.outerWidth() + ooleft;
    var ootop = dropZone.offset().top;
    var oobottom = dropZone.outerHeight() + ootop;
    var inputFile = dropZone.find("input");
    document.getElementById(dropZoneId).addEventListener("dragover", function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.addClass(mouseOverClass);
        var x = e.pageX;
        var y = e.pageY;

        if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
            inputFile.offset({ top: y - 15, left: x - 100 });
        } else {
            inputFile.offset({ top: -400, left: -400 });
        }

    }, true);

    if (buttonId != "") {
        var clickZone = $("#" + buttonId);

        var oleft = clickZone.offset().left;
        var oright = clickZone.outerWidth() + oleft;
        var otop = clickZone.offset().top;
        var obottom = clickZone.outerHeight() + otop;

        $("#" + buttonId).mousemove(function (e) {
            var x = e.pageX;
            var y = e.pageY;
            if (!(x < oleft || x > oright || y < otop || y > obottom)) {
                inputFile.offset({ top: y - 15, left: x - 160 });
            } else {
                inputFile.offset({ top: -400, left: -400 });
            }
        });
    }

    document.getElementById(dropZoneId).addEventListener("drop", function (e) {
        $("#" + dropZoneId).removeClass(mouseOverClass);
    }, true);

})
</script>


<style>


#drop-zone {
    /*Sort of important*/
    width: 700px;
    /*Sort of important*/
    height: 250px;
    position:absolute;
    left:25%;
    top:0px;
    margin-left:-150px;
    border: 2px dashed rgba(0,0,0,.3);
    border-radius: 20px;
    font-family: Arial;
    text-align: center;
    position: relative;
    line-height: 180px;
    font-size: 20px;
    color: rgba(0,0,0,.3);
}

    #drop-zone input {
        /*Important*/
        position: absolute;
        /*Important*/
        cursor: pointer;
        left: 0px;
        top: 0px;
        /*Important This is only comment out for demonstration purposes.
        opacity:0; */
    }


    /*Important*/
    #drop-zone.mouse-over {
        border: 2px dashed rgba(0,0,0,.5);
        color: rgba(0,0,0,.5);
    }


/*If you dont want the button*/
#clickHere {
    position: absolute;
    cursor: pointer;
    left: 50%;
    top: 50%;
    margin-left: -50px;
    margin-top: 20px;
    line-height: 26px;
    color: white;
    font-size: 12px;
    width: 100px;
    height: 26px;
    border-radius: 4px;
    background-color: #3b85c3;

}

    #clickHere:hover {
        background-color: #4499DD;

    }
</style>




</head>
<body>
<?php
/******************** ERROR MESSAGES*************************************************
This code is to show error messages 
**************************************************************************/
if(!empty($err))  
{
    echo "<p>";
    echo "<div class=\"msg\">";
    foreach ($err as $e) {echo "$e <br>";}
    echo "</div>";
    echo "</p>";
}
if(!empty($msg))  
{
    echo "<p>";
    echo "<div class=\"msg\">";
    foreach ($msg as $m) {echo "$m <br>";}
    echo "</div>";
    echo "</p>";
}
/******************************* END ********************************/
$avatar = $pdir. "drug_". $id . ".jpg";
echo "<form action='updatedrugimage.php?msg=".$id."' method='post' enctype='multipart/form-data'>";
echo "<div class=''><img src=\"".$avatar."\" width='80' height='80' /></div>";
echo "<input type=hidden name=ptid value=".$id.">";
echo "<label for='avatar_file'>Drug Picture:</label><!-- max size 5 MB (as many people directly upload high res pictures from their digicams) -->";
$filter = mysqli_query($link, "select * from drug_id  WHERE `id` = $id");
while($row = mysqli_fetch_array($filter))
{
    echo $row['dname'];
    echo " [".$row['dgname']."] ";echo " [".$row['size']."] ";
}
echo "<input type='hidden' name='MAX_FILE_SIZE' value='2000000' />";
echo "<div id='drop-zone'>";
echo "Drop files here...";
echo "<div id='clickHere'>";
echo "or click here..";
echo "<input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\">";
echo "</div></div>";
echo "<p align='center'><input name='submit' value='Upload' type='submit'></p>";
echo "</form></body></html>"
?>
