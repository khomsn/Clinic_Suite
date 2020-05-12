<?php 
include '../../config/dbc.php';

page_protect();
$pdir = "../".AVATAR_PATH;

if($_POST['combine'] == "CBD")
{
    if( $_POST['id1'] > $_POST['id2'])
    {
        $ID1 = $_POST['id2'];
        $ID2 = $_POST['id1'];
    }    
    else 
    {
        $ID1 = $_POST['id1'];
        $ID2 = $_POST['id2'];
    }
    //get ctzid for updae
    if(!empty($_POST['ctzid'])) $ctzid = $_POST['ctzid'];
    
    $table1 = "pt_".$ID1;
    $table2 = "pt_".$ID2;
    
    if(( $ID1 % 1000 )==0)
    {
        $dbopd1 = "clinic_opd".$ID1;
    }
    else
    {
        $dbopd1 = "clinic_opd".( 1000 + $ID1 - ( $ID1 % 1000 ));
    }
    if(( $ID2 % 1000 )==0)
    {
        $dbopd2 = "clinic_opd".$ID2;
    }
    else
    {
        $dbopd2 = "clinic_opd".( 1000 + $ID2 - ( $ID2 % 1000 ));
    }
    
    $linkopdx1 = mysqli_connect(DB_HOST, DB_USER, DB_PASS, $dbopd1);
    $linkopdx2 = mysqli_connect(DB_HOST, DB_USER, DB_PASS, $dbopd2);
    mysqli_set_charset($linkopdx1, "utf8mb4");
    mysqli_set_charset($linkopdx2, "utf8mb4");
    mysqli_query($linkopd, "TRUNCATE TABLE tempcopy;");

    // Copy data from both tables to tempcopy
    $from_tb1 = mysqli_query($linkopdx1, "SELECT * FROM $table1 " );
    while($row = mysqli_fetch_array($from_tb1))
    {
       $ccp = mysqli_real_escape_string($linkopdx, $row['ccp']);
       $dofhis = mysqli_real_escape_string($linkopdx, $row['dofhis']);
       $phex = mysqli_real_escape_string($linkopdx, $row['phex']);
       $ddx = mysqli_real_escape_string($linkopdx, $row['ddx']);
       $inform = mysqli_real_escape_string($linkopdx, $row['inform']);
       $obsandpgnote = mysqli_real_escape_string($linkopdx, $row['obsandpgnote']);
      
       $sql_insert = "
       INSERT INTO `tempcopy` 
       (`date`, `ccp`, `dofhis`, `phex`, `ddx`, `inform`, `idtr1`, `tr1`, `trv1`, `tr1o1`, `tr1o1v`, `tr1o2`, `tr1o2v`, `tr1o3`, `tr1o3v`, `tr1o4`, `tr1o4v`, `trby1`, `idtr2`, `tr2`, `trv2`, `tr2o1`, `tr2o1v`, `tr2o2`, `tr2o2v`, `tr2o3`, `tr2o3v`, `tr2o4`, `tr2o4v`, `trby2`, `idtr3`, `tr3`, `trv3`, `tr3o1`, `tr3o1v`, `tr3o2`, `tr3o2v`, `tr3o3`, `tr3o3v`, `tr3o4`, `tr3o4v`, `trby3`, `idtr4`, `tr4`, `trv4`, `tr4o1`, `tr4o1v`, `tr4o2`, `tr4o2v`, `tr4o3`, `tr4o3v`, `tr4o4`, `tr4o4v`, `trby4`, `idrx1`, `rx1`, `rxg1`, `rx1uses`, `rx1v`, `rxby1`, `idrx2`, `rx2`, `rxg2`, `rx2uses`, `rx2v`, `rxby2`, `idrx3`, `rx3`, `rxg3`, `rx3uses`, `rx3v`, `rxby3`, `idrx4`, `rx4`, `rxg4`, `rx4uses`, `rx4v`, `rxby4`, `idrx5`, `rx5`, `rxg5`, `rx5uses`, `rx5v`, `rxby5`, `idrx6`, `rx6`, `rxg6`, `rx6uses`, `rx6v`, `rxby6`, `idrx7`, `rx7`, `rxg7`, `rx7uses`, `rx7v`, `rxby7`, `idrx8`, `rx8`, `rxg8`, `rx8uses`, `rx8v`, `rxby8`, `idrx9`, `rx9`, `rxg9`, `rx9uses`, `rx9v`, `rxby9`, `idrx10`, `rx10`, `rxg10`, `rx10uses`, `rx10v`, `rxby10`, `idrx11`, `rx11`, `rxg11`, `rx11uses`, `rx11v`, `rxby11`, `idrx12`, `rx12`, `rxg12`, `rx12uses`, `rx12v`, `rxby12`, `idrx13`, `rx13`, `rxg13`, `rx13uses`, `rx13v`, `rxby13`, `idrx14`, `rx14`, `rxg14`, `rx14uses`, `rx14v`, `rxby14`, `disprxby`, `temp`, `weight`, `height`, `bpsys`, `bpdia`, `hr`, `rr`, `labid`, `labresult`, `fup`, `doctor`, `dtlc`, `chronicill`, `drugallergy`, `concurdrug`, `obsandpgnote`, `clinic`) 
       VALUES
       ( '$row[date]', '$ccp', '$dofhis', '$phex', '$ddx', '$inform', '$row[idtr1]', '$row[tr1]', '$row[trv1]', '$row[tr1o1]', '$row[tr1o1v]', '$row[tr1o2]', '$row[tr1o2v]', '$row[tr1o3]', '$row[tr1o3v]', '$row[tr1o4]', '$row[tr1o4v]', '$row[trby1]', '$row[idtr2]', '$row[tr2]', '$row[trv2]', '$row[tr2o1]', '$row[tr2o1v]', '$row[tr2o2]', '$row[tr2o2v]', '$row[tr2o3]', '$row[tr2o3v]', '$row[tr2o4]', '$row[tr2o4v]', '$row[trby2]', '$row[idtr3]', '$row[tr3]', '$row[trv3]', '$row[tr3o1]', '$row[tr3o1v]', '$row[tr3o2]', '$row[tr3o2v]', '$row[tr3o3]', '$row[tr3o3v]', '$row[tr3o4]', '$row[tr3o4v]', '$row[trby3]', '$row[idtr4]', '$row[tr4]', '$row[trv4]', '$row[tr4o1]', '$row[tr4o1v]', '$row[tr4o2]', '$row[tr4o2v]', '$row[tr4o3]', '$row[tr4o3v]', '$row[tr4o4]', '$row[tr4o4v]', '$row[trby4]', '$row[idrx1]', '$row[rx1]', '$row[rxg1]', '$row[rx1uses]', '$row[rx1v]', '$row[rxby1]', '$row[idrx2]', '$row[rx2]', '$row[rxg2]', '$row[rx2uses]', '$row[rx2v]', '$row[rxby2]', '$row[idrx3]', '$row[rx3]', '$row[rxg3]', '$row[rx3uses]', '$row[rx3v]', '$row[rxby3]', '$row[idrx4]', '$row[rx4]', '$row[rxg4]', '$row[rx4uses]', '$row[rx4v]', '$row[rxby4]', '$row[idrx5]', '$row[rx5]', '$row[rxg5]', '$row[rx5uses]', '$row[rx5v]', '$row[rxby5]', '$row[idrx6]', '$row[rx6]', '$row[rxg6]', '$row[rx6uses]', '$row[rx6v]', '$row[rxby6]', '$row[idrx7]', '$row[rx7]', '$row[rxg7]', '$row[rx7uses]', '$row[rx7v]', '$row[rxby7]', '$row[idrx8]', '$row[rx8]', '$row[rxg8]', '$row[rx8uses]', '$row[rx8v]', '$row[rxby8]', '$row[idrx9]', '$row[rx9]', '$row[rxg9]', '$row[rx9uses]', '$row[rx9v]', '$row[rxby9]', '$row[idrx10]', '$row[rx10]', '$row[rxg10]', '$row[rx10uses]', '$row[rx10v]', '$row[rxby10]', '$row[idrx11]', '$row[rx11]', '$row[rxg11]', '$row[rx11uses]', '$row[rx11v]', '$row[rxby11]', '$row[idrx12]', '$row[rx12]', '$row[rxg12]', '$row[rx12uses]', '$row[rx12v]', '$row[rxby12]', '$row[idrx13]', '$row[rx13]', '$row[rxg13]', '$row[rx13uses]', '$row[rx13v]', '$row[rxby13]', '$row[idrx14]', '$row[rx14]', '$row[rxg14]', '$row[rx14uses]', '$row[rx14v]', '$row[rxby14]', '$row[disprxby]', '$row[temp]', '$row[weight]', '$row[height]', '$row[bpsys]', '$row[bpdia]', '$row[hr]', '$row[rr]', '$row[labid]', '$row[labresult]', '$row[fup]', '$row[doctor]', '$row[dtlc]', '$row[chronicill]', '$row[drugallergy]', '$row[concurdrug]', '$obsandpgnote', '$row[clinic]');
       ";
        mysqli_query($linkopd, $sql_insert) or die("Create Failed:" . mysqli_error($linkopd));
    }
    
    $from_tb2 = mysqli_query($linkopdx2, "SELECT * FROM $table2 " );
    while($row = mysqli_fetch_array($from_tb2))
    {
       $ccp = mysqli_real_escape_string($linkopdx, $row['ccp']);
       $dofhis = mysqli_real_escape_string($linkopdx, $row['dofhis']);
       $phex = mysqli_real_escape_string($linkopdx, $row['phex']);
       $ddx = mysqli_real_escape_string($linkopdx, $row['ddx']);
       $inform = mysqli_real_escape_string($linkopdx, $row['inform']);
       $obsandpgnote = mysqli_real_escape_string($linkopdx, $row['obsandpgnote']);
       
       $sql_insert = "
       INSERT INTO `tempcopy` 
       (`date`, `ccp`, `dofhis`, `phex`, `ddx`, `inform`, `idtr1`, `tr1`, `trv1`, `tr1o1`, `tr1o1v`, `tr1o2`, `tr1o2v`, `tr1o3`, `tr1o3v`, `tr1o4`, `tr1o4v`, `trby1`, `idtr2`, `tr2`, `trv2`, `tr2o1`, `tr2o1v`, `tr2o2`, `tr2o2v`, `tr2o3`, `tr2o3v`, `tr2o4`, `tr2o4v`, `trby2`, `idtr3`, `tr3`, `trv3`, `tr3o1`, `tr3o1v`, `tr3o2`, `tr3o2v`, `tr3o3`, `tr3o3v`, `tr3o4`, `tr3o4v`, `trby3`, `idtr4`, `tr4`, `trv4`, `tr4o1`, `tr4o1v`, `tr4o2`, `tr4o2v`, `tr4o3`, `tr4o3v`, `tr4o4`, `tr4o4v`, `trby4`, `idrx1`, `rx1`, `rxg1`, `rx1uses`, `rx1v`, `rxby1`, `idrx2`, `rx2`, `rxg2`, `rx2uses`, `rx2v`, `rxby2`, `idrx3`, `rx3`, `rxg3`, `rx3uses`, `rx3v`, `rxby3`, `idrx4`, `rx4`, `rxg4`, `rx4uses`, `rx4v`, `rxby4`, `idrx5`, `rx5`, `rxg5`, `rx5uses`, `rx5v`, `rxby5`, `idrx6`, `rx6`, `rxg6`, `rx6uses`, `rx6v`, `rxby6`, `idrx7`, `rx7`, `rxg7`, `rx7uses`, `rx7v`, `rxby7`, `idrx8`, `rx8`, `rxg8`, `rx8uses`, `rx8v`, `rxby8`, `idrx9`, `rx9`, `rxg9`, `rx9uses`, `rx9v`, `rxby9`, `idrx10`, `rx10`, `rxg10`, `rx10uses`, `rx10v`, `rxby10`, `idrx11`, `rx11`, `rxg11`, `rx11uses`, `rx11v`, `rxby11`, `idrx12`, `rx12`, `rxg12`, `rx12uses`, `rx12v`, `rxby12`, `idrx13`, `rx13`, `rxg13`, `rx13uses`, `rx13v`, `rxby13`, `idrx14`, `rx14`, `rxg14`, `rx14uses`, `rx14v`, `rxby14`, `disprxby`, `temp`, `weight`, `height`, `bpsys`, `bpdia`, `hr`, `rr`, `labid`, `labresult`, `fup`, `doctor`, `dtlc`, `chronicill`, `drugallergy`, `concurdrug`, `obsandpgnote`, `clinic`) 
       VALUES
       ( '$row[date]', '$ccp', '$dofhis', '$phex', '$ddx', '$inform', '$row[idtr1]', '$row[tr1]', '$row[trv1]', '$row[tr1o1]', '$row[tr1o1v]', '$row[tr1o2]', '$row[tr1o2v]', '$row[tr1o3]', '$row[tr1o3v]', '$row[tr1o4]', '$row[tr1o4v]', '$row[trby1]', '$row[idtr2]', '$row[tr2]', '$row[trv2]', '$row[tr2o1]', '$row[tr2o1v]', '$row[tr2o2]', '$row[tr2o2v]', '$row[tr2o3]', '$row[tr2o3v]', '$row[tr2o4]', '$row[tr2o4v]', '$row[trby2]', '$row[idtr3]', '$row[tr3]', '$row[trv3]', '$row[tr3o1]', '$row[tr3o1v]', '$row[tr3o2]', '$row[tr3o2v]', '$row[tr3o3]', '$row[tr3o3v]', '$row[tr3o4]', '$row[tr3o4v]', '$row[trby3]', '$row[idtr4]', '$row[tr4]', '$row[trv4]', '$row[tr4o1]', '$row[tr4o1v]', '$row[tr4o2]', '$row[tr4o2v]', '$row[tr4o3]', '$row[tr4o3v]', '$row[tr4o4]', '$row[tr4o4v]', '$row[trby4]', '$row[idrx1]', '$row[rx1]', '$row[rxg1]', '$row[rx1uses]', '$row[rx1v]', '$row[rxby1]', '$row[idrx2]', '$row[rx2]', '$row[rxg2]', '$row[rx2uses]', '$row[rx2v]', '$row[rxby2]', '$row[idrx3]', '$row[rx3]', '$row[rxg3]', '$row[rx3uses]', '$row[rx3v]', '$row[rxby3]', '$row[idrx4]', '$row[rx4]', '$row[rxg4]', '$row[rx4uses]', '$row[rx4v]', '$row[rxby4]', '$row[idrx5]', '$row[rx5]', '$row[rxg5]', '$row[rx5uses]', '$row[rx5v]', '$row[rxby5]', '$row[idrx6]', '$row[rx6]', '$row[rxg6]', '$row[rx6uses]', '$row[rx6v]', '$row[rxby6]', '$row[idrx7]', '$row[rx7]', '$row[rxg7]', '$row[rx7uses]', '$row[rx7v]', '$row[rxby7]', '$row[idrx8]', '$row[rx8]', '$row[rxg8]', '$row[rx8uses]', '$row[rx8v]', '$row[rxby8]', '$row[idrx9]', '$row[rx9]', '$row[rxg9]', '$row[rx9uses]', '$row[rx9v]', '$row[rxby9]', '$row[idrx10]', '$row[rx10]', '$row[rxg10]', '$row[rx10uses]', '$row[rx10v]', '$row[rxby10]', '$row[idrx11]', '$row[rx11]', '$row[rxg11]', '$row[rx11uses]', '$row[rx11v]', '$row[rxby11]', '$row[idrx12]', '$row[rx12]', '$row[rxg12]', '$row[rx12uses]', '$row[rx12v]', '$row[rxby12]', '$row[idrx13]', '$row[rx13]', '$row[rxg13]', '$row[rx13uses]', '$row[rx13v]', '$row[rxby13]', '$row[idrx14]', '$row[rx14]', '$row[rxg14]', '$row[rx14uses]', '$row[rx14v]', '$row[rxby14]', '$row[disprxby]', '$row[temp]', '$row[weight]', '$row[height]', '$row[bpsys]', '$row[bpdia]', '$row[hr]', '$row[rr]', '$row[labid]', '$row[labresult]', '$row[fup]', '$row[doctor]', '$row[dtlc]', '$row[chronicill]', '$row[drugallergy]', '$row[concurdrug]', '$obsandpgnote', '$row[clinic]');
       ";
        mysqli_query($linkopd, $sql_insert) or die("Create Failed:" . mysqli_error($linkopd));
    }
    
    // Drop Table1 and recreate
    mysqli_query($linkopdx1, "DROP TABLE $table1;");
    
    $_SESSION['Patient_id']=$ID1;
    
    include '../../libs/pt_table.php';
    
    // now drop table2
    mysqli_query($linkopdx2, "DROP TABLE $table2;");
    
    // Now insert sorted data by date to pt_table
    $tmpsort = "SELECT * FROM `tempcopy` ORDER BY `tempcopy`.`date` ASC ";
    $tmptodest = mysqli_query($linkopd, $tmpsort);
    while($row = mysqli_fetch_array($tmptodest))
    {
       $ccp = mysqli_real_escape_string($linkopdx, $row['ccp']);
       $dofhis = mysqli_real_escape_string($linkopdx, $row['dofhis']);
       $phex = mysqli_real_escape_string($linkopdx, $row['phex']);
       $ddx = mysqli_real_escape_string($linkopdx, $row['ddx']);
       $inform = mysqli_real_escape_string($linkopdx, $row['inform']);
       $obsandpgnote = mysqli_real_escape_string($linkopdx, $row['obsandpgnote']);
       
       $sql_insert = "
       INSERT INTO `$table1` 
       (`date`, `ccp`, `dofhis`, `phex`, `ddx`, `inform`, `idtr1`, `tr1`, `trv1`, `tr1o1`, `tr1o1v`, `tr1o2`, `tr1o2v`, `tr1o3`, `tr1o3v`, `tr1o4`, `tr1o4v`, `trby1`, `idtr2`, `tr2`, `trv2`, `tr2o1`, `tr2o1v`, `tr2o2`, `tr2o2v`, `tr2o3`, `tr2o3v`, `tr2o4`, `tr2o4v`, `trby2`, `idtr3`, `tr3`, `trv3`, `tr3o1`, `tr3o1v`, `tr3o2`, `tr3o2v`, `tr3o3`, `tr3o3v`, `tr3o4`, `tr3o4v`, `trby3`, `idtr4`, `tr4`, `trv4`, `tr4o1`, `tr4o1v`, `tr4o2`, `tr4o2v`, `tr4o3`, `tr4o3v`, `tr4o4`, `tr4o4v`, `trby4`, `idrx1`, `rx1`, `rxg1`, `rx1uses`, `rx1v`, `rxby1`, `idrx2`, `rx2`, `rxg2`, `rx2uses`, `rx2v`, `rxby2`, `idrx3`, `rx3`, `rxg3`, `rx3uses`, `rx3v`, `rxby3`, `idrx4`, `rx4`, `rxg4`, `rx4uses`, `rx4v`, `rxby4`, `idrx5`, `rx5`, `rxg5`, `rx5uses`, `rx5v`, `rxby5`, `idrx6`, `rx6`, `rxg6`, `rx6uses`, `rx6v`, `rxby6`, `idrx7`, `rx7`, `rxg7`, `rx7uses`, `rx7v`, `rxby7`, `idrx8`, `rx8`, `rxg8`, `rx8uses`, `rx8v`, `rxby8`, `idrx9`, `rx9`, `rxg9`, `rx9uses`, `rx9v`, `rxby9`, `idrx10`, `rx10`, `rxg10`, `rx10uses`, `rx10v`, `rxby10`, `idrx11`, `rx11`, `rxg11`, `rx11uses`, `rx11v`, `rxby11`, `idrx12`, `rx12`, `rxg12`, `rx12uses`, `rx12v`, `rxby12`, `idrx13`, `rx13`, `rxg13`, `rx13uses`, `rx13v`, `rxby13`, `idrx14`, `rx14`, `rxg14`, `rx14uses`, `rx14v`, `rxby14`, `disprxby`, `temp`, `weight`, `height`, `bpsys`, `bpdia`, `hr`, `rr`, `labid`, `labresult`, `fup`, `doctor`, `dtlc`, `chronicill`, `drugallergy`, `concurdrug`, `obsandpgnote`, `clinic`) 
       VALUES
       ( '$row[date]', '$ccp', '$dofhis', '$phex', '$ddx', '$inform', '$row[idtr1]', '$row[tr1]', '$row[trv1]', '$row[tr1o1]', '$row[tr1o1v]', '$row[tr1o2]', '$row[tr1o2v]', '$row[tr1o3]', '$row[tr1o3v]', '$row[tr1o4]', '$row[tr1o4v]', '$row[trby1]', '$row[idtr2]', '$row[tr2]', '$row[trv2]', '$row[tr2o1]', '$row[tr2o1v]', '$row[tr2o2]', '$row[tr2o2v]', '$row[tr2o3]', '$row[tr2o3v]', '$row[tr2o4]', '$row[tr2o4v]', '$row[trby2]', '$row[idtr3]', '$row[tr3]', '$row[trv3]', '$row[tr3o1]', '$row[tr3o1v]', '$row[tr3o2]', '$row[tr3o2v]', '$row[tr3o3]', '$row[tr3o3v]', '$row[tr3o4]', '$row[tr3o4v]', '$row[trby3]', '$row[idtr4]', '$row[tr4]', '$row[trv4]', '$row[tr4o1]', '$row[tr4o1v]', '$row[tr4o2]', '$row[tr4o2v]', '$row[tr4o3]', '$row[tr4o3v]', '$row[tr4o4]', '$row[tr4o4v]', '$row[trby4]', '$row[idrx1]', '$row[rx1]', '$row[rxg1]', '$row[rx1uses]', '$row[rx1v]', '$row[rxby1]', '$row[idrx2]', '$row[rx2]', '$row[rxg2]', '$row[rx2uses]', '$row[rx2v]', '$row[rxby2]', '$row[idrx3]', '$row[rx3]', '$row[rxg3]', '$row[rx3uses]', '$row[rx3v]', '$row[rxby3]', '$row[idrx4]', '$row[rx4]', '$row[rxg4]', '$row[rx4uses]', '$row[rx4v]', '$row[rxby4]', '$row[idrx5]', '$row[rx5]', '$row[rxg5]', '$row[rx5uses]', '$row[rx5v]', '$row[rxby5]', '$row[idrx6]', '$row[rx6]', '$row[rxg6]', '$row[rx6uses]', '$row[rx6v]', '$row[rxby6]', '$row[idrx7]', '$row[rx7]', '$row[rxg7]', '$row[rx7uses]', '$row[rx7v]', '$row[rxby7]', '$row[idrx8]', '$row[rx8]', '$row[rxg8]', '$row[rx8uses]', '$row[rx8v]', '$row[rxby8]', '$row[idrx9]', '$row[rx9]', '$row[rxg9]', '$row[rx9uses]', '$row[rx9v]', '$row[rxby9]', '$row[idrx10]', '$row[rx10]', '$row[rxg10]', '$row[rx10uses]', '$row[rx10v]', '$row[rxby10]', '$row[idrx11]', '$row[rx11]', '$row[rxg11]', '$row[rx11uses]', '$row[rx11v]', '$row[rxby11]', '$row[idrx12]', '$row[rx12]', '$row[rxg12]', '$row[rx12uses]', '$row[rx12v]', '$row[rxby12]', '$row[idrx13]', '$row[rx13]', '$row[rxg13]', '$row[rx13uses]', '$row[rx13v]', '$row[rxby13]', '$row[idrx14]', '$row[rx14]', '$row[rxg14]', '$row[rx14uses]', '$row[rx14v]', '$row[rxby14]', '$row[disprxby]', '$row[temp]', '$row[weight]', '$row[height]', '$row[bpsys]', '$row[bpdia]', '$row[hr]', '$row[rr]', '$row[labid]', '$row[labresult]', '$row[fup]', '$row[doctor]', '$row[dtlc]', '$row[chronicill]', '$row[drugallergy]', '$row[concurdrug]', '$obsandpgnote', '$row[clinic]');
       ";

        mysqli_query($linkopdx1, $sql_insert) or die("Create Failed: " . mysqli_error($linkopdx1));
    }
    
    // now update sell_account
    $ctmacno = 11000000 + $ID1;
    mysqli_query($link, "UPDATE `sell_account` SET `ctmid` = '$ID1', `ctmacno` = '$ctmacno' WHERE `ctmid` = '$ID2';");
    
    //delete record from patient_id table
    mysqli_query($linkopd, "DELETE FROM `patient_id` WHERE `id` = $ID2 ;");
    if(!empty($ctzid))
    {
        mysqli_query($linkopd,  "update `patient_id` set `ctz_id`= '$ctzid' where `id`='$ID1'") or die(mysqli_error($linkopd));
    }

    $msg = "Finish! รวมข้อมูลเสร็จ ผู้ป่วยมี ID = ".$ID1;
}

