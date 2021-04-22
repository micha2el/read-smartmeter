#!/bin/bash

DEVICE=/dev/ttyUSB0
OUTPUT=/var/www/html/smart_meter.data
DELIMITER=1b1b1b1b
# VALUES for current power
START=77070100100700ff0101621b520055
END=0177070100240700ff

START2=77070100240700ff0101621b520055
END2=0177070100380700ff

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
		#echo $u
		PART1=${u##*$START}
		PART2=${PART1%$END*}
		FIRST_CHAR=${PART2:0:1}
		#echo $FIRST_CHAR
		if [[ "$(( 16#$FIRST_CHAR ))" -gt "8" ]]; then
			#echo "A"
			#echo $PART2
			PART2=$(( 0x100000000 - 0x$PART2 ))
			echo "-"$PART2" Watt"
		else
			#echo $PART2
			echo $(( 16#$PART2 ))" Watt"
		fi
		break
		#echo $PART2
	fi
done
