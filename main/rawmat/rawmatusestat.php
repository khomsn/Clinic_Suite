<?php 
include '../../config/dbc.php';
page_protect();
include '../../libs/progdate.php';

$title = "::ห้องคลังวัตถุดิบ::";
include '../../main/header.php';
include '../../libs/currency.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="160" valign="top"><div class="pos_l_fix">
    <?php
        if (isset($_SESSION['user_id']))
        {
            include 'rawmatmenu.php';
        } 
    ?></div>
    </td><td>
        <?php 
        $dtemp = mysqli_query($link, "select * from drugcombset ORDER BY `id` ASC ");
        while($row=mysqli_fetch_array($dtemp))
        { //(`id`, `drugidin`, `invol`, `drugidout`, `outvol`, `outsetpoint`, `outcount`) 
            $k = $row['id'];
            //  $idin[$k] = $row['drugidin'];
            //  $invol[$k] = $row['invol'];
            $idout[$k] = $row['drugidout'];
            $outvol[$k] = $row['outvol'];
            $outsetpoint[$k] = $row['outsetpoint'];
            $outcount[$k] = $row['outcount'];
        }

        for($l=1;$l<=$k;$l++)
        {
            if(mb_substr($idout[$l], 0, 1 ) =="r" or mb_substr( $idout[$l], 0, 1 ) =="R")
            {
                $rawid = substr($idout[$l], 1);
                
                $alloutcount[$rawid] = $alloutcount[$rawid]+$outcount[$l] * $outvol[$l];
                
                mysqli_query($link, "UPDATE drugcombset SET `outcount` = '0' WHERE `id` = '$l' ")  or die(mysqli_error($link));
            
                $idin[$l] = $rawid;
                
                for($n=1;$n<=$l;$n++)
                {
                
                    if(empty($rwinid[$n]))
                    {
                        $rwinid[$n]=$idin[$l];
                        $nrw = $nrw+1;
                        $rowidin[$nrw] = $l;
                        
                        goto Next1;
                    }
                    
                    if(($rwinid[$n]!=$idin[$l]) AND (empty($rwinid[$n])))
                    {
                        $rwinid[$n]=$idin[$l];
                        $nrw = $nrw+1;
                        $rowidin[$nrw] = $l;
                        
                        goto Next1;
                        
                    }
                    
                    Next1:
        
                    
                    if($rwinid[$n]==$idin[$l])
                    {
                        //$alloutcount[$n] = $alloutcount[$n] + $outcount;
                        goto Next3;
                    }
                    
                    if(($rwinid[$n]!=$idin[$l]) AND (!empty($rwinid[$n])))
                    {
                        goto Next2;
                        
                    }
                    Next2:
                }
            }
        Next3: 
        }

        for($a=1;$a<=$nrw;$a++)
        {
            $tvol[$a] = $alloutcount[$rwinid[$a]];
            if($tvol[$a]<$outsetpoint[$rowidin[$a]])
            {
                $newoutcount[$a] =  ($tvol[$a]%$outsetpoint[$rowidin[$a]])/$outvol[$rowidin[$a]];
                goto JPincaseof0;
            }
            if($tvol[$a]>=$outsetpoint[$rowidin[$a]])
            {
                $ctv = floor($tvol[$a]/$outsetpoint[$rowidin[$a]]);
                $acvol = $ctv;
                //new outcount
                $newoutcount[$a] =  ($tvol[$a]%$outsetpoint[$rowidin[$a]])/$outvol[$rowidin[$a]];
                
                {/*****************cutrm start********************/
                $drugidout[$a] = $rwinid[$a];
                $drugtable = "rawmat_".$drugidout[$a];
                $tabletoupdate = "rawmat";
                $ddrug3 = mysqli_query($link, "select * from rawmat WHERE id = $drugidout[$a] ");

                while ($rowd3 = mysqli_fetch_array($ddrug3))
                {
                    $volold = $rowd3['volume'];
                    $dacno = $rowd3['ac_no']; //get account no into stock
                }

                $stock_out = mysqli_query($link, "select * from $drugtable ORDER BY `id` ASC ");
                while ($ctv > 0)
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
                    if ($dleft <= $ctv)
                    {
                        // Update drug_id at volume and buyprice.
                        $upvol = $dcustomer + $dleft;
                        mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
                        $ctv = $ctv - $dleft;
                    }
                    else
                    {
                        // Update drug_id at volume
                        $upvol = $dcustomer + $ctv;
                        mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
                        $ctv = 0;
                    }
                }
                // update drug_# here---end----//
                //CHECK TO update reserve volume
                if($acvol>0)
                {
                    $volnew = $volold - $acvol;

                    if($volnew<0)
                    {
                        $volnew=0;
                        $acvol = $volold;

                        if($acvol>0)
                        {
                            // Price to cut
                            $alldp = $price*$acvol;
                            //if price is 0 don't record in account system
                            if($alldp == 0) goto Next_rawmat1;
                            // assign insertion pattern
                            $detail ="เบิกใช้ จำนวน ".$acvol;

                            $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
                            VALUES  (now(),'59999999','$dacno','$detail','$alldp','c','0')";
                            // Now insert Drug order information to "rawmat_#id" table
                            mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
                            //
                            Next_rawmat1:
                            //record use stat
                            $sql_insert = "INSERT into `rawmattouse`	(`date`, `rawmatid`, `volume`,`user`)
                            VALUES  (now(),'$drugidout[$a]','$acvol','0')";
                            // Now insert Drug order information to "rawmat_#id" table
                            mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));


                        }

                        $err[] = "Not enough inventory to Cut";
                        goto JPincaseof0;
                    }

                    mysqli_query($link, "UPDATE $tabletoupdate SET `volume` = '$volnew' WHERE `id` = '$drugidout[$a]' ")  or die(mysqli_error($link));

                    //account system and stat
                    if($acvol>0)
                    {
                        // Price to cut
                        $alldp = $price*$acvol;
                        //if price is 0 don't record in account system
                        if($alldp == 0) goto Next_rawmat2;
                        // assign insertion pattern
                        $detail ="เบิกใช้ จำนวน ".$acvol;

                        $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
                        VALUES  (now(),'59999999','$dacno','$detail','$alldp','c','0')";
                        // Now insert Drug order information to "rawmat_#id" table
                        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
                        //
                        Next_rawmat2:
                        //record use stat
                        $sql_insert = "INSERT into `rawmattouse`	(`date`, `rawmatid`, `volume`,`user`)
                        VALUES  (now(),'$drugidout[$a]','$acvol','0')";
                        // Now insert Drug order information to "rawmat_#id" table
                        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));


                    }
                }
                }/***************************cutrm finished****************/
            }
            JPincaseof0:
            mysqli_query($link, "UPDATE drugcombset SET  `outcount` = '$newoutcount[$a]' WHERE  id = '$rowidin[$a]'") or die(mysqli_error($link));   
        }
    ?>
