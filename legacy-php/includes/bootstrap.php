<?php

session_start();

require_once __DIR__ . '/env.php';
load_env(dirname(__DIR__) . '/.env');

$config = require __DIR__ . '/config.php';
$GLOBALS['config'] = $config;
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/mailer.php';
