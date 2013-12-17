#!/bin/sh

LIMIT=$1
COUNTER=0
echo Launching $LIMIT pounders

PIDS=()
while [  $COUNTER -lt $LIMIT ]; do
    /usr/bin/php publisherPounder.php &
    PIDS+=("$!")
    let COUNTER+=1
done


/usr/bin/php publisherSpecificExchange.php 50000 5 1

for P in $PIDS
do
    echo Killing $P
    sudo kill $P
done
