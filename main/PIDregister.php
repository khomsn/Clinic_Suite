<?php 

include '../login/dbc.php';
include '../libs/resizeimage.php';

page_protect();
$err = array();
$msg = array();

if($_POST['doRegister'] == 'Register') 
{ 
// get variable from html form
$fname = mysqli_real_escape_string($linkopd, $_POST['fname']);
$lname = mysqli_real_escape_string($linkopd, $_POST['lname']);
$day = $_POST['day'];
if(empty($day)) $day = 1;
$month = $_POST['month'];
if(empty($month)) $month = 1;
$byear = $_POST['year'];
if($_POST['Era'] == 1) $year = $byear - 543;
if($_POST['Era'] == 2) $year = $byear;
$gender = mysqli_real_escape_string($linkopd, $_POST['Gender']);
$weight = $_POST['weight'];

$ctzid = $_POST['ctz_id'];

if(($ctzid<1000000000000))
{
  if(!preg_match('/[a-zA-Z\.]/i', $ctzid))
  {
  $err[]= "เลขประจำตัวผิด ไม่ครบ 13 หลัก";
  $ctzid ='';
  }
}
// check for duplicated record for ctz_id
$rs_duplicate = mysqli_query($linkopd, "select count(*) as total from patient_id where ctz_id='$_POST[ctz_id]' AND ctz_id !=0 ") or die(mysqli_error($linkopd));
list($total) = mysqli_fetch_array($rs_duplicate);

if ($total > 0)
{
$err = urlencode("คำเตือน: มีเลขบัตรประชาชน ".$ctzid."  อยู่ในบัญชีแล้ว.");
header("Location: PIDregister.php?msg=$err");
exit();
}
// check for duplicated record
$rs_duplicate = mysqli_query($linkopd, "select count(*) as total from patient_id where fname='$fname' and lname='$lname' and gender='$gender' and ctz_id='$_POST[ctz_id]'") or die(mysqli_error($linkopd));
list($total) = mysqli_fetch_array($rs_duplicate);

if ($total > 0)
{
$err = urlencode("คำเตือน: มีชื่อคุณ ".$fname." ".$lname." เพศ ".$gender."  อยู่ในบัญชีแล้ว.");
header("Location: PIDregister.php?msg=$err");
exit();
}

if(!empty($_POST['address3']) and empty($_POST['address4']))
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
if(!empty($_POST['address3']) and !empty($_POST['address4']))
{
	$sql="SELECT * FROM zip WHERE tname='$_POST[address3]' AND aname='$_POST[address4]'";
	$result = mysqli_query($linkcm,$sql) or die(mysqli_error());
		while($row=mysqli_fetch_array($result))
		{
		  $_POST['address5'] = $row['jname'];
		  $_POST['zipcode'] = $row['zipcode'];
		}
}

// format birthday for mysql
$birthday = $year.'-'.$month.'-'.$day;
// Check for aviable ID 
$checkid = mysqli_query($linkopd, "select id from patient_id ") or die(mysqli_error($linkopd));
while ($cid = mysqli_fetch_array($checkid))
{
    if(empty($_SESSION['maxconid']))
    {   
        $_SESSION['maxconid'] = 1;
        $cidmin = $cid['id'];
        if (($cidmin - $_SESSION['maxconid'])>1)      goto Got_ID;  
    }
     if(!empty($_SESSION['maxconid']))
    {   
        $cidmin = $cid['id'];
        if (($cidmin - $_SESSION['maxconid'])==1)
           {
		 $_SESSION['maxconid'] = $cidmin;
		 goto Next1;
	   }
        if (($cidmin - $_SESSION['maxconid'])>1)      goto Got_ID;  
    }
   Next1:
}
//Got ID
 Got_ID:
$idtoinsert = $_SESSION['maxconid']+1+$_SESSION['opdidoffset'];
$checkid = mysqli_query($linkopd, "select MAX(id) from patient_id ") or die(mysqli_error($linkopd));
$cid = mysqli_fetch_array($checkid);
$maxid = $cid[0];
//if($maxid==1) $idtoinsert=2;
if($_SESSION['opdidoffset']>0)
{
    if(($idtoinsert<$maxid) OR ($idtoinsert==$maxid)) $idtoinsert=$maxid+1;
}

for($i=1;$i<=10;$i++)
{
  $in = 'con_drug_'.$i;
  if(!empty($_POST[$in]))
  {
    if(empty($concurdrug))
    {
      $concurdrug = $_POST[$in];
    } 
    else
    {
      $concurdrug = $concurdrug.','.$_POST[$in];
    }
  }
}

$concurdrug = mysqli_real_escape_string($link, $concurdrug);

// assign insertion pattern
$sql_insert = "INSERT into `patient_id`
  			(`id`,`ctz_id`,`prefix`,`fname`,`lname`, `gender`, `birthday`, `bloodgrp`, `height`, `drug_alg_1`, `drug_alg_2`, `drug_alg_3`, `drug_alg_4`, `drug_alg_5`
			, `chro_ill_1`,`chro_ill_2`, `chro_ill_3`, `chro_ill_4`, `chro_ill_5`, `concurdrug`, `address1`, `address2`, `address3`, `address4`, `address5`, `zipcode`, `hometel`, `mobile`, `clinic`  )
		    VALUES
		    ('$idtoinsert','$ctzid','$_POST[prefix]','$fname','$lname','$gender','$birthday','$_POST[Bldgroup]','$_POST[height]','$_POST[drug_alg_1]','$_POST[drug_alg_2]','$_POST[drug_alg_3]','$_POST[drug_alg_4]','$_POST[drug_alg_5]',
			'$_POST[chro_ill_1]','$_POST[chro_ill_2]','$_POST[chro_ill_3]','$_POST[chro_ill_4]','$_POST[chro_ill_5]','$concurdrug','$_POST[address1]','$_POST[address2]','$_POST[address3]','$_POST[address4]','$_POST[address5]',
			'$_POST[zipcode]','$_POST[hometel]','$_POST[mobile]','$_SESSION[clinic]')
			";


// Now insert Patient to "patient_id" table
mysqli_query($linkopd, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkopd));
//update prefix table
    $imp = mysqli_query($linkcm, "select name from prefix WHERE name = '$_POST[prefix]'");

    list($imprs) = mysqli_fetch_row($imp);
    if(empty($imprs))
    {
    $sql_insert = "INSERT into `prefix` (name) value ('$_POST[prefix]')";
    mysqli_query($linkcm, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkcm));

    }

