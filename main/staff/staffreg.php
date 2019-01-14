<?php 
include '../../config/dbc.php';

include '../../libs/resizeimage.php';

page_protect();

$err = array();
$msg = array();

if($_SESSION['user_accode']%13 != 0)
// go on to other step
header("Location: ../../login/myaccount.php");  

if($_POST['doRegister'] == 'Register') 
{ 
    //first check for duplicated ID
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
    $rs_duplicate = mysqli_query($link, "select count(*) as total from staff where ctz_id='$_POST[ctz_id]' AND ctz_id !=0 ") or die(mysqli_error($link));
    list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
        $err[] = "คำเตือน: มีเลขบัตรประชาชน ".$ctzid."  อยู่ในบัญชีแล้ว.";
        goto ERROR_JP;
    }
    // get variable from html form
    $prefix = mysqli_real_escape_string($link, $_POST['prefix']);
    $fname = mysqli_real_escape_string($link, $_POST['fname']);
    $lname = mysqli_real_escape_string($link, $_POST['lname']);
    $Eprefix = mysqli_real_escape_string($link, $_POST['Eprefix']);
    $Efname = mysqli_real_escape_string($link, $_POST['Efname']);
    $Elname = mysqli_real_escape_string($link, $_POST['Elname']);

    $day = $_POST['day'];
    if(empty($day)) $day=1;
    $month = $_POST['month'];
    if(empty($month)) $month=1;
    $byear = $_POST['year'];
    if($_POST['Era'] == 1) $year = $byear - 543;
    if($_POST['Era'] == 2) $year = $byear;

    $gender = mysqli_real_escape_string($link, $_POST['Gender']);

    // check for duplicated record
    $rs_duplicate = mysqli_query($link, "select count(*) as total from staff where F_Name='$fname' and L_Name='$lname' and gender='$gender' ") or die(mysqli_error($link));
    list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
        $err[] = "คำเตือน: มีชื่อคุณ ".$fname." ".$lname." เพศ ".$gender."  อยู่ในบัญชีแล้ว.";
        goto ERROR_JP;
    }

    // Then get user ID to process to other step.
    $result = mysqli_query($link, "SELECT * FROM users WHERE user_name='$_POST[user_name]'");

    $row = mysqli_fetch_array($result);
    // Pass Patient ID as a session parameter.
    $user_id= $row['id'];
    if(empty($user_id)) $user_id=0;
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
    $addstr = mysqli_real_escape_string($link, $_POST['addstr']);
    // assign insertion pattern
    $sql_insert = "INSERT into `staff`
                (`ctz_id`,`prefix`,`F_Name`,`L_Name`,`Eprefix`,`EF_Name`,`EL_Name`, `gender`, `birthday`, `posit`, `license`, `add_hno`, `add_mu`, `add_str`, `add_t`, `add_a`, `add_j`, `add_zip`, `h_tel`, `mobile`, `email`, `user_id`, `status` )
                VALUES
                ('$_POST[ctz_id]','$prefix','$fname','$lname','$Eprefix','$Efname','$Elname','$gender','$birthday','$_POST[Posit]','$_POST[license]','$_POST[address1]','$_POST[address2]','$addstr','$_POST[address3]','$_POST[address4]','$_POST[address5]',
                '$_POST[zipcode]','$_POST[hometel]','$_POST[mobile]','$_POST[email]','$user_id','$_POST[status]')
                ";


    // Now insert staff to "staff" table
    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

    //create OPD
    // check for duplicated record
    $rs_duplicate = mysqli_query($linkopd, "select count(*) as total from patient_id where fname='$fname' and lname='$lname' and gender='$gender' and ctz_id='$_POST[ctz_id]'") or die(mysqli_error($linkopd));
    list($total) = mysqli_fetch_array($rs_duplicate);
    // check for duplicated record if same person
    $rs_duplicate = mysqli_query($linkopd, "select count(*) as total from patient_id where fname='$fname' and lname='$lname' and gender='$gender' and birthday='$birthday'") or die(mysqli_error($linkopd));
    list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
        goto jumphere;
    }
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

    include '../../libs/pt_table.php';
    //end opd creation
    jumphere:
    // Then get Staff ID to process to other step.
    $result = mysqli_query($link, "SELECT ID FROM staff WHERE F_Name='$fname' AND L_Name='$lname' AND gender='$gender' AND ctz_id='$_POST[ctz_id]");

    $row = mysqli_fetch_array($result);
    // Pass Patient ID as a session parameter.
    $staffid = $row[0];
    
    //assign account no. for payment to this staff_id //51000000-59999998 เงินเดือน จ่าย 51000000 for non staff payment //start with 51000000 + staff_id
    $acfp = 51000000+$staffid;
    $acnfp= $fname." ".$lname ."-".$staffid;
    
    $sql_insert = "INSERT into `acnumber` (`ac_no`,`name`)  VALUES  ('$acfp','$acnfp')";
    // Now insert Patient to "acnumber" table
    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

	//update users table at staff_id
	$sql_update = "UPDATE users SET `staff_id` = '$staffid' WHERE `id` = '$user_id';";
	// Now insert Staff to "users" table
	mysqli_query($link, $sql_update) or die("Update Failed:" . mysqli_error($link));
	

    //avatar part
    $stimpath = "../".AVATAR_PATH;
    //createAvatar($_FILES['avatar_file']['tmp_name']);
        
    if (is_dir($stimpath) && is_writable($stimpath))
    {
        
        if (!empty ($_FILES['avatar_file']['tmp_name'])) 
        {

            // get the image width, height and mime type
            // btw: why does PHP call this getimagesize when it gets much more than just the size ?
            $image_proportions = getimagesize($_FILES['avatar_file']['tmp_name']);

            // dont handle files > 5MB
            if ($_FILES['avatar_file']['size'] <= 2000000 ) 
            {

                if ($image_proportions[0] >= 100 && $image_proportions[1] >= 100) 
                {

                    if ($image_proportions['mime'] == 'image/jpeg' || $image_proportions['mime'] == 'image/png') 
                    {

                        $target_file_path = $stimpath ."st_". $staffid . ".jpg";
                            
                        // creates a 44x44px avatar jpg file in the avatar folder
                        // see the function defintion (also in this class) for more info on how to use
                        resize_image($_FILES['avatar_file']['tmp_name'], $target_file_path, 120, 120, 85, true);

                        $msg[] = FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL;

                    }
                    else
                    {

                        $err[] = FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE;

                    }

                }
                else
                {

                    $err[] = FEEDBACK_AVATAR_UPLOAD_TOO_SMALL;

                }

            }
            else
            {

                $err[] = FEEDBACK_AVATAR_UPLOAD_TOO_BIG;

            } 
        }  

    }
    else
    {
        $err[] = FEEDBACK_AVATAR_FOLDER_NOT_WRITEABLE;
    }
        
    $_SESSION['Staff_id']= $staffid;
    // go on to other step
    header("Location: ../../main/staft/staffupdate.php");  
}
ERROR_JP:
$title = "::Staff::";
include '../../main/header.php';
include '../../libs/autojatz.php';
include '../../libs/autoname.php';
$formid = "regForm";
include '../../libs/validate.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="160" valign="top"><div class="pos_l_fix">
    <?php 
    if (isset($_SESSION['user_id'])) 
    {
        include '../../login/menu_admam.php';
    }
    ?></div>
    </td><td valign="top">
        <p>
        <?php
        /******************** ERROR MESSAGES*************************************************
        This code is to show error messages 
        **************************************************************************/
        if(!empty($err))
        {
            echo "<div class=\"msg\">";
            foreach ($err as $e) {echo "$e <br>";}
            echo "</div>";
        }
        ?></p>
        <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
        <tr><td width="160" valign="top"></td>
            <td width="732" valign="top">
                <h3 class="titlehdr">ระบบลงทะเบียน พนักงาน</h3>
                <p>ในการลงทะเบียน [ ชื่อ นามสกุล ] <span class="required">*</span> จำเป็นต้องมี. ที่อยู่ ถ้าใส่ ตำบลแล้ว อำเภอ จังหวัด รหัส ไม่ต้องใส่ก็ได้</p>

                <form action="staffreg.php" method="post" name="regForm" id="regForm" enctype="multipart/form-data">

                <table style="background-color: rgb(204, 204, 204); width: 750px; text-align: left; margin-left: 
                auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
                <tbody>
                <tr><td style="text-align: left;">
                    ยศ*<input  tabindex="1" autofocus align="center" name="prefix" size="5" type="text" id="pref" tabindex=2 >ชื่อ:*<input  align="center" tabindex="3" name="fname" id="fname" size="20" class="required" type="text" >&nbsp; นามสกุล:*  <input tabindex="4" name="lname" id="lname" size="20" class="required" type="text" ><br>Prefix*<input  tabindex="5" align="center" name="Eprefix" size="5" type="text" id="epref">FName:*<input  align="center" name="Efname" size="20" class="required" type="text" tabindex="6" >&nbsp; LName:*  <input name="Elname" size="20" class="required" type="text" tabindex="7">&nbsp;เพศ*<input type="radio" tabindex="8" name="Gender" class="required" value="ชาย">ชาย<input type="radio" tabindex="8" name="Gender" class="required" value="หญิง">หญิง
                    <br>
                    เลขประจำตัวประชาชน<input name="ctz_id" tabindex="9" type="text" size="18" maxlength="13" class="required">
                    วันเกิด: วันที่&nbsp;
                    <select tabindex="10" name="day">
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
                    <select tabindex="11" name="month">
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
                    <input type="radio" name="Era" value="1" checked>พ.ศ. <input type="radio" name="Era" value="2">ค.ศ. <input tabindex="12" name="year" size="5" maxlength="4" type="number" required min="1914" max="2657" step="1" class="typenumber">
                    <hr style="width: 100%; height: 2px;">ตำแหน่งงาน:
                    <input type="radio" tabindex="13" name="Posit" class="required" value="แพทย์">แพทย์
                    <input type="radio" tabindex="13" name="Posit" class="required" value="พยาบาล">พยาบาล
                    <input type="radio" tabindex="13" name="Posit" class="required" value="ผู้ช่วยพยาบาล">ผู้ช่วยพยาบาล
                    <input type="radio" tabindex="13" name="Posit" class="required" value="เจ้าหน้าที่">เจ้าหน้าที่
                    <input type="radio" tabindex="13" name="Posit" class="required" value="งานบ้าน">งานบ้าน
                    &nbsp; &nbsp;License No.:
                    <input tabindex="14" name="license" size="10" type="text">
                    <br>
                    Email:<input tabindex="15" name="email" size="20" type="text">
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
                    บ้านเลขที่<input tabindex="16" name="address1" type="text" class="required">
                    หมู่ที่ <input tabindex="17" name="address2" type="number" size=4 min="0" value="0"><br>ถนน<input name="addstr" type="text" class="addstr" tabindex="18"><br>ตำบล/แขวง<input tabindex="19" name="address3" type="text" id="tname" class="required">
                    <br>
                    อำเภอ/เขต<input tabindex="20" name="address4" type="text" id="aname">
                    &nbsp; &nbsp; &nbsp; &nbsp; 
                    จังหวัด<input tabindex="21" name="address5" type="text" id="jname">
                    &nbsp;รหัสไปรษณีย์<input tabindex="22" name="zipcode" size="6" maxlength="5" type="text" id="zip">
                    <br>
                    <div style="text-align: center;">
                    โทรศัพท์มือถือ<input tabindex="23" name="mobile" size="16" maxlength="15" type="text">&nbsp;
                    โทรศัพท์บ้าน<input tabindex="24" name="hometel" size="26" maxlength="25" type="text">
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
                </td></tr>
                </tbody>
                </table>
                <p align="center"><input  tabindex="25" name="doRegister" id="doRegister" value="Register" type="submit"></p>
                </form>
        </td><td valign="top" width="196">&nbsp;</td>
        </tr>
        </tbody>
        </table>
    </td><td><div class="pos_r_fix"><?php include 'stmenurt.php';?></div></td></tr>
</table>
</body>
</html>
