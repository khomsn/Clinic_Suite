<?php 
include '../../config/dbc.php';
page_protect();
$err = array();

$sql = "
CREATE TABLE IF NOT EXISTS `stockplace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `placeindex` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullplace` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

mysqli_query($link, $sql);

$sql_select =  mysqli_query($link, "SELECT * FROM `initdb` WHERE `refname`='stockplace' ORDER BY `id`" );
$tableversion = mysqli_num_rows($sql_select);
if(!$tableversion)
{
    $sql  = "INSERT INTO `initdb` (`refname`, `version`) VALUES (\'stockplace\', \'1\')";
    mysqli_query($link, $sql);
}

$err = array();
$msg = array();

$pin = mysqli_query($link, "select MAX(id) from stockplace");
$rid = mysqli_fetch_array($pin);
$i = $rid[0];

if(($_POST['addmore']=='+')  AND (ltrim($_POST['plindex']!== '')) AND (ltrim($_POST['fullplace']!== '')) )
{
  
// check for duplicated record
$rs_duplicate = mysqli_query($link, "select count(*) as total from stockplace where placeindex='$_POST[plindex]'") or $err[]=(mysqli_error($link));
list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
      //$err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
      $err[] = "คำเตือน: Shortcut  ".$_POST['plindex']."  ได้ถูกกำหนดไว้ในบัญชีของท่านแล้ว.";
    }
    else
    {
      // assign insertion pattern
      $sql_insert = "INSERT into `stockplace` (`placeindex`,`fullplace` ) VALUES ('$_POST[plindex]','$_POST[fullplace]')";

      // Now insert Patient to "stockplace" table
      mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
    }
}

for($j=1;$j<=$i;$j++)
{
  if($_POST['del'] == $j)
  {
	  // Now Delete Patient from "pt_to_doc" table
	  mysqli_query($link, "DELETE FROM stockplace WHERE id = '$j' ") or $err[]=(mysqli_error($link));
  }
}

$title = "::ตำแหน่งจัดเก็บ Stock::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" >
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td><div class="pos_l_fix">
        <?php 
        /*********************** MYACCOUNT MENU ****************************
        This code shows my account menu only to logged in users. 
        Copy this code till END and place it in a new html or php where
        you want to show myaccount options. This is only visible to logged in users
        *******************************************************************/
        if (isset($_SESSION['user_id'])) 
        {
        include '../../login/menu_ms.php';
        } 
        /*******************************END**************************/
        ?></div></td>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr><td width="10%"></td>
                <td width="90%" valign="top"><form action="stockplacement.php" method="post" name="ptd" id="ptd">
                <h3 class="titlehdr">ตำแหน่งจัดเก็บ Stock</h3>
                <?php 
                if(!empty($err))
                {
                    echo "<div class=\"msg\">";
                    foreach ($err as $e) { echo "* $e <br>";}
                    echo "</div>";	
                }
                $result = mysqli_query($link, "SELECT * FROM stockplace ORDER BY placeindex ASC ");
                
                $n_of_row = mysqli_num_rows($result);
                echo "<table border='1' width=100% class='TFtable' >";
                echo "<tr><th>No</th><th width=25%>Stack Index</th><th width=50%>ตำแหน่งจัดเก็บ</th><th>+/-</th></tr>";
                // keeps getting the next row until there are no more to get
                        // Print out the contents of each row into a table
                        echo "<tr><th>";
                        echo "-";
                        echo "</th><th>";
                        echo "<input type=text size=50% name='plindex' autofocus>";
                        echo "</th><th>";
                        echo "<input type=text size=80% name='fullplace'>";
                        echo "</th><th>";
                        echo "<input type=submit name='addmore' value='+'>";
                        echo "</th></tr>";
                        //$j=1;
                
                while ($row_settings = mysqli_fetch_array($result))
                {
                        // Print out the contents of each row into a table
                        echo "<tr><th>";
                        echo $row_settings['id'];
                        echo "</th><th>";
                        echo $row_settings['placeindex'];
                        echo "</th><th>";
                        echo $row_settings['fullplace'];
                        echo "</th><th>";
                        echo "<input type=submit name='del' value='".$row_settings['id']."'>";
                        echo "</th></tr>";
                        
                
                }
                echo "</table>";
                //////////////////////////
                ?>						
            </form>
        </td></tr></table>
</td></tr></table>
</body>
</html>
