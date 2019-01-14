<?php 
include '../../config/dbc.php';

page_protect();

$id = $_SESSION['patdesk'];

// prepare and bind for drug_id update:
$stmt = $link->prepare("UPDATE drug_id SET `volreserve` = ? WHERE `id` = ? ");
$stmt->bind_param("ii", $volreserve, $id);

include '../../libs/ptdrugill.php';

include '../../libs/ccdrlist.php';

include '../../libs/settempfddi.php';

include '../../libs/dricilist.php';

$catc = $_SESSION['catc'];

include '../../libs/catcset.php';

include '../../libs/drugfilter.php';

//-------------------------------------------------------------------
//get drug from temp file

$filter = mysqli_query($link, "select * from $tmp");
//control count//

if($_SESSION['ORDER']==0)
{
    while ($row = mysqli_fetch_array($filter))
    {
        $_SESSION['DG']=1;
        for($t=1;$t<=10;$t++)
        {
            $idrx[$t]= $row['idrx'.$t];
            $rx[$t]=$row['rx'.$t];
            $rxg[$t]=$row['rxg'.$t];
            $rxuses[$t]=$row['rx'.$t.'uses'];
            $rxv[$t]=$row['rx'.$t.'v'];
            $rxby[$t]=$row['rxby'.$t];
            //echo $idrx[$t];
            /*    if($idrx[$t] >"0")
            {
            $_SESSION['DG']=$_SESSION['DG']+1;
            }*/
            $ptin = mysqli_query($link, "select * from drug_id where id='$idrx[$t]' AND $cat AND $fdout AND $foutlast  AND $fddi");
            while ($row2 = mysqli_fetch_array($ptin))
                {
                    $rxtypen[$t] =  $row2['typen'];
                    $rxsize[$t] = $row2['size'];
                    $rxsv[$t] = $row2['volume'];
                    $rxrserve[$t] = $row2['volreserve'];
                }
            //$row['id']."-".$row['dgname']."-".$row['dname']."-".$row['size']."-".$row['typen']
            if($idrx[$t]!=0 )
            {
                $_SESSION['drugid'.$t]= $idrx[$t];
                $_SESSION['drug'.$t]= $idrx[$t]."-".$rxg[$t]."-".$rx[$t]."-".$rxsize[$t]."-".$rxtypen[$t];
                $_SESSION['dgname'.$t] = $rxg[$t];
                $_SESSION['vol'.$t]=$rxv[$t];
                $_SESSION['inivol'.$t]=$rxv[$t];
                $_SESSION['uses'.$t]=$rxuses[$t];
                $_SESSION['svol'.$t]=$rxsv[$t];
                $_SESSION['svolr'.$t]=$rxrserve[$t];
                $_SESSION['rxby'.$t]=$rxby[$t];
            }
            elseif($idrx[$t]==0)
            {
                unset($_SESSION['drugid'.$t]);
                unset($_SESSION['drug'.$t]);
                unset($_SESSION['dgname'.$t]);
                unset($_SESSION['inivol'.$t]);
                unset($_SESSION['vol'.$t]);
                unset($_SESSION['uses'.$t]);
                unset($_SESSION['svol'.$t]);
                unset($_SESSION['svolr'.$t]);
            }
        }
    }

    for($t=1;$t<=10;$t++)
    {
        if(!empty($_SESSION['drug'.$t]))
        {
            $_SESSION['DG']=$_SESSION['DG']+1;
        }
    }
    //reorder drug need $_SESSION['drug'.$t], $_SESSION['inivol'.$t],unset($_SESSION['uses'.$t]);
    // new stack for record value 
    $j=1; $k=1;
    for($i=1;$i<=$_SESSION['DG'];$i++)
    {
        for($j=$k;$j<=10;$j++)
        {
            if(empty($_SESSION['drug'.$j]))
            {
                $k=$k+1;
            }
            else
            {
                //assign value to new stack
                $_SESSION['drugnew'.$i]=$_SESSION['drug'.$j];
                $_SESSION['dgnamenew'.$i]=$_SESSION['dgname'.$j];
                $_SESSION['volnew'.$i]=$_SESSION['vol'.$j];
                $_SESSION['inivolnew'.$i]=$_SESSION['inivol'.$j];
                $_SESSION['usesnew'.$i]=$_SESSION['uses'.$j];
                $_SESSION['svolnew'.$i]=$_SESSION['svol'.$j];
                $_SESSION['svolrnew'.$i]=$_SESSION['svolr'.$j];
                $_SESSION['rxbynew'.$i]=$_SESSION['rxby'.$j];
                $k=$k+1;
        //	    $j=$j+1;
                //now unset old $_SESSION['drug'.$t], $_SESSION['inivol'.$t],unset($_SESSION['uses'.$t]);
                unset($_SESSION['drug'.$j]);
                unset($_SESSION['dgname'.$j]);
                unset($_SESSION['vol'.$j]);
                unset($_SESSION['inivol'.$j]);
                unset($_SESSION['uses'.$j]);
                unset($_SESSION['svol'.$j]);
                unset($_SESSION['svolr'.$j]);
                unset($_SESSION['rxby'.$j]);
                goto nexti;
            }
        }
        nexti:
    }
    // now put data back to $_SESSION['drug'.$t], $_SESSION['inivol'.$t],$_SESSION['uses'.$t];
    for($i=1;$i<=$_SESSION['DG'];$i++)
    {
        $_SESSION['drug'.$i]=$_SESSION['drugnew'.$i];
        $_SESSION['dgname'.$i]=$_SESSION['dgnamenew'.$i];
        $_SESSION['vol'.$i]=$_SESSION['volnew'.$i];
        $_SESSION['inivol'.$i]=$_SESSION['inivolnew'.$i];
        $_SESSION['uses'.$i]=$_SESSION['usesnew'.$i];
        $_SESSION['svol'.$i]=$_SESSION['svolnew'.$i];
        $_SESSION['svolr'.$i]=$_SESSION['svolrnew'.$i];
        $_SESSION['rxby'.$i]=$_SESSION['rxbynew'.$i];
        //clear data at new stack
        unset($_SESSION['drugnew'.$i]);
        unset($_SESSION['dgnamenew'.$i]);
        unset($_SESSION['volnew'.$i]);
        unset($_SESSION['inivolnew'.$i]);
        unset($_SESSION['usesnew'.$i]);
        unset($_SESSION['svolnew'.$i]);
        unset($_SESSION['svolrnew'.$i]);
        unset($_SESSION['rxbynew'.$i]);
    }
      
    ////control count//set to 1
    $_SESSION['ORDER']=1;
    //
}

