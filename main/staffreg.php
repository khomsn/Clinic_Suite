<?php 

include '../login/dbc.php';
include '../libs/resizeimage.php';

page_protect();

$err = array();
$msg = array();

if($_SESSION['user_accode']%13 != 0)
// go on to other step
header("Location: staff.php");  

					 
if($_POST['doRegister'] == 'Register') 
{ 
// get variable from html form
$prefix = mysqli_real_escape_string($link, $_POST['prefix']);
$fname = mysqli_real_escape_string($link, $_POST['fname']);
$lname = mysqli_real_escape_string($link, $_POST['lname']);
$day = $_POST['day'];
$month = $_POST['month'];
$byear = $_POST['year'];
$year = $byear - 543;
$gender = mysqli_real_escape_string($link, $_POST['Gender']);

// check for duplicated record
$rs_duplicate = mysqli_query($link, "select count(*) as total from staff where F_Name='$fname' and L_Name='$lname' and gender='$gender' ") or die(mysqli_error($link));
list($total) = mysqli_fetch_array($rs_duplicate);

if ($total > 0)
{
$err = urlencode("คำเตือน: มีชื่อคุณ ".$fname." ".$lname." เพศ ".$gender."  อยู่ในบัญชีแล้ว.");
header("Location: staffreg.php?msg=$err");
exit();
}

// Then get user ID to process to other step.
$result = mysqli_query($link, "SELECT * FROM users WHERE user_name='$_POST[user_name]'");

$row = mysqli_fetch_array($result);
// Pass Patient ID as a session parameter.
$user_id= $row['id'];

// format birthday for mysql
$birthday = $year.'-'.$month.'-'.$day;
// check address

if(!empty($_POST['address3']))
{
	$sql="SELECT * FROM zip WHERE tname='$_POST[address3]'";
	$result = mysqli_query($linkcm,$sql) or die(mysqli_error());
		while($row=mysqli_fetch_array($result))
		{
		  $_POST['address4'] = $row['aname'];
		  $_POST['address5'] = $row['jname'];
		  $_POST['zipcode'] = $row['zipcode'];
		}
}

// assign insertion pattern
$sql_insert = "INSERT into `staff`
  			(`ctz_id`,`prefix`,`F_Name`,`L_Name`, `gender`, `birthday`, `posit`, `license`, `add_hno`, `add_mu`, `add_t`, `add_a`, `add_j`, `add_zip`, `h_tel`, `mobile`, `email`, `user_id`, `status` )
		    VALUES
		    ('$_POST[ctz_id]','$prefix','$fname','$lname','$gender','$birthday','$_POST[Posit]','$_POST[license]','$_POST[address1]','$_POST[address2]','$_POST[address3]','$_POST[address4]','$_POST[address5]',
			'$_POST[zipcode]','$_POST[hometel]','$_POST[mobile]','$_POST[email]','$user_id','$_POST[status]')
			";


// Now insert staff to "staff" table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
//create OPD
// assign insertion pattern
$sql_insert = "INSERT into `patient_id`
  			(`ctz_id`,`prefix`,`fname`,`lname`, `gender`, `birthday`, `address1`, `address2`, `address3`, `address4`, `address5`, `zipcode`, `hometel`, `mobile`, `staff` )
		    VALUES
		    ('$_POST[ctz_id]','$_POST[prefix]','$fname','$lname','$gender','$birthday','$_POST[address1]','$_POST[address2]','$_POST[address3]','$_POST[address4]','$_POST[address5]',
			'$_POST[zipcode]','$_POST[hometel]','$_POST[mobile]','$_POST[status]')
			";


// Now insert Patient to "patient_id" table
mysqli_query($linkopd, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkopd));
// Then get Patient ID to process to other step.
$result = mysqli_query($linkopd, "SELECT id FROM patient_id
 WHERE fname='$fname' AND lname='$lname' AND gender='$gender' AND ctz_id='$_POST[ctz_id]'");

$row = mysqli_fetch_array($result);
// Pass Patient ID as a session parameter.
$_SESSION['Patient_id']= $row[0];

include '../libs/pt_table.php';

// Then get Staff ID to process to other step.
$result = mysqli_query($link, "SELECT id FROM staff WHERE F_Name='$fname' AND L_Name='$lname' AND gender='$gender' AND ctz_id='$_POST[ctz_id]");

$row = mysqli_fetch_array($result);
// Pass Patient ID as a session parameter.
$staffid = $row[0];

//update users table at staff_id
mysqli_query($link, "UPDATE users SET `staff_id` = '$staffid' WHERE `id` = '$user_id' ") or die(mysqli_error($link));

//avatar part
 //       createAvatar($_FILES['avatar_file']['tmp_name']);
        
        if (is_dir(AVATAR_PATH) && is_writable(AVATAR_PATH)) {
           
            if (!empty ($_FILES['avatar_file']['tmp_name'])) {

                // get the image width, height and mime type
                // btw: why does PHP call this getimagesize when it gets much more than just the size ?
                $image_proportions = getimagesize($_FILES['avatar_file']['tmp_name']);

                // dont handle files > 5MB
                if ($_FILES['avatar_file']['size'] <= 2000000 ) {

                    if ($image_proportions[0] >= 100 && $image_proportions[1] >= 100) {

                        if ($image_proportions['mime'] == 'image/jpeg' || $image_proportions['mime'] == 'image/png') {

                            $target_file_path = AVATAR_PATH ."st_". $staffid . ".jpg";
                               
                            // creates a 44x44px avatar jpg file in the avatar folder
                            // see the function defintion (also in this class) for more info on how to use
                            resize_image($_FILES['avatar_file']['tmp_name'], $target_file_path, 120, 120, 85, true);

                            $msg[] = FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL;

                        } else {

                            $err[] = FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE;

                        }

                    } else {

                        $err[] = FEEDBACK_AVATAR_UPLOAD_TOO_SMALL;

                    }

                } else {

                    $err[] = FEEDBACK_AVATAR_UPLOAD_TOO_BIG;

                } 
            }  

        } else {
            
            $err[] = FEEDBACK_AVATAR_FOLDER_NOT_WRITEABLE;
            
        }
        
$_SESSION['Staff_id']= $staffid;
// go on to other step
header("Location: staffupdate.php");  

} 
?>

