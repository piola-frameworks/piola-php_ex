<?php

namespace CEIT\core
{
    final class CFrontController
    {
        private $_controller = "Dashboard";
        private $_action = "index";
        private $_params = array();
        
        public function __construct()
        {
            // verifico la session para saver si necesito irme a un lado u otro.
            if(session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['user_logged']))
            {
                // localhost/controller/action/param1/param2/paramN
                $request = filter_input(INPUT_SERVER, "REQUEST_URI", FILTER_SANITIZE_URL);
                $path = trim(parse_url($request, PHP_URL_PATH), "/");

                if(strpos($path, __DIR__) === 0)
                {
                    $path = substr($path, strlen(__DIR__));
                }

                $stripedUrl = explode("/", $path, 3);

                // Controller part
                if(!empty($stripedUrl[0]))
                {
                    $this->_controller = $stripedUrl[0];
                }
                $this->_controller = "CEIT\\mvc\\controllers\\" . ucfirst(strtolower($this->_controller)) . "Controller";

                // Action part
                if(!empty($stripedUrl[1]))
                {
                    $this->_action = $stripedUrl[1];
                }

                // Parameters part
                if(!empty($stripedUrl[2]))
                {
                    $this->_params = explode("/", $stripedUrl[2]);
                }
            }
            else
            {
                $this->_controller = "CEIT\\mvc\\controllers\\LoginController";
            }
        }
        
        public function run()
        {
            if(class_exists($this->_controller))
            {
                $controller = new $this->_controller();
                $controller->_action = $this->_action;
                $controller->_params = $this->_params;

                if(method_exists($controller, $this->_action))
                {
                    call_user_func_array(array($controller, $this->_action), $this->_params);
                }
                else
                {
                    $controller = new \CEIT\mvc\controllers\WebController();
                    $controller->_action = "error404";
                    $controller->_params = array("Accion " . $this->_action .  " no encontrada en el controlador.");
                    
                    call_user_func_array(array($controller, $controller->_action), $controller->_params);
                }
            }
            else
            {
                $controller = new \CEIT\mvc\controllers\WebController();
                $controller->_action = "error404";
                $controller->_params = array("El controlador " . $this->_controller . " no existe.");
                
                call_user_func_array(array($controller, $controller->_action), $controller->_params);
            }
        }
    }
}

?>