	<link rel="stylesheet" type="text/css" href="../public/css/jquery.autocomplete.css" />
	<script type="text/javascript" src="../public/js/jquery.autocomplete.js"></script>
	<script>
	$(document).ready(function(){
	$("#fname").autocomplete("../libs/namelist.php", {
			selectFirst: true
		});
	$("#lname").autocomplete("../libs/lnamelist.php", {
			selectFirst: true
		});
	$("#htel").autocomplete("../libs/plinelist.php", {
			selectFirst: true
		});
	$("#mtel").autocomplete("../libs/cellplist.php", {
			selectFirst: true
		});
	$("#cid").autocomplete("../libs/cidlist.php", {
			selectFirst: true
		});
	});
	</script>
