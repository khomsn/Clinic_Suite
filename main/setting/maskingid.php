<?php 
include '../../config/dbc.php';
page_protect();
$err = array();
$msg = array();

$sql = "

CREATE TABLE IF NOT EXISTS `maskid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drugid` int(11) NOT NULL,
  `dname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dgname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mask` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
  
";

mysqli_query($link, $sql);

$sql_select =  mysqli_query($link, "SELECT * FROM `initdb` WHERE `refname`='maskid' ORDER BY `id`" );
$tableversion = mysqli_num_rows($sql_select);
if(!$tableversion)
{
    $sql  = "INSERT INTO `initdb` (`refname`, `version`) VALUES (\'maskid\', \'1\')";
    mysqli_query($link, $sql);
}

$pin = mysqli_query($link, "select MAX(id) from maskid");
$rid = mysqli_fetch_array($pin);
$i = $rid[0];

if($_POST['addmore']=='+')
{
  
    // check for duplicated record
    $rs_duplicate = mysqli_query($link, "select count(*) as total from maskid where drugid='$_POST[drugid]'") or $err[]=(mysqli_error($link));
    list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
      $err[] = "คำเตือน: Drug ID =  ".$_POST['drugid']."  ได้ถูกกำหนดไว้ในบัญชีของท่านแล้ว.";
    }
    else
    {
    $result = mysqli_query($link, "SELECT * FROM drug_id WHERE id=$_POST[drugid] ");
    while ($row_settings = mysqli_fetch_array($result))
      {
      $dname = $row_settings['dname'];
      $dgname = $row_settings['dgname'];
      // assign insertion pattern
      $sql_insert = "INSERT into `maskid` (`drugid`,`dname`,`dgname`, `mask`) VALUES ('$_POST[drugid]','$dname','$dgname','1')";

      // Now insert Patient to "patient_id" table
      mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
      }
    }
}

for($j=1;$j<=$i;$j++)
{
  if($_POST['del'.$j] == 'ลบ')
  {
	  // Now Delete Patient from "pt_to_doc" table
	  mysqli_query($link, "DELETE FROM maskid WHERE id = '$j' ") or $err[]=(mysqli_error($link));
  }
 if(!is_null($_POST['mask'.$j]))
 {
    $maskj = $_POST['mask'.$j];
    $sql_insert = "UPDATE `maskid` SET `mask` = '$maskj' WHERE id = '$j'; ";
    mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
 }
}
if(!is_null($_POST['mask']))
{
    $sql_insert = "UPDATE `maskid` SET `mask` = '$_POST[mask]'; ";
    mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
}

$title = "::Drug used Masking::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
        <?php 
        if (isset($_SESSION['user_id'])) 
        {
        include '../../login/menu_ms.php';
        } 
        /*******************************END**************************/
        ?></div></td><td>
        <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
            <tr><td><?php 
            if(!empty($err))
            {
                echo "<div class=\"msg\">";
                foreach ($err as $e) { echo "* $e <br>";}
                echo "</div>";	
            }
            ?></td></tr>
            <tr><td width="100%" valign="top">
            <form action="maskingid.php" method="post" name="ptd" id="ptd">
                <h3 class="titlehdr">Maskin Drug Id in OPD Card<?php
/*                $result = mysqli_query($link, "SELECT * FROM maskid ORDER BY dgname ASC ");
                while ($row_settings = mysqli_fetch_array($result))
                {
                    $mask=$row_settings['mask'];
                }
*/
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "Masking All-ID <input type='radio' name='mask' id='m1' value='1'";
                echo "><label for='m1'>ON</label><input type='radio' name='mask' id='m0' value='0'";
                echo "><label for='m0'>OFF</label>";
                echo "</h3>";
                
                $result = mysqli_query($link, "SELECT * FROM maskid ORDER BY dgname ASC ");
                
                $n_of_row = mysqli_num_rows($result);
                echo "<table border='1' width=100% class='TFtable'>";
                echo "<tr><th>No</th><th  width=15%>Drug Id</th><th>Mask</th><th>Drug Name</th><th>Generig Name</th><th>+</th></tr>";
                // keeps getting the next row until there are no more to get
                        // Print out the contents of each row into a table
                        echo "<tr><th>";
                        echo "-";
                        echo "</th><th>";
                        echo "<input type=text name='drugid' autofocus>";
                        echo "</th><th>";
                        echo "</th><th>";
                        echo "</th><th>";
                        echo "</th><th>";
                        echo "<input type=submit name='addmore' value='+'>";
                        echo "</th></tr>";
                        //$j=1;
                
                while ($row_settings = mysqli_fetch_array($result))
                {
                        // Print out the contents of each row into a table
                        echo "<tr><th>";
                        echo "<input type=submit name='del".$row_settings['id']."' value='ลบ'>";
                        echo "</th><th>";
                        echo $row_settings['drugid'];
                        echo "</th><th>";
                        echo "<input type='radio' name='mask".$row_settings['id']."' id='mask".$row_settings['id']."_1' value='1'";
                        if ($row_settings['mask']=='1') echo "checked";
                        echo "><label for='mask".$row_settings['id']."_1'>ON</label><input type='radio' name='mask".$row_settings['id']."' id='mask".$row_settings['id']."_0'  value='0'";
                        if ($row_settings['mask']=='0') echo "checked";
                        echo "><label for='mask".$row_settings['id']."_0'>OFF</label>";
                        echo "</th><th>";
                        echo $row_settings['dname'];
                        echo "</th><th>";
                        echo $row_settings['dgname'];
                        echo "</th><th>+";
                        echo "</th></tr>";
echo "
<script type='text/javascript'>
$(document).ready(function() 
{ 
  
    $('input[name=mask".$row_settings['id']."]').change(function(){
        $('form').submit();
    });
});
</script>
";              
                }
                echo "</table>";
                ?>
            </form>
        </td></tr></table>
</td></tr></table>
</body>
</html>
<script type='text/javascript'>
$(document).ready(function() 
{ 
    $('input[name=mask]').change(function(){
        $('form').submit();
    });
});
</script>
