<?php 
include '../../config/dbc.php';
page_protect();

if(!empty($_SESSION['DDx']) AND !$_SESSION['cddx'])
{
    $n = substr_count($_SESSION['DDx'], '-');
    //$str = 'hypertext;language;programming';
    $charsl = preg_split('/-/', $_SESSION['DDx']);
    
    for($i=0;$i<=$n;$i++)
    {

        $_SESSION['dx'.$i] = rtrim($charsl[$i], "1234567890 ");
        $_SESSION['DX'] = $i;
    }
    $_SESSION['cddx'] = 1;
}

if($_POST['add'] == "เพิ่ม") 
{
    //update post value first
    for($j=1;$j<=$_SESSION['DX'];$j++)
    {
        $diags = $_POST['diag'.$j];
        //echo $diags = mysqli_real_escape_string($link, $diags);
        $_SESSION['dx'.$j] = $diags;
    }
    
    $j=$_SESSION['DX'];
    //then check if it is valid post
    $dx = $_SESSION['dx'.$j];
    
    if(ltrim($dx)!=='')
    {
        $diags = $_POST['diag'.$j];
        //echo $diags = mysqli_real_escape_string($link, $diags);
        $_SESSION['dx'.$j] = $diags;
        $validid = 1;

       if(($_SESSION['DX']<5) AND $validid)
        {
            $_SESSION['DX'] = $_SESSION['DX']+1;
        }
        if(!$validid)
        {
            unset($_SESSION['dx'.$j]);
        }
    }
    else
    {
        $_SESSION['close'] = $_SESSION['close'] + 1;
        goto Goon;
    }
    $_SESSION['close'] = 0;
}

Goon:

if($_SESSION['close'] == 2)
{
    $_POST['todo'] = 'Diag';
}

for($n=1;$n<=$_SESSION['DX'];$n++)
{
    if($_POST['del'.$n]=='ลบ')
    {
        for($m=$n;$m<$_SESSION['DX'];$m++)
        {
            $j=$m+1;
            
            $_SESSION['dx'.$m]=$_SESSION['dx'.$j];
        }
        unset($_SESSION['dx'.$m]);
     
        if($_SESSION['DX']>1)
        {
            $_SESSION['DX'] = $_SESSION['DX']-1;
        }
    }
}

if($_POST['todo']=='Diag')
{
  for($j=1;$j<=$_SESSION['DX'];$j++)
  {
    if(!empty($_POST['diag'.$j]))
    {
        $dxx = $j."-".$_POST['diag'.$j];
        $_SESSION['ddx'] = $_SESSION['ddx'].$dxx." ";
        unset($_SESSION['dx'.$j]);
    }
  }
    unset($_SESSION['DX']);
    unset($_SESSION['close']);
    unset($_SESSION['cddx']);
echo '<script>window.opener.location.reload()</script>';
echo '<script>self.close()</script>';
   
}

if($_SESSION['DX']<1)
{ 
  $_SESSION['DX']=1;
  $_SESSION['dx']='';
}

$title = "::DDx::";
include '../../main/header.php';
include '../../libs/autodiag.php';
?>
</head>
<body>
<div id="content">
<form name="ddx" method="post" action="ddx.php">
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
	<tr>
		<td>
            <table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
            <tr><th>Order</th><th>Diagnosis</th><th>เพิ่ม</th><th>ลบ</th></tr>
			<?php 
			for($j=1;$j<=$_SESSION['DX'];$j++)
			{
			if($j==$_SESSION['DX'])
			{
			echo	"<tr><th>".$j."</th><th><input name='diag".$j."' type=text id=diag".$j;
			echo        " value=\"".$_SESSION['dx'.$j]."\" size=50 autofocus/></th>";
			}
			else
			{
			echo	"<tr><th>".$j."</th><th><input name='diag".$j."' type=text id=diag".$j;
			echo        " value=\"".$_SESSION['dx'.$j]."\" size=50 /></th>";
			}
			echo	    "<th><input type=submit name='add' value='เพิ่ม'></th>";
			echo	    "<th><input type=submit name='del".$j."' value='ลบ'></th>";
			echo	"</tr>";
			}
			//echo $_SESSION['ddx'];
			?>
            </table>
		</td>
	</tr>
	<tr><th><input type="submit" name="todo" value="Diag"/></th></tr>
</table>
<!--end menu-->
</form></div>
</body>
</html>