<!DOCTYPE html>

<html>

<head><meta content="text/html; charset=utf8" http-equiv="content-type">
	<title>ลงทะเบียน พนักงาน</title>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/validate-1.5.5/jquery.validate.js"></script>
<?php 
include '../libs/autojatz.php';
$formid = "regForm";
include '../libs/validate.php';
?>
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
		<tr><td colspan="3">&nbsp;</td>
		    <td width="160" valign="top">
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
				?>
			</td>
			<td width="" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
				<tbody>
					<tr><td width="160" valign="top"></td>
						<td width="732" valign="top">
							<h3 class="titlehdr">ระบบลงทะเบียน พนักงาน</h3>
							<p>ในการลงทะเบียน [ ชื่อ นามสกุล ] <span class="required">*</span> จำเป็นต้องมี. ที่อยู่ ถ้าใส่ ตำบลแล้ว อำเภอ จังหวัด รหัส ไม่ต้องใส่ก็ได้</p>
							
							<form action="staffreg.php" method="post" name="regForm" id="regForm" enctype="multipart/form-data">
							
								<table style="background-color: rgb(204, 204, 204); width: 700px; text-align: left; margin-left: 
								auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
								<tbody>
								<tr>
								<td style="text-align: left;">*<input  align="center" name="prefix" size="5" type="text" id="pref" tabindex=1 >ชื่อ:*<input  align="center" tabindex="2" name="fname" size="20" class="required" type="text" >
									&nbsp; นามสกุล:*  <input tabindex="3" name="lname" size="20" class="required" type="text" >
									&nbsp;เพศ*<input type="radio" tabindex="4" name="Gender" class="required" value="ชาย">ชาย 
												 <input type="radio" tabindex="4" name="Gender" class="required" value="หญิง">หญิง
									<br>
									เลขประจำตัวประชาชน<input name="ctz_id" tabindex="5" type="text" size="18" maxlength="13">
									วันเกิด: วันที่&nbsp;
									<select tabindex="6" name="day">
									<option value="" selected></option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
									<option value="25">25</option>
									<option value="26">26</option>
									<option value="27">27</option>
									<option value="28">28</option>
									<option value="29">29</option>
									<option value="30">30</option>
									<option value="31">31</option>
									</select>
									&nbsp;เดือน &nbsp;
									<select tabindex="7" name="month">
									<option value="" selected></option>
									<option value="1">มค</option>
									<option value="2">กพ</option>
									<option value="3">มีค</option>
									<option value="4">เมย</option>
									<option value="5">พค</option>
									<option value="6">มิย</option>
									<option value="7">กค</option>
									<option value="8">สค</option>
									<option value="9">กย</option>
									<option value="10">ตค</option>
									<option value="11">พย</option>
									<option value="12">ธค</option>
									</select>
									พ.ศ. <input tabindex="8" name="year" size="5" maxlength="4" type="number" min=2447 step=1><br>
									<hr style="width: 100%; height: 2px;">ตำแหน่งงาน:
									<input type="radio" tabindex="9" name="Posit" class="required" value="แพทย์">แพทย์
									<input type="radio" tabindex="9" name="Posit" class="required" value="พยาบาล">พยาบาล
									<input type="radio" tabindex="9" name="Posit" class="required" value="ผู้ช่วยพยาบาล">ผู้ช่วยพยาบาล
									<input type="radio" tabindex="9" name="Posit" class="required" value="เจ้าหน้าที่">เจ้าหน้าที่
									<input type="radio" tabindex="9" name="Posit" class="required" value="งานบ้าน">งานบ้าน
									&nbsp; &nbsp;License No.:
									<input tabindex="10" name="license" size="10" type="text">
									<br>
									Email:<input tabindex="11" name="email" size="20" type="text">
									&nbsp; &nbsp;
									Login [user name]:
							<select name="user_name">
								<option value="" selected></option>
								<?php 
								$dgroup = mysqli_query($link, "SELECT * FROM users");
								while($grow = mysqli_fetch_array($dgroup))
								{
									echo "<option value=\"";
									echo $grow['user_name'];
									echo "\">";
									echo $grow['user_name']."</option>";
								}
								?>
							</select>

									<hr style="width: 100%; height: 2px;">
									ที่อยู่:
									<br>
									บ้านเลขที่<input tabindex="12" name="address1" type="text">
									หมู่ที่<input tabindex="13" name="address2" type="text" size=4>
									&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;ตำบล <input tabindex="14" name="address3" type="text" id="tname">
									<br>
									อำเภอ<input tabindex="15" name="address4" type="text" id="aname">
									&nbsp; &nbsp; &nbsp; &nbsp; 
									จังหวัด<input tabindex="16" name="address5" type="text" id="jname">
									&nbsp;รหัสไปรษณีย์<input tabindex="17" name="zipcode" size="6" maxlength="5" type="text" id="zip">
									<br>
									<div style="text-align: center;">
									โทรศัพท์มือถือ<input tabindex="18" name="mobile" size="16" maxlength="15" type="text">&nbsp;
									โทรศัพท์บ้าน<input tabindex="19" name="hometel" size="26" maxlength="25" type="text">
									<br>
									<hr style="width: 100%; height: 2px;">
									<input type="radio" name="status" value=1 checked>Active
									<input type="radio" name="status" value=0 >Inactive
									<hr style="width: 50%; height: 2px;">
		<label for="avatar_file">Select an avatar image (Max size 2 MB):</label>
		<!-- max size 5 MB (as many people directly upload high res pictures from their digicams) -->
		<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
		<input type="file" name="avatar_file" />
									</div>
									<br>
									
									<br>
								</td>
								</tr>
								</tbody>
								</table>
							<p align="center"> <input  tabindex="20" name="doRegister" id="doRegister" value="Register" type="submit"></p>
							</form>
						</td>
						<td valign="top" width="196">&nbsp;</td>
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					</tbody>
					</table>
			</td>
			<td>
			  <table style="text-align: left;" border="1" cellpadding="5" cellspacing="5">
			      <tbody>
				    <tr>
				      <td style="text-align: right;">
				      <?php 
				      if($_SESSION['user_accode']%13 == 0)
				      {
				      echo "<a href='../main/staffreg.php'>";
				      ?>
				      <img style="border: 0px solid ; width: 120px; height: 120px;" alt="ลงทะเบียนพนักงาน"
					  src="../image/staffadd.jpeg">
				      <?php echo  "</a>";?>
				    </td></tr>
				    <tr><td>
				    </td></tr>
				    <tr><td style="text-align: right;">
				      <?php echo "<a href='../main/staffsearch.php'>"; ?>
				      <img style="border: 0px solid ; width: 120px; height: 120px;" alt="ค้นทะเบียนพนักงาน"
					  src="../image/user-management_3.jpg">
					  <?php 
					  echo "</a>";
				      }
				      ?></td>
				    </tr>
			      </tbody>
			    </table>
			</td>
		</tr>
	</table>
</body>
</html>
