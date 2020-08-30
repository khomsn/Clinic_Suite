<?php 
include '../../config/dbc.php';
page_protect();

$ptid = $_SESSION['patdesk'];
$Patient_id = $ptid;
include '../../libs/opdxconnection.php';

$pttable = "pt_".$ptid;

$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable ");
$maxid = mysqli_fetch_array($pin);

function rediagset() {
  // Start diag count
  //global $ddx;
  //$ddx = array();
  for($j=1;$j<=$_SESSION['DX'];$j++)
  {
    if(!empty($_POST['diag'.$j]))
    {
        $_SESSION['di'] = $_SESSION['di']+1;
        $m = $_SESSION['di'];
        $_SESSION['xdx'][$m] = $_POST['diag'.$j];
    }
  }
  /*
echo "di=".$_SESSION['di'];

  for ($m=1;$m<=$_SESSION['di'];$m++){
    echo $m."=";
    echo $_SESSION['xdx'][$m];
    echo " ";
  }
  print_r(array_unique($_SESSION['xdx'])); 
  $_SESSION['xdx1'] = array_unique($_SESSION['xdx'],SORT_STRING);
  print_r($_SESSION['xdx1']);
  */
}

if($maxid[0]!=$_SESSION['mrid'])
{
    
    $_SESSION['mrid'] = $maxid[0];
    $_SESSION['rid'] = $maxid[0]-1;
}

if ($_POST['todo'] == '<<' )
{
	$_SESSION['rid'] = $_SESSION['rid'] - 1;
	rediagset();
}
if ($_POST['todo'] == '>>' ) 
{
	$_SESSION['rid'] = $_SESSION['rid'] +1;
	rediagset();
}
if ($_POST['todo'] == 'Last' )
{
	$_SESSION['rid'] = $_SESSION['mrid']-1;
	rediagset();
}
if($_POST['todo']=='Diag')
{
  rediagset();
  if($_SESSION['di'] == 1){$_SESSION['ddx'] = $_SESSION['xdx'][1];}
  else {
        for($i=1;$i<=$_SESSION['di'];$i++)
        {
            $dxal = $dxal.$i."-".$_SESSION['xdx'][$i]." ";
        }
        $_SESSION['ddx'] = $dxal;
  }
  unset($_SESSION['xdx']);
  unset($_SESSION['di']);
  unset($_SESSION['DX']);
  unset($_SESSION['mrid']);
  unset($_SESSION['rid']);
echo '<script>window.opener.location.reload()</script>';
echo '<script>self.close()</script>';
}

$title = "::ประวัติ Diag::";
include '../../main/header.php';
?>
<script language="JavaScript" type="text/javascript" src="../../jscss/js/checkthemall.js"></script>
</head><body>
<form method="post" action="reddx.php" name="formMultipleCheckBox" id="formMultipleCheckBox">
<div style="text-align: right;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
<tr><td width =25% style="text-align: right;">
    <?php 
    if($_SESSION['rid'] > 1){ echo "<input type='submit' name='todo' value='<<'/>";} ?>
	</td><td width =25% style="text-align: center;">
	<?php if($_SESSION['rid']<($_SESSION['mrid']-1)) echo "<input type='submit' name='todo' value='>>' />"; ?>
	</td><td width =25% style="text-align: left;">
	      <?php echo "<input type='submit' name='todo' value='Last'/>";?>
	</td><td width =80 style="text-align: center;">
		<input type='submit' name='todo' value='Diag'/>
</td></tr>
</table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
<tr><td>
    <h5 class="titlehdr2">ประวัติ Diag ของ <?php
    $ptin = mysqli_query($linkopd, "select * from patient_id where id='$ptid' ");
    while ($row_settings = mysqli_fetch_array($ptin))
    {
        echo $row_settings['fname']; 
        echo "&nbsp;"; 
        echo $row_settings['lname'];
    }
    $pin = mysqli_query($linkopdx, "select * from $pttable WHERE id = '$_SESSION[rid]' ");
    while ($row_settings = mysqli_fetch_array($pin))
    {
        echo " &nbsp; เมื่อวันที่ ";
        $date = new DateTime($row_settings['date']);
        $sd = $date->format("d");
        $sm = $date->format("m");
        $sy = $date->format("Y");
        $bsy = $sy+543;
        echo $sd;
        echo " ";
        $m = $sm;
        switch ($m)
        {
            case 1:
            echo "มกราคม";
            break;
            case 2:
            echo "กุมภาพันธ์";
            break;
            case 3:
            echo "มีนาคม";
            break;
            case 4:
            echo "เมษายน";
            break;
            case 5:
            echo "พฤษภาคม";
            break;
            case 6:
            echo "มิถุนายน";
            break;
            case 7:
            echo "กรกฎาคม";
            break;
            case 8:
            echo "สิงหาคม";
            break;
            case 9:
            echo "กันยายน";
            break;
            case 10:
            echo "ตุลาคม";
            break;
            case 11:
            echo "พฤศจิกายน";
            break;
            case 12:
            echo "ธันวาคม";
            break;
        }
        echo " พ.ศ.";
        echo $bsy; //date("Y")+543;
        echo " ณ ".$row_settings['clinic'];
    }
    ?></h5>
    <form id="formMultipleCheckBox" name="formMultipleCheckBox">
        <table style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2"  class="forms">
        <tr><th width = 10><input name="checkAll" type="checkbox" id="checkAll" value="1" onclick="javascript:checkThemAll(this);" /></th><th>Items</th><th>Diag</th></tr>
        <?php 
            $ptin = mysqli_query($linkopdx, "select ddx from $pttable WHERE id = '$_SESSION[rid]' ");
            $oldddx = mysqli_fetch_array($ptin);
            $msg[] = $oldddx[0];
            if(!empty($oldddx[0]))
            {
                $n = substr_count($oldddx[0], '-');
                //$str = 'hypertext;language;programming';
                $charsl = preg_split('/-/', $oldddx[0]);
            }
            for($i=1;$i<=$n;$i++)
            {
                echo "<tr><th>";
                $check = 0;
                $dxm = rtrim($charsl[$i], "1234567890 ");
                for($m=0;$m<=$_SESSION['di'];$m++){
                    if ($dxm == $_SESSION['xdx'][$m]){
                        $check = 1; 
                        }
                }
                if(!$check){
                echo "<input type='checkbox' name='diag".$i."' id='checkBoxes' value=\"".rtrim($charsl[$i], "1234567890 ")."\">";
                }
                echo "</th><th>";
                echo $_SESSION['DX'] = $i;
                echo "</th><th>";
                echo rtrim($charsl[$i], "1234567890 ");
                echo "</th></tr>";
            }
            if(empty($n))
            {
                echo "<tr><th>";
                $check = 0;
                for($m=0;$m<=$_SESSION['di'];$m++){
                    if ($oldddx[0] == $_SESSION['xdx'][$m]){
                    $check = 1; 
                    }
                }
                if(!$check){
                echo "<input type='checkbox' name='diag1' id='checkBoxes' value=\"".$oldddx[0]."\">";
                }
                echo "</th><th>";
                echo $_SESSION['DX'] = 1;
                echo "</th><th>";
                echo $oldddx[0];
                echo "</th></tr>";
                
            }
        ?>
        </table>
    </form>
</td></tr>
</table>
</form>
</body>
</html>
