      <div class="pos_r_fix">
      <a href="../main/lablist.php"><img	style="border: 0px solid ; width: 120px; height: 120px;" alt="Lab" src="../image/lab.jpg"></a><br>
 <?php 
 if($_SESSION['accode']%13==0 or $_SESSION['accode']%5==0)
 {
 ?>
      <a href="../main/labwait.php"><img style="border: 0px solid ; width: 120px; height: 120px;" alt="Waiting Lab" src="../image/wait1.jpg"></a><br><a href="../main/labadd.php"><img style="border: 0px solid ; width: 120px; height: 120px;" alt="addlab" src="../image/Labadd.jpg"></a><br>
      <a href="../main/labaddset.php"><img style="border: 0px solid ; width: 120px; height: 120px;" alt="Set of Lab" src="../image/labsadd.jpg"></a><br>
      <a href="../main/labstats.php"><img style="border: 0px solid ; width: 120px; height: 120px;" alt="Lab Stats" src="../image/statastic.jpg"></a><br>
    <!--  <a href="../main/labdel.php"><img style="border: 0px solid ; width: 120px; height: 120px;" alt="Delete Lab" src="../image/minusr.jpg"></a><br> -->
  <?php }?>
     </div>
