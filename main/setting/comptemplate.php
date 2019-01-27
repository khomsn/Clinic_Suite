<?php 
include '../../config/dbc.php';
page_protect();
$sql = "
CREATE TABLE IF NOT EXISTS `reccompany` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comdt` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

mysqli_query($link, $sql);

$err = array();
$msg = array();

$pin = mysqli_query($link, "select MAX(id) from reccompany");
$rid = mysqli_fetch_array($pin);
$i = $rid[0];

if(($_POST['addmore']=='+')  AND (ltrim($_POST['shortcut']!== '')) AND (ltrim($_POST['fulltext']!== '')) )
{
  
// check for duplicated record
$rs_duplicate = mysqli_query($link, "select count(*) as total from reccompany where comname='$_POST[shortcut]'") or $err[]=(mysqli_error($link));
list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
      //$err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
      $err[] = "คำเตือน: Shortcut  ".$_POST['shortcut']."  ได้ถูกกำหนดไว้ในบัญชีของท่านแล้ว.";
    }
    else
    {
      // assign insertion pattern
      $sql_insert = "INSERT into `reccompany` (`comname`,`comdt` ) VALUES ('$_POST[shortcut]','$_POST[fulltext]')";

      // Now insert Patient to "reccompany" table
      mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
    }
}

for($j=1;$j<=$i;$j++)
{
  if($_POST['del'] == $j)
  {
	  // Now Delete Patient from "pt_to_doc" table
	  mysqli_query($link, "DELETE FROM reccompany WHERE id = '$j' ") or $err[]=(mysqli_error($link));
  }
}

$title = "::รายชื่อบริษัท ใช้ในการออก ใบเสร็จรับเงิน ในนาม::";
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
                <td width="90%" valign="top"><form action="comptemplate.php" method="post" name="ptd" id="ptd">
                <h3 class="titlehdr">Short Cut template</h3>
                <?php 
                if(!empty($err))
                {
                    echo "<div class=\"msg\">";
                    foreach ($err as $e) { echo "* $e <br>";}
                    echo "</div>";	
                }
                $result = mysqli_query($link, "SELECT * FROM reccompany ORDER BY comname ASC ");
                
                $n_of_row = mysqli_num_rows($result);
                echo "<table border='1' width=100% class='TFtable' >";
                echo "<tr><th>No</th><th width=25%>Company</th><th width=50%>Full Text to print</th><th>+/-</th></tr>";
                // keeps getting the next row until there are no more to get
                        // Print out the contents of each row into a table
                        echo "<tr><th>";
                        echo "-";
                        echo "</th><th>";
                        echo "<input type=text size=50% name='shortcut' autofocus>";
                        echo "</th><th>";
                        echo "<input type=text size=80% name='fulltext'>";
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
                        echo $row_settings['comname'];
                        echo "</th><th>";
                        echo $row_settings['comdt'];
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
