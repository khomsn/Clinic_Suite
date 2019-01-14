<?php 
include '../../config/dbc.php';
page_protect();
$err = array();
$msg = array();

$doctemplate = "doctemplate_".$_SESSION['sflc'];

$sql = "CREATE TABLE IF NOT EXISTS `$doctemplate` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `scode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `textconv` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;";

mysqli_query($link, $sql);

$pin = mysqli_query($link, "select MAX(id) from $doctemplate");
$rid = mysqli_fetch_array($pin);
$i = $rid[0];

if(($_POST['addmore']=='+' ) AND (ltrim($_POST['shortcut']!== '')) AND (ltrim($_POST['fulltext']!== '')))
{
  
    // check for duplicated record
    $rs_duplicate = mysqli_query($link, "select count(*) as total from $doctemplate where scode='$_POST[shortcut]'") or die(mysqli_error($link));
    list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
      //$err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
      $err[] = "คำเตือน: Shortcut  ".$_POST['shortcut']."  ได้ถูกกำหนดไว้ในบัญชีของท่านแล้ว.";
    }
    else
    {
      // assign insertion pattern
      $sql_insert = "INSERT into `$doctemplate` (`scode`,`textconv` ) VALUES ('$_POST[shortcut]','$_POST[fulltext]')";

      // Now insert Patient to "patient_id" table
      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
    }
}

for($j=1;$j<=$i;$j++)
{
  if($_POST['del'.$j] == 'ลบ')
  {
	  // Now Delete Patient from "pt_to_doc" table
	  mysqli_query($link, "DELETE FROM $doctemplate WHERE id = '$j' ") or die(mysqli_error($link));
  }
}
$title = "::Shortcut for Drug Uses::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
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
        <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
            <tr><td width="100%" valign="top">
                <form action="ordertemplate.php" method="post" name="ptd" id="ptd">
                <h3 class="titlehdr">Shortcut for Drug uses in Prescription per Doctor</h3>
                <?php
                if(!empty($err))
                {
                    echo "<div class=\"msg\">";
                    foreach ($err as $e) {echo "* $e <br>";}
                    echo "</div>";
                }
                $result = mysqli_query($link, "SELECT * FROM $doctemplate ORDER BY scode ASC ");
                
                $n_of_row = mysqli_num_rows($result);
                echo "<table border='1' width=100% class='TFtable' >";
                echo "<tr><th>No</th><th width=25%>Short Cut</th><th width=50%>Full Text</th><th>+</th></tr>";
                echo "<tr><td>";
                echo "-";
                echo "</td><td>";
                echo "<input type=text size=50% name='shortcut' autofocus >";
                echo "</td><td>";
                echo "<input type=text size=80% name='fulltext'>";
                echo "</td><td>";
                echo "<input type=submit name='addmore' value='+'>";
                echo "</td></tr>";
                //$j=1;
            
                while ($row_settings = mysqli_fetch_array($result))
                {
                // Print out the contents of each row into a table
                echo "<tr><td>";
                echo "<input type=submit name='del".$row_settings['id']."' value='ลบ'>";
                echo "</td><td>";
                echo $row_settings['scode'];
                echo "</td><td>";
                echo $row_settings['textconv'];
                echo "</td><td>+";
                echo "</td></tr>";
                }
                echo "</table>";
            //////////////////////////
            ?>						
        </form>
        </td></tr></table>
    </td></tr>
</table>
</body>
</html>
