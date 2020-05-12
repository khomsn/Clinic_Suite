<?php 
include '../../config/dbc.php';
page_protect();
$err = array();

$filter = mysqli_query($link, "select * from rawmat ");		
while ($row = mysqli_fetch_array($filter))
{
    if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
}
$filter = mysqli_query($link, "select * from rawmat  WHERE `volume`<=0 ORDER BY `rmtype` ASC ,`rawcode` ASC ,`rawname` ASC");

if($_POST['register'] == 'ลบข้อมูล') 
{ 
	$id = $_POST['rawid'];
	$acno = mysqli_query($link, "SELECT * FROM rawmat WHERE id = $id");
	while ($row = mysqli_fetch_array($acno))
	{
		$dacno = $row['ac_no'];
		$vol = $row['volume'];
		// info for loging
		$rawcode = $row['rawcode'];
		$rawname = $row['rawname'];
		$size = $row['size'];
	}
	echo $vol;
	if($vol > '0')
	{
        $err[] = "จำนวนคงเหลือมากกว่า 0 ไม่สามารถลบได้";
	}

	if ($vol =='0')
	{

        $sql_del = "DELETE FROM rawmat WHERE id = $id";

        // Now delete drug information from rawmat table
        mysqli_query($link, $sql_del) or $err[]=("Deletion Failed:" . mysqli_error($link));

        $tid = "rawmat_".$id;
        $ftid = mysqli_query($link, "select * from $tid");
        while($row = mysqli_fetch_array($ftid))
        {
            if($row['price']!=0)
            {
                goto NextStep;
            }
        }
        //if have price don't drop table *use in account system ในการคำนวน กำไร
        $sql_drop ="DROP TABLE `$tid`" ;
        mysqli_query($link, $sql_drop) or $err[]=("Insertion Failed:" . mysqli_error($link));
        // Delete Ac No
        NextStep:
//        $sql_del = "DELETE FROM acnumber WHERE ac_no = $dacno";
        // Now remove drug information table
//        mysqli_query($link, $sql_del) or $err[]=("Insertion Failed:" . mysqli_error($link));
        //loging del item
        $sql_insert = "INSERT into `deleted_rm` 
                (`id`,`rawcode`,`rawname`, `size`, `ac_no`,`bystid` ) 
                VALUES 
                ('$id','$rawcode','$rawname','$size','$dacno','$_SESSION[staff_id]')";
        // Now insert 
        mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
        // go on to other step
        header("Location: rawmatdel.php"); 
	} 
}

$title = "::ลบรายการ RawMat::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="160" valign="top"><div class="pos_l_fix">
		<?php 
			if (isset($_SESSION['user_id']))
			{
				include 'rawmatmenu.php';
			} 
		?></div>
    </td><td>
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
    <h3 class="titlehdr">ลบ ทะเบียน RawMat</h3>
    <form method="post" action="rawmatdel.php" name="regForm" id="regForm">
        <table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
            <tr><td style="vertical-align: middle; ">
                <div style="text-align: center;">
                <?php
                    echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
                    echo "<tr><th>เลือก</th><th>Code</th><th>ชื่อ</th><th>ขนาด</th><th>คงคลัง</th><th>Type</th></tr>";
                    while($row = mysqli_fetch_array($filter))
                        {
                            // Print out the contents of each row into a table
                            echo "<tr><td>";
                            echo "<input type=\"radio\" name=\"rawid\" value=\"".$row['id']."\" />";
                            echo "</td><td>"; 
                            echo $row['rawcode'];
                            echo "</td><td>"; 
                            echo $row['rawname'];
                            echo "</td><td>"; 
                            echo $row['size'];
                            echo "</td><td>"; 
                            echo $row['volume'];
                            echo "</td><td>"; 
                            echo $row['rmtype'];
                            echo "</td></tr>";
                        } 
                    echo "</table>";
                ?></div>
            </td></tr>
            <tr><td><div style="text-align: center;"><input name="register" value="ลบข้อมูล" type="submit"></div></td></tr>
        </table>
    </form>
</td></tr>
</table>
</body></html>
