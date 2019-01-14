<?php 
include '../../config/dbc.php';
page_protect();

$fulluri = $_SERVER['REQUEST_URI'];
$id = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);
$_SESSION['patdesk'] = $id;

$title = "::OPD ROOM::";
include '../../main/header.php';
include '../../libs/refreshptcall.php';
include '../../main/bodyheader.php';
?>
<script type="text/javascript">
  function iframeLoaded() {
      var iFrameLeft = document.getElementById('LeftIframe');
      if(iFrameLeft) {
            // here you can make the height, I delete it first, then I make it again
            iFrameLeft.height = "";
            iFrameLeft.height = iFrameLeft.contentWindow.document.body.scrollHeight + "px";
      }   
      var iFrameMid = document.getElementById('MidIframe');
      if(iFrameMid) {
            // here you can make the height, I delete it first, then I make it again
            iFrameMid.height = "";
            iFrameMid.height = iFrameMid.contentWindow.document.body.scrollHeight + "px";
      }   
      var iFrameRight = document.getElementById('RightIframe');
      if(iFrameRight) {
            // here you can make the height, I delete it first, then I make it again
            iFrameRight.height = "";
            iFrameRight.height = iFrameRight.contentWindow.document.body.scrollHeight + "px";
      }   
  }
</script> 
<script type="text/javascript">
$(function() {
    $("#MidIframe").bind("load", function(){
        $(this).contents().find("#firstfocus").focus();
    });
});

</script>
<table width=100% border=0>
<tr><th width=180px>
<iframe src="lmenubar.php"  id="LeftIframe" onload="iframeLoaded()" scrolling="no" frameborder="0" max-width='150px' width="14%" style="float:left; position: fixed; left: 10px; top: 15px; width: 180px;" name="lmenu"></iframe>
</th><th>
<iframe src="pt1page.php"  id="MidIframe" autofocus="true" onload="iframeLoaded()" scrolling="no" frameborder="0" style="float:none; left:5px; right: 5px;  top: 15px; width: 100%;" name="MAIN"></iframe>
</th><th width=180px>
<iframe src="rmenubar.php" id="RightIframe" onload="iframeLoaded()" scrolling="no" frameborder="0"  max-width='150px' width="14%" style="float:right; position: fixed; right: 10px; top: 15px; width: 180px;" name="rmenu"></iframe>
</th></tr>
</table>
<div id="callpt"></div>
</body>
</html>
