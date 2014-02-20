<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once '/core/CAutoLoader.php';

if(!empty($_SESSION))
{
    var_dump($_SESSION);
}

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