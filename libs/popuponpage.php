<?php
echo "<script>\n";
for($i=1;$i<=$popupmaxid;$i++)
{
echo "function myFunction".$i."() {\n";
echo "    var popup".$i." = document.getElementById('myPopup".$i."');\n";
echo "    popup".$i.".classList.toggle('show');\n";
echo "}\n";
}
echo "function myFunction() {\n";
echo "    var popup = document.getElementById('myPopup');\n";
echo "    popup.classList.toggle('show');\n";
echo "}\n";
echo "</script>";
?>
