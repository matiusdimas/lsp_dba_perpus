<?php

session_start();

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Config/Database.php';
require_once BASE_PATH . '/app/Core/Model.php';
require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/App.php';

$app = new App();
