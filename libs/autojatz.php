	<link rel="stylesheet" type="text/css" href="../public/css/jquery.autocomplete.css" />
	<script type="text/javascript" src="../public/js/jquery.autocomplete.js"></script>
	<script>
	$(document).ready(function(){
	$("#jname").autocomplete("../libs/jlist.php", {
			selectFirst: true
		});
	$("#aname").autocomplete("../libs/alist.php", {
			selectFirst: true
		});
	$("#tname").autocomplete("../libs/tlist.php", {
			selectFirst: true
		});
	$("#zip").autocomplete("../libs/ziplist.php", {
			selectFirst: true
		});
	$("#pref").autocomplete("../libs/prefixlist.php", {
			selectFirst: true
		});
	});
	</script>
