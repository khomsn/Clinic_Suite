<?php
/* Link : Define connection parameter */

/* This is Link connection for Programme database */
/* 1st is general database ************************/
/* 2nd is Opd card database ***********************/

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$linkcm = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_COMMON_NAME);
$linkopd = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_OPD_NAME);

if (mysqli_connect_errno($link)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
if (mysqli_connect_errno($linkcm)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
if (mysqli_connect_errno($linkopd)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// set MySql code page
mysqli_set_charset($link, "utf8mb4");
mysqli_set_charset($linkcm, "utf8mb4");
mysqli_set_charset($linkopd, "utf8mb4");

// set Timezone for DateTime function
date_default_timezone_set('Asia/Bangkok');
?>
