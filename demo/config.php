<?php

require_once __DIR__.'/../vendor/autoload.php';

define('HOSTS', 'localhost');
define('PORT', 5672);
define('USER', '');
define('PASS', '');
define('VHOST', '/');

//If this is enabled you can see AMQP output on the CLI
define('AMQP_DEBUG', false);

global $publishHosts, $destinationHosts;
$publishHosts = array('localhost');
$destinationHosts = array('localhost');