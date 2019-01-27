<?php 
include '../../config/dbc.php';
page_protect();
$err = array();
$msg = array();

$sql ="CREATE TABLE  IF NOT EXISTS `queuesystem` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `FromIp` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ToIp` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ptid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

mysqli_query($link, $sql);

$pin = mysqli_query($link, "select MAX(id) from queuesystem");
$rid = mysqli_fetch_array($pin);
$i = $rid[0];

if(($_POST['addmore']=='+' ) AND (ltrim($_POST['fromip']!== '')) AND (ltrim($_POST['toip']!== '')))
{
  
    // check for duplicated record
    $rs_duplicate = mysqli_query($link, "select count(*) as total from queuesystem where FromIp='$_POST[fromip]' and ToIp='$_POST[toip]' ") or $err[]=(mysqli_error($link));
    list($total) = mysqli_fetch_array($rs_duplicate);

    if ($total > 0)
    {
      //$err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
      $err[] = "คำเตือน: การเชื่อมต่อ ระหว่าง ".$_POST['fromip']." และ ".$_POST['toip']."  ได้ถูกกำหนดแล้ว.";
    }
    else
    {
      // assign insertion pattern
      $sql_insert = "INSERT into `queuesystem` (`FromIp`,`ToIp` ) VALUES ('$_POST[fromip]','$_POST[toip]')";

      // Now insert Patient to "patient_id" table
      mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
    }
}

for($j=1;$j<=$i;$j++)
{
  if($_POST['del'.$j] == 'ลบ')
  {
	  // Now Delete Patient from "pt_to_doc" table
	  mysqli_query($link, "DELETE FROM queuesystem WHERE id = '$j' ") or $err[]=(mysqli_error($link));
  }
}
$title = "::ระบบเรียกคนไข้::";
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
        include '../../login/menu_admam.php';
        } 
        /*******************************END**************************/
        ?></div></td>
        <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
            <tr><td width="100%" valign="top">
                <form action="netcommu.php" method="post" name="ptd" id="ptd">
                <h3 class="titlehdr">Communication between Pair of COM-IPs</h3>
                <?php
                if(!empty($err))
                {
                    echo "<div class=\"msg\">";
                    foreach ($err as $e) {echo "* $e <br>";}
                    echo "</div>";
                }
                $result = mysqli_query($link, "SELECT * FROM queuesystem ORDER BY FromIp ASC ");
                
                $n_of_row = mysqli_num_rows($result);
                echo "<table border='1' width=100% class='TFtable' >";
                echo "<tr><th>No</th><th width=25%>From IP</th><th width=50%>TO IP</th><th>+</th></tr>";
                echo "<tr><td>";
                echo "-";
                echo "</td><td>";
                echo "<input type=text size=50% name='fromip' autofocus >";
                echo "</td><td>";
                echo "<input type=text size=80% name='toip'>";
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
                echo $row_settings['FromIp'];
                echo "</td><td>";
                echo $row_settings['ToIp'];
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
