<?php 
include 'dbc.php';
page_protect();

$sql = "
CREATE TABLE IF NOT EXISTS `stcpdrug` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `stcpdrug`
  ADD KEY `id` (`id`);
  
ALTER TABLE `stcpdrug`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
";
mysqli_query($link, $sql);

if($_POST['save']=="บันทึก")
{
    //if()
    {
    $sql_insert = "INSERT into `stcpdrug` (name) value ('$_POST[tin]')";
    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
    }
}
?>
<html>
<head>
<title>Staff co-pay drug list</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#regForm").validate();
  });
  </script>
<link rel="stylesheet" href="../public/css/styles.css">
</head>
<?php 
if(!empty($_SESSION['user_background']))
{
echo "<body style='background-image: url(".$_SESSION['user_background'].");' alink='#000088' link='#006600' vlink='#660000'>";
}
else
{
?>
<body style="background-image: url(../image/ptbg.jpg);" alink="#000088" link="#006600" vlink="#660000">
<?php
}
?>
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
				include 'menu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">รายการยาที่ Staff ต้องร่วมจ่าย เท่าราคาทุน</h3>
				<table style="text-align: center; margin-left: auto; margin-right: auto; width: 80%; background-color: rgb(255, 255, 204);" border="1" cellpadding="2" cellspacing="2">
				<form method="post" action="stcpdrug.php" name="regis" id="regForm">
					<tbody>
						<tr>
							<td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
							<table style="text-align: center; margin-left: auto; margin-right: auto; background-color: rgb(255, 255, 204);" border="1" cellpadding="2" cellspacing="2">
                                <tr><td>ชนิดยาและผลิตภัณฑ์</td></tr>
                                <tr><td><input type=text name=tin></td></tr>
                                <?php
                                $std=mysqli_query($link, "select * from stcpdrug");
                                while($row = mysqli_fetch_array($std))
                                {
                                    echo "<tr><td>";
                                    echo $row['name'];
                                    echo "</td></tr>";
                                }
                                ?>
							</table>
							</td>
						</tr>
						<tr>
							<td style="width: 100%; vertical-align: top; background-color: rgb(255, 255, 204);">
								<input type="submit" name="save" value="บันทึก">
							</td>
						</tr>
					</tbody>
				</form>	
				</table>
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>
