<?php 

include '../login/dbc.php';
page_protect();
include '../libs/resizeimage.php';

$err = array();
$msg = array();


        $id = $_SESSION['Patient_id'];
        //createAvatar Directory for this id.
 //       $pdir = PT_AVATAR_PATH.$id."/";
        $pdir = AVATAR_PATH;
        
if($_POST['doRegister'] == 'แก้ไข') 
{ 
// get variable from html form
$fname = mysqli_real_escape_string($linkopd, $_POST['fname']);
$lname = mysqli_real_escape_string($linkopd, $_POST['lname']);
$gender = mysqli_real_escape_string($linkopd, $_POST['Gender']);
$ctzidin = $_POST['ctz_id'];
$day = $_POST['day'];
if(empty($day)) $day = 1;
$month = $_POST['month'];
if(empty($month)) $month = 1;
$byear = $_POST['year'];
$year = $byear - 543;

// check for duplicated record for ctz_id
$rs_duplicate = mysqli_query($linkopd, "select count(*) as total from patient_id where ctz_id='$_POST[ctz_id]' AND ctz_id !=0 ") or die(mysqli_error($linkopd));
list($total) = mysqli_fetch_array($rs_duplicate);

if ($total > 0)
{
$err = urlencode("คำเตือน: มีเลขบัตรประชาชน ".$ctzidin."  อยู่ในบัญชีแล้ว.");
header("Location: patientupdate.php?msg=$err");
exit();
}

if(($ctzidin<1000000000000))
{
  if(!preg_match('/[a-zA-Z\.]/i', $ctzidin))
  {
  $err[]= "เลขประจำตัวผิด ไม่ครบ 13 หลัก";
  $ctzidin='';
  }
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

// assign insertion pattern WHERE `patient_id`.`id` =1 LIMIT 1 ;
$sql_insert = "UPDATE `patient_id` SET 
				`ctz_id` = '$ctzidin',
				`prefix` = '$_POST[prefix]',
				`fname` = '$fname',
				`lname` = '$lname',
				`gender` = '$gender',
				`birthday` = '$birthday',
				`bloodgrp` = '$_POST[Bldgroup]',
				`height` = '$_POST[height]',
				`drug_alg_1` = '$_POST[drug_alg_1]',
				`drug_alg_2` = '$_POST[drug_alg_2]',
				`drug_alg_3` = '$_POST[drug_alg_3]',
				`drug_alg_4` = '$_POST[drug_alg_4]',
				`drug_alg_5` =	 '$_POST[drug_alg_5]',
				`chro_ill_1` = '$_POST[chro_ill_1]',
				`chro_ill_2` = '$_POST[chro_ill_2]',
				`chro_ill_3` = '$_POST[chro_ill_3]',
				`chro_ill_4` = '$_POST[chro_ill_4]',
				`chro_ill_5` = '$_POST[chro_ill_5]',
				`concurdrug` = '$concurdrug',
				`address1` = '$_POST[address1]',
				`address2` = '$_POST[address2]',
				`address3` = '$_POST[address3]',
				`address4` = '$_POST[address4]',
				`address5` = '$_POST[address5]',
				`zipcode` = '$_POST[zipcode]',
				`hometel` = '$_POST[hometel]',
				`mobile` = '$_POST[mobile]',
				`staff` = '$_POST[monk]',
				`reccomp` = '$_POST[compy]'
				WHERE `id` ='$_SESSION[Patient_id]' LIMIT 1 ; 
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
/////////////////////////////////////////////////////
        $id = $_SESSION['Patient_id'];
        
        
        
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

                            $target_file_path = AVATAR_PATH ."pt_". $id . ".jpg";
                               
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
	


/////////////////

// go on to other step
//header("Location: patientupdate.php");  

} 
?>

<!DOCTYPE html>

<html>

<head><meta content="text/html; charset=utf8" http-equiv="content-type">
	<title>แก้ไขทะเบียน</title>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/validate-1.5.5/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
  include '../libs/autojatz.php';
$formid = "regForm";
include '../libs/validate.php';
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
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
		<tr><td colspan="3">&nbsp;</td>
		    <td width="160" valign="top"><div class="pos_l_fix">
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
				?></div>
			</td>
			<td width="" valign="top">
	  <?php
	  /******************** ERROR MESSAGES*************************************************
	  This code is to show error messages 
	  **************************************************************************/
	  if(!empty($err))  
	  {
	   echo "<p>";
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "$e <br>";
	    }
	  echo "</div>";
	  echo "</p>";
	   }
	  /******************************* END ********************************/	  
	  ?><table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
				<tbody>
					<tr><td width="160" valign="top"></td>
						<td width="732" valign="top">
							<h3 class="titlehdr">ระบบแก้ไขทะเบียนผู้ป่วย</h3>
							
							<p>ในการลงทะเบียนผู้ป่วย [ ชื่อ นามสกุล ] <span class="required">*</span> จำเป็นต้องมี.</p>
							
							<form action="patientupdate.php" method="post" name="regForm" id="regForm" enctype="multipart/form-data">
							
								<table style="background-color: rgb(204, 204, 204); width: 700px; text-align: left; margin-left: 
								auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2">
								<tbody>
								<tr>
								<?php 
								$result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id = '$_SESSION[Patient_id]'");
								while($row = mysqli_fetch_array($result))
								{
									$ctzid = $row['ctz_id'];
									$prefix = $row['prefix'];
									$fname = $row['fname'];
									$lname = $row['lname'];
									$gender = $row['gender'];
									$birthday =$row['birthday'];
									$bloodgrp = $row['bloodgrp'];
									$height = $row['height'];
									for($i=1;$i<=5;$i++)
									{
										$drl = "drug_alg_".$i;
										$drugal[$i] = $row[$drl];
										$cho = "chro_ill_".$i;
										$chorn[$i] = $row[$cho];
									}
									$concurdrug = $row['concurdrug'];
									for($i=1;$i<=5;$i++)
									{
										$addr="address".$i;
										$addre[$i] = $row[$addr];
									}
									$zip = $row['zipcode'];
									$htel = $row['hometel'];
									$mtel = $row['mobile'];
									$staff = $row['staff'];
									$company = $row['reccomp'];
								}
								$date = new DateTime($birthday);
								$day = $date->format("d");
								$mon = $date->format("m");
								$yr = $date->format("Y") + 543;
								//concurdrug
								if(!empty($concurdrug))
								{
								    $n = substr_count($concurdrug, ',');
								    //$str = 'hypertext;language;programming';
								    $charsl = preg_split('/,/', $concurdrug);
								}
								
								?>
								<td style="text-align: left;">*<input  align="center" name="prefix" id="pref" size="10"
								type="text"  value="<?php echo $prefix; ?>">ชื่อ:*<input  align="center" tabindex="1" name="fname" size="20" 
								class="required" type="text"  value="<?php echo $fname; ?>">
									&nbsp; นามสกุล:*  <input tabindex="2" name="lname" size="20" class="required" type="text"
									value="<?php echo $lname; ?>">
									<?php
									if($_SESSION['user_accode']%13==0 AND $_SESSION['user_level'] == 5)
									{
									   echo "&nbsp;เพศ: ";
									   echo "<input type=radio name=Gender value='ชาย'";
									   if ($gender=='ชาย') echo "checked";
									   echo ">ชาย<input type=radio  name=Gender value='หญิง'"; if ($gender=='หญิง') echo "checked"; echo ">หญิง";
									echo "<br><br>เลขประจำตัวประชาชน: "; 
										echo "<input name='ctz_id' tabindex='4' type='text' size='18' maxlength='13' value='";
										echo $ctzid;
										echo "'>";
										?>
									วันเกิด: วันที่&nbsp;
									<select name="day">
									<option value="<?php echo $day;?>" selected><?php echo $day;?></option>
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
									<select name="month">
									<option value="<?php echo $mon;?>" selected><?php if($mon==1) echo "มค"; if($mon==2) echo "กพ"; if($mon==3) echo "มีค"; if($mon==4) echo "เมย"; if($mon==5) echo "พค"; if($mon==6) echo "มิย"; if($mon==7) echo "กค"; if($mon==8) echo "สค"; if($mon==9) echo "กย"; if($mon==10) echo "ตค"; if($mon==11) echo "พย"; if($mon==12) echo "ธค"; ?></option>
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
									พ.ศ. <input tabindex="8" name="year" size="5" maxlength="4" type="number" required min="2457" max="2657" step="1" value=<?php echo $yr;?> >
									<?php 			
									}
									else
									{
									   echo "&nbsp;เพศ: ".$gender; 
										echo "<input name='Gender' type='hidden' value='";
										echo $gender;
										echo "'>";
									echo "<br><br>เลขประจำตัวประชาชน: "; 									if($ctzid < 1000000000000)
									{
										echo "<input name='ctz_id' tabindex='4' type='text' size='18' maxlength='13' value='";
										echo $ctzid;
										echo "'>";
									}
									else
									{
										echo $ctzid;
										echo "<input name='ctz_id' type='hidden' value='";
										echo $ctzid;
										echo "'>";
									}	
									echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									?>
									วันเกิด: วันที่&nbsp;<?php 
									echo $day.'-'.$mon.'-'.$yr;
									echo "<input type=hidden name=day value=".$day.">";
									echo "<input type=hidden name=month value=".$mon.">";
									echo "<input type=hidden name=year value=".$yr.">";
									
									}
									?>
									<br>
									<hr style="width: 100%; height: 2px;">ส่วนสูง:
									<input tabindex="8" name="height" size="4" maxlength="3" type="text" value="<?php echo $height; ?>"> ซม.
									&nbsp; &nbsp;
									&nbsp; <!--น้ำหนัก: <input tabindex="9" name="weight" size="4" maxlength="3" type="text"> กก.-->
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									หมู่เลือด : <select tabindex="10" name="Bldgroup">
									<option value="<?php echo $bloodgrp; ?>" selected><?php echo $bloodgrp; ?></option>
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="AB">AB</option>
									<option value="O">O</option>
									</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php 
									if($staff==1) echo "<input type=checkbox name=monk value=1 checked>พนักงาน"; 
									elseif($staff==9) echo "<input type=checkbox name=monk value=9 checked>ภิกษุสงฆ์"; 
									else echo "<input type=checkbox name=monk value=9>ภิกษุสงฆ์";
									?>
									<div class="avatar">
									<img src="<?php $avatar = $pdir. "pt_". $id . ".jpg";
									echo $avatar; ?>" width="44" height="44" /></div>
									<br>
									<label for="avatar_file">Avatar:</label>
		<!-- max size 5 MB (as many people directly upload high res pictures from their digicams) -->
									<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
									<input type="file" name="avatar_file" /> &nbsp;&nbsp;&nbsp;&nbsp;ใบเสร็จรับเงินในนาม:<select name="compy">
									<?php 
									if($company==0)	echo "<option value='0' selected></option>";
									else echo "<option value='0'></option>";
									$comp = mysqli_query($link, "SELECT * FROM reccompany");
									while($crow = mysqli_fetch_array($comp))
									{
									  echo "<option value='".$crow['id']."'";
									  if($company==$crow['id']) echo "selected ";
									  echo ">".$crow['comname']."</option>";
									}
									?>
									  </select>

									<br>
									<hr style="width: 100%; height: 2px;">
									ที่อยู่:
									<br>
									บ้านเลขที่<input tabindex="11" name="address1" type="search" value="<?php echo $addre[1]; ?>" >หมู่ที่ 
									<input tabindex="12" name="address2" type="text" value="<?php echo $addre[2]; ?>" >
									ตำบล<input tabindex="13" name="address3" type="search" id="tname" value="<?php echo $addre[3]; ?>" >
									<br>
									อำเภอ<input tabindex="14" name="address4" type="search" id="aname" value="<?php echo $addre[4]; ?>" >
									&nbsp; &nbsp; &nbsp; &nbsp; จังหวัด<input tabindex="15" id="jname" name="address5" type="search" 
									value="<?php echo $addre[5]; ?>"  >
									&nbsp;รหัสไปรษณีย์<input tabindex="16" name="zipcode" size="6" maxlength="5" type="search" id="zip" value="<?php 
									echo $zip; ?>" >
									<br>
									<div style="text-align: center;">
									โทรศัพท์มือถือ<input tabindex="17" name="mobile" size="16" maxlength="15" type="search" value="<?php 
									echo $mtel; ?>" >&nbsp;
									โทรศัพท์บ้าน<input tabindex="18" name="hometel" size="26" maxlength="25" type="search" value="<?php 
									echo $htel; ?>" >
									<br>
									<hr style="width: 100%; height: 2px;"></div>
									<br>
									
									<table style="background-color: rgb(255, 204, 153); width: 60%; text-align: left;
									margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
										<tbody>
											<tr>
												<td style="width: 50%; text-align: center;">แพ้ยา</td>
												<td style="text-align: center;">โรคประจำตัว</td>
												<td style="width: 25%; text-align: center;">ยาที่ใช้ประจำ</td>
												<td style="text-align: center;">ยาที่ใช้ประจำ</td>
											</tr>
											<tr>
												<td style="text-align: center;"><input name="drug_alg_1"  type="search" value="<?php 
												echo $drugal[1]; ?>" > </td>
												<td style="text-align: center;"><input name="chro_ill_1"  type="search" value="<?php 
												echo $chorn[1]; ?>" ></td>
												<td style="text-align: center;"><input name="con_drug_1" type="text" value="<?php 
												echo $charsl[0]; ?>"></td>
												<td style="text-align: center;"><input name="con_drug_2" type="text" value="<?php 
												echo $charsl[1]; ?>"></td>
											</tr>
											<tr>
												<td style="text-align: center;"><input name="drug_alg_2"  type="search" value="<?php 
												echo $drugal[2]; ?>" ></td>
												<td style="text-align: center;"><input name="chro_ill_2"  type="search" value="<?php 
												echo $chorn[2]; ?>" ></td>
												<td style="text-align: center;"><input name="con_drug_3" type="text" value="<?php 
												echo $charsl[2]; ?>"></td>
												<td style="text-align: center;"><input name="con_drug_4" type="text" value="<?php 
												echo $charsl[3]; ?>"></td>
											</tr>
											<tr>
												<td style="text-align: center;"><input name="drug_alg_3" type="search" value="<?php 
												echo $drugal[3]; ?>" ></td>
												<td style="text-align: center;"><input name="chro_ill_3" type="search" value="<?php 
												echo $chorn[3]; ?>" ></td>
												<td style="text-align: center;"><input name="con_drug_5" type="text" value="<?php 
												echo $charsl[4]; ?>"></td>
												<td style="text-align: center;"><input name="con_drug_6" type="text" value="<?php 
												echo $charsl[5]; ?>"></td>
											</tr>
											<tr>
												<td style="text-align: center;"><input name="drug_alg_4" type="search" value="<?php 
												echo $drugal[4]; ?>" ></td>
												<td style="text-align: center;"><input name="chro_ill_4" type="search" value="<?php 
												echo $chorn[4]; ?>" ></td>
												<td style="text-align: center;"><input name="con_drug_7" type="text" value="<?php 
												echo $charsl[6]; ?>"></td>
												<td style="text-align: center;"><input name="con_drug_8" type="text" value="<?php 
												echo $charsl[7]; ?>"></td>
											</tr>
											<tr>
												<td style="text-align: center;"><input name="drug_alg_5" type="search" value="<?php 
												echo $drugal[5]; ?>" ></td>
												<td style="text-align: center;"><input name="chro_ill_5" type="search" value="<?php 
												echo $chorn[5]; ?>" ></td>
												<td style="text-align: center;"><input name="con_drug_9" type="text" value="<?php 
												echo $charsl[8]; ?>"></td>
												<td style="text-align: center;"><input name="con_drug_10" type="text" value="<?php 
												echo $charsl[9]; ?>"></td>
											</tr>
										</tbody>
									</table>
									<br>
								</td>
								</tr>
								</tbody>
								</table>
							<p align="center"> <input  tabindex="19" name="doRegister" id="doRegister" value="แก้ไข" type="submit"></p>
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
