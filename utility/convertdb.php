<?php
include '../dbc.php';
page_protect();

    // connect your database here first 
    // 

    // Actual code starts here 

    $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'clinic' 
        AND ENGINE <> 'InnoDB'";

    $rs = mysqli_query($link,$sql);

    while($row = mysqli_fetch_array($rs))
    {
        $tbl = $row[0];
        $sql = "ALTER TABLE $tbl ENGINE=INNODB";
        mysqli_query($link,$sql);
        echo "converting".$tbl; 
    }
   echo "finish";
?>

