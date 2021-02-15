<?php 
include '../config/dbc.php';

page_protect();
?>
<!DOCTYPE html>
<html>
<head>
<title>Update pt_table</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="content">
<div style="text-align: center;">
<?php

                //update stock at the end of the month 
                echo "lastday=".$lastday = cal_days_in_month(CAL_GREGORIAN, date("n"), date("Y"));
                echo "<br> today=".$sd = date("d");
                $lastday = 29;
                if($sd==$lastday)
                {
                    echo "<br>== for loop<br>";
                }


/*
    for ($i=1; $i<=37732 ;$i++){
        $sql_insert  = "INSERT INTO `initdb` (`refname`, `version`) VALUES ('pt_$i', '1')";
        mysqli_query($link, $sql_insert);
        }
*/
/*
        $table = 3;
        echo "Test for pt_$table\n";
        $version = 2;
        $sql_select = "SELECT `version` FROM `initdb` WHERE `refname` = 'pt_$table'";
        $table_version = mysqli_query($link, $sql_select);
        $ovs = mysqli_fetch_array($table_version);
        echo "\n oldversion=".$oldversion = intval($ovs[0]);
        echo "\nYes\n";
       while ($oldversion < $version) {
       echo "while loop";
        
            if ($oldversion == 1)
            {
            */
            /*
                $sql_alter = "ALTER TABLE `$tabletocreate` CHANGE `clinic` `clinic` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '$_SESSION[clinic]'; ";
                mysqli_query($linkopdx, $sql_alter);
                */
                /*
                $sql_update = "UPDATE `initdb` SET `version` = '2' WHERE `initdb`.`refname` = 'pt_$table';"; 
                mysqli_query($link, $sql_update);
            }
            
        $table_version = mysqli_query($link, $sql_select);
        $ovs = mysqli_fetch_array($table_version);
        echo "<br> oldversion=".$oldversion = intval($ovs[0]);
        echo "<br>Yes\n";
        if ($oldversion != 3 ) break;
        }
        */
?>
</div>
</div>
</body>
</html>
