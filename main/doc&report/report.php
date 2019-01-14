<?php 
include '../../config/dbc.php';
page_protect();


$title = "::Document&Report::";
include '../../main/header.php';
include '../../main/bodyheader.php';
?>
<table width="100%">
<tr><td width=160px><div class="menur">
        <?php 
            if (isset($_SESSION['user_id']))
            {
                include 'reportmenu.php';
            } 
        ?>
    </div></td>
    <td><div class="forms"><h3 class="titlehdr">Report Document</h3>
        <p>รายงานทั่วไปของ คลินิก<p>
        ประกอบด้วย
        <ul>
        <li>รายงานการใช้ยาที่ต้องติดตามการใช้และทำรายงานยาควบคุม</li>
        <li>รายงานจำนวนผู้ป่วยในรอบปี แบ่งเป็น จำนวน Visit, จำนวนผู้ป่วยทั้งหมด, จำนวนผู้ป่วยใหม่</li>
        <li>รายงาน ราคา ซื้อ ยาและเวชภัณฑ์</li>
        </ul>
   </div></td>
    <td width=160px></td></tr>
</table>
</body></html>
