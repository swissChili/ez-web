<?php

namespace SwissChili;

class Migrations
{
	public $version;
	protected $migrations;

	public function __construct(int $version, array $migrations)
	{
		$this->version = $version;
		$this->migrations = $migrations;
	}

	public function migrate(int $v, callable $f)
	{
		while ($v != $this->version)
		{
			$verb = "up";
			if ($this->version > $v)
				$verb = "down";

			$name = "$this->version-$verb";

			if (!\array_key_exists($name, $this->migrations))
			{
				throw new \Exception("Migration does not exist: '$name'");
			}

			$f($this->migrations[$name]);

			$this->version = ($verb == "up") ? $this->version + 1 : $this->version - 1;
		}
	}

	public static function getFiles(string $basedir): array
	{
		if (!\is_dir($basedir))
			throw new \Exception("Not a directory: $basedir");

		$dir = new \DirectoryIterator($basedir);
		$files = [];

		foreach ($dir as $f)
		{
			if (!$f->isDot())
			{
				$name = $f->getFilename();
				if (\preg_match('/^(\d)+-((up)|(down))/', $name, $matches))
				{
					$files[$matches[0]] = \file_get_contents($f->getPathname());
				}
			}
		}
		return $files;
	}
}
