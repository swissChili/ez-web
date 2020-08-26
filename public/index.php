<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \SwissChili\Router;
use \SwissChili\Templates;

$t = new Templates(__DIR__ . "/../templates");

Router::route('GET', '/', function () {
	echo 'home';
});

Router::get('/names/:name', function ($name) use ($t) {
	echo $t->render("home", ["name" => $name]);
});
