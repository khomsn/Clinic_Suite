<?php 
include '../../config/dbc.php';

page_protect();
include '../../libs/resizeimage.php';

if($_SESSION['user_accode']%13 != 0)
// go on to other step
header("Location: ../../main/staff/staff.php");  

					 
if($_POST['doRegister'] == 'แก้ไข') 
{ 
	// get variable from html form
	$prefix = mysqli_real_escape_string($link, $_POST['prefix']);
	$F_Name = mysqli_real_escape_string($link, $_POST['F_Name']);
	$L_Name = mysqli_real_escape_string($link, $_POST['L_Name']);
	$Eprefix = mysqli_real_escape_string($link, $_POST['Eprefix']);
	$EF_Name = mysqli_real_escape_string($link, $_POST['EF_Name']);
	$EL_Name = mysqli_real_escape_string($link, $_POST['EL_Name']);
	$gender = mysqli_real_escape_string($link, $_POST['Gender']);
	$add_str = mysqli_real_escape_string($link, $_POST['add_str']);


	$log2 = $_POST['logged'] + 1;
	
    $day = $_POST['day'];
    $month = $_POST['month'];
    $byear = $_POST['year'];
    $year = $byear - 543;
   // format birthday for mysql
    $birthday = $year.'-'.$month.'-'.$day;


	// Then get user ID to process to other step.
	$result = mysqli_query($link, "SELECT * FROM users WHERE user_name='$_POST[user_name]'");

	$row = mysqli_fetch_array($result);
	// Pass Patient ID as a session parameter.
	$user_id= $row['id'];
	if(empty($user_id)) $user_id=0;

	// assign insertion pattern WHERE `staff`.`id` =1 LIMIT 1 ;
	$sql_update = "UPDATE `staff` SET 
					`ctz_id` = '$_POST[ctz_id]',
					`prefix` = '$prefix',
					`F_Name` = '$F_Name',
					`L_Name` = '$L_Name',
					`Eprefix` = '$Eprefix',
					`EF_Name` = '$EF_Name',
					`EL_Name` = '$EL_Name',
					`gender` = '$gender',
					`birthday` = '$birthday',
					`license` = '$_POST[license]',
					`email` = '$_POST[email]',
					`add_hno` = '$_POST[add_hno]',
					`add_mu` = '$_POST[add_mu]',
					`add_str` = '$_POST[add_str]',
					`add_t` = '$_POST[add_t]',
					`add_a` = '$_POST[add_a]',
					`add_j` = '$_POST[add_j]',
					`add_zip` = '$_POST[add_zip]',
					`h_tel` = '$_POST[h_tel]',
					`mobile` = '$_POST[mobile]',
					`posit` = '$_POST[Posit]',
					`user_id` = '$user_id',
					`clog` = '$log2',
					`ch_by` = '$_SESSION[user_id]',
					`status` = '$_POST[status]'
					WHERE `ID` ='$_SESSION[Staff_id]' LIMIT 1 ; 
					";
	// Now insert Staff to "staff" table
	mysqli_query($link, $sql_update);
	
	$sql_update = "UPDATE `patient_id` SET `staff`= '$_POST[status]' WHERE `ctz_id` = '$_POST[ctz_id]' LIMIT 1 ;";
	mysqli_query($linkopd, $sql_update) ;
	
	$staffid= $_SESSION['Staff_id'];

	//update users table at staff_id
	$sql_update = "UPDATE users SET `staff_id` = '$staffid' WHERE `id` = '$user_id';";
	// Now insert Staff to "users" table
	mysqli_query($link, $sql_update);
	

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
	//unset SESSION variable
	//unset($_SESSION['Staff_id']);
	// go on to other step
	header("Location: ../../main/staff/staffupdate.php");  
}
$title = "::Staff แก้ไขทะเบียน::";
include '../../main/header.php';
include '../../libs/autojatz.php';
include '../../libs/autoname.php';
$formid = "regForm";
include '../../libs/validate.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="160" valign="top">
    <div class="pos_l_fix">
    <?php 
    /*********************** MYACCOUNT MENU ****************************
    This code shows my account menu only to logged in users. 
    Copy this code till END and place it in a new html or php where
    you want to show myaccount options. This is only visible to logged in users
    *******************************************************************/
    if (isset($_SESSION['user_id'])) 
    {
        include '../../login/menu_admam.php';
    }
    /*******************************END**************************/
    ?><div>
    </td><td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
                <tr><td width="160" valign="top"></td>
                    <td width="732" valign="top">
                        <h3 class="titlehdr">ระบบแก้ไขทะเบียน</h3>
                        <p>ในการลงทะเบียน [ ชื่อ นามสกุล ] <span class="required">*</span> จำเป็นต้องมี.</p>
                        <form action="staffupdate.php" method="post" name="regForm" id="regForm" enctype="multipart/form-data">
                            <table style="background-color: rgb(204, 204, 204); width: 700px; text-align: left; margin-left: 
                            auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2">
                            <tr>
                            <?php 
                            $result = mysqli_query($link, "SELECT * FROM staff WHERE id = '$_SESSION[Staff_id]'");
                            while($row = mysqli_fetch_array($result))
                            {
                                $ctzid = $row['ctz_id'];
                                $prefix = $row['prefix'];
                                $F_Name = $row['F_Name'];
                                $L_Name = $row['L_Name'];
                                $Eprefix = $row['Eprefix'];
                                $EF_Name = $row['EF_Name'];
                                $EL_Name = $row['EL_Name'];
                                $gender = $row['gender'];
                                $birthday =$row['birthday'];
                                $license = $row['license'];
                                $email = $row['email'];
                                $posit = $row['posit'];
                                $log1 = $row['clog'];
                                $add_hno = $row['add_hno'];
                                $add_mu = $row['add_mu'];
                                $add_str = $row['add_str'];
                                $add_t = $row['add_t'];
                                $add_a = $row['add_a'];
                                $add_j = $row['add_j'];
                                $zip = $row['add_zip'];
                                $htel = $row['h_tel'];
                                $mtel = $row['mobile'];
                                $us_id = $row['user_id'];
                                $status = $row['status'];
                            }
                            
                            echo "<input type=hidden name=logged value=".$log1.">";
                            $date = new DateTime($birthday);
                            $day = $date->format("d");
                            $mon = $date->format("m");
                            $yr = $date->format("Y") + 543;
                            ?><td style="text-align: left;">ยศ*<input  align="center" name="prefix" size="5" type="text" id="pref"  value=<?php echo $prefix;?>>ชื่อ:*<input  align="center" name="F_Name" id="fname" size="20" 
                            class="required" type="text"  style="email: 35px;" value="<?php echo $F_Name; ?>">
                                &nbsp; นามสกุล:*  <input name="L_Name" id="lname" style="email: 35px;" size="25" class="required" type="text"
                                value="<?php echo $L_Name; ?>">
                                <br>Prefix*<input  align="center" name="Eprefix" size="5" type="text" id="Epref" value=<?php echo $Eprefix;?>>Fname:*<input  align="center"  name="EF_Name" size="20" 
                            class="required" type="text"  style="email: 35px;" value="<?php echo $EF_Name; ?>">
                                &nbsp; Lname:*  <input  name="EL_Name" style="email: 35px;" size="25" class="required" type="text"
                                value="<?php echo $EL_Name; ?>">
                                <br><br>
                                <?php
                                if($_SESSION['user_accode']%13==0 AND $_SESSION['user_level'] == 5)
                                {
                                    echo "&nbsp;เพศ: ";
                                    echo "<input type=radio name=Gender value='ชาย'";
                                    if ($gender=='ชาย') echo " checked";
                                    echo ">ชาย<input type=radio  name=Gender value='หญิง'"; if ($gender=='หญิง') echo " checked"; echo ">หญิง";
                                echo "<br><br>เลขประจำตัวประชาชน: "; 
                                    echo "<input name='ctz_id' type='text' size='18' maxlength='13' value='";
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
                                พ.ศ. <input  name="year" type="number" required min="2457" max="2657" step="1" class="typenumber" value=<?php echo $yr;?> >
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
                                    echo "<input name='ctz_id'  type='text' size='18' maxlength='13' value='";
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
                                <hr style="width: 100%; email: 2px;">email:
                                <input name="email" size="20" maxlength="20" type="text" value="<?php echo $email; ?>">
                                &nbsp; &nbsp;
                                Login [user name]:
                        <select name="user_name">
                            <option value=""></option>
                            <?php 
                            $dgroup = mysqli_query($link, "SELECT * FROM users WHERE id=$us_id");
                            while($grow = mysqli_fetch_array($dgroup))
                            {
                                echo "<option value=\"";
                                echo $grow['user_name'];
                                echo "\" selected>";
                                echo $grow['user_name']."</option>";
                            }
                            $dgroup = mysqli_query($link, "SELECT * FROM users WHERE id !='50'");
                            while($grow = mysqli_fetch_array($dgroup))
                            {
                                echo "<option value=\"";
                                echo $grow['user_name'];
                                echo "\">";
                                echo $grow['user_name']."</option>";
                            }
                            ?>
                        </select><br>ตำแหน่งงาน :
                                
                                <input type="radio"  name="Posit" class="required" value="แพทย์" <?php if($posit =='แพทย์') echo "checked";?>>แพทย์
                                <input type="radio"  name="Posit" class="required" value="พยาบาล" <?php if($posit =='พยาบาล') echo "checked";?>>พยาบาล
                                <input type="radio"  name="Posit" class="required" value="ผู้ช่วยพยาบาล" <?php if($posit =='ผู้ช่วยพยาบาล') echo "checked";?>>ผู้ช่วยพยาบาล
                                <input type="radio"  name="Posit" class="required" value="เจ้าหน้าที่" <?php if($posit =='เจ้าหน้าที่') echo "checked";?>>เจ้าหน้าที่
                                <input type="radio"  name="Posit" class="required" value="งานบ้าน" <?php if($posit =='งานบ้าน') echo "checked";?>>งานบ้าน
                                &nbsp; &nbsp;License : <input type=text size="8" tabindex="10" name="license" value="<?php echo $license; ?>">
                                <br>
                                <hr style="width: 100%; email: 2px;">
                                ที่อยู่:
                                <br>
                                บ้านเลขที่<input name="add_hno" type="text" value="<?php echo $add_hno; ?>" >หมู่ที่ 
                                <input  name="add_mu" type="number" min="0" value="<?php echo $add_mu; ?>" ><br>ถนน<input  name="add_str" type="text" class="addstr" value="<?php echo $add_str; ?>" ><br>
                                ตำบล/แขวง<input name="add_t" type="text" id="tname" value="<?php echo $add_t; ?>" >
                                <br>
                                อำเภอ/เขต<input name="add_a" type="text" id="aname" value="<?php echo $add_a; ?>" >
                                &nbsp; &nbsp; &nbsp; &nbsp; จังหวัด<input id="jname" name="add_j" type="text" 
                                value="<?php echo $add_j; ?>"  >
                                &nbsp;รหัสไปรษณีย์<input name="add_zip" size="6" maxlength="5" type="text" id="zip" value="<?php 
                                echo $zip; ?>" >
                                <br>
                                <div style="text-align: center;">
                                โทรศัพท์มือถือ<input name="mobile" size="16" maxlength="15" type="text" value="<?php 
                                echo $mtel; ?>" >&nbsp;
                                โทรศัพท์บ้าน<input name="h_tel" size="26" maxlength="25" type="text" value="<?php 
                                echo $htel; ?>" >
                                <br>
                                <hr style="width: 100%; email: 2px;">
                                <input type="radio" name="status" value=1 <?php if($status) echo "checked";?>>Active
                                <input type="radio" name="status" value=0 <?php if(!($status)) echo "checked";?>>Inactive
                                <hr style="width: 100%; height: 2px;">
                                <img src="<?php echo "../".AVATAR_PATH."st_".$_SESSION[Staff_id].".jpg"; ?>" />
                                <label for="avatar_file">Select an avatar image (Max size 2 MB):</label>
                                <!-- max size 5 MB (as many people directly upload high res pictures from their digicams) -->
                                <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                                <input type="file" name="avatar_file" />
                                </div>
                                <br>
                                <br>
                            </td>
                            </tr>
                            </table>
                        <p align="center"> <input name="doRegister" id="doRegister" value="แก้ไข" type="submit"></p>
                        </form>
                </td><td valign="top" width="196">&nbsp;</td></tr>
            </table>
</td><td><div class="pos_r_fix"><?php include 'stmenurt.php';?></div></td></tr>
</table>
</body>
</html>
