<?php
//check if Pt is in Pay station
	$id = $_SESSION['patcash'];
	$tmax = $tty = 0;
	$result1 = mysqli_query($link, "SELECT id FROM  pt_to_drug WHERE id = '$id'");
	if(mysqli_num_rows($result1) == 0) 
	{
	  unset($_SESSION['patcash']);
	  header("Location: ../opd/pt_to_drug.php ");
	}

//get drug from temp to pt_table
	$j=1;
	$dtemp = mysqli_query($link, "select * from $tmp ");
	while ($rowd = mysqli_fetch_array($dtemp))
	{
		for($i=1;$i<=10;$i++)
		{
			$idrxt = "idrx".$i;
			$rxt = "rx".$i;
			$rgt = "rxg".$i;
			$ust = "rx".$i."uses";
			$vlt = "rx".$i."v";
			$rxby = "rxby".$i;
			if($rowd[$idrxt] != 0)
			{
			$idrxd[$j] = $rowd[$idrxt];
			$rxd[$j] = $rowd[$rxt];
			$rgd[$j] = $rowd[$rgt];
			$usd[$j] = $rowd[$ust];
			$vld[$j] = $rowd[$vlt];
			$rxbd[$j]= $rowd[$rxby];
			$drugmax = $j;
			$j = $j+1;
			}
		}
	}
	//_SESSION เพือตัด วัตถุดิบ
	$_SESSION['drugmax']=$drugmax;
	//update drug @ pttable
	for($i=1;$i<=$drugmax;$i++)
	{
		$idrx = "idrx".$i;
		$rx = "rx".$i;
		$rxg = "rxg".$i;
		$us = "rx".$i."uses";
		$vl = "rx".$i."v";
		$rxb = "rxby".$i;
		mysqli_query($linkopdx, "UPDATE $pttable SET
			`$idrx` = '$idrxd[$i]',
			`$rx` = '$rxd[$i]',
			`$rxg` = '$rgd[$i]',
			`$us` = '$usd[$i]',
			`$vl` = '$vld[$i]',
			`$rxb` = '$rxbd[$i]'
			WHERE id = '$rid' ");
		//_SESSION เพือตัด วัตถุดิบ
		$_SESSION['idrx'.$i]=$idrxd[$i];
		$_SESSION['rxvol'.$i]=$vld[$i];
	}
	
	//update Staff ที่จ่ายยา
	mysqli_query($linkopdx, "UPDATE $pttable SET `disprxby` = '$_SESSION[staff_id]' WHERE id = '$rid' ");
	//get Treatment from temp to pt_table
	$j = 1;	
	$dtemp = mysqli_query($link, "select * from $tmp ");
	while ($rowd = mysqli_fetch_array($dtemp))
	{
		for($i=1;$i<=4;$i++)
		{
			$idtrt ="idtr".$i;
			$trt = "tr".$i;
			$trvt = "trv".$i;
			$tr1o1t = "tr".$i."o1";
			$tr1o1vt ="tr".$i."o1v";
			$tr1o2t = "tr".$i."o2";
			$tr1o2vt ="tr".$i."o2v";
			$tr1o3t = "tr".$i."o3";
			$tr1o3vt ="tr".$i."o3v";
			$tr1o4t = "tr".$i."o4";
			$tr1o4vt ="tr".$i."o4v";
			$trby ="trby".$i;
			if($rowd[$idtrt] != '0' )
			{
			$idtrd[$j] = $rowd[$idtrt];
			$trd[$j] = $rowd[$trt];
			$trvd[$j] = $rowd[$trvt];
			$tr1o1d[$j] = $rowd[$tr1o1t];
			$tr1o1vd[$j] = $rowd[$tr1o1vt];
			$tr1o2d[$j] = $rowd[$tr1o2t];
			$tr1o2vd[$j] = $rowd[$tr1o2vt];
			$tr1o3d[$j] = $rowd[$tr1o3t];
			$tr1o3vd[$j] = $rowd[$tr1o3vt];
			$tr1o4d[$j] = $rowd[$tr1o4t];
			$tr1o4vd[$j] = $rowd[$tr1o4vt];
			$trbyd[$j] = $rowd[$trby];
			$tmax = $j;
			$j = $j+1;
			}
		}	
	}

	// _SESSION tmax for cut rawmatcut
	$_SESSION['tmax']=$tmax;
	//put data to pttable
	for($i=1;$i<=$tmax;$i++)
	{
		$idtr ="idtr".$i;
		$tr = "tr".$i;
		$trv = "trv".$i;
		$tr1o1 = "tr".$i."o1";
		$tr1o1v ="tr".$i."o1v";
		$tr1o2 = "tr".$i."o2";
		$tr1o2v ="tr".$i."o2v";
		$tr1o3 = "tr".$i."o3";
		$tr1o3v ="tr".$i."o3v";
		$tr1o4 = "tr".$i."o4";
		$tr1o4v ="tr".$i."o4v";
		$trby ="trby".$i;
		
			mysqli_query($linkopdx, "UPDATE $pttable SET
				`$idtr` = '$idtrd[$i]',
				`$tr` = '$trd[$i]',
				`$trv` = '$trvd[$i]',
				`$tr1o1` = '$tr1o1d[$i]',
				`$tr1o1v` = '$tr1o1vd[$i]',
				`$tr1o2` = '$tr1o2d[$j]',
				`$tr1o2v` = '$tr1o2vd[$i]',
				`$tr1o3` = '$tr1o3d[$i]',
				`$tr1o3v` = '$tr1o3vd[$i]',
				`$tr1o4` = '$tr1o4d[$i]',
				`$tr1o4v` = '$tr1o4vd[$i]',
				`$trby` = '$trbyd[$i]'
			WHERE id = '$rid'");
		//_SESSION เพือตัด วัตถุดิบ
		$_SESSION['idtr'.$i]=$idtrd[$i];
		$_SESSION['trvol'.$i]=$trvd[$i];
	}
	//ตัดยอดยา
	for($i=1;$i<=$drugmax;$i++)
	{
		$drgid = $idrxd[$i];
		//new
		//record drug use per month in dupm table for statistics MONTH(mon) = '$sm'
		$month1 = date("m");
		$year1 = date("Y");
		$dupmin = mysqli_query($link, "SELECT * FROM dupm WHERE drugid = '$drgid' AND MONTH(mon) = '$month1' AND YEAR(mon) = '$year1'");
//		$dupmo = mysqli_fetch_array($dupmin);
		while($dupmo = mysqli_fetch_array($dupmin))
		{
            $idstat = $dupmo['id'];
            $newvol = $dupmo['vol'] + $vld[$i];
		}
		if(!empty($idstat))
		{
		  $sql_insert = "UPDATE `dupm` SET `mon` = now(),`vol` = '$newvol'
						  WHERE id='$idstat'; 
						  ";
		  mysqli_query($link, $sql_insert);
		
		}
		else
		{
		  $sql_insert = "INSERT into `dupm`
			    (`drugid`,`mon`,`vol`)
			VALUES
			    ('$drgid',now(),'$vld[$i]')";
		    mysqli_query($link, $sql_insert);
		
		}

		$drugtable = "drug_".$drgid;
		//get ctz-id
		$ptin = mysqli_query($linkopd, "select * from patient_id where id='$ctmid' ");
		while ($rowct = mysqli_fetch_array($ptin))
		{
			$ctzid = $rowct['ctz_id'];
		}	
		// update drug_# here-------//
		$ctv = $vld[$i];
		$stock_out = mysqli_query($link, "select * from $drugtable ORDER BY `id` ASC ");
		while ($ctv != 0)
		{
			while ($row_settings = mysqli_fetch_array($stock_out))
			{
				$dvolume = $row_settings['volume']; //get volume
				$dprice = $row_settings['price']; //get price
				$price = $dprice/$dvolume;
				$dcustomer = $row_settings['customer']; //get volume on customer
				$rowid = $row_settings['id'];
				if ($dcustomer < $dvolume) break 1;
			}
			$dleft = $dvolume - $dcustomer;
			//no drug left to sell set $ctv to 0 to exit from this loop
			if($dleft == 0) $ctv = 0;
			//
			if ($dleft <= $ctv)
			{
				// Update drug_#id.
				$upvol = $dcustomer + $dleft;
				mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
				$ctv = $ctv - $dleft;
			}	
			else
			{
				// Update drug_#id.
				$upvol = $dcustomer + $ctv;
				mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
				$ctv = 0;
			}	
		}	
		// update drug_# here---end----//
		//update drug_id volume
		$ddrug = mysqli_query($link, "select * from drug_id WHERE id = $drgid ");
		while ($rowd = mysqli_fetch_array($ddrug))
		{
			//trackingsystem
			if($rowd['track'] == 1)
			{
				$drugtrack = "tr_drug_".$drgid; 
				mysqli_query($link, " INSERT INTO $drugtrack  (`date` , `ctz_id` , `pt_id` , `volume` )
												VALUES (now(), '$ctzid', '$ctmid', '$vld[$i]');  ");
			}	
			//tracking end
			$volold = $rowd['volume'];
			$rsvolold = $rowd['volreserve'];
			//get ต้นทุนยา ส่งไป account
			$tty = $tty + $vld[$i]*$price;
			// ต้นทุน end
			if($rowd['seti'] == 1)
			{
				$setdrug = "set_drug_".$drgid;
				$drugset = mysqli_query($link, "select * from $setdrug");
				while ($rowd2 = mysqli_fetch_array($drugset))
				{
					$id2 = $rowd2['drugid'];
					$vol2 = $rowd2['volume'];
					$ddrug3 = mysqli_query($link, "select * from drug_id WHERE id = $id2 ");
					while ($rowd3 = mysqli_fetch_array($ddrug3))
					{
						$volold = $rowd3['volume'];
						$volout = $vld[$i]*$vol2;
						$volnew = $volold - $volout;
						//trackingsystem
						if($rowd3['track'] == 1)
						{
							$drugtrack = "tr_drug_".$id2; 
							mysqli_query($link, " INSERT INTO $drugtrack  (`date` , `ctz_id` , `pt_id` , `volume` )
											VALUES (now(), '$ctzid', '$ctmid', '$volout');  ");
						}	
						//tracking end
						// update drug_# here-------//
						$drugtable = "drug_".$id2;
						$ctv = $volout;
						$stock_out = mysqli_query($link, "select * from $drugtable ORDER BY `id` ASC  ");
						while ($ctv != 0)
						{
							while ($row_settings = mysqli_fetch_array($stock_out))
							{
								$dvolume = $row_settings['volume']; //get volume
								$dcustomer = $row_settings['customer']; //get vo.ume on customer
								$rowid = $row_settings['id'];
								if ($dcustomer < $dvolume) break 1;
							}
							$dleft = $dvolume - $dcustomer;
							if ($dleft <= $ctv)
							{
								// Update drug_id at volume and buyprice.
								$upvol = $dcustomer + $dleft;
								mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
								$ctv = $ctv - $dleft;
							}	
							else
							{
								// Update drug_id at volume and buyprice.
								$upvol = $dcustomer + $ctv;
								mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
								$ctv = 0;
							}	
						}	
						// update drug_# here---end----//
						mysqli_query($link, "UPDATE drug_id SET `volume` = '$volnew' WHERE id = $id2 ");
					}	
				}
			}	
		}
	//CHECK TO update reserve volume
	$volnew = $volold - $vld[$i];
	$rsvolnew = $rsvolold - $vld[$i];
	//check no reserve less than 0
	if ($volnew < 0) 
	{ 
	  $volnew = 0;
	  $rsvolnew = 0;
	 }
	 
	if($rsvolnew < 0) $rsvolnew = 0;
	
	mysqli_query($link, "UPDATE drug_id SET `volume` = '$volnew', `volreserve` = '$rsvolnew' WHERE id = $drgid ");

	}
	// account system 11000000-19999999 ลูกหนี้ คนไข้ค้างชำระ
	$ctmacno = 11000000 + $ctmid;
	$buytoday = $_SESSION['buyprice'];
	$olddeb = $_SESSION['olddeb'];
	$pbac = $_SESSION['pbac'];
	$paytoday = $_SESSION['paytoday'];
	$newdeb = $_SESSION['newdeb'];
	//sell account
	$sd = date("d");
	$sm = date("m");
	$sy = date("Y");
	$cashtoday = $paytoday - $olddeb;
	$own = ceil($buytoday - $cashtoday);
	if($cashtoday <=0)
	{
		$cashtoday = 0;
		$own = $buytoday;
	}
	$diags = $_SESSION['diag'];
	$diags = mysqli_real_escape_string($link, $diags);
	if(empty($tty)) $tty=0;
	$sql_insert = " INSERT INTO `sell_account` ( `day` , `month` ,`year` ,`ctmid` , `ctmacno` ,`payby_acno` , `pay` , `own` , `total`, `ddx`, `tty`, `vsdate`)
									VALUES ('$sd', '$sm', '$sy', '$ctmid', '$ctmacno', '$pbac', '$cashtoday', '$own', '$buytoday', '$diags', '$tty', '$visitdt');";
	mysqli_query($link, $sql_insert);
	
	//debtors account update
	if($newdeb <= 0)
	{
		mysqli_query($link, "DELETE FROM `debtors` WHERE `ctmid` = $ctmid ");
	}	
	elseif($newdeb > 0)
	{
		mysqli_query($link, "DELETE FROM `debtors` WHERE `ctmid` = $ctmid ");
		$sql_insert = "INSERT INTO debtors (`ctmid`,`ctmacno`,`price`)
						VALUES ('$ctmid','$ctmacno','$newdeb');";
		mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	}	
	
	//daily_account on debtor; 10000001 เงินสด
	if($olddeb !=0)
	{
		if($paytoday <= $_SESSION['olddeb'])
		{
			$sql_insert = " INSERT INTO `daily_account` ( `date` , `ac_no_i` , `ac_no_o` , `detail` , `price` , `type`, `bors`, `recordby`	)
											VALUES (now(), '$pbac', '$ctmacno', 'รับชำระหนี้', '$paytoday', 'd', 's','$_SESSION[user_id]' );";
			mysqli_query($link, $sql_insert);
		}
		elseif($paytoday >$olddeb)
		{
			$sql_insert = " INSERT INTO `daily_account` ( `date` , `ac_no_i` , `ac_no_o` , `detail` , `price` , `type`, `bors`, `recordby`	)
											VALUES (now(), '$pbac', '$ctmacno', 'รับชำระหนี้', '$olddeb', 'd','s','$_SESSION[user_id]' );";
			mysqli_query($link, $sql_insert);
		}	
	}
	
	//remove tmp table
	$sql_del = " DROP TABLE $tmp " ;
	mysqli_query($link, $sql_del);
	
	// Now Delete Patient from "pt_to_lab" table
	mysqli_query($link, "DELETE FROM pt_to_lab WHERE ptid = '$ctmid' ");
				  
	// Now Delete Patient from "pt_to_treatment" table
	mysqli_query($link, "DELETE FROM pt_to_treatment WHERE ptid = '$ctmid' ");
				  
	//remove patient form pt-to-drug
	$sql_del = "DELETE FROM `pt_to_drug` WHERE `id` = '$ctmid' ";
	mysqli_query($link, $sql_del);
	// go on to other step
	//check OPD Card printting
	$prt = mysqli_fetch_row(mysqli_query($link, "select prtopdcard from parameter where ID='1'"));
	$propdcard = $prt[0];
	//
	
	//next ตัดยอด วัตถุดิบ ถ้ามี
	// ส่วนของขา
	$_SESSION['TM']=0;
	include '../../libs/rawmatcut.php';
	//ส่วน ของ$_SESSION['idrx'.$i] Treatment
	{
	$_SESSION['TM']=1;
	include '../../libs/rawmatcut.php';
	}
	//unset _SESSION
	for($i=1;$i<=4;$i++)
	{
	  unset($_SESSION['idtr'.$i]);
	  unset($_SESSION['trvol'.$i]);
	}
	for($i=1;$i<=10;$i++)
	{
	  unset($_SESSION['idrx'.$i]);
	  unset($_SESSION['rxvol'.$i]);
	}
	unset($_SESSION['drugmax']);
	unset($_SESSION['tmax']);
	unset($_SESSION['TM']);

?>
