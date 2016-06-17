<?php 
include '../login/dbc.php';
page_protect();

/// alter source
/*
{
    $table = "patient_id";
    $sql_insert = "
    
    ALTER TABLE `$table` 
    
    CHANGE   `ctz_id` `ctz_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE  `prefix` `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE  `fname` `fname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE  `lname` `lname` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `gender` `gender` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE   `birthday`  `birthday` date NOT NULL,
    CHANGE   `bloodgrp`  `bloodgrp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `height` `height` smallint(3) NOT NULL DEFAULT '1',
    CHANGE   `drug_alg_1` `drug_alg_1` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE  `drug_alg_2`  `drug_alg_2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `drug_alg_3`  `drug_alg_3` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `drug_alg_4`  `drug_alg_4` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `drug_alg_5` `drug_alg_5` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `chro_ill_1`  `chro_ill_1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `chro_ill_2`  `chro_ill_2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `chro_ill_3` `chro_ill_3` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `chro_ill_4` `chro_ill_4` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `chro_ill_5`  `chro_ill_5` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `concurdrug` `concurdrug` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `address1` `address1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `address2` `address2` tinyint(4) NOT NULL DEFAULT '0',
    CHANGE    `address3` `address3` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `address4` `address4` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `address5`  `address5` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE   `zipcode` `zipcode` mediumint(5) NOT NULL DEFAULT '0',
    CHANGE  `hometel`  `hometel` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE  `mobile`  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    CHANGE    `user_id` `user_id` int(11) NOT NULL DEFAULT '0',
    CHANGE   `log` `log` int(11) NOT NULL DEFAULT '0',
     CHANGE  `staff`  `staff` tinyint(1) NOT NULL DEFAULT '0',
    CHANGE   `reccomp`  `reccomp` smallint(6) NOT NULL DEFAULT '0',
    CHANGE    `fup` `fup` tinyint(1) NOT NULL DEFAULT '0',
    CHANGE  `clinic`   `clinic` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL
    
    ";
    mysqli_query($linkopd, $sql_insert);
}

$pin = mysqli_query($linkopd, "SELECT MAX(id) FROM patient_id");
$maxrow = mysqli_fetch_row($pin);
$maxid = $maxrow[0];

for($i=13679;$i<=$maxid;$i++)
{
    $table = "pt_".$i;
//    $sql_insert = "ALTER TABLE `$table` 
//    ADD `fup` INT NOT NULL ;";
//    mysqli_query($linkopd, $sql_insert) or die("Insertion Failed: $i" . mysqli_error($linkopd));

     $sql_insert = "ALTER TABLE `$table`
     
   CHANGE  `ccp`  `ccp` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `dofhis`  `dofhis` VARCHAR( 1000 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `phex`  `phex` VARCHAR( 1000 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `ddx`  `ddx` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `inform`  `inform` VARCHAR( 500 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `idtr1`  `idtr1` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr1`  `tr1` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `trv1`  `trv1` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr1o1`  `tr1o1` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr1o1v`  `tr1o1v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr1o2`  `tr1o2` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr1o2v`  `tr1o2v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr1o3`  `tr1o3` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr1o3v`  `tr1o3v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr1o4`  `tr1o4` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr1o4v`  `tr1o4v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `trby1`  `trby1` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idtr2`  `idtr2` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr2`  `tr2` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `trv2`  `trv2` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr2o1`  `tr2o1` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr2o1v`  `tr2o1v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr2o2`  `tr2o2` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr2o2v`  `tr2o2v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr2o3`  `tr2o3` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr2o3v`  `tr2o3v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr2o4`  `tr2o4` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr2o4v`  `tr2o4v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `trby2`  `trby2` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idtr3`  `idtr3` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr3`  `tr3` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `trv3`  `trv3` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr3o1`  `tr3o1` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr3o1v`  `tr3o1v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr3o2`  `tr3o2` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr3o2v`  `tr3o2v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr3o3`  `tr3o3` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr3o3v`  `tr3o3v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr3o4`  `tr3o4` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr3o4v`  `tr3o4v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `trby3`  `trby3` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idtr4`  `idtr4` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr4`  `tr4` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `trv4`  `trv4` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr4o1`  `tr4o1` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr4o1v`  `tr4o1v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr4o2`  `tr4o2` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr4o2v`  `tr4o2v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr4o3`  `tr4o3` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr4o3v`  `tr4o3v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `tr4o4`  `tr4o4` VARCHAR( 15 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `tr4o4v`  `tr4o4v` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `trby4`  `trby4` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idrx1`  `idrx1` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `rx1`  `rx1` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rxg1`  `rxg1` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx1uses`  `rx1uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx1v`  `rx1v` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rxby1`  `rxby1` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idrx2`  `idrx2` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `rx2`  `rx2` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rxg2`  `rxg2` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx2uses`  `rx2uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx2v`  `rx2v` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rxby2`  `rxby2` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idrx3`  `idrx3` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `rx3`  `rx3` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rxg3`  `rxg3` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx3uses`  `rx3uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx3v`  `rx3v` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rxby3`  `rxby3` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idrx4`  `idrx4` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `rx4`  `rx4` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rxg4`  `rxg4` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx4uses`  `rx4uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx4v`  `rx4v` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rxby4`  `rxby4` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idrx5`  `idrx5` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `rx5`  `rx5` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rxg5`  `rxg5` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx5uses`  `rx5uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx5v`  `rx5v` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rxby5`  `rxby5` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idrx6`  `idrx6` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `rx6`  `rx6` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rxg6`  `rxg6` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx6uses`  `rx6uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx6v`  `rx6v` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rxby6`  `rxby6` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idrx7`  `idrx7` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `rx7`  `rx7` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rxg7`  `rxg7` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx7uses`  `rx7uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx7v`  `rx7v` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rxby7`  `rxby7` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idrx8`  `idrx8` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `rx8`  `rx8` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rxg8`  `rxg8` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx8uses`  `rx8uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx8v`  `rx8v` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rxby8`  `rxby8` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idrx9`  `idrx9` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `rx9`  `rx9` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rxg9`  `rxg9` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx9uses`  `rx9uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx9v`  `rx9v` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rxby9`  `rxby9` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `idrx10`  `idrx10` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `rx10`  `rx10` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rxg10`  `rxg10` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx10uses`  `rx10uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `rx10v`  `rx10v` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rxby10`  `rxby10` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `disprxby`  `disprxby` SMALLINT NOT NULL DEFAULT '0'    
,   CHANGE  `temp`  `temp` DECIMAL (3,1) NOT NULL DEFAULT '0'  
,   CHANGE  `weight`  `weight` DECIMAL (4,1) NOT NULL DEFAULT '0'  
,   CHANGE  `height`  `height` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `bpsys`  `bpsys` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `bpdia`  `bpdia` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `hr`  `hr` SMALLINT( 3 ) NOT NULL  DEFAULT '0' 
,   CHANGE  `rr`  `rr` TINYINT NOT NULL DEFAULT '0'    
,   CHANGE  `labid`  `labid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL  
,   CHANGE  `labresult`  `labresult` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL  
,   CHANGE  `fup`  `fup` date NULL       
,   CHANGE  `doctor`  `doctor` VARCHAR( 100 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `dtlc`  `dtlc` VARCHAR( 20 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
,   CHANGE  `chronicill`  `chronicill` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL  
,   CHANGE  `drugallergy`  `drugallergy` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL  
,   CHANGE  `concurdrug`  `concurdrug` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL  
,   CHANGE  `obsandpgnote`  `obsandpgnote` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL  
,   CHANGE  `clinic`  `clinic` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL  

;";
    mysqli_query($linkopd, $sql_insert) or die("Insertion Failed: $i" . mysqli_error($linkopd));
}
*/
/*
$pin = mysqli_query($link, "SELECT MAX(id) FROM drug_id");
$maxrow = mysqli_fetch_row($pin);
$maxid = $maxrow[0];

for($i=209;$i<=$maxid;$i++)
{
    $table = "drug_".$i;
    $sql_insert = "ALTER TABLE `$table` CHANGE `customer` `customer` INT(11) NOT NULL DEFAULT '0';";
    mysqli_query($link, $sql_insert);
}

$pin = mysqli_query($link, "SELECT MAX(id) FROM rawmat");
$maxrow = mysqli_fetch_row($pin);
$maxid = $maxrow[0];


for($i=1;$i<=$maxid;$i++)
{
    $table = "rawmat_".$i;
    $sql_insert = "ALTER TABLE `$table` CHANGE `customer` `customer` INT(11) NOT NULL DEFAULT '0';";
    mysqli_query($link, $sql_insert);
}

$pin = mysqli_query($link, "SELECT MAX(id) FROM supplier");
$maxrow = mysqli_fetch_row($pin)  or die("Insertion Failed: $i" . mysqli_error($link));
$maxid = $maxrow[0];


for($i=16;$i<=$maxid;$i++)
{
    
    $table = "sp_".$i;
//    $sql_insert = "ALTER TABLE `$table` CHANGE `payment` `payment` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `duedate` `duedate` DATE NULL;";
    $sql_insert = "ALTER TABLE `$table` CHANGE `payment` `payment` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `duedate` `duedate` DATE NULL DEFAULT NULL;";
    mysqli_query($link, $sql_insert) or die("Insertion Failed: $i" . mysqli_error($link));
}
*/
?>

<!DOCTYPE html>
<html>
<head>
<title>ประวัติ ตรวจร่างกาย</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="content">
							<div style="text-align: center;">
							<p>"Golden standard for defining obesity"
							<br>Men: BMI < 27 kg/m2 -> %BF < 25%  <->  Women: BMI < 25 kg/m2 ->%BF < 35%
							<br>Thai, cut-off values of BMI for diagnosing obesity should be lowered to 
							<br>27 kg/m2 in men and 25 kg/m2 in women.
							<br>Underweight = <18.5</p>
							</div>
<!--end menu-->
</div>
</body>
<script>

var content = document.getElementById("content");
window.resizeTo(content.offsetWidth + 30, content.offsetHeight + 50);

</script>
</html>
