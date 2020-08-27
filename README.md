# A PHP Framework for People who Hate PHP Frameworks

Huge PHP frameworks suck. That's my opinion at least, and thats
why I made this. My goal with this project is to create the smallest
useful PHP framework I can that serves my needs.

So far it includes a router and a template engine.

## Example

```php
<?php // migration.php

require_once __DIR__ . "/vendor/autoload.php";

use \SwissChili\Migrations;

// Gets all the files in migrations/ with the format
// <version>-up|down and stores them in an array of
// name to contents
$files = Migrations::getFiles(__DIR__ . "/migrations");
// Create a new migrations object with current version 1
// and migrations $files (in reality you should get the
// current version from your database or from some file)
$m = new Migrations(1, $files);

// This will apply migrations 1-up, 2-up, and 3-up.
// If target version < current version it will look
// for N-down mirgations
$m->mirgate(4, function ($code)
{
	// Apply the migration in whatever way you usually do
	$myDbConnection->executeSql($code);
});
```

```php
<?php // public/index.php

require_once __DIR__ . '/../vendor/autoload.php';

use \SwissChili\Router;
use \SwissChili\Templates;

$t = new Templates(__DIR__ . "/../templates");

Router::get('/', function () {
	echo 'home';
});

Router::get('/names/:name', function ($name) use ($t) {
	echo $t->render("home", ["name" => $name]);
});

Router::failure(function () {
	echo "404 Not Found";
});
```

```php
<!-- templates/home.php -->

<?php $this->layout('layout', ["title" => "<b>My</b> Site"]) ?>

<h1>Hello, <?= $name ?></h1>
```

```php
<!-- templates/layout.php -->

<title><?= $this->e($title) ?></title>

<?= $content ?>

<hr>

Footer
```

## Reference

The router is 50 lines of code, and the template engine is 70.
If you want to know how they work in more detail, just read the
code. It should be pretty self explanatory.

Here are the relevant functions anyway:

#### Router

- `Router::route($method, $route, $func)` Calls func when a request
  on $route is made with HTTP method $method
- `Router::get`, `post`, `put`, etc, all the HTTP methods have helper
  functions that work like `route()` but without the `$method` argument
- `Router::failure($func)` Calls $func if no path was matched yet.
  Call after all route handlers to handle a 404.

#### Templates

- `new Templates($path)` set $path as the base path for templates
- `Templates::render($name, $args)` Render template named $name with $args

In templates, `$this` refers to an instance of `View`:

- `View::layout($name[, $args])` Set the layout for this template.
- `View::e($data)` HTML escape $e
- `View::include($name[, $args])` Include template $name with $args

#### Migrations

- `Migrations::getFiles($dir)` Find all files in $dir prefixed with
  &lt;number>-up|down. E.g: `3-up-add-users-table.sql`
- `new Migrations($version, $migrations)` Create a new migrations
  object with the current version $version and the migrations
  $migrations.
- `Migrations::migrate($version, $func)` Migrate to $version by
  calling $func with every step of the migration.
