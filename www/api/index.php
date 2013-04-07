<?php

require_once dirname(dirname(__DIR__)) . '/src/bootstrap.php';

$app = new Todo\Application\TodoApplication;

$app->input = new Joomla\Input\Json;

$app->loadRouter()
	->loadDatabase()
	->execute();