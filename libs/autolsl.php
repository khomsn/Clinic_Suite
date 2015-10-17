	<link rel="stylesheet" type="text/css" href="../public/css/jquery.autocomplete.css" />
	<script type="text/javascript" src="../public/js/jquery.autocomplete.js"></script>
	<script>
	$(document).ready(function(){
	$("#lsn").autocomplete("../libs/lslist.php", {
			selectFirst: true
		});
	$("#lsp").autocomplete("../libs/lsplist.php", {
			selectFirst: true
		});
	});
	</script>
