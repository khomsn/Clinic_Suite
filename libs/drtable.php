<?php
// to use this just assign $drugtableid 
$drtb = "drug_".$drugtableid;
$sql_insert ="
            CREATE TABLE IF NOT EXISTS `$drtb` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `date` DATE NOT NULL ,
            `expdate` DATE NULL ,
            `supplier` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL ,
            `inv_num` VARCHAR( 20 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL ,
            `volume` INT NOT NULL ,
            `price` DECIMAL (7,2) NOT NULL ,
            `customer` INT NOT NULL DEFAULT '0'
            ) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; ";

// Now create drug information table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
?>
