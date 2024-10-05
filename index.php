<?php

use App\Utils\LoggerHelper;

require 'vendor/autoload.php';

$router = require 'src/Routers/web.php';

LoggerHelper::init();

// $router->init();
