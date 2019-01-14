<?php

//get drug allergy and chronic illness condition

$ptin = mysqli_query($linkopd, "SELECT * FROM patient_id where id='$id' ");
while($row = mysqli_fetch_array($ptin)) 
{
	$dl[1] = $row['drug_alg_1'];
	$dl[2] = $row['drug_alg_2'];
	$dl[3] = $row['drug_alg_3'];
	$dl[4] = $row['drug_alg_4'];
	$dl[5] = $row['drug_alg_5'];
	$chron[1] = $row['chro_ill_1'];
	$chron[2] = $row['chro_ill_2'];
	$chron[3] = $row['chro_ill_3'];
	$chron[4] = $row['chro_ill_4'];
	$chron[5] = $row['chro_ill_5'];
	$concurdrug = $row['concurdrug'];
}
?>
