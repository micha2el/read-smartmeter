#!/bin/bash

BASEDIR=/var/www/html/smart_data/
OUTPUT=(smart_meter_einspeise_history.data smart_meter_power_history.data smart_meter_verbrauch_history.data)
TMP=/tmp/smart_log_roll.data

# length in seconds
LENGTH=86400 
TIME=`date +"%s"`
TIME_BORDER=$(($TIME-$LENGTH))

for ((i=0; i<${#OUTPUT[@]}; i++))
do
	while IFS= read -r line; do
		TIME=${line%;*}
		TIME=${TIME:2:10}
		if [[ "$TIME" -gt "$TIME_BORDER" ]]; then
			printf "$line\n" >> $TMP
	  	fi
	done < $BASEDIR${OUTPUT[$i]}
	mv $BASEDIR${OUTPUT[$i]} $BASEDIR${OUTPUT[$i]}"_bak"
	mv $TMP $BASEDIR${OUTPUT[$i]}
	chown www-data.www-data $BASEDIR${OUTPUT[$i]}
done
