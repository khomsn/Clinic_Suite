<?php
if(!empty($dl[1])) {$fdo1 = "dgname NOT LIKE '%".$dl[1]."%'"; $fdo = $fdo1;}
if(!empty($dl[2])) 
    {
        $fdo2 = "dgname NOT LIKE '%".$dl[2]."%'";
        if (empty($fdo)) $fdo = $fdo2;
        else $fdo = $fdo." AND ".$fdo2;
    }
if(!empty($dl[3])) 
    {
        $fdo3 = "dgname NOT LIKE '%".$dl[3]."%'";
        if (empty($fdo)) $fdo = $fdo3;
        else $fdo = $fdo." AND ".$fdo3;
    }
if(!empty($dl[4]))
    {
        $fdo4 = "dgname NOT LIKE '%".$dl[4]."%'";
        if (empty($fdo)) $fdo = $fdo4;
        else $fdo = $fdo." AND ".$fdo4;
    }
if(!empty($dl[5]))
    {
        $fdo5 = "dgname NOT LIKE '%".$dl[5]."%'";
        if (empty($fdo)) $fdo= $fdo5;
        else $fdo = $fdo." AND ".$fdo5;
    }

for($i=1;$i<=5;$i++)
{
    if(!empty($dl[$i]))
    {
        $drug = mysqli_query($linkcm, "select * from druggeneric where name='$dl[$i]'");

        while ($drugrs = mysqli_fetch_array($drug))
        {
            $dgg[$i] = $drugrs['dgroup'];
            if (ltrim($dgg[$i]) === '') unset($dgg[$i]);
            $dgsg[$i] = $drugrs['dsgroup'];
            if (ltrim($dgsg[$i]) === '') unset($dgsg[$i]);
        }
        
        $drug = mysqli_query($linkcm, "select  distinct dgroup from druggeneric where dgroup='$dl[$i]'");

        while ($drugrs = mysqli_fetch_array($drug))
        {
            $dgg[$i] = $drugrs['dgroup'];
            if (ltrim($dgg[$i]) === '') unset($dgg[$i]);
        }
        
        $drug = mysqli_query($linkcm, "select distinct dsgroup from druggeneric where dsgroup='$dl[$i]'");

        while ($drugrs = mysqli_fetch_array($drug))
        {
            $dgsg[$i] = $drugrs['dsgroup'];
            if (ltrim($dgsg[$i]) === '') unset($dgsg[$i]);
        }
        
    }
}
//rearrange
for($i=1;$i<5;$i++)
{
    if(empty($dgg[$i]))
    {
        $dgg[$i] = $dgg[($i+1)]; 
        $dgg[($i+1)] = "";
    }
    if(empty($dgsg[$i]))
    {
        $dgsg[$i] = $dgsg[($i+1)];
        $dgsg[($i+1)]= "";
    }
}
if(!empty($dgg[1])) {$fgo1 = 'groupn != "'.$dgg[1].'"'; $fgo = $fgo1;}

if(!empty($dgg[2])) 
    {
        $fgo2 = 'groupn != "'.$dgg[2].'"';
        if (empty($fgo)) $fgo = $fgo2;
        else $fgo = $fgo." AND ".$fgo2;
    }
if(!empty($dgg[3])) 
    {
        $fgo3 = 'groupn != "'.$dgg[3].'"';
        if (empty($fgo)) $fgo = $fgo3;
        else $fgo = $fgo." AND ".$fgo3;
    }
if(!empty($dgg[4])) 
    {
        $fgo4 = 'groupn != "'.$dgg[4].'"';
        if (empty($fgo)) $fgo = $fgo4;
        else $fgo = $fgo." AND ".$fgo4;
    }
if(!empty($dgg[5])) 
    {
        $fgo5 = 'groupn != "'.$dgg[5].'"';
        if (empty($fgo)) $fgo = $fgo5;
        else $fgo = $fgo." AND ".$fgo5;
    }

if(!empty($dgsg[1])) {$fsg1 = 'subgroup != "'.$dgsg[1].'"'; $fsg = $fsg1;}
if(!empty($dgsg[2])) 
    {
        $fsg2 = 'subgroup != "'.$dgsg[2].'"';
        if (empty($fsg)) $fsg = $fsg2;
        else $fsg = $fsg." AND ".$fsg2;
    }
if(!empty($dgsg[3])) 
    {
        $fsg3 = 'subgroup != "'.$dgsg[3].'"';
        if (empty($fsg)) $fsg = $fsg3;
        else $fsg = $fsg." AND ".$fsg3;
    }
if(!empty($dgsg[4])) 
    {
        $fsg4 = 'subgroup != "'.$dgsg[4].'"';
        if (empty($fsg)) $fsg = $fsg4;
        else $fsg = $fsg." AND ".$fsg4;
    }
if(!empty($dgsg[5])) 
    {
        $fsg5 = 'subgroup != "'.$dgsg[5].'"';
        if (empty($fsg)) $fsg = $fsg5;
        else $fsg = $fsg." AND ".$fsg5;
    }

if (!empty($fdo) and !empty($fgo) and !empty($fsg))
{
    $fdout = "(".$fdo.") AND (".$fgo.") AND (".$fsg.")";
}
elseif(!empty($fdo) and !empty($fgo) and empty($fsg))
{
    $fdout = "(".$fdo.") AND (".$fgo.")";
}
elseif(!empty($fdo) and empty($fgo) and !empty($fsg))
{
    $fdout = "(".$fdo.") AND (".$fsg.")";
}
elseif(empty($fdo) and !empty($fgo) and !empty($fsg))
{
    $fdout = "(".$fgo.") AND (".$fsg.")";
}
elseif(!empty($fdo) and empty($fgo) and empty($fsg))
{
    $fdout = $fdo;
}
elseif(empty($fdo) and empty($fgo) and !empty($fsg))
{
    $fdout = $fsg;
}
elseif(empty($fdo) and !empty($fgo) and empty($fsg))
{
    $fdout = $fgo;
}
else
{
    $fdout = 1;
}
?>
