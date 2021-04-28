<?php 
include '../../config/dbc.php';

include '../../libs/resizeimage.php';

page_protect();
$err = array();

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
    $rs_duplicate = mysqli_query($linkopd, "select count(*) as total from patient_id where ctz_id='$_POST[ctz_id]' AND ctz_id !=0 ") or $err[]=(mysqli_error($linkopd));
    list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
        $err[] = "คำเตือน: มีเลขบัตรประชาชน ".$ctzid."  อยู่ในบัญชีแล้ว.";
        goto Error1;
    }

    // format birthday for mysql
    $birthday = $year.'-'.$month.'-'.$day;

    // check for duplicated record
    $rs_duplicate = mysqli_query($linkopd, "select count(*) as total from patient_id where fname='$fname' and lname='$lname' and gender='$gender' and ctz_id='$_POST[ctz_id]'") or $err[]=(mysqli_error($linkopd));
    list($total) = mysqli_fetch_array($rs_duplicate);
    // check for duplicated record if same person
    $rs_duplicate = mysqli_query($linkopd, "select count(*) as total from patient_id where fname='$fname' and lname='$lname' and gender='$gender' and birthday='$birthday'") or $err[]=(mysqli_error($linkopd));
    list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
        $err[] = "คำเตือน: มีชื่อคุณ ".$fname." ".$lname." เพศ ".$gender."  อยู่ในบัญชีแล้ว.";
        goto Error1;
    }

    if(!empty($_POST['address3']) and empty($_POST['address4']))
    {
        $sql="SELECT * FROM zip WHERE tname='$_POST[address3]'";
        $result = mysqli_query($linkcm,$sql) or $err[]=(mysqli_error());
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
        $result = mysqli_query($linkcm,$sql) or $err[]=(mysqli_error());
            while($row=mysqli_fetch_array($result))
            {
                $_POST['address5'] = $row['jname'];
                $_POST['zipcode'] = $row['zipcode'];
            }
    }
    $addstr = mysqli_real_escape_string($linkopd, $_POST['addstr']);
    // Check for aviable ID 
    //initialize
    $maxconid=0;

    $checkid = mysqli_query($linkopd, "select id from patient_id ") or $err[]=(mysqli_error($linkopd));
    while ($cid = mysqli_fetch_array($checkid))
    {
            $cidmin = $cid['id'];
            if (($cidmin - $maxconid)==1)
            {
            $maxconid = $cidmin;
            }
            elseif (($cidmin - $maxconid)>1)      goto Got_ID;  
    }
    //Got ID
    Got_ID:
    $idtoinsert = $maxconid+1+$_SESSION['opdidoffset'];
    $checkid = mysqli_query($linkopd, "select MAX(id) from patient_id ") or $err[]=(mysqli_error($linkopd));
    $cid = mysqli_fetch_array($checkid);
    $maxid = $cid[0];
    //if($maxid==1) $idtoinsert=2;
    if($_SESSION['opdidoffset']>0)
    {
        if(($idtoinsert<=$maxid)) $idtoinsert=$maxid+1;
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
    if(empty($_POST['address2'])) $_POST['address2']=0;
    if(empty($_POST['zipcode'])) $_POST['zipcode']=0;
    $prefix = trim(preg_replace('/\s+/', '', $_POST['prefix']));
    // assign insertion pattern
    $sql_insert = "INSERT into `patient_id`
                (`id`,`ctz_id`,`ctzid_m`,`ctzid_f`,`prefix`,`fname`,`lname`, `gender`, `birthday`, `bloodgrp`, `height`, `drug_alg_1`, `drug_alg_2`, `drug_alg_3`, `drug_alg_4`, `drug_alg_5`
                , `chro_ill_1`,`chro_ill_2`, `chro_ill_3`, `chro_ill_4`, `chro_ill_5`, `concurdrug`, `address1`, `address2`,  `addstr`,`address3`, `address4`, `address5`, `zipcode`, `hometel`, `mobile`, `user_id`, `clinic`  )
                VALUES
                ('$idtoinsert','$ctzid','$_POST[ctzid_m]','$_POST[ctzid_f]','$prefix','$fname','$lname','$gender','$birthday','$_POST[Bldgroup]','$_POST[height]','$_POST[drug_alg_1]','$_POST[drug_alg_2]','$_POST[drug_alg_3]','$_POST[drug_alg_4]','$_POST[drug_alg_5]',
                '$_POST[chro_ill_1]','$_POST[chro_ill_2]','$_POST[chro_ill_3]','$_POST[chro_ill_4]','$_POST[chro_ill_5]','$concurdrug','$_POST[address1]','$_POST[address2]','$addstr','$_POST[address3]','$_POST[address4]','$_POST[address5]',
                '$_POST[zipcode]','$_POST[hometel]','$_POST[mobile]','$_SESSION[user_id]','$_SESSION[clinic]')
                ";


    // Now insert Patient to "patient_id" table
    mysqli_query($linkopd, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($linkopd));
    //update prefix table
    $imp = mysqli_query($linkcm, "select name from prefix WHERE name = '$prefix'");

    list($imprs) = mysqli_fetch_row($imp);
    if(empty($imprs))
    {
        $sql_insert = "INSERT into `prefix` (name) value ('$prefix')";
        mysqli_query($linkcm, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($linkcm));
    }

    // Pass Patient ID as a session parameter.
    $_SESSION['Patient_id']= $idtoinsert;

    /**********************************/

    include '../../libs/pt_table.php';

    /**********************************/
    /************Pt Avatar************/
    $id = $_SESSION['Patient_id'];
    
    $ptavatardir = "../".PT_AVATAR_PATH;
    $ptimagedir = "../".PT_IMAGE_PATH;
    
    //create Avatar + Image Directory for this id.
    if ( file_exists($ptimagedir)) 
    {
        mkdir($ptimagedir.$id, 0755, true);
        chmod($ptimagedir.$id, 0755);
    }
   
    
    if (is_dir($ptavatardir) && is_writable($ptavatardir)) 
    {
        if (!empty ($_FILES['avatar_file']['tmp_name'])) 
        {
            // get the image width, height and mime type
            // btw: why does PHP call this getimagesize when it gets much more than just the size ?
            $image_proportions = getimagesize($_FILES['avatar_file']['tmp_name']);

            // dont handle files > 5MB
            if ($_FILES['avatar_file']['size'] <= 5000000 ) 
            {
                if ($image_proportions[0] >= 100 && $image_proportions[1] >= 100) 
                {
                    if ($image_proportions['mime'] == 'image/jpeg' || $image_proportions['mime'] == 'image/png') 
                    {
                        $target_file_path = $ptavatardir. "pt_". $id . ".jpg";
                        
                        // creates a 44x44px avatar jpg file in the avatar folder
                        // see the function defintion (also in this class) for more info on how to use
                        resize_image($_FILES['avatar_file']['tmp_name'], $target_file_path, 220, 220, 85, true);
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
    /////////////////
    // go on to other step
    header("Location: pt_to_service.php");
}

Error1:
// If Register error come here!
$title = "::ลงทะเบียน::";
include '../../main/header.php';
include '../../libs/autoname.php';
include '../../libs/autojatz.php';
$formid = "regForm";
include '../../libs/validate.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
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
						include 'registermenu.php';
					}
				/*******************************END**************************/
				?></div>
        </td>
        <td valign="top">
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
	  /******************************* END ********************************/	  
	  ?></p>
    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
        <tbody>
            <tr><td width="160" valign="top"></td>
                <td width="732" valign="top">
                <h3 class="titlehdr">ระบบลงทะเบียนผู้ป่วย</h3>
                <p style="background-color: yellow;">ในการลงทะเบียนผู้ป่วย [ ชื่อ นามสกุล ] <span class="required">*</span> จำเป็นต้องมี. ที่อยู่ สามารถใส่ถึงแค่ ตำบล/อำเภอ ได้ ข้อมูลอื่นๆ ระบบจะใส่ให้</p>
                <p style="background-color: yellow;">เลขประจำตัวประชาชน ถ้าเป็นชาวต่างชาติที่ไม่มีเลขที่บัตร ให้ใช้ เลข Passport แทน โดยใส่ รหัสประเทศ ตามด้วย เลขที่ Passport เช่น "TH-E123456"</p>
                <form action="PIDregister.php" method="post" name="regForm" id="regForm" enctype="multipart/form-data">
                    <table style="background-color: rgb(204, 204, 204); width: 700px; text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2">
                        <tbody>
                        <tr><td style="text-align: left;">ยศ*<input  align="center" name="prefix" size="5" type="text" id="pref" tabindex=1 autofocus>ชื่อ:*<input  align="center" tabindex="2" name="fname" id="fname" size="20" class="required" type="text" >&nbsp; นามสกุล:*  <input tabindex="3" name="lname" id="lname" size="20" class="required" type="text" >&nbsp;เพศ*<input type="radio" tabindex="4" name="Gender" class="required" id="gm" value="ชาย"><label for="gm">ชาย</label><input type="radio" tabindex="4" name="Gender" class="required" id="gf" value="หญิง"><label for="gf">หญิง</label>
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
                            <input type="radio" name="Era" value="1" id="Bera" checked><label for="Bera">พ.ศ.</label> <input type="radio" name="Era" id="era" value="2"><label for="era">ค.ศ.</label> <input tabindex="8" name="year" size="5" maxlength="4" type="number" required min="1914" max="2657" step="1" class="typenumber">
                            <hr style="width: 100%; height: 2px;">เลขประจำตัวประชาชน-แม่<input name="ctzid-m" type="text" size="18" maxlength="13">เลขประจำตัวประชาชน-พ่อ<input name="ctzid-f" type="text" size="18" maxlength="13">
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
                            บ้านเลขที่<input tabindex="11" name="address1" type="text" class="typenumber">หมู่ที่<input tabindex="12" name="address2" type="number" min="0" value="0" class="typenumber"><br>หมู่บ้าน/ถนน<input tabindex="13" name="addstr" type="text" class="addstr"><br>ตำบล/แขวง<input tabindex="14" name="address3" type="text" id="tname" >
                            <br>
                            อำเภอ/เขต<input tabindex="15" name="address4" type="text" id="aname">
                            &nbsp; &nbsp; &nbsp; &nbsp; จังหวัด<input tabindex="16" name="address5" type="text" id="jname">
                            &nbsp;รหัสไปรษณีย์<input tabindex="17" name="zipcode" size="6" maxlength="5" type="text" id="zip">
                            <br>
                            <div style="text-align: center;">
                            โทรศัพท์มือถือ<input tabindex="18" name="mobile" id="mtel" size="16" maxlength="15" type="text">&nbsp;
                            โทรศัพท์บ้าน<input tabindex="19" name="hometel" id="htel" size="26" maxlength="25" type="text">
                            <br>
                            <hr style="width: 100%; height: 2px;"></div>
                            <br>
                            <table style="background-color: rgb(255, 204, 153); width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
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
						</td></tr>
					</tbody>
					</table>
					<p align="center"> <input  tabindex="20" name="doRegister" id="doRegister" value="Register" type="submit"></p>
				</form>
				</td>
				<td valign="top" width="196">&nbsp;</td></tr>
				<tr><td colspan="3">&nbsp;</td></tr>
			</tbody>
		</table>
	</td>
	<td width="106" valign="top">&nbsp;</td></tr>
</table>
</body>
</html>
