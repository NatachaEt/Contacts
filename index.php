<?php

require_once 'vendor/autoload.php';
include_once 'src/Model/Model.php';
include_once 'src/Route/Routeur.php';
include_once 'src/Service/History.php';




$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$history = History::getInstance();

route($_SERVER['REQUEST_METHOD'], $path);
