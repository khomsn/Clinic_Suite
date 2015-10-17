<?php 

include '../login/dbc.php';
page_protect();
$pdir = AVATAR_PATH;
?>

<html>

<head>
<head>
<title>ผู้ป่วยรอ Treatment</title>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">

<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>

<!--add menu -->
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td width="160" valign="top">
<?php 
/*********************** MYACCOUNT MENU ****************************
This code shows my account menu only to logged in users. 
Copy this code till END and place it in a new html or php where
you want to show myaccount options. This is only visible to logged in users
*******************************************************************/
if (isset($_SESSION['user_id'])) {?>

<?php
include 'clinicmenu.php';
?>

<?php } 
/*******************************END**************************/
?>
      <p>&nbsp; </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
	  </td>
    <td width="" valign="top">
<!--menu-->
				<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
				  <tr> 
					<td colspan="3">&nbsp;</td>
				  </tr>
				  <tr> 
					<td width="160" valign="top"><p>&nbsp;</p>
					  <p>&nbsp; </p>
					  <p>&nbsp;</p>
					  <p>&nbsp;</p>
					  <p>&nbsp;</p>
					</td>
					<td width="732" valign="top">
					
					<!-- Process Data-->
					<form action="pt_to_trm.php" method="post" name="ptd" id="ptd">
						<h3 class="titlehdr">ผู้ป่วยรอการ Treatment</h3>
						<p align="right">&nbsp; </p>
						
						<!--List Patient wait for doctor-->
						<?php
						$result = mysqli_query($link, "SELECT * FROM pt_to_treatment ORDER BY rtime ASC ");
						
						$n_of_row = mysqli_num_rows($result);
						echo "<table border='1'>";
						echo "<tr><th>เลขทะเบียน</th><th>ยศ</th><th>ชื่อ</th><th>นามสกุล</th></tr>";
						// keeps getting the next row until there are no more to get
						$j=1;
						while($row = mysqli_fetch_array($result))
						 {
								// Print out the contents of each row into a table
								echo "<tr><th>"; 
						?>
							<?php
								$msg = urlencode($row['ptid']);
							?>
								<a href="pattrm.php
								<?php echo "?msg=".$msg; ?>"><?php echo $row['ptid'];?></a>
							
						<?php 
								//$mpq = $row['ptid'];
								//$ps = "pqn".$row['ptid'];
								//$ps ="pqn".$j;
								//$_SESSION[$ps] = $row['ptid'];
								//echo $row['ptid'];
								echo "</th><th>"; 
								echo "<a href=pattrm.php?msg=".$msg.">".$row['prefix']."</a>";
								echo "</th><th width=150>"; 
								echo "<a href=pattrm.php?msg=".$msg.">".$row['fname']."</a>";
								echo "</th><th width=150>"; 
								echo "<a href=pattrm.php?msg=".$msg.">".$row['lname']."</a>";
								echo "</th><th>";
								?><div class="avatar">
									<img src="<?php $avatar = $pdir. "pt_".$row['ptid'].".jpg";
									echo $avatar; ?>" width="44" height="44" /></div>
								<?php
								echo "</th></tr>";
								$j+=1;
						} 
						echo "</table>";
						//////////////////////////
						?>						
					</form>
					<!-- Process Data finished-->
					</td>
					<td width="196" valign="top">&nbsp;
					</td>
				  </tr>
				  <tr> 
					<td colspan="3">&nbsp;</td>
				  </tr>
				</table>
				
				
	<!--menu end-->
	</td>
	<!--end menu-->
</body>
</html>
