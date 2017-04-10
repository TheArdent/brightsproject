<?php

function __autoload($classname)
{
	if (file_exists(__DIR__.'/classes/'.$classname.'.php'))
		require __DIR__.'/classes/'.$classname.'.php';
	elseif (file_exists(__DIR__.'/controllers/'.$classname.'.php'))
		require __DIR__.'/controllers/'.$classname.'.php';
	elseif (file_exists(__DIR__.'/models/'.$classname.'.php'))
		require __DIR__.'/models/'.$classname.'.php';
	else die("Failed class name $classname");
}
