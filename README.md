# A PHP Framework for People who Hate PHP Frameworks

Huge PHP frameworks suck. That's my opinion at least, and thats
why I made this. My goal with this project is to create the smallest
useful PHP framework I can that serves my needs.

So far it includes a router and a template engine.

## Example

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

- `Router::route($method, $route, $func)` Calls func when a request
  on $route is made with HTTP method $method
- `Router::get`, `post`, `put`, etc, all the HTTP methods have helper
  functions that work like `route()` but without the `$method` argument


- `Templates::__construct($path)` set $path as the base path for templates
- `Templates::render($name, $args)` Render template named $name with $args

In templates, `$this` refers to an instance of `View`:

- `View::layout($name[, $args])` Set the layout for this template.
- `View::e($data)` HTML escape $e
- `View::include($name[, $args])` Include template $name with $args
