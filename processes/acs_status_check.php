<?php
/* 
	ACS status check page (called from the out of order page)
*/
require __DIR__ . '/../vendor/autoload.php';
include_once('../config.php');

$mysip = new sip2;

// Set host name
$mysip->hostname = $sip_hostname;
$mysip->port = $sip_port;

// connect to SIP server
$connect=$mysip->connect();

if ($connect) {
	echo json_encode('online');
}
?>
