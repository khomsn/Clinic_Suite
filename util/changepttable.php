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

//$ptid = mysqli_fetch_array(mysqli_query($linkopd, "SELECT MAX(id) FROM patient_id"));
//$maxid = $ptid[0];34938
$maxid = 34938;
for ($i=1;$i<=$maxid;$i++)
{
$Patient_id = $i;
include '../libs/opdxconnection.php';
$pttable = "pt_".$i;
//mysqli_query($linkopdx, "ALTER TABLE `$pttable` DROP `fub`;");
//mysqli_query($linkopdx, "ALTER TABLE `$pttable` DROP `fup`;");
//mysqli_query($linkopdx, "ALTER TABLE `$pttable` ADD `fup` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `labresult`;");
/*
"ALTER TABLE `$pttable` 
ADD `idrx11` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby10`, 
ADD `rx11` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx11`, 
ADD `rxg11` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx11`, 
ADD `rx11uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg11`, 
ADD `rx11v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx11uses`, 
ADD `rxby11` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx11v`,
ADD `idrx12` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby11`, 
ADD `rx12` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx12`, 
ADD `rxg12` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx12`, 
ADD `rx12uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg12`, 
ADD `rx12v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx12uses`, 
ADD `rxby12` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx12v`,
ADD `idrx13` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby12`, 
ADD `rx13` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx13`, 
ADD `rxg13` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx13`, 
ADD `rx13uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg13`, 
ADD `rx13v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx13uses`, 
ADD `rxby13` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx13v`,
ADD `idrx14` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby13`, 
ADD `rx14` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx14`, 
ADD `rxg14` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx14`, 
ADD `rx14uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg14`, 
ADD `rx14v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx14uses`, 
ADD `rxby14` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx14v`,
ADD `idrx15` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby14`, 
ADD `rx15` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx15`, 
ADD `rxg15` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx15`, 
ADD `rx15uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg15`, 
ADD `rx15v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx15uses`, 
ADD `rxby15` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx15v`,
ADD `idrx16` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby15`, 
ADD `rx16` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx16`, 
ADD `rxg16` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx16`, 
ADD `rx16uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg16`, 
ADD `rx16v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx16uses`, 
ADD `rxby16` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx16v`,
ADD `idrx17` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby16`, 
ADD `rx17` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx17`, 
ADD `rxg17` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx17`, 
ADD `rx17uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg17`, 
ADD `rx17v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx17uses`, 
ADD `rxby17` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx17v`,
ADD `idrx18` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby17`, 
ADD `rx18` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx18`, 
ADD `rxg18` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx18`, 
ADD `rx18uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg18`, 
ADD `rx18v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx18uses`, 
ADD `rxby18` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx18v`,
ADD `idrx19` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby18`, 
ADD `rx19` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx19`, 
ADD `rxg19` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx19`, 
ADD `rx19uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg19`, 
ADD `rx19v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx19uses`, 
ADD `rxby19` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx19v`,
ADD `idrx20` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby19`, 
ADD `rx20` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx20`, 
ADD `rxg20` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx20`, 
ADD `rx20uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg20`, 
ADD `rx20v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx20uses`, 
ADD `rxby20` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx20v`,

;"
*/
mysqli_query($linkopdx, 
"ALTER TABLE `$pttable` 
ADD `idrx11` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby10`, 
ADD `rx11` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx11`, 
ADD `rxg11` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx11`, 
ADD `rx11uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg11`, 
ADD `rx11v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx11uses`, 
ADD `rxby11` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx11v`,
ADD `idrx12` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby11`, 
ADD `rx12` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx12`, 
ADD `rxg12` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx12`, 
ADD `rx12uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg12`, 
ADD `rx12v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx12uses`, 
ADD `rxby12` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx12v`,
ADD `idrx13` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby12`, 
ADD `rx13` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx13`, 
ADD `rxg13` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx13`, 
ADD `rx13uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg13`, 
ADD `rx13v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx13uses`, 
ADD `rxby13` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx13v`,
ADD `idrx14` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rxby13`, 
ADD `rx14` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `idrx14`, 
ADD `rxg14` VARCHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rx14`, 
ADD `rx14uses` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `rxg14`, 
ADD `rx14v` SMALLINT(3) NOT NULL DEFAULT '0' AFTER `rx14uses`, 
ADD `rxby14` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `rx14v`
;");

}
?>
</div>
</div>
</body>
</html>
