<?php
$hostString="host=localhost port=5432 dbname=smarthome user=mila password=mila2009";

$output_power = "/var/www/html/smart_data/smart_meter_power.data";
$output_verbrauch = "/var/www/html/smart_data/smart_meter_verbrauch.data";
$output_einspeise = "/var/www/html/smart_data/smart_meter_einspeise.data";
$output_history = "/var/www/html/smart_data/smart_meter_power_history.data";
$output_einspeise_history = "/var/www/html/smart_data/smart_meter_einspeise_history.data";
$output_verbrauch_history = "/var/www/html/smart_data/smart_meter_verbrauch_history.data";



$number = rand(1, 1000000);
$o_tmp_1 = "/tmp/data1".$number; //power
$o_tmp_2 = "/tmp/data2".$number; //verbrauch
$o_tmp_3 = "/tmp/data3".$number; //einspeise
$rawdata = file_get_contents("php://input");
$decoded = json_decode($rawdata);
$conn = pg_connect($hostString);
$moment = 0;$einspeise = 0;$verbrauch = 0;
for ($i=0;$i<sizeof($decoded->data);$i++) {
	if ($decoded->data[$i]->uuid === "2") {
		$moment = $decoded->data[$i]->tuples[0][1];
		$myfile_power = fopen($o_tmp_1, "w");
		fwrite($myfile_power,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_power,"W=".$moment);
		fclose($myfile_power);
		rename($o_tmp_1,$output_power);
		$myfile_history = fopen($output_history, "a+");
		fwrite($myfile_history,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_history,"W=".$moment."\n");
		fclose($myfile_history);
		$query="insert into smartmeter_momentleistung (zeitpunkt, momentleistung) values (now(), $moment);";
		$data= pg_query($conn, $query);
	}else if ($decoded->data[$i]->uuid === "1") {
		$einspeise = $decoded->data[$i]->tuples[0][1];
		$myfile_einspeise = fopen($o_tmp_3, "w");
		fwrite($myfile_einspeise,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_einspeise,"W=".$einspeise);
		fclose($myfile_einspeise);
		rename($o_tmp_3,$output_einspeise);
		$myfile_history = fopen($output_einspeise_history, "a+");
		fwrite($myfile_history,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_history,"W=".$einspeise."\n");
		fclose($myfile_history);
		$query="insert into smartmeter_einspeisung (zeitpunkt, einspeisung) values (now(), $einspeise);";
		$data= pg_query($conn, $query);
	}else if ($decoded->data[$i]->uuid === "0") {
		$verbrauch = $decoded->data[$i]->tuples[0][1];
		$myfile_verbrauch = fopen($o_tmp_2, "w");
		fwrite($myfile_verbrauch,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_verbrauch,"W=".$verbrauch);
		fclose($myfile_verbrauch);
		rename($o_tmp_2,$output_verbrauch);
		$myfile_history = fopen($output_verbrauch_history, "a+");
		fwrite($myfile_history,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_history,"W=".$verbrauch."\n");
		fclose($myfile_history);
		$query="insert into smartmeter_verbrauch (zeitpunkt, verbrauch) values (now(), $verbrauch);";
		$data= pg_query($conn, $query);
	}
}
pg_close($conn);
?>
