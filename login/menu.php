<div class="myaccount">
<img src="<?php echo $_SESSION['user_avatar_file']; ?>" />
<a href="../login/myaccount.php"><p><strong>Main Menu</strong></p></a><br><br>
<a href="../login/mysettings.php">My Setting</a><br><br>
<?php 
if ($_SESSION['user_accode']%13==0)
echo "<a href=../main/staff.php>Staff</a><br><br>";
if (($_SESSION['user_accode']%11==0) AND (!empty($_SESSION['sflc'])))
{
echo "<a href=../main/ordertemplate.php>Order Template</a><br><br>";
echo "<a href=../main/catcenable.php>DCC & DDI</a><br><br>";
echo "<a href=../main/maskingid.php>DRUG ID Masking</a><br><br>";
}
if ($_SESSION['user_accode']%2==0)
echo "<a href=../main/comptemplate.php>รายชื่อบริษัท</a><br><br>";
?>
<a href="../login/logout.php">Logout </a><br><br><br>
<?php
if (checkAdmin()) 
{
/*******************************END**************************/
?>
      <p> <a href="../login/admin.php">Admin CP </a></p>

<?php 
}
?>
</div>
