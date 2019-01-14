<script>
var t;
function reloadParentAndClose()
  {
      reloadParent();
	  window.close();
  }
function reloadParent()
  {
	  // reload the opener or the parent window
	  t = setTimeout("window.opener.location.reload()",30);
	  window.opener.location.reload();
  }
</script>
