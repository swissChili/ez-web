<?php 
namespace SwissChili;

class Router
{
	static $success = false;
	public static function route(string $method, string $path, callable $func)
	{
		$m = $_SERVER['REQUEST_METHOD'];
		$uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

		if ($uri === '/' || $uri === '')
		{
			if ($path === '/')
			{
				self::$success = true;
				$func();
			}
			return;
		}
		$not_empty = function ($a) { return !empty(trim($a)); };
		$parts = array_filter(explode('/', $path), $not_empty);
		$uri_parts = array_filter(explode('/', explode('#', $uri, 2)[0]),
			$not_empty);
		$args = [];

		foreach ($parts as $p)
		{
			if (count($uri_parts) <= 0)
				return;
			$f = array_shift($uri_parts);
			if (substr($p, 0, 1) == ':')
				$args[] = $f;
			else if ($f !== $p)
				return;
		}
		$count = count($uri_parts);
		if (count($uri_parts) > 0)
			return;
		self::$success = true;
		call_user_func_array($func, $args);
	}
	public static function get(string $p, callable $f)     { self::route('GET', $p, $f); }
	public static function post(string $p, callable $f)    { self::route('POST', $p, $f); }
	public static function put(string $p, callable $f)     { self::route('PUT', $p, $f); }
	public static function patch(string $p, callable $f)   { self::route('PATCH', $p, $f); }
	public static function delete(string $p, callable $f)  { self::route('DELETE', $p, $f); }
	public static function options(string $p, callable $f) { self::route('OPTIONS', $p, $f); }
	public static function failure(callable $f) { if (!self::$success) $f(); }
}
