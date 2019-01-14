<?php
echo "<script>\n";
for($i=1;$i<=$popupmaxid;$i++)
{
echo "function myFunctionDrugpig".$i."() {\n";
echo "    var popupdrugpig".$i." = document.getElementById('myPopupdrugpig".$i."');\n";
echo "    popupdrugpig".$i.".classList.toggle('show');\n";
echo "}\n";
}
echo "</script>";
?>
