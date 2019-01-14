<?php 
include '../../config/dbc.php';
page_protect();

//$fulluri = $_SERVER['REQUEST_URI'];
//$id = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);

$title = "::Patient File::";
include '../../main/header.php';
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
<?php
include '../../main/bodyheader.php';
?>
<iframe src="lmenubar.php"  id="LeftIframe" onload="iframeLoaded()" scrolling="no" frameborder="0" max-width='150px' width="14%" style="float:left;" name="lmenu"></iframe>
<iframe src="opdpage.php"  id="MidIframe" onload="iframeLoaded()" scrolling="no" frameborder="0"  width="71%" style="float:none;" name="MAIN"></iframe>
<iframe src="rmenuopd.php" id="RightIframe" onload="iframeLoaded()" scrolling="no" frameborder="0"  max-width='150px' width="14%" style="float:right;" name="rmenu"></iframe>
</body>
</html>
