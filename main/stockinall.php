<?php 
include '../login/dbc.php';
page_protect();

if ($_SESSION['DG']<1)
{ 
  $i=1; 
  $_SESSION['DG']=1;
}

else $i = $_SESSION['DG'];

$i = $_SESSION['DG'];

if($_POST['add'] == 'เพิ่ม') 
{ 
  $_SESSION['supplier'] = $_POST['supplier'];
  $_SESSION['invoice'] = $_POST['inv_num'];
  
  if ($_SESSION['DG']<1) 
  {
  $_SESSION['DG']=1;
  $i = $_SESSION['DG'];
  }
  for($j=1;$j<=$i;$j++)
      {
      $_SESSION['drug'.$j]=$_POST['drug'.$j];
      $_SESSION['vol'.$j]=$_POST['vol'.$j];
      $_SESSION['buyprice'.$j]=$_POST['buyprice'.$j];
      //
	  $drug = $_SESSION['drug'.$j];
	  $drugid[$j] = strstr($drug, '-', true); 
	  //echo $drugid[$j];
	  //
	  $ptin = mysqli_query($link, "select * from drug_id where id='$drugid[$j]'");
	  while ($row2 = mysqli_fetch_array($ptin))
		  {
            $_SESSION['dgname'.$j]= $row2['dgname'];
            $_SESSION['size'.$j] =  $row2['size'];
            $_SESSION['dname'.$j]= $row2['dname'];
            $_SESSION['unit'.$j] = $row2['unit'];
			  
		  }
      //
      }
  if($_SESSION['DG']<30)
  {
  $_SESSION['DG'] = $_SESSION['DG']+1;
  $i = $_SESSION['DG'];
  }
//header("Location: stockinall.php");  
}
	
for($n=1;$n<=30;$n++)
{
  
  if($_POST['del'.$n]=='ลบ')
  {
 
    unset($_SESSION['vol'.$n]);
    unset($_SESSION['dgname'.$n]);
    unset($_SESSION['buyprice'.$n]);
 
   for($m=$n;$m<$i;$m++)
     {
      $n=$n+1;
      $_SESSION['drug'.$m]=$_SESSION['drug'.$n];
      $_SESSION['vol'.$m]=$_SESSION['vol'.$n];
      $_SESSION['buyprice'.$m]=$_SESSION['buyprice'.$n];
     }
          //
	for($j=1;$j<=$i;$j++)
	    {
	    //$_SESSION['drug'.$j]=$_POST['drug'.$j];
	    //
		$drug = $_SESSION['drug'.$j];
		$drugid[$j] = strstr($drug, '-', true); 
		//echo $drugid[$j];
		$ptin = mysqli_query($link, "select * from drug_id where id='$drugid[$j]'");
		while ($row2 = mysqli_fetch_array($ptin))
			{
                                $_SESSION['dname'.$j]= $row2['dname'];
				$_SESSION['unit'.$j] = $row2['unit'];
                                $_SESSION['dgname'.$j]= $row2['dgname'];
				$_SESSION['size'.$j] = $row2['size'];
			}
	    }
      //

     unset($_SESSION['drug'.$i]);
     unset($_SESSION['dgname'.$i]);
     unset($_SESSION['size'.$i]);
     
     $_SESSION['DG'] = $_SESSION['DG']-1;
     $i = $_SESSION['DG'];
     unset($_SESSION['dgname'.$i]);
     unset($_SESSION['size'.$i]);
     
    // header("Location: stockinall.php"); 
  }
  if ($_SESSION['DG']<1)
  { 
    $i=1; 
    $_SESSION['DG']=1;
    
  }
  
}

///new

