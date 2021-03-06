<?php
//create database for store opd record limit for  1000 table for ease of maintainance
if(($_SESSION['Patient_id']%1000)==1)
{
    $dbopd = "clinic_opd".($_SESSION['Patient_id']+999);
    
    $sql_create = "CREATE DATABASE IF NOT EXISTS `$dbopd`  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
    mysqli_query($link, $sql_create) or die("Create Failed:" . mysqli_error($link));
}

$Patient_id = $_SESSION['Patient_id'];

include 'opdxconnection.php';

if(!empty($_SESSION['Patient_id']))
{
    $tabletocreate = "pt_".$_SESSION['Patient_id'];

    $tb_create = " CREATE TABLE IF NOT EXISTS `$tabletocreate` (
				`id` SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
				`date` DATETIME NOT NULL ,
				 `ccp` VARCHAR( 500 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `dofhis`  TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `phex`  TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `ddx` VARCHAR( 1000 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `inform`  TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `idtr1` SMALLINT NOT NULL DEFAULT '0' ,
				 `tr1` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `trv1` SMALLINT NOT NULL DEFAULT '0' ,
				 `tr1o1` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr1o1v` TINYINT NOT NULL DEFAULT '0',
				 `tr1o2` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr1o2v` TINYINT NOT NULL DEFAULT '0',
				 `tr1o3` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr1o3v` TINYINT NOT NULL DEFAULT '0',
				 `tr1o4` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr1o4v` TINYINT NOT NULL DEFAULT '0',
				 `trby1` SMALLINT NOT NULL DEFAULT '0' ,
				 `idtr2` SMALLINT NOT NULL DEFAULT '0' ,
				 `tr2` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `trv2` SMALLINT NOT NULL DEFAULT '0' ,
				 `tr2o1` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr2o1v` TINYINT NOT NULL DEFAULT '0',
				 `tr2o2` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr2o2v` TINYINT NOT NULL DEFAULT '0',
				 `tr2o3` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr2o3v` TINYINT NOT NULL DEFAULT '0',
				 `tr2o4` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr2o4v` TINYINT NOT NULL DEFAULT '0',
				 `trby2` SMALLINT NOT NULL DEFAULT '0' ,
				 `idtr3` SMALLINT NOT NULL DEFAULT '0' ,
				 `tr3` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `trv3` SMALLINT NOT NULL DEFAULT '0' ,
				 `tr3o1` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr3o1v` TINYINT NOT NULL DEFAULT '0',
				 `tr3o2` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr3o2v` TINYINT NOT NULL DEFAULT '0',
				 `tr3o3` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr3o3v` TINYINT NOT NULL DEFAULT '0',
				 `tr3o4` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr3o4v` TINYINT NOT NULL DEFAULT '0',
				 `trby3` SMALLINT NOT NULL DEFAULT '0' ,
				 `idtr4` SMALLINT NOT NULL DEFAULT '0' ,
				 `tr4` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `trv4` SMALLINT NOT NULL DEFAULT '0' ,
				 `tr4o1` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr4o1v` TINYINT NOT NULL DEFAULT '0',
				 `tr4o2` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr4o2v` TINYINT NOT NULL DEFAULT '0',
				 `tr4o3` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr4o3v` TINYINT NOT NULL DEFAULT '0',
				 `tr4o4` VARCHAR( 30 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `tr4o4v` TINYINT NOT NULL DEFAULT '0',
				 `trby4` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx1` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx1` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg1` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx1uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx1v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby1` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx2` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx2` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg2` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx2uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx2v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby2` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx3` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx3` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg3` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx3uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx3v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby3` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx4` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx4` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg4` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx4uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx4v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby4` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx5` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx5` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg5` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx5uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx5v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby5` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx6` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx6` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg6` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx6uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx6v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby6` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx7` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx7` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg7` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx7uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx7v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby7` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx8` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx8` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg8` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx8uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx8v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby8` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx9` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx9` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg9` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx9uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx9v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby9` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx10` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx10` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg10` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx10uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx10v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby10` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx11` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx11` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg11` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx11uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx11v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby11` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx12` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx12` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg12` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx12uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx12v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby12` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx13` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx13` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg13` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx13uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx13v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby13` SMALLINT NOT NULL DEFAULT '0' ,
				 `idrx14` SMALLINT NOT NULL DEFAULT '0' ,
				 `rx14` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rxg14` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx14uses` VARCHAR( 300 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `rx14v` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rxby14` SMALLINT NOT NULL DEFAULT '0' ,
				 `disprxby` SMALLINT NOT NULL DEFAULT '0' ,
				 `temp` DECIMAL (3,1) NOT NULL DEFAULT '0',
				 `weight` DECIMAL (4,1) NOT NULL DEFAULT '0' ,
				 `height` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `bpsys` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `bpdia` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `hr` SMALLINT( 3 ) NOT NULL  DEFAULT '0',
				 `rr` TINYINT NOT NULL DEFAULT '0',
				 `labid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `labresult` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `fup` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `doctor` VARCHAR( 100 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `dtlc` VARCHAR( 20 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `chronicill` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `drugallergy` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `concurdrug` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `obsandpgnote` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
				 `clinic` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL  DEFAULT '$_SESSION[clinic]'
				) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        // Now insert Patient to "patient_id" table
        mysqli_query($linkopdx, $tb_create) or die("Create Failed:". mysqli_error($linkopdx));

    /*table version number*/
    $version = 2;
    
    $sql_check =  mysqli_query($link, "SELECT * FROM `initdb` WHERE `refname`='$tabletocreate' ORDER BY `id`" );
    $tabcheck = mysqli_num_rows($sql_check);
    if(!$tabcheck)
    {
        $sql_insert  = "INSERT INTO `initdb` (`refname`, `version`) VALUES ('$tabletocreate', '$version')";
        mysqli_query($link, $sql_insert);
    }
    else
    {/*********** update data base code here *****************/

        $sql_select = "SELECT `version` FROM `initdb` WHERE `refname` = '$tabletocreate'";
        $table_version = mysqli_query($link, $sql_select);
        $ovs = mysqli_fetch_array($table_version);
        $oldversion = intval($ovs[0]);
        
        while ($oldversion < $version) {
        
            if ($oldversion == 1)
            {
                $sql_alter = "ALTER TABLE `$tabletocreate` CHANGE `clinic` `clinic` VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '$_SESSION[clinic]'; ";
                mysqli_query($linkopdx, $sql_alter);
                
                $sql_update = "UPDATE `initdb` SET `version` = '2' WHERE `initdb`.`refname` = '$tabletocreate';"; 
                mysqli_query($link, $sql_update);
            }
            
            $table_version = mysqli_query($link, $sql_select);
            $ovs = mysqli_fetch_array($table_version);
            $oldversion = intval($ovs[0]);
        }

    /*end update data base table*/
    }
}
?>
