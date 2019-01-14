<?php
if(( $Patient_id % 1000 )==0)
{
    $dbopd = "clinic_opd".$Patient_id;
}
else
{
    $dbopd = "clinic_opd".( 1000 + $Patient_id - ( $Patient_id % 1000 ));
}

define ("DB_OPDX_NAME", $dbopd); // set database

/* This is Link connection for Programme database */
$linkopdx = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_OPDX_NAME);
// set MySql code page
mysqli_set_charset($linkopdx, "utf8mb4");
?>
