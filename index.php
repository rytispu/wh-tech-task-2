<?php

use WHInterviewTask\App;
use WHInterviewTask\Utility\Logger;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$logger = new Logger();
$app = new App($logger);
$app->run();

