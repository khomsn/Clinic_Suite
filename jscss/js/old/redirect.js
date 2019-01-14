<script type="text/javascript">
inactivityTimeout = False
resetTimeout()
function onUserInactivity() {
   window.location.href = "../main/mycounter.php"
}
function resetTimeout() {
   clearTimeout(inactivityTimeout)
   inactivityTimeout = setTimeout(onUserInactivity, 1000 * 120)
}
window.onmousemove = resetTimeout
</script>
