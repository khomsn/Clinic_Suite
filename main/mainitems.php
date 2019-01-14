<table style="text-align: center; width: 100%; "border="0" cellpadding="0" cellspacing="0">
<tr><td>
<p><h3 class="hdrname"><?php 
while ($row_settings = mysqli_fetch_array($rs_settings))
{ 
    echo $_SESSION['clinic'] = $row_settings['name'];
    echo "<br>" ;
    $_SESSION['opdidoffset'] = $row_settings['opdidoffset'];
}
?>
</h3></p></td>
</tr>
<tr>
<td><a href="../main/opd/mycounter.php"><img style="border: 0px solid ; width: 120px; height: 120px;" alt="คลินิก" src="../image/clinic.gif"></a></td>
<td><a href="../main/pharma/pharmacy.php"><img style="border: 0px solid ; width: 120px; height: 120px;" alt="คลังยา และ ผลิตภัณฑ์" src="../image/drug.png"></a></td></tr>
<tr><td><a href="../main/lab/lab.php"><img	style="border: 0px solid ; width: 120px; height: 120px;" alt="Lab" src="../image/lab.jpg"></a></td><td style="text-align: center;">
<a href="../main/rawmat/rawmat.php"><img	style="border: 0px solid ; width: 120px; height: 120px;" alt="RawMat" src="../image/rawmat.jpeg"></a></td>
</tr>
<tr><td><a href="../main/account/accounting.php"><img	style="border: 0px solid ; width: 120px; height: 120px;" alt="บัญชีและการเงิน" src="../image/account.gif"></a></td>
<td><a href="../main/doc&report/report.php"><img	style="border: 0px solid ; width: 120px; height: 120px;" alt="รายงานต่างๆ" src="../image/report.jpeg"></a></td></tr>
</table>
