<?php
//if($_SESSION['Patient_id']%1000 ==1)
{
  //  $endid = $_SESSION['Patient_id']  + 999;
    $endid = $_SESSION['Patient_id'];
    $dbname = DB_OPD_NAME.$endid;
    $sql  = "CREATE DATABASE `$dbname` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;";
}
?>