// Then get Patient ID to process to other step.
$result = mysqli_query($linkopd, "SELECT * FROM patient_id
 WHERE fname='$fname' AND lname='$lname' AND gender='$gender' AND ctz_id='$_POST[ctz_id]'");

$row = mysqli_fetch_array($result);
// Pass Patient ID as a session parameter.
$_SESSION['Patient_id']= $row['id'];

/**********************************/

include '../libs/pt_table.php';

/**********************************/

        $id = $_SESSION['Patient_id'];
        //createAvatar Directory for this id.
        //mkdir(PT_AVATAR_PATH.$id, 0777);
        
//        $pdir = PT_AVATAR_PATH.$id."/";
        $pdir = PT_AVATAR_PATH;
        
        
        if (is_dir($pdir) && is_writable($pdir)) {
            
            if (!empty ($_FILES['avatar_file']['tmp_name'])) {

                // get the image width, height and mime type
                // btw: why does PHP call this getimagesize when it gets much more than just the size ?
                $image_proportions = getimagesize($_FILES['avatar_file']['tmp_name']);

                // dont handle files > 5MB
                if ($_FILES['avatar_file']['size'] <= 5000000 ) {

                    if ($image_proportions[0] >= 100 && $image_proportions[1] >= 100) {

                        if ($image_proportions['mime'] == 'image/jpeg' || $image_proportions['mime'] == 'image/png') {

                            $target_file_path = $pdir. "pt_". $id . ".jpg";
                               
                            // creates a 44x44px avatar jpg file in the avatar folder
                            // see the function defintion (also in this class) for more info on how to use
                            resize_image($_FILES['avatar_file']['tmp_name'], $target_file_path, 220, 220, 85, true);

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
      


/////////////////

// go on to other step
header("Location: pt_to_doctor.php");  

} 
?>

<!DOCTYPE html>

<html>

<head><meta content="text/html; charset=utf8" http-equiv="content-type">
	<title>ลงทะเบียน</title>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/validate-1.5.5/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
$formid = "regForm";
include '../libs/validate.php';
include '../libs/autojatz.php';
?>
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
						include 'clinicmenu.php';
					}
				/*******************************END**************************/
				?>
			</td>
			<td width="" valign="top">
	  <p>
	  <?php
	  /******************** ERROR MESSAGES*************************************************
	  This code is to show error messages 
	  **************************************************************************/
	  if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "$e <br>";
	    }
	  echo "</div>";	
	   }
	  /******************************* END ********************************/	  

	  ?></p>
				<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
				<tbody>
					<tr><td width="160" valign="top"></td>
						<td width="732" valign="top">
							<h3 class="titlehdr">ระบบลงทะเบียนผู้ป่วย</h3>
							<p>ในการลงทะเบียนผู้ป่วย [ ชื่อ นามสกุล ] <span class="required">*</span> จำเป็นต้องมี. ที่อยู่ สามารถใส่ถึงแค่ ตำบล/อำเภอ ได้ ข้อมูลอื่นๆ ระบบจะใส่ให้</p>
							<p>เลขประจำตัวประชาชน ถ้าเป็นชาวต่างชาติที่ไม่มีเลขที่บัตร ให้ใช้ เลข Passport แทน โดยใส่ รหัสประเทศ ตามด้วย เลขที่ Passport เช่น "TH-E123456"</p>
							
							<form action="PIDregister.php" method="post" name="regForm" id="regForm" enctype="multipart/form-data">
							
								<table style="background-color: rgb(204, 204, 204); width: 700px; text-align: left; margin-left: 
								auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2">
								<tbody>
								<tr>
								<td style="text-align: left;">*<input  align="center" name="prefix" size="5" type="text" id="pref" tabindex=1 autofocus>ชื่อ:*<input  align="center" tabindex="2" name="fname" size="20" class="required" type="text" >
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
									<input type="radio" name="Era" value="1" checked>พ.ศ. <input type="radio" name="Era" value="2">ค.ศ. <input tabindex="8" name="year" size="5" maxlength="4" type="number" required min="1914" max="2657" step="1" class="typenumber">
									<hr style="width: 100%; height: 2px;">ส่วนสูง:
									<input tabindex="9" name="height" size="4" maxlength="3" type="number" value="1" class="typenumber"> ซม.
									&nbsp; &nbsp;
									&nbsp; <!--น้ำหนัก: <input tabindex="9" name="weight" size="4" maxlength="3" type="text"> กก.-->
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									หมู่เลือด : <select tabindex="10" name="Bldgroup">
									<option value="" selected></option>
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="AB">AB</option>
									<option value="O">O</option>
									</select><br>
									<label for="avatar_file">Avatar:</label>
		<!-- max size 5 MB (as many people directly upload high res pictures from their digicams) -->
									<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
									<input type="file" name="avatar_file"/>
									
									<br>
									<hr style="width: 100%; height: 2px;">
									ที่อยู่:
									<br>
									บ้านเลขที่<input tabindex="11" name="address1" type="text" class="typenumber">หมู่ที่<input tabindex="12" name="address2" type="text" class="typenumber">
									&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;ตำบล <input tabindex="13" name="address3" type="text" id="tname" >
									<br>
									อำเภอ<input tabindex="14" name="address4" type="text" id="aname">
									&nbsp; &nbsp; &nbsp; &nbsp; จังหวัด<input tabindex="15" name="address5" type="text" id="jname">
									&nbsp;รหัสไปรษณีย์<input tabindex="16" name="zipcode" size="6" maxlength="5" type="text" id="zip">
									<br>
									<div style="text-align: center;">
									โทรศัพท์มือถือ<input tabindex="17" name="mobile" size="16" maxlength="15" type="text">&nbsp;
									โทรศัพท์บ้าน<input tabindex="18" name="hometel" size="26" maxlength="25" type="text">
									<br>
									<hr style="width: 100%; height: 2px;"></div>
									<br>
									
									<table style="background-color: rgb(255, 204, 153); width: 100%; text-align: left;
									margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
										<tbody>
											<tr>
												<td style="width: 25%; text-align: center;" >แพ้ยา</td>
												<td style="width: 25%; text-align: center;">โรคประจำตัว</td>
												<td style="width: 25%; text-align: center;">ยาที่ใช้ประจำ</td>
												<td style="text-align: center;">ยาที่ใช้ประจำ</td>
											</tr>
											<tr>
												<td style="text-align: center;"><input name="drug_alg_1" type="search" value="ปฎิเสธ"></td>
												<td style="text-align: center;"><input name="chro_ill_1" type="text" ></td>
												<td style="text-align: center;"><input name="con_drug_1" type="text" ></td>
												<td style="text-align: center;"><input name="con_drug_2" type="text" ></td>
											</tr>
											<tr>
												<td style="text-align: center;"><input name="drug_alg_2" type="text"></td>
												<td style="text-align: center;"><input name="chro_ill_2" type="text" ></td>
												<td style="text-align: center;"><input name="con_drug_3" type="text" ></td>
												<td style="text-align: center;"><input name="con_drug_4" type="text" ></td>
											</tr>
											<tr>
												<td style="text-align: center;"><input name="drug_alg_3" type="text"></td>
												<td style="text-align: center;"><input name="chro_ill_3" type="text" ></td>
												<td style="text-align: center;"><input name="con_drug_5" type="text" ></td>
												<td style="text-align: center;"><input name="con_drug_6" type="text" ></td>
											</tr>
											<tr>
												<td style="text-align: center;"><input name="drug_alg_4" type="text"></td>
												<td style="text-align: center;"><input name="chro_ill_4" type="text" ></td>
												<td style="text-align: center;"><input name="con_drug_7" type="text" ></td>
												<td style="text-align: center;"><input name="con_drug_8" type="text" ></td>
											</tr>
											<tr>
												<td style="text-align: center;"><input name="drug_alg_5" type="text"></td>
												<td style="text-align: center;"><input name="chro_ill_5" type="text" ></td>
												<td style="text-align: center;"><input name="con_drug_9" type="text" ></td>
												<td style="text-align: center;"><input name="con_drug_10" type="text" ></td>
											</tr>
										</tbody>
									</table>
									<br>
								</td>
								</tr>
								</tbody>
								</table>
							<p align="center"> <input  tabindex="19" name="doRegister" id="doRegister" value="Register" type="submit"></p>
							</form>
						</td>
						<td valign="top" width="196">&nbsp;</td>
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					</tbody>
					</table>
			</td>
			<td width="106" valign="top">&nbsp;</td>
		</tr>
	</table>
</body>
</html>