if($_POST['doSave'] == 'Save')  
{
      $day = $_POST['day'];
      $month = $_POST['month'];
      $byear = $_POST['year'];
      $year = $byear - 543;

      // format date for mysql
      $bday = $year.'-'.$month.'-'.$day;
      
        for($a=1;$a<=30;$a++)
        {
            $_SESSION['drug'.$a]=$_POST['drug'.$a];
            $drug = $_SESSION['drug'.$a];
            $drugid[$a] = strstr($drug, '-', true); 
            $id = $drugid[$a];
            if(empty($id) or $id == 0) goto Noid; //check for blank input
            $buyvolume = $_POST['vol'.$a];
            if(empty($buyvolume) or $buyvolume == 0) goto Noid; //check for blank input
            $buyprice = $_POST['buyprice'.$a];
            $stock_in = mysqli_query($link, "select * from drug_id where id='$id' ");
            $drugtable = "drug_".$id;
            
                while ($row_settings = mysqli_fetch_array($stock_in))
                {
                        $volume = $row_settings['volume']; //get volume to update
                        $dacno = $row_settings['ac_no']; //get account no into stock 
                        $prod = $row_settings['prod'];
                }
          


                {// assign insertion pattern
                            $sql_insert = "INSERT into `$drugtable`	(`date`,`supplier`,`inv_num`, `volume`, `price`)
                                        VALUES  ('$bday','$_POST[supplier]','$_POST[inv_num]','$buyvolume','$buyprice')";
                }
                // Now insert Drug order information to "drug_#id" table
                mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));


                // Update drug_id at volume and buyprice.
                $upvol = $volume + $buyvolume;

                mysqli_query($link, "UPDATE drug_id SET `volume` = '$upvol' WHERE `id` = '$id'");	

                //for own product 
                if($prod == '1')
                {
                    if($buyprice==0) goto Next1;
                    
                    $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`bors`,`recordby`)
                                        VALUES  (now(),'$dacno','180000','สินค้าจากวัตถุดิบ','$buyprice','c','p','$_SESSION[user_id]')";
                    // Now insert Drug order information to "drug_#id" table
                    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
                }
                //for product not register in supplier and account


                Next1:

                if($prod != '1')
                {
                        //supplier tracking system
                        $supac = mysqli_query($link, "SELECT * FROM supplier WHERE name='$_POST[supplier]'");
                        while($rowac = mysqli_fetch_array($supac))
                        { $spid = $rowac['id'];}

                        // assign insertion pattern
                        $sql_insert = "INSERT into `sp_$spid`	(`date`,`inid`,`inv_num`, `price`, `payment`)
                                                    VALUES  ('$bday','$id','$_POST[inv_num]','$buyprice','$_POST[pay]')";
                        // Now insert Drug order information to "drug_#id" table
                        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

                        // accounting system
                        //$acc = mysqli_query($link, "SELECT ac_no FROM drug_id WHERE id = $id");
                        //while($rowac = mysqli_fetch_array($acc))
                        //{ $dacno = $rowac['ac_no'];}
                        if($buyprice!=0)
                            {
                                if ($_POST['pay'] == '1')
                                { 
                                        $sup_ac = $_POST['payby'];
                                        if($_POST['payby'] != 1001)//ค่าธรรมเนียม
                                        {
                                            if($_POST['free']!=0)
                                            {
                                                // assign insertion pattern
                                                $pacnum = $_POST['payby'] + 4000;
                                                $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`bors`,`recordby`)
                                                                    VALUES  (now(),'$pacnum','$_POST[payby]','ค่าธรรมเนียมการโอนเงิน','$_POST[free]','c','p','$_SESSION[user_id]')";
                                                // Now insert Drug order information to "drug_#id" table
                                                mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
                                            }
                                                
                                        }	
                                }
                                else 
                                {
                                        $supac = mysqli_query($link, "SELECT ac_no FROM supplier WHERE name='$_POST[supplier]'");
                                        while($rowac = mysqli_fetch_array($supac))
                                        {$sup_ac = $rowac['ac_no'];}
                                }
                                // assign insertion pattern
                                if($buyprice>0)
                                {
                                $detail ="ซื้อ ".$_SESSION['supplier'].' '.$_SESSION['invoice'];
                                $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`, `inv_num`, `price`,`type`,`bors`,`recordby`)
                                                    VALUES  (now(),'$dacno','$sup_ac','$detail','$_SESSION[invoice]','$buyprice','c','b','$_SESSION[user_id]')";
                                // Now insert Drug order information to "drug_#id" table
                                mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
                                }
                          }      

                }
            Noid:
        }
    // go on to other step
    //header("Location: stock.php"); 
    if(isset($_POST['doSave']))
    {
        for($i=1;$i<=30;$i++)
        {
        unset($_SESSION['drug'.$i]);
        unset($_SESSION['dgname'.$i]);
        unset($_SESSION['size'.$i]);
        unset($_SESSION['dname'.$i]);
        unset($_SESSION['unit'.$i]);
        unset($_SESSION['vol'.$i]);
        unset($_SESSION['buyprice'.$i]);
        }
    unset($_SESSION['DG']);
    unset($_SESSION['supplier']);
    unset($_SESSION['invoice']);
    
    echo  "<script type='text/javascript'>";
    echo "window.close();";
    echo "</script>";
    }

}

?>

<!DOCTYPE html>
<html>
<head>
<title>นำเข้ายาและผลิตภัณฑ์</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="../public/js/jquery.autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="../public/css/jquery.autocomplete.css" />
	<script language="JavaScript" type="text/javascript" src="../public/js/validate-1.5.5/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
include '../libs/autodrugin.php';
$formid = "inForm";
include '../libs/validate.php';
?>
</head>
<body>
<div id="content"><h3 class="titlehdr">นำเข้ายาและผลิตภัณฑ์: </h3>
<form name="ddx" method="post" action="stockinall.php" id="inForm">
<table width="90%" border="1" align="center" cellpadding="3" cellspacing="3" class="forms">
    <tr>
            <td>วันที่&nbsp;
                                                    <select name="day">
                                                    <option value="<?php echo (idate("d"));?>" selected><?php echo (idate("d"));?></option>
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
                                                    <option value="<?php echo (idate("m"));?>" selected><?php echo (idate("m"));?></option>
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
                                                    </select>
                                                    พ.ศ. <input name="year" size="5" maxlength="4" type="text" value="<?php echo (idate("Y")+543);?>"><br>
            </td>
            <td></td>
    </tr>
    <?php //------
    if($prod!='1')
    {
    ?>	
    <tr>
                    <td width=50% >บริษัท&nbsp;
                    <select name="supplier" class="required" >
                            <?php	
                                    $supplier = mysqli_query($link, "SELECT name FROM supplier");
                            ?>
                                            <?php while($sprow = mysqli_fetch_array($supplier))
                                            {
                                                    echo "<option value=\"";
                                                    echo $sprow['name'];
                                                    echo " \">";
                                                    echo $sprow['name']."</option>";
                                            }
                                                    if(!empty($_SESSION['supplier']))
                                                    {
                                                    echo "<option value=\"";
                                                    echo $_SESSION['supplier'];
                                                    echo " \"";
                                                    echo " selected";
                                                    echo ">";
                                                    echo $_SESSION['supplier']."</option>";
                                                    }
                                            ?>
                    </select>
            </td>
            <td width=50%>
            <?php 
            //for own product 
            if($prod != '1')
            {
            echo "ใบส่งของเลขที่&nbsp;<input name='inv_num' type='text' class='required' value=";
            echo $_SESSION['invoice'];
            echo ">";
            }
            ?><br>
            </td>
    </tr>
    <?php 
    }
    ?>
</table>



<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr>
    <td>
     <table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
      <tr><th>Order</th><th width="20%">Barcode/รหัสสินค้า</th><th width="30%">ชื่อสินค้า</th><th>ขนาด</th><th>จำนวน</th><th>ราคา</th><th>เพิ่ม</th><th>ลบ</th></tr>
	    <?php 
	    for($j=1;$j<=$i;$j++)
	    {
	    if($j==$i)
	    {
	    echo "<tr><td>".$j."</td><td><input name='drug".$j."' type=text id=drug".$j;
	    echo " value='".$_SESSION['drug'.$j]."' size=20% autofocus/></td>";
	    }
	    else
	    {
	    echo "<tr><td>".$j."</td><td><input name='drug".$j."' type=text id=drug".$j;
	    echo " value='".$_SESSION['drug'.$j]."' size=20%/></td>";
	    }
	    echo "<td>";
	    if(!empty($_SESSION['dname'.$j]))
	    echo $_SESSION['dname'.$j]."-".$_SESSION['dgname'.$j]."-".$_SESSION['size'.$j]."-".$_SESSION['unit'.$j];
	    echo "</td>";
	    echo "<td>";
	    echo $_SESSION['size'.$j];
	    echo "</td>";
	    echo "<td>";
	    echo "<input type='number' tabindex=".($j*2-1)." class='typenumber' name='vol".$j."' min=0 step=1 value=".$_SESSION['vol'.$j].">";
	    echo "</td>";
	    echo "<td>";
	    echo "<input type='number' tabindex=".($j*2)." class='typenumber' name='buyprice".$j."' min=0 step=0.01 value=".$_SESSION['buyprice'.$j].">";
	    echo "</td>";
	    echo "<td><input type=submit name='add' value='เพิ่ม'></td>";
	    echo "<td><input type=submit name='del".$j."' value='ลบ'></td>";
	    echo "</tr>";
	    }
	    ?>
      </table>
    </td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
    <tr><td><div style="text-align: center;">
    การชำระเงิน&nbsp;<input type="radio" tabindex="61" name="pay" value="1">จ่ายแล้ว<input type="radio" tabindex="62" name="pay" CHECKED value="0">ค้างจ่าย
    </div></td></tr>
    <tr><td><div style="text-align: center;">
                            ชำระโดย&nbsp;<select tabindex="63" name="payby">
                                                            <option value="1001" selected>เงินสด</option>
                                                            <?php //1002-1020 ธนาคาร
                                                            $acname = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no > 1001 AND ac_no <=1020");
                                                            while($row = mysqli_fetch_array($acname))
                                                            {
                                                                    echo "<option value='";
                                                                    echo $row['ac_no'];
                                                                    echo "'>";
                                                                    echo $row['name'];
                                                                    echo "</option>";
                                                            }	
                                                            ?>
                                                            </select>&nbsp;ค่าธรรมเนียมการโอน<input type="number" min=0 name="free" size="6" >
    </div></td></tr>
</table>
<p align="center"> 
<input name="doSave" type="submit" id="doSave" value="Save"  >
</p>

<!--end menu-->
</form></div>
</body>
</html>
