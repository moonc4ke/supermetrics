<?php

declare (strict_types = 1);

use App\App;

define('ROOT_DIR', __DIR__);

// Autoload all classes via composer-generated autoloader 
require ROOT_DIR . '/vendor/autoload.php';

// Load server-specific configuration
require ROOT_DIR . '/app/config/routes.php';

// Creating Connection, Schema, Repository, and Session objects
$app = new App();