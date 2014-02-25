<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

if(!defined('BASE_DIR'))
{
    define('BASE_DIR', dirname(__FILE__));
}

require_once BASE_DIR . '/core/CAutoLoader.php';

use \CEIT\core;

try
{
    // cargador de clases
    $autloader = new core\CAutoLoader();
    
    // inicio session
    session_start();
    
    // coso que maneja para que lado va el programa
    $front = new core\CFrontController();
    $front->run();
}
catch(Exception $ex)
{
    echo "<pre>" . $ex->getMessage() . "</pre>" . PHP_EOL;
    echo "<pre>" . $ex->getTraceAsString() . "</pre>" . PHP_EOL;
}

?>