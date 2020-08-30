<?php 
include '../../config/dbc.php';

page_protect();


$title = "::OPD::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/popuponpage.css\"/>";
include '../../libs/popup.php';
include '../../libs/popuponpage.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="160" valign="top">
    <?php 
    if (isset($_SESSION['user_id'])) 
    {
        include 'registermenu.php';
    } 
    ?>
    </td><td width="" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
    <tr><td width="732" valign="top"><h3 class="titlehdr"><div style="background-color:rgba(0,255,128,0.7); display:inline-block;">Thank you</div></h3>
        <h3><strong><div style="background-color:rgba(255,128,0,0.7); display:inline-block;">ลงทะเบียน และ เข้าสู่การตรวจ เรียบร้อยแล้ว <br>Your registration is now complete!</div></strong></h3>
        <p align="right">&nbsp;</p>
        <?php
        $PID = $_SESSION['Patient_id'];
        $Patient_id = $PID;
        include '../../libs/opdxconnection.php';
    
        $ptinfo = mysqli_fetch_array(mysqli_query($linkopdx, "SELECT MAX(id) FROM pt_$PID"));
        $maxr = $ptinfo[0]+1;

        //check id at any point of service..
        $result1 = mysqli_query($link, "SELECT id FROM pt_to_scr WHERE id = $PID");
        if(mysqli_num_rows($result1) != 0)
        {
            $err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
            goto checkfound;
        }
        $result1 = mysqli_query($link, "SELECT id FROM pt_to_doc WHERE id = $PID");
        if(mysqli_num_rows($result1) != 0)
        {
            $err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
            goto checkfound;
        }
        $result1 = mysqli_query($link, "SELECT id FROM pt_to_obs WHERE id = $PID");
        if(mysqli_num_rows($result1) != 0)
        {
            $err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
            goto checkfound;
        }
        $result1 = mysqli_query($link, "SELECT id FROM pt_to_drug WHERE id = $PID");
        if(mysqli_num_rows($result1) == 0) 
        {
            $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id='$PID' ");
            echo "<table border='1' class='TFtable'>";
            echo "<tr><th>เลขทะเบียน</th><th>ยศ</th><th>ชื่อ</th><th>นามสกุล</th></tr>";
            // keeps getting the next row until there are no more to get
            while($row = mysqli_fetch_array($result))
            {
                // Print out the contents of each row into a table
                echo "<tr><th>"; 
                echo $ID = $row['id'];
                echo "</th><th>";
                echo $prefix = $row['prefix'];
                echo "</th><th width=150>"; 
                echo $F_Name = $row['fname'];
                echo "</th><th width=150>"; 
                echo $L_Name = $row['lname'];
                echo "</th></tr>"; 
                //get staff condition
                $staffyn = $row['staff'];
                $ctzid = $row['ctz_id'];
            }
            echo "</table>";
            
            if ($ctzid < 1000000000000) $msg[] = "กรุณา ใส่ เลขประจำตัวประชาชนด้วย";

            $sql_insert = "INSERT INTO `pt_to_scr` (`ID`, `prefix`, `F_Name`, `L_Name`) VALUES ('$ID', '$prefix', '$F_Name', '$L_Name')";
            
            $MaxRowCheck=0;
            while($MaxRowCheck < 1)
                {
                // Now insert Patient to "pt_#id" table
                mysqli_query($link, $sql_insert);
                // Check back if it is recorded.
                $result1 = mysqli_query($link, "SELECT ID FROM pt_to_scr WHERE ID = $ID");
                $MaxRowCheck = mysqli_num_rows($result1);
                }

            // Now create tmp table
            $sql_insert = "CREATE TABLE IF NOT EXISTS `tmp_$ID` (
            `preg` tinyint(4) NOT NULL DEFAULT '0',
            `csf` varchar(120) COLLATE utf8mb4_unicode_ci NULL,
            `idtr1` smallint(6) NOT NULL DEFAULT '0',
            `tr1` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `trv1` smallint(6) NOT NULL DEFAULT '0',
            `tr1o1` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr1o1v` tinyint(4) NOT NULL DEFAULT '0',
            `tr1o2` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr1o2v` tinyint(4) NOT NULL DEFAULT '0',
            `tr1o3` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr1o3v` tinyint(4) NOT NULL DEFAULT '0',
            `tr1o4` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr1o4v` tinyint(4) NOT NULL DEFAULT '0',
            `trby1` smallint(6) NOT NULL DEFAULT '0',
            `idtr2` smallint(6) NOT NULL DEFAULT '0',
            `tr2` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `trv2` smallint(6) NOT NULL DEFAULT '0',
            `tr2o1` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr2o1v` tinyint(4) NOT NULL DEFAULT '0',
            `tr2o2` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr2o2v` tinyint(4) NOT NULL DEFAULT '0',
            `tr2o3` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr2o3v` tinyint(4) NOT NULL DEFAULT '0',
            `tr2o4` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr2o4v` tinyint(4) NOT NULL DEFAULT '0',
            `trby2` smallint(6) NOT NULL DEFAULT '0',
            `idtr3` smallint(6) NOT NULL DEFAULT '0',
            `tr3` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `trv3` smallint(6) NOT NULL DEFAULT '0',
            `tr3o1` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr3o1v` tinyint(4) NOT NULL DEFAULT '0',
            `tr3o2` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr3o2v` tinyint(4) NOT NULL DEFAULT '0',
            `tr3o3` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr3o3v` tinyint(4) NOT NULL DEFAULT '0',
            `tr3o4` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr3o4v` tinyint(4) NOT NULL DEFAULT '0',
            `trby3` smallint(6) NOT NULL DEFAULT '0',
            `idtr4` smallint(6) NOT NULL DEFAULT '0',
            `tr4` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `trv4` smallint(6) NOT NULL DEFAULT '0',
            `tr4o1` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr4o1v` tinyint(4) NOT NULL DEFAULT '0',
            `tr4o2` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr4o2v` tinyint(4) NOT NULL DEFAULT '0',
            `tr4o3` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr4o3v` tinyint(4) NOT NULL DEFAULT '0',
            `tr4o4` varchar(30) COLLATE utf8mb4_unicode_ci NULL,
            `tr4o4v` tinyint(4) NOT NULL DEFAULT '0',
            `trby4` smallint(6) NOT NULL DEFAULT '0',
            `idrx1` smallint(6) NOT NULL DEFAULT '0',
            `rx1` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg1` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx1uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx1v` smallint(3) NOT NULL DEFAULT '0',
            `rxby1` smallint(6) NOT NULL DEFAULT '0',
            `idrx2` smallint(6) NOT NULL DEFAULT '0',
            `rx2` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg2` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx2uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx2v` smallint(3) NOT NULL DEFAULT '0',
            `rxby2` smallint(6) NOT NULL DEFAULT '0',
            `idrx3` smallint(6) NOT NULL DEFAULT '0',
            `rx3` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg3` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx3uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx3v` smallint(3) NOT NULL DEFAULT '0',
            `rxby3` smallint(6) NOT NULL DEFAULT '0',
            `idrx4` smallint(6) NOT NULL DEFAULT '0',
            `rx4` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg4` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx4uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx4v` smallint(3) NOT NULL DEFAULT '0',
            `rxby4` smallint(6) NOT NULL DEFAULT '0',
            `idrx5` smallint(6) NOT NULL DEFAULT '0',
            `rx5` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg5` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx5uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx5v` smallint(3) NOT NULL DEFAULT '0',
            `rxby5` smallint(6) NOT NULL DEFAULT '0',
            `idrx6` smallint(6) NOT NULL DEFAULT '0',
            `rx6` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg6` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx6uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx6v` smallint(3) NOT NULL DEFAULT '0',
            `rxby6` smallint(6) NOT NULL DEFAULT '0',
            `idrx7` smallint(6) NOT NULL DEFAULT '0',
            `rx7` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg7` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx7uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx7v` smallint(3) NOT NULL DEFAULT '0',
            `rxby7` smallint(6) NOT NULL DEFAULT '0',
            `idrx8` smallint(6) NOT NULL DEFAULT '0',
            `rx8` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg8` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx8uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx8v` smallint(3) NOT NULL DEFAULT '0',
            `rxby8` smallint(6) NOT NULL DEFAULT '0',
            `idrx9` smallint(6) NOT NULL DEFAULT '0',
            `rx9` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg9` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx9uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx9v` smallint(3) NOT NULL DEFAULT '0',
            `rxby9` smallint(6) NOT NULL DEFAULT '0',
            `idrx10` smallint(6) NOT NULL DEFAULT '0',
            `rx10` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg10` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx10uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx10v` smallint(3) NOT NULL DEFAULT '0',
            `rxby10` smallint(6) NOT NULL DEFAULT '0',
            `idrx11` smallint(6) NOT NULL DEFAULT '0',
            `rx11` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg11` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx11uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx11v` smallint(3) NOT NULL DEFAULT '0',
            `rxby11` smallint(6) NOT NULL DEFAULT '0',
            `idrx12` smallint(6) NOT NULL DEFAULT '0',
            `rx12` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg12` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx12uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx12v` smallint(3) NOT NULL DEFAULT '0',
            `rxby12` smallint(6) NOT NULL DEFAULT '0',
            `idrx13` smallint(6) NOT NULL DEFAULT '0',
            `rx13` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg13` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx13uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx13v` smallint(3) NOT NULL DEFAULT '0',
            `rxby13` smallint(6) NOT NULL DEFAULT '0',
            `idrx14` smallint(6) NOT NULL DEFAULT '0',
            `rx14` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rxg14` varchar(60) COLLATE utf8mb4_unicode_ci NULL,
            `rx14uses` varchar(300) COLLATE utf8mb4_unicode_ci NULL,
            `rx14v` smallint(3) NOT NULL DEFAULT '0',
            `rxby14` smallint(6) NOT NULL DEFAULT '0',
            `licprice` smallint(6) NOT NULL DEFAULT '0',
            `lcprice` smallint(6) NOT NULL DEFAULT '0',
            `pricepolicy` tinyint(4) NOT NULL DEFAULT '0',
            `medcert` tinyint(4) NOT NULL DEFAULT '0',
            `course` tinyint(1) NOT NULL DEFAULT '0',
            `prolab` tinyint(1) NOT NULL DEFAULT '0',
            `rindex` smallint(6) NOT NULL DEFAULT '$maxr',
            `ddiltemp` tinyint(1) NULL
            ) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            ";
            mysqli_query($link, $sql_insert) or $err = mysqli_error($link);
        } 
        else $err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
        checkfound:
        if(!empty($err))
        {
            echo "<div class=\"msg\">";
            foreach ($err as $e) { echo "* $e <br>"; }
            echo "</div>";	
        }
        if(!empty($msg))
        {
            echo "<div class=\"msg\">";
            foreach ($msg as $m) {echo "* $m <br>";}
            echo "</div>";
        }
        ?> 
</td></tr>
</table>
</body>
</html>
