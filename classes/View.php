<?php

/**
 * Created by PhpStorm.
 * User: theardent
 * Date: 28.12.16
 * Time: 6:10
 */

class View
		implements Iterator
{
	protected $data = [];
	private $position;
	private $mainView = 'main';

	public function __set($key,$value)
	{
		$this->data[$key] = $value;
	}

	public function __construct()
	{
		$this->position = 0;
	}

	public function __get($key)
	{
		return $this->data[$key];
	}

	public function render($template)
	{
		foreach ($this->data as $key => $value)
		{
			$$key = $value;
		}
		ob_start();
		include __DIR__.'/../views/'.$template.'.php';
		return ob_get_clean();
	}

	public function display()
	{
		echo $this->render($this->mainView);
	}

	public function current()
	{
		return $this->data[$this->position];
	}

	public function next()
	{
		++$this->position;
	}

	public function key()
	{
		return $this->position;
	}

	public function valid()
	{
		return isset($this->data[$this->position]);
	}

	public function rewind()
	{
		$this->position = 0;
	}
}