$filter = mysqli_query($link, "select * from drug_id");
while ($row = mysqli_fetch_array($filter))
{
    if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
}

if ($_SESSION['DG']<1)
{ 
  $i=1; 
  $_SESSION['DG']=1;
}

else $i = $_SESSION['DG'];

//check ddiltemp and change 
$_SESSION['ddiltemp_'.$id] = $_POST['ddiltemp'];


if($_POST['add'] == 'เพิ่ม') 
{ 
  
  if ($_SESSION['DG']<1) 
  {
    $_SESSION['DG']=1;
    $i = $_SESSION['DG'];
  }
  
  for($j=1;$j<=$i;$j++)
    {
        $_SESSION['drug'.$j]=$_POST['drug'.$j];
        $_SESSION['uses'.$j]=$_POST['uses'.$j];
//        $_SESSION['oldvol'.$j]= $_SESSION['inivol'.$j];
        $_SESSION['vol'.$j]=$_POST['vol'.$j];
        //
        $drug = $_SESSION['drug'.$j];
        $drugid[$j] = strstr($drug, '-', true); 
        //echo $drugid[$j]; if no drug id no add line 
        if($drugid[$j]<=0)
        {
            $_SESSION['close'] = $_SESSION['close'] + 1;
            goto Goon;
        }
        $ptin = mysqli_query($link, "select * from drug_id where id='$drugid[$j]' AND $cat AND $fdout AND $foutlast  AND $fddi");
        while ($row2 = mysqli_fetch_array($ptin))
            {
                $_SESSION['dgname'.$j]= $row2['dgname'];
                $rxuses[$j] =  $row2['uses'];
                //check and compair uses order
                if(empty($_SESSION['uses'.$j]))
                {
                    $_SESSION['uses'.$j] = $rxuses[$j];
                }
                $_SESSION['svol'.$j] =  $row2['volume'];
                $_SESSION['svolr'.$j] = $row2['volreserve'];
            }
        //
    }
  if($_SESSION['DG']<10)
    {
        $_SESSION['DG'] = $_SESSION['DG']+1;
        $i = $_SESSION['DG'];
        
    }
//header("Location: drugorder.php");  
}

Goon:

if($_SESSION['close'] == 2)
{
    $_POST['todo'] = 'Close';
}

