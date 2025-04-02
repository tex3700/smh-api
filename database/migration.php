<?php

use database\DbHandler;

require 'DbHandler.php';

$dbConfig = require '../config/database.php';

$dbHandler = new DbHandler(...array_values($dbConfig));

$dbHandler->migrate( __DIR__ . '/migrations/create_products_table.sql');
