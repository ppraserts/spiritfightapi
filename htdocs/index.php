<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
$settings = require '../src/settings.php';
//$app = new \Slim\App(["settings" => $config]);
$app = new \Slim\App($settings);

// Set up dependencies
require '../src/dependencies.php';

// Register middleware
require '../src/middleware.php';

// Routes
require '../src/routes.php';

$app->run();