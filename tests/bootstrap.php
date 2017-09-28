<?php

require __DIR__ . '/../vendor/autoload.php';

define('TEMP_DIR', __DIR__ . '/temp');
date_default_timezone_set('Europe/Prague');

\Tester\Environment::setup();
\Tracy\Debugger::$logDirectory = TEMP_DIR;