for($n=1;$n<=10;$n++)
{
  if($_POST['del'.$n]=='ลบ')
  {
    $i = $_SESSION['DG'];
    //check ddiltemp
    $_SESSION['ddiltemp_'.$id] = $_POST['ddiltemp'];
    
 
    //update drug_id at volreserve return volume.
    //update reserve volume at drug_id
    
    $volreserve = $_SESSION['svolr'.$n] - $_SESSION['vol'.$n] ;
    if($volreserve<0) $volreserve=0;
    
    $id=$_SESSION['drugid'.$n];
    
    $stmt->execute();
    ///update reserve volume at drug_id end
    
    unset($_SESSION['vol'.$n]);
    unset($_SESSION['inivol'.$n]);
    unset($_SESSION['dgname'.$n]);
    unset($_SESSION['svol'.$n]);
    unset($_SESSION['svolr'.$n]);

    //
 
   for($m=$n;$m<=$i;$m++)
     {
      $n=$n+1;
      $_SESSION['drug'.$m]=$_SESSION['drug'.$n];
      $_SESSION['vol'.$m]=$_SESSION['vol'.$n];
      $_SESSION['inivol'.$m]=$_SESSION['inivol'.$n];
      $_SESSION['uses'.$m]=$_SESSION['uses'.$n];
      $_SESSION['rxby'.$m]=$_SESSION['rxby'.$n];
     }
    for($j=1;$j<=$i;$j++)
    {
        $drug = $_SESSION['drug'.$j];
        $drugid[$j] = strstr($drug, '-', true); 
        //echo $drugid[$j];
        $ptin = mysqli_query($link, "select * from drug_id where id='$drugid[$j]' AND $cat AND $fdout AND $foutlast  AND $fddi");
        while ($row2 = mysqli_fetch_array($ptin))
        {
            $_SESSION['drugid'.$j]= $row2['id'];
            $_SESSION['dgname'.$j]= $row2['dgname'];
            $rxuses[$j] =  $row2['uses'];
            $_SESSION['svol'.$j] =  $row2['volume'];
            $_SESSION['svolr'.$j] = $row2['volreserve'];
        }
    }
    unset($_SESSION['drug'.$i]);
    unset($_SESSION['drugid'.$i]);
    unset($_SESSION['uses'.$i]);
    unset($_SESSION['rxby'.$i]);
    $_SESSION['DG'] = $_SESSION['DG']-1;
    $i = $_SESSION['DG'];
  }
  if ($_SESSION['DG']<1)
  { 
    $i=1; 
    $_SESSION['DG']=1;
  }
  
}

