	<script>
	$(document).ready(function(){
	<?php 
	for ($drug=1;$drug<=30;$drug++)
	{
            echo "$('#drug".$drug."').autocomplete('../libs/druglistin.php', {
			selectFirst: true
		});\n";
        }
	?>
	});
	</script>
