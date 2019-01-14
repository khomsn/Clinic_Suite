<script>
$( function() {
$( "#menu" ).menu();
} );
</script>
<style>
.ui-menu { width: 150px; }
</style>
<div class="myaccount">
<div><img src="../../<?php echo $_SESSION['user_avatar_file']; ?>" /></div>
<br>
<ul id="menu">
  <li><div><a href="../../login/myaccount.php">Main Menu</a></div>

  <li><div>ยาควบคุม</div>
    <ul>
      <li class="ui-state-disabled"><div>ยาควมคุม</div></li>
      <li><div><a href="../doc&report/drugtracklist8.php">บจ.8</a></div></li>
      <li><div><a href="../doc&report/drugtracklist.php">บจ.9</a></div></li>
    </ul>
  </li>
  <li><div><a href="../doc&report/ptreport.php">รายงานจำนวนผู้ป่วย</a></div></li>
  <li><div>รายงานราคาซื้อ</div>
    <ul>
      <li><div><a href="../doc&report/druglistbuyprice.php">Drug</a></div></li>
      <li><div><a href="../doc&report/rawmatlistbuyprice.php">Raw Material</a></div></li>
    </ul>
  </li>
  <li><div>สินค้าคงคลัง</div>
    <ul>
      <li><div><a href="../doc&report/druglistprice.php">Drug</a></div></li>
      <li><div><a href="../doc&report/rawmatlistprice.php">Raw Material</a></div></li>
    </ul>
  </li>
  <li><div><a href="../doc&report/drugusedstat.php">รายงานสถิติการใช้ยา</a></div></li>
  <li><div><a href="../../login/logout.php">Logout</a></div>
</ul>
</div>
