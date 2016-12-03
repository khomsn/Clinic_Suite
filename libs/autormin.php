	<script>
	$(document).ready(function(){
	<?php 
	for ($rawmat=1;$rawmat<=30;$rawmat++)
	{
            echo "$('#rawmat".$rawmat."').autocomplete('../libs/rmlistin.php', {
			selectFirst: true
		});\n";
        }
	?>
	});
	</script>
