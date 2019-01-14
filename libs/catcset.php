<?php
$tmp = "tmp_".$id;

//check preg condition
$preg = mysqli_query($link, "select preg from $tmp");

while ($row = mysqli_fetch_array($preg)) $preg=$row['preg'];

if($preg==1)
{
    $cat = '(cat = "A" or cat = "B" or cat = "N")';
    if($catc ==1)
    {
        $cat = '(cat = "A" or cat = "B" or cat = "C" or cat = "N")';
    }
}
else $cat=1;
?>
