<script>
var t;
function reloadParentAndClose()
  {
	  // reload the opener or the parent window
	  t = setTimeout("window.opener.location.reload()",300);
	  window.opener.location.reload()
	  // then close this pop-up window
	  window.close();
  }
function reloadParent()
  {
	  // reload the opener or the parent window
	  t = setTimeout("window.opener.location.reload()",30);
	  window.opener.location.reload()
  }
</script>
