#!/bin/bash

BASEDIR=/var/www/html/smart_data/
OUTPUT=(smart_meter_einspeise.data smart_meter_verbrauch.data)
SUMMARY=(smart_meter_einspeise_summary_monthly.data smart_meter_verbrauch_summary_monthly.data)

for ((i=0; i<${#OUTPUT[@]}; i++))
do
	DATA=`cat $BASEDIR${OUTPUT[$i]}`
	printf "$DATA\n" >> $BASEDIR${SUMMARY[$i]}
done
