<?php 

include '../login/dbc.php';
page_protect();
$err = array();
$msg = array();

$sql = "

CREATE TABLE IF NOT EXISTS `maskid` (
  `id` int(11) NOT NULL,
  `drugid` int(11) NOT NULL,
  `dname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `dgname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mask` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `maskid`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `maskid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
";

mysqli_query($link, $sql);

$pin = mysqli_query($link, "select MAX(id) from maskid");
$rid = mysqli_fetch_array($pin);
$i = $rid[0];

if($_POST['addmore']=='+')
{
  
// check for duplicated record
$rs_duplicate = mysqli_query($link, "select count(*) as total from maskid where drugid='$_POST[drugid]'") or die(mysqli_error($link));
list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
      //$err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
      $err[] = "คำเตือน: Drug ID =  ".$_POST['drugid']."  ได้ถูกกำหนดไว้ในบัญชีของท่านแล้ว.";
    }
    else
    {
    $result = mysqli_query($link, "SELECT * FROM drug_id WHERE id=$_POST[drugid] ");
    while ($row_settings = mysqli_fetch_array($result))
      {
      $dname = $row_settings['dname'];
      $dgname = $row_settings['dgname'];
      // assign insertion pattern
      $sql_insert = "INSERT into `maskid` (`drugid`,`dname`,`dgname` ) VALUES ('$_POST[drugid]','$dname','$dgname')";

      // Now insert Patient to "patient_id" table
      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
      }
    }
}

for($j=1;$j<=$i;$j++)
{
  if($_POST['del'.$j] == 'ลบ')
  {
	  // Now Delete Patient from "pt_to_doc" table
	  mysqli_query($link, "DELETE FROM maskid WHERE id = '$j' ") or die(mysqli_error($link));
  }
}
if($_POST['Set_Mask']=='set')
{
    $sql_insert = "UPDATE `maskid` SET `mask` = '$_POST[mask]'; ";
    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
}
?>

<html>

<head>
<head>
<title>ผู้ป่วยรอตรวจ</title>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">

<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>

<!--add menu -->
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td width="160" valign="top"><div class="pos_l_fix">
<?php 
/*********************** MYACCOUNT MENU ****************************
This code shows my account menu only to logged in users. 
Copy this code till END and place it in a new html or php where
you want to show myaccount options. This is only visible to logged in users
*******************************************************************/
if (isset($_SESSION['user_id'])) 
{
  include '../login/menu.php';
} 
/*******************************END**************************/
?></div></td><td>
<!--menu-->
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td><?php 
if(!empty($err))  {
      echo "<div class=\"msg\">";
    foreach ($err as $e) {
      echo "* $e <br>";
      }
    echo "</div>";	
      }
?></td></tr>
<tr><td width="100%" valign="top">

<!-- Process Data-->
<form action="maskingid.php" method="post" name="ptd" id="ptd">
    <h3 class="titlehdr">Maskin Drug Id in OPD Card</h3>
    <p align="right">&nbsp; </p>
    
    <!--List Patient wait for doctor-->

    <?php
    $result = mysqli_query($link, "SELECT * FROM maskid ORDER BY dgname ASC ");
    
    $n_of_row = mysqli_num_rows($result);
    echo "<table border='1' width=100%>";
    echo "<tr><th>No</th><th  width=15%>Drug Id</th><th>Drug Name</th><th>Generig Name</th><th>+</th></tr>";
    // keeps getting the next row until there are no more to get
		    // Print out the contents of each row into a table
		    echo "<tr><th>";
		    echo "-";
		    echo "</th><th>";
		    echo "<input type=text name='drugid'>";
		    echo "</th><th>";
		    echo "</th><th>";
		    echo "</th><th>";
		    echo "<input type=submit name='addmore' value='+'>";
		    echo "</th></tr>";
		    //$j=1;
    
    while ($row_settings = mysqli_fetch_array($result))
    {
		    // Print out the contents of each row into a table
		    echo "<tr><th>";
		    echo "<input type=submit name='del".$row_settings['id']."' value='ลบ'>";
		    echo "</th><th>";
		    echo $row_settings['drugid'];
		    echo "</th><th>";
		    echo $row_settings['dname'];
		    echo "</th><th>";
		    echo $row_settings['dgname'];
		    echo "</th><th>+";
		    echo "</th></tr>";
		    $mask=$row_settings['mask'];
    
    }
    echo "</table>";
    echo "Masking ID <input type='radio' name='mask' value='1'";
    if ($mask=='1') echo "checked";
    echo ">ON<input type='radio' name='mask' value='0'";
    if ($mask=='0') echo "checked";
    echo ">OFF";
    //////////////////////////
    ?>
    <input type=submit name=Set_Mask value='set'>
</form>
<!-- Process Data finished-->
</td></tr></table>
</td></tr></table>
<!--end menu-->
</body>
</html>
