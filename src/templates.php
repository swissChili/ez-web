<?php
namespace SwissChili;

class Templates
{
	protected $path;

	public function __construct(string $path)
	{
		$this->path = $path;
	}

	public function render(string $name, array $data)
	{
		$path = $this->path . "/" . $name . ".php";
		if (!file_exists($path))
		{
			throw new \Exception("Could not find template $name");
		}
		$view = new View($this);
		return $view->render($path, $data);
	}
}

class View
{
	private $templates;
	private $layout;
	private $layout_data;

	public function __construct(&$t)
	{
		$this->templates = $t;
	}

	public function layout(string $name, array $data = [])
	{
		$this->layout = $name;
		$this->layout_data = $data;
	}

	public function e($data)
	{
		return htmlspecialchars($data);
	}

	private function f(string $__template_path, array $data)
	{
		extract($data);
		require $__template_path;
	}

	public function include(string $template, array $data = [])
	{
		return $this->templates->render($template, $data);
	}

	public function render(string $path, array $data)
	{
		ob_start();
		$this->f($path, $data);
		$body = ob_get_clean();

		if (!empty($this->layout))
		{
			$this->layout_data["content"] = $body;
			return $this->templates->render($this->layout, $this->layout_data);
		}
		return $body;
	}
}