$title = "::Registration::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';

?>
<html>
<head>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td width="160" valign="top"><div class="pos_l_fix">
<?php 

if (isset($_SESSION['user_id']))
{
    include 'registermenu.php';
} 
?></div>
</td><td haligh="center">
<?php
	  if(!empty($msg))
	  {
        echo "<div class=\"msg\">";
        foreach ($msg as $e) {echo "$e <br>";}
        echo "</div>";	
	  }

if($_POST['search'] =='ค้นหา')
{
    echo "<div style=\"text-align: center;\"><a HREF=\"combine_ptdatatable.php\" ><input value=\"ค้นหาใหม่\" type=\"submit\"></a></div>";
}
?>
<h3 class="titlehdr">ประวัติผู้ป่วยที่จะ รวมข้อมูล</h3>
<form action="combine_ptdatatable.php" method="post" name="searchForm" id="searchForm">
<?php

if($_POST['search'] =='')
{
?>
<div style="text-align: center;">
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="forms">
<tr>
    <td width="50%" valign="top">เลขทะเบียน:<input maxlength="5" size="7" name="ptid1"></td>
    <td width="50%" valign="top">เลขทะเบียน:<input maxlength="5" size="7" name="ptid2"></td>
</tr>
</table>
<br>
<input tabindex="5" value="ค้นหา" name="search" type="submit">
</div>
<?php
}
echo "<div style='text-align: center;'>";
if($_POST['search'] == 'ค้นหา')
{
    if($_POST['ptid1'] == $_POST['ptid2'])
    {
        echo "<div style='background-color:rgba(255,125,0,1); display:inline-block;'>Same ID Please Check again!</div>";
        goto SameId;
    }
    $result1 = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id='$_POST[ptid1]' ");
    $result2 = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id='$_POST[ptid2]' ");

    while($row = mysqli_fetch_array($result1))
    {
    $id1 = $row['id'];
    $prefix1 = $row['prefix'];
    $fname1 = $row['fname'];
    $lname1 = $row['lname'];
    $ctz_id1 = $row['ctz_id'];
    $mobile1 = $row['mobile'];
    $avatar1 = $pdir. "pt_".$y.".jpg";
    $address1 = $row['address1']." ม.".$row['address2']." ต.".$row['address3']." อ.".$row['address4']." จ.".$row['address5'];
    } 
    while($row = mysqli_fetch_array($result2))
    {
    $id2 = $row['id'];
    $prefix2 = $row['prefix'];
    $fname2 = $row['fname'];
    $lname2 = $row['lname'];
    $ctz_id2 = $row['ctz_id'];
    $mobile2 = $row['mobile'];
    $avatar2 = $pdir. "pt_".$y.".jpg";
    $address2 = $row['address1']." ม.".$row['address2']." ต.".$row['address3']." อ.".$row['address4']." จ.".$row['address5'];
    } 


echo "<table border='1' class='TFtable' align='center'>";
echo "<tr><th>Item</th><th>PID: ".$id1."</th><th>PID: ".$id2."</th></tr>";
// keeps getting the next row until there are no more to get
echo "<tr><td>";
echo "Image";
echo "</td><td>";
echo "<div align='center'><img src=\"".$avatar1."\" width=\"100\" height=\"100\" /></div>";
echo "</td><td>";
echo "<div align='center'><img src=\"".$avatar2."\" width=\"100\" height=\"100\" /></div>";
echo "</td></tr>";
echo "<tr><td>";
echo "ยศ";
echo "</td><td>"; 
echo $prefix1;
echo "</td><td>"; 
echo $prefix2;
echo "</td></tr>"; 
echo "<tr><td>";
echo "ชื่อ";
echo "</td><td>"; 
echo $fname1;
echo "</td><td>"; 
echo $fname2;
echo "</td></tr>"; 
echo "<tr><td>";
echo "สกุล";
echo "</td><td>"; 
echo $lname1;
echo "</td><td>"; 
echo $lname2;
echo "</td></tr>"; 
echo "<tr><td>";
echo "เลขประชาชน";
echo "</td><td>"; 
echo $ctz_id1;
echo "<input type='radio' name='ctzid' value='".$ctz_id1."'>";
echo "</td><td>"; 
echo $ctz_id2;
echo "<input type='radio' name='ctzid' value='".$ctz_id2."'>";
echo "</td></tr>"; 
echo "<tr><td>";
echo "โทร";
echo "</td><td>"; 
echo $mobile1;
echo "</td><td>";
echo $mobile2;
echo "</td></tr>"; 
echo "<tr><td>";
echo "ที่อยู่";
echo "</td><td>";
echo $address1;
echo "</td><td>";
echo $address2;
echo "</td></tr>";
echo "</table>";
}

echo "<br><br>";
if($id1 && $id2){
echo "<input name='id1' value='".$id1."' type='hidden'>";
echo "<input name='id2' value='".$id2."' type='hidden'>";
echo "<input name='combine' value='CBD' type='submit'>";
}
SameId:
?>
</dev>
</form>
</td><td width="160"></td>
</tr>
</table>
</body>
</html>
