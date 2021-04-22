#!/bin/bash

DEVICE=/dev/ttyUSB0
OUTPUT=/var/www/html/smart_meter.data
DELIMITER=1b1b1b1b
# VALUES for current power
START=77070100100700ff0101621b520055
END=0177070100240700ff

DATA=`timeout 2s cat $DEVICE | hexdump -e '16/1 "%02x"'`

s=$DATA$DELIMITER
array=();
while [[ $s ]]; do
    array+=( "${s%%"$DELIMITER"*}" );
    s=${s#*"$DELIMITER"};
done;

for u in "${array[@]}"
do
	if [ ${#u} -gt 500 ]; then
		echo $u
	fi
done
