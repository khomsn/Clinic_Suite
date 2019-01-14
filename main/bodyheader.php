<?php 
echo "</head>";
if(!empty($_SESSION['user_background']))
{
echo "<body style='background-image: url(../".$_SESSION['user_background']."); background-size: cover;' alink='#000088' link='#006600' vlink='#660000'>";
}
else
{
echo "<body  style='background-image: url(../".WALLPAPER."); background-size: cover;'>";
}
?>
