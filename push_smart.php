<?php
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
for ($i=0;$i<sizeof($decoded->data);$i++) {
	if ($decoded->data[$i]->uuid === "2") {
		$myfile_power = fopen($o_tmp_1, "w");
		fwrite($myfile_power,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_power,"W=".$decoded->data[$i]->tuples[0][1]);
		fclose($myfile_power);
		rename($o_tmp_1,$output_power);
		$myfile_history = fopen($output_history, "a+");
		fwrite($myfile_history,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_history,"W=".$decoded->data[$i]->tuples[0][1]."\n");
		fclose($myfile_history);
	}else if ($decoded->data[$i]->uuid === "1") {
		$myfile_einspeise = fopen($o_tmp_3, "w");
		fwrite($myfile_einspeise,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_einspeise,"W=".$decoded->data[$i]->tuples[0][1]);
		fclose($myfile_einspeise);
		rename($o_tmp_3,$output_einspeise);
		$myfile_history = fopen($output_einspeise_history, "a+");
		fwrite($myfile_history,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_history,"W=".$decoded->data[$i]->tuples[0][1]."\n");
		fclose($myfile_history);
	}else if ($decoded->data[$i]->uuid === "0") {
		$myfile_verbrauch = fopen($o_tmp_2, "w");
		fwrite($myfile_verbrauch,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_verbrauch,"W=".$decoded->data[$i]->tuples[0][1]);
		fclose($myfile_verbrauch);
		rename($o_tmp_2,$output_verbrauch);
		$myfile_history = fopen($output_verbrauch_history, "a+");
		fwrite($myfile_history,"T=".$decoded->data[$i]->tuples[0][0].";");
		fwrite($myfile_history,"W=".$decoded->data[$i]->tuples[0][1]."\n");
		fclose($myfile_history);
	}
}
?>