<!-- auto rawmat sum and cut stock end-->
    <h3 class="titlehdr">ยอดการใช้ RawMat ประจำเดือน <?php $m = $sm;// date("m");
    switch ($m)
    {
        case 1:
        echo "มกราคม";
        break;
        case 2:
        echo "กุมภาพันธ์";
        break;
        case 3:
        echo "มีนาคม";
        break;
        case 4:
        echo "เมษายน";
        break;
        case 5:
        echo "พฤษภาคม";
        break;
        case 6:
        echo "มิถุนายน";
        break;
        case 7:
        echo "กรกฎาคม";
        break;
        case 8:
        echo "สิงหาคม";
        break;
        case 9:
        echo "กันยายน";
        break;
        case 10:
        echo "ตุลาคม";
        break;
        case 11:
        echo "พฤศจิกายน";
        break;
        case 12:
        echo "ธันวาคม";
        break;
    }?> พ.ศ. <?php echo $bsy; //date("Y")+543;?></h3>
    <table class='TFtable' style="text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
        <tr><th>ID</th><th>Code</th><th>ชื่อ</th><th>Size</th><th>Stock-Vol</th><th>Buy-Vol</th><th>Used-Vol</th><th>Supp.</th></tr>
        <?php
        if($sm == date("m") and $sy == date("Y")) $imax = date("d");
        elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
        elseif($sm == 2 and $sy%4 == 0) $imax = 29;
        elseif($sm == 2 and $sy%4 != 0) $imax = 28;
        else $imax = 30;

        $pin = mysqli_query($link, "select MAX(id) from rawmat");
        $rid = mysqli_fetch_array($pin);
        $pin = mysqli_query($link, "select * from rawmat  ORDER BY `rmtype` ASC ,`rawcode` ASC ,`rawname` ASC");
        $i=1;
        while($row = mysqli_fetch_array($pin))
        {
            $did[$i] = $row['id'];
            $didacno[$i] = $row['ac_no'];
            $i=$i+1;
        }
        for ($i = 1;$i<=$rid[0];$i++)
        {
            // Print out the contents of each row into a table
            $drawcode = mysqli_query($link, "select * from rawmat WHERE id=$did[$i]");
            $ridinf = mysqli_fetch_array($drawcode);
            if(!empty($ridinf['rawcode']))
            {
                echo "<tr><th >";
                echo $i;
                echo "</th><th style='text-align: left;' >";
                echo $ridinf['rawcode'];
                echo "</th><th style='text-align: left;'>";
                echo $ridinf['rawname'];
                echo "</th><th>";
                echo $ridinf['size'];
                echo "</th><th>";
                echo $ridinf['volume'];
                echo "</th><th>";
                $dtype = mysqli_query($link, "SELECT * FROM rawmat_$did[$i] WHERE MONTH(date) = '$sm' AND YEAR(date) = '$sy'");
                while($row = mysqli_fetch_array($dtype))
                {
                    //$druguse[$i] = $druguse[$i] + $row['customer'];
                    $drugbuy[$i] = $drugbuy[$i] + $row['volume'];
                    $supp[$i] = $row['supplier'];
                } 
                echo $drugbuy[$i];
                echo "</th><th>";
                //take from rawmattouse table , all rawmat cut record here.
                $rawmatupmin = mysqli_query($link, "SELECT * FROM rawmattouse WHERE rawmatid = '$did[$i]' AND MONTH(date) = '$sm' AND YEAR(date) = '$sy'");
                while ($rawmatupmo = mysqli_fetch_array($rawmatupmin))
                {
                    $rmvol[$i] = $rmvol[$i]+$rawmatupmo['volume'];
                }
                echo $rmvol[$i];
                //echo $druguse[$i];
                echo "</th><th>";
                echo $supp[$i];
                echo "</th></tr>";
                //update allrsupm table price calculation
                $dtype = mysqli_query($link, "SELECT * FROM rawmat_$did[$i]");
                while ($row1 =  mysqli_fetch_array($dtype))
                {
                    $allprice[$i] = $allprice[$i]+$row1['price']*$row1['customer']/$row1['volume'];
                }
                //get previous month price
                
                /*** allrsupm drugid use ac_no for drug and rawmat = $didacno[$i] ****/
                
                $allrsu = mysqli_query($link, "select * from allrsupm WHERE drugid=$didacno[$i] AND mandy < date('$sy-$sm-01')");
                while ($row1 =  mysqli_fetch_array($allrsu))
                {
                    $omprice[$i] = $omprice[$i]+$row1['price'];
                }
                //get this month price
                $allrsu = mysqli_query($link, "select * from allrsupm WHERE drugid=$didacno[$i] AND MONTH(mandy) = '$sm' AND YEAR(mandy) = '$sy'");
                $rinfalrsu = mysqli_fetch_array($allrsu);
                $rowid = $rinfalrsu['id'];
                $tmprice[$i] = $rinfalrsu['price'];
                //check for month to update  if not this current month not update data

                if( (date('m') == $sm) AND (date('Y') == $sy))
                {
                    if(empty($rowid))
                    {
                        $tmprice[$i] = $allprice[$i]-$omprice[$i];
                        
                        if ($tmprice[$i]<0) $tmprice[$i] = 0;
                        
                        $sql_insert = "INSERT into `allrsupm`
                            (`drugid`,`mandy`,`price`)
                        VALUES
                            ('$didacno[$i]',now(),'$tmprice[$i]')";
                        // Now insert Patient to "patient_id" table
                        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
                    }
                    else
                    { 
                        $tmpricenew = $allprice[$i]-$omprice[$i];
                        
                        if ($tmpricenew<0)$tmpricenew = 0;
                        
                        $sql_insert = "UPDATE `allrsupm` SET `mandy` = now(),`price` = '$tmpricenew'
                                        WHERE id=$rowid LIMIT 1 ; 
                                        ";

                        // Now insert Patient to "patient_id" table
                        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
                    }
                }
            }
        }
    ?>
    </table>
</td>
<td width="160" valign="top">
    <div class="pos_r_fix_mypage1">
        <?php 
        if (isset($_SESSION['user_id']))
        {
            include 'rawusmenu.php';
        } 
        ?>
    </div>	
</td></tr>
</table>
</body></html>
