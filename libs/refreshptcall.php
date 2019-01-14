<script type="text/javascript" language="javascript">
$(document).ready(function() { /// Wait till page is loaded
setInterval(timingLoad, 1500);
function timingLoad() {
$('#callpt').load('../opd/updateptcalltosv.php #callpt', function() {
/// can add another function here
});
}
}); //// End of Wait till page is loaded
</script>
