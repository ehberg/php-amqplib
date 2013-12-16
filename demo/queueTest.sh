#!/bin/sh

/usr/bin/php publisherPounder.php &

PID0=$!

/usr/bin/php publisherPounder.php &

PID1=$!

/usr/bin/php publisherPounder.php &

PID2=$!

/usr/bin/php publisherPounder.php &

PID3=$!

/usr/bin/php publisherPounder.php &

PID4=$!

/usr/bin/php publisherSpecificExchange.php 50000 5 1

sudo kill -9 $PID
sudo kill -9 $PID1
sudo kill -9 $PID2
sudo kill -9 $PID3
sudo kill -9 $PID4