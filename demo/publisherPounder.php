<?php

include(__DIR__ . '/config.php');
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'tester';
$queue = 'msgs';

// Simulate the randomizing of hosts
shuffle($poundHosts);
$host = $poundHosts[0];

echo "Connecting to host: $host\n";
$conn = new \PhpAmqpLib\Connection\AMQPSocketConnection($host, PORT, USER, PASS, VHOST, false, "AMQPLAIN", null, "en_US", 30);
$ch = $conn->channel();

$ch->queue_declare($queue, false, true, false, false);
$ch->exchange_declare($exchange, 'direct', false, true, false);

$ch->queue_bind($queue, $exchange, $queue);

// Clear out the queue
$ch->queue_purge($queue);

// Dont ever stop
while (true) {
	$msg = new AMQPMessage('My Message body', array('content_type' => 'text/plain', 'delivery_mode' => 2));
	$ch->basic_publish($msg, $exchange, $queue);
}

$ch->close();
$conn->close();

