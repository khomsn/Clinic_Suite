<?php 
include '../../config/dbc.php';
page_protect();
unset($_SESSION['LLSName']);
unset($_SESSION['SLSName']);
unset($_SESSION['SetNum']);

$sql= "CREATE TABLE IF NOT EXISTS `lab` (
  `id` smallint(6) NOT NULL,
  `L_Name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `S_Name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `L_Set` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `L_specimen` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Lrunit` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `normal_r` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `r_min` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `r_max` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Linfo` text COLLATE utf8_unicode_ci,
  `price` smallint(6) NOT NULL DEFAULT '0',
  `volume` int(11) NOT NULL DEFAULT '0',
  `ltr` tinyint(1) NOT NULL DEFAULT '0',
  `colourcode` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `colourcode2` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

mysqli_query($link, $sql);

$sql_select =  mysqli_query($link, "SELECT * FROM `initdb` WHERE `refname`='lab' ORDER BY `id`" );
$tableversion = mysqli_num_rows($sql_select);
if(!$tableversion)
{
    $sql  = "INSERT INTO `initdb` (`refname`, `version`) VALUES (\'lab\', \'1\')";
    mysqli_query($link, $sql);
}


$title = "::Laboratory::";
include '../../main/header.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0">
<tr><td width=160px>
    <div class="pos_l_fix">
    <?php 
        /*********************** MYACCOUNT MENU ****************************
        This code shows my account menu only to logged in users. 
        Copy this code till END and place it in a new html or php where
        you want to show myaccount options. This is only visible to logged in users
        *******************************************************************/
        if (isset($_SESSION['user_id']))
        {
            include 'labmenu.php';
        } 
    /*******************************END**************************/
    ?>
    </div>
    </td><td>
        <h3 class="titlehdr">Lab Room</h3>
        <table width="100%" border="0">
        <tr><td width=300px><div class="forms">
        <p>การใช้งานระบบห้อง Lab<p>
        ระบบห้อง Lab ประกอบด้วย
        <ul>
        <li>รายการ Lab สามารถทำการ update Lab ได้</li>
        <li>เพิ่ม รายการ Lab รายตัว</li>
        <li>เพิ่ม รายการ ชุด Lab </li>
        <li>ลงผล Lab</li>
        <li>Lab Statistics</li>
        </ul></div></td><td></td></tr>
        </table>
   </td><td width=130px><div class="pos_r_fix"><?php include 'labrmenu.php';?></div>
</td></tr>
</table>
</body></html>
