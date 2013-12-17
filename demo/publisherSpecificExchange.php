<?php

include(__DIR__ . '/config.php');
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'tester';
$queue = 'msgs';

if (count($argv) != 4) {
	die('dont be no fool');
}

// Set loop vars
$messageTestCount = $argv[1];
$testCount = $argv[2];
$messageType = $argv[3];
$totalPublished = 0;
$totalRate = 0;
$totalTime = 0;

echo "*** $messageTestCount messages publish test ***\n";

// Perform this test 5 times, setup and tear down all of the connections/channels
for ($i = 1; $i <= $testCount; $i++) {
	// Simulate the randomizing of hosts
	shuffle($publishHosts);
	shuffle($destinationHosts);

	echo "-- Run $i --\n";
	foreach ($publishHosts as $host) {
		try {
			echo "Connecting to host: $host\n";
			$conn = new \PhpAmqpLib\Connection\AMQPSocketConnection($host, PORT, USER, PASS, VHOST, false, "AMQPLAIN", null, "en_US", 30);
			$ch = $conn->channel();
			break;
		} catch (Exception $e) {
			echo "Failed connecting to host: '$host' because: " . $e->getMessage() . "\n";
		}
	}

	if (!$conn instanceof \PhpAmqpLib\Connection\AMQPSocketConnection) {
		echo "Could not connect to any host in the host list!\n";

		// Try next test
		continue;
	}


	$ch->queue_declare($queue, false, true, false, false);
	$ch->exchange_declare($exchange, 'direct', false, true, false);

	$ch->queue_bind($queue, $exchange, $queue);

	$startTime = microtime(true);
	$messageCount = 1;

	while ($messageCount < $messageTestCount) {
		$msg = new AMQPMessage('My Message body', array('content_type' => 'text/plain', 'delivery_mode' => $messageType));
		$ch->basic_publish($msg, $exchange, $queue);
		$messageCount++;
	}

	$endTime = microtime(true);

	// Time diff
	$totalRunTime = $endTime - $startTime;

	// Get msg per second
	$msgPerSec = $messageCount / $totalRunTime;

	// Get secs per message
	$secPerMsg = $totalRunTime / $messageCount;

	// Add in total
	$totalPublished += $messageCount;

	// Add in total rate
	$totalRate += $msgPerSec;

	// Total run time
	$totalTime += $totalRunTime;

	echo "Total published: $messageCount\n";
	echo "Total Time Required: $totalRunTime\n";
	echo "Msg/sec published: $msgPerSec\n";

	$ch->close();
	$conn->close();

	sleep(2);

	foreach ($destinationHosts as $host2) {
		try {
			echo "Connecting to destination host: $host2\n";
			$conn2 = new \PhpAmqpLib\Connection\AMQPSocketConnection($host2, PORT, USER, PASS, VHOST, false, "AMQPLAIN", null, "en_US", 30);
			$ch2 = $conn2->channel();

			// Clear out the queue
			$ch2->queue_purge($queue);
			break;
		} catch (Exception $e) {
			echo "Failed connecting to destination host: '$host2' because: " . $e->getMessage() . "\n";
		}
	}

	if ($conn2 instanceof \PhpAmqpLib\Connection\AMQPSocketConnection) {
		$ch2->close();
		$conn2->close();
	}
}

// Calc the total results for all runs
$avgTotal = $totalPublished / $testCount;
$avgRate = $totalRate / $testCount;
$avgTime = $totalTime / $testCount;

echo "--------------------------\n";
echo "Test avg published: $avgTotal\n";
echo "Test avg time: $avgTime sec\n";
echo "Test avg rate: $avgRate msg/sec\n";
echo "--------------------------\n";