<?php
set_include_path(
	get_include_path() .
	PATH_SEPARATOR .
	"../lib/");

require_once "Tachyon/Application.php";

$urls = array(
	"/home" => "Home",
	"/user/:name" => function($name) {
		echo $name;
	},
	"404" => "NotFound"
);

$app = new \Tachyon\Application($urls);
$app->setTemplateDir(getcwd() . "/../views/")
	->setControllerDir(getcwd() . "/../controllers/")
	->run();
