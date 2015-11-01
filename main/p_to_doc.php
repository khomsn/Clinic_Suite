<?php 

include '../login/dbc.php';
page_protect();
$pdir = AVATAR_PATH;
?>

<html>

<head>
<head>
<title>ผู้ป่วยรอตรวจ</title>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<meta http-equiv="refresh" content="10">
<link rel="stylesheet" href="../public/css/styles.css">
<link rel="stylesheet" href="../public/css/table_alt_color1.css">
<script type="text/javascript">
    if(window.top.location != window.location) 
    {
        window.top.location.href = window.location.href; 
    }
</script>        
</head>
<body  style="background-image: url(../config/rotate.php); background-size: cover;">
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
	  </td>
    <td width="" valign="top">
<!--menu-->
				<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
				  <tr> 
					<td colspan="3">&nbsp;</td>
				  </tr>
				  <tr> 
					<td width="160" valign="top"><p>&nbsp;</p>
					</td>
					<td width="732" valign="top">
					
					<!-- Process Data-->
					<form action="p_to_doc.php" method="post" name="ptd" id="ptd">
						<h3 class="titlehdr">ผู้ป่วยรอตรวจ</h3>
						<p align="right">&nbsp; </p>
						
						<!--List Patient wait for doctor-->
						<?php
						$result = mysqli_query($link, "SELECT * FROM pt_to_doc ORDER BY time ASC ");
						$n_of_row = mysqli_num_rows($result);
						echo "<table border='1' class='TFtable'>";
						echo "<tr><th>เลขทะเบียน</th><th>ยศ</th><th>ชื่อ</th><th>นามสกุล</th><th>";?><div class="avatar">
									<img src="<?php $avatar = $pdir."default.jpg";
									echo $avatar; ?>" width="44" height="44" /></div>
								<?php echo "</th></tr>";
						// keeps getting the next row until there are no more to get
						$j=1;
						while($row = mysqli_fetch_array($result))
						 {
								// Print out the contents of each row into a table
								echo "<tr><th>"; 
						?>
							<?php
								$msg = urlencode($row['ID']);
							?>
								<a href="patdesk.php
								<?php echo "?msg=".$msg; ?>"><?php echo $row['ID'];?></a>
							
						<?php 
								echo "</th><th>"; 
								echo "<a href=patdesk.php?msg=".$msg.">".$row['prefix']."</a>";$row['prefix'];
								echo "</th><th width=150>"; 
								echo "<a href=patdesk.php?msg=".$msg.">".$row['F_Name']."</a>";$row['F_Name'];
								echo "</th><th width=150>"; 
								echo "<a href=patdesk.php?msg=".$msg.">".$row['L_Name']."</a>";$row['L_Name'];
								echo "</th><th>";
								?><div class="avatar">
									<img src="<?php $avatar = $pdir. "pt_".$row['ID'].".jpg";
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
