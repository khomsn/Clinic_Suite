<?php 
include '../config/dbc.php';
page_protect();
$msg = array();
//set start date for Program referense point start
if(empty($_SESSION['acstrdate']))
{
    $gddmin = mysqli_query($link, "SELECT `date` FROM `daily_account` WHERE id=1 ");
    $gddate = mysqli_fetch_array($gddmin);
    $gdm = $gddate[0];
    $gdm = date_create($gdm);
    $_SESSION['acstrdate'] = date_format($gdm, 'Y-m-d');
}

$sf_settings = mysqli_query($link, "select * from staff where ID = $_SESSION[staff_id]");
while ($sf = mysqli_fetch_array($sf_settings, MYSQLI_BOTH))
{
if($sf['gender']=="ชาย" and $sf['posit']=="แพทย์"){$prefix = "นายแพทย์ ";$eprefix = $sf['Eprefix'];}
if($sf['gender']=="หญิง" and $sf['posit']=="แพทย์"){$prefix = "แพทย์หญิง ";$eprefix = $sf['Eprefix'];}

$_SESSION['sfname'] = $prefix.$sf['F_Name']." ".$sf['L_Name'];
$_SESSION['Esfname']= $eprefix." ".$sf['EF_Name']." ".$sf['EL_Name'];
$_SESSION['sflc'] = $sf['license'];
}
$title = "::My Account::";
include '../main/header.php';
include '../main/bodyheader.php';
?>
<div class="pos_l_fix">
<?php 
/*********************** MYACCOUNT MENU ****************************
This code shows my account menu only to logged in users. 
Copy this code till END and place it in a new html or php where
you want to show myaccount options. This is only visible to logged in users
*******************************************************************/
if (isset($_SESSION['user_id'])) 
{
include 'menu.php';
}
/*******************************END**************************/
?>
</div>
<table border=0 style="width: 100%; ">
<tr><td style="text-align: center; width: 160px; "></td>
    <td>
      <h3 class="titlehdr">Welcome <?php echo $_SESSION['user_name'];?></h3>  
        <?php
//        if (isset($_GET['msg'])) {echo "<div class=\"error\">$_GET[msg]</div>";}
        $rs_settings = mysqli_query($link, "select * from parameter where id='1'");
        if($_SESSION['staff_id']>0)
        {
            include '../main/mainitems.php';
        }
        else
        {
            $msg[] = "Only Staff will see the Menu";
            $msg[] = "Please register STAFF first then relogin with staff user";
            echo "<div class=\"msg\">";
            foreach ($msg as $m) {echo "$m <br>";}
            echo "</div>";
        }
        ?>
    </td>
    <td style="text-align: center; width: 130px; "></td>
</tr>
</table>
</body>
</html>