if($_POST['todo']=='Close')
{
   //Set to no reload from same page
   $_SESSION['Prescription'] = 1;
   
    for($a=1;$a<=10;$a++)
    {
      $_SESSION['drug'.$a]=$_POST['drug'.$a];
      $drug = $_SESSION['drug'.$a];
      $drugid[$a] = strstr($drug, '-', true); 
    }
    for($a=1;$a<=10;$a++)
    {
        $idrx[$a] = $drugid[$a];
        
        $ptin = mysqli_query($link, "select * from drug_id where id='$idrx[$a]' AND $cat AND $fdout AND $foutlast  AND $fddi");
        while ($row2 = mysqli_fetch_array($ptin))
            {
                $rx[$a] =  $row2['dname'].'-'.$row2['size'];
                $rxgn[$a] = $row2['dgname'];
                $rxuses[$a] =  $row2['uses'];
                $rxv[$a] =  $_POST['vol'.$a];
                $_SESSION['svol'.$a] =  $row2['volume'];
                $_SESSION['uses'.$a]= $_POST['uses'.$a];
                //check uses if change from default
                if($_SESSION['uses'.$a]!=$rxuses[$a])
                {
                    $rxuses[$a]=$_SESSION['uses'.$a];
                }
                $_SESSION['vol'.$a]= $_POST['vol'.$a];
                $_SESSION['svolr'.$a] = $row2['volreserve'];
                $candp = $row2['candp'];
                if($candp ==1){$_SESSION['course']=1;}
                if($candp ==2){$_SESSION['prolab']=1;}
            }
        //check for blocking drug and reset $idrx[$a] from $rxgn[$a]
        if(empty($rxgn[$a]))
        {
            $idrx[$a]=0;
        }
   // }
   // for($a=1;$a<=10;$a++)
   // {
    // check to empty data if no idrx = 0
        if($idrx[$a]==0)
        {
            $rx[$a] = '';
            $rxuses[$a] = '';
            $rxv[$a] = '';
        }
        $us = "rx".$a."uses";
        $vl = "rx".$a."v";
        $svl = "rx".$a."sv";
        $idp = $idrx[$a];
        $rxp = $rx[$a];
        $rgp = $rxgn[$a];
        $usp = $rxuses[$a];
        $vlp = $rxv[$a];
        $svlp = $_SESSION['svol'.$a];
        if(empty($vlp)) $vlp=0; //mysql not null
        if(empty($svlp)) $svlp=0; //mysql not null
        mysqli_query($link, "UPDATE $tmp SET
            `idrx$a` = '$idp',
            `rx$a` = '$rxp',
            `rxg$a` = '$rgp',
            `$us` = '$usp',
            `$vl` = '$vlp',
            `$svl` = '$svlp'
            ") or die(mysqli_error($link));
            
        //update reserve volume at drug_id $_SESSION['svolr'
        $_SESSION['svolr'.$a] = $vlp - $_SESSION['inivol'.$a] + $_SESSION['svolr'.$a];
        //check not reserve less than 0
        if ($_SESSION['svolr'.$a] < 0) $_SESSION['svolr'.$a] = 0;
        //now update execute
        $volreserve=$_SESSION['svolr'.$a];
        $id=$idrx[$a];
        $stmt->execute();
        
        unset($_SESSION['drug'.$a]);
        unset($_SESSION['uses'.$a]);
        unset($_SESSION['vol'.$a]);
        unset($_SESSION['inivol'.$a]);
        unset($_SESSION['svolr'.$a]);
        unset($_SESSION['rxby'.$a]);
    }
    unset($_SESSION['DG']);
    unset($_SESSION['ORDER']);
    unset($_SESSION['close']);
    $_SESSION['ORDER']=0;// set to 0 for sure
    $stmt->close();
    
    echo '<script>window.opener.location.reload()</script>';
    echo '<script>self.close()</script>';
}
$title = "::Prescription::";
include '../../main/header.php';
include '../../libs/autodrug.php';
include '../../libs/autoorder.php';
?>
<script type='text/javascript'>
$(document).ready(function() { 
   $('input[name=ddiltemp]').change(function(){
        $('form').submit();
   });
  });
</script>
</head>
<body>
<div id="content">
<form name="ddx" method="post" action="drugorder.php">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr>
    <td><?php echo "BW = ".$_SESSION['weight']." kg ";  echo "DDIL default = ".$_SESSION['ddil'];?> [กำหนด DDIL เฉพาะรายนี้:
    <?php 
    if(empty($_SESSION['ddiltemp_'.$id]) AND ($_SESSION['ddiltemp_'.$id]!=='0'))
    {
        $_SESSION['ddiltemp_'.$id]=$_SESSION['ddil'];
    }
    ?>
    <input name='ddiltemp' type='radio' value='0' <?php if($_SESSION['ddiltemp_'.$id]==0) echo 'checked';?>>0 
    <input name='ddiltemp' type='radio' value='1' <?php if($_SESSION['ddiltemp_'.$id]==1) echo 'checked';?>>|1| 
    <input name='ddiltemp' type='radio' value='2' <?php if($_SESSION['ddiltemp_'.$id]==2) echo 'checked';?>>|2| 
    <input name='ddiltemp' type='radio' value='3' <?php if($_SESSION['ddiltemp_'.$id]==3) echo 'checked';?>>|3|]&nbsp; &nbsp; &nbsp; &nbsp;
    <a HREF="prescriptold.php" >(ยาเก่า)</a> 
    <table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
      <tr><th width = 10 >No</th><th width = 250px >ชื่อ+ขนาด</th><th>วิธีใช้</th><th width =50px>จำนวน</th><th>เพิ่ม</th><th>ลบ</th></tr>
	    <?php 
	    for($j=1;$j<=$i;$j++)
	    {
            if($j==$i)
            {
                echo "<tr><td>".$j."</td><td><input autofocus name='drug".$j."' type=text id=drug".$j;
                echo " value='".$_SESSION['drug'.$j]."' size=44% /></td>";
            }
            else
            {
                echo "<tr><td>".$j."</td><td><input name='drug".$j."' type=text id=drug".$j;
                echo " value='".$_SESSION['drug'.$j]."' size=44%/></td>";
            }
            echo "<td>";
            echo "<input type='search' id=ordertxt$j size=40% name='uses".$j."' value='".$_SESSION['uses'.$j]."'>";
            echo "</td>";
            echo "<td>";
            echo "<input type='hidden' name='oldvol".$j."' value='".$_SESSION['vol'.$j]."'>";
            echo "<input type='number' tabindex=".$j." class=typenumber name='vol".$j."' min=0 step=1 max='".$max=($_SESSION['svol'.$j]-$_SESSION['svolr'.$j]+$_SESSION['vol'.$j])."' value='";
            if(empty($_SESSION['vol'.$j]) and ($j<=$i))
            {
                if($max>=1) echo "1";
                else echo "0";
            }
            if(!empty($_SESSION['vol'.$j])) echo $_SESSION['vol'.$j];
            echo "'>";
            echo "</td>";
            echo "<td><input type=submit name='add' value='เพิ่ม'></td>";
            echo "<td>";
            if($_SESSION['rxby'.$j]==0)
            {
                echo "<input type=submit name='del".$j."' value='ลบ'>";
            }
            echo "</td></tr>";
	    }
	    ?>
      </table>
    </td>
  </tr>
  <tr><th><input type="submit" name="todo" value="Close" onClick="reloadParentAndClose();"/></th></tr>
</table>
<!--end menu-->
</form></div>
</body>
</html>
