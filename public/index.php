<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \SwissChili\Router;
use \SwissChili\Templates;
use \SwissChili\Migrations;

$files = Migrations::getFiles(__DIR__ . "/../migrations");
$m = new Migrations(1, $files);
$t = new Templates(__DIR__ . "/../templates");

$m->migrate(2, function ($migration)
{
	echo $migration;
});

echo "<h1>Migrated to v2</h1>";

$m->migrate(1, function ($migration)
{
	echo $migration;
});

Router::route('GET', '/', function () {
	echo 'home';
});

Router::get('/names/:name', function ($name) use ($t) {
	echo $t->render("home", ["name" => $name]);
});

Router::failure(function () {
	echo "404 Not Found";
});
