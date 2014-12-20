<?php

use ComposerProxy\Server\Server;
use Klein\Klein;
use Klein\Request;

define('APP_ROOT', realpath(__DIR__.'/../'));

require_once APP_ROOT.'/vendor/autoload.php';

$klein = new Klein();

$klein->respond('POST', '/packages', function (Request $request){
	$server = new Server();
	return $server->serve($request->paramsPost()->all());
});

$klein->dispatch();