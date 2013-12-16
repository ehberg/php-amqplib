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

/usr/bin/php publisherPounder.php &
PID5=$!

/usr/bin/php publisherPounder.php &
PID6=$!

/usr/bin/php publisherPounder.php &
PID7=$!

/usr/bin/php publisherPounder.php &
PID8=$!

/usr/bin/php publisherPounder.php &
PID9=$!

/usr/bin/php publisherSpecificExchange.php 50000 5 1

sudo kill -9 $PID0
sudo kill -9 $PID1
sudo kill -9 $PID2
sudo kill -9 $PID3
sudo kill -9 $PID4
sudo kill -9 $PID5
sudo kill -9 $PID6
sudo kill -9 $PID7
sudo kill -9 $PID8
sudo kill -9 $PID9