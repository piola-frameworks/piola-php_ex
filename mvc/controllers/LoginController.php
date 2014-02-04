<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class LoginController extends core\AController
    {
        public function __construct()
        {
            if(DEBUG)
            {
                echo '<pre>[LOG ' . date('c') . '] Llamada a ' . __METHOD__ . '(ArgCount: ' . func_num_args() . ')</pre>' . PHP_EOL;
            }
            
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = new models\UsuarioModel();
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\LoginView();
            }
        }
        
        public function __destruct()
        {
            if(DEBUG)
            {
                echo '<pre>[LOG ' . date('c') . '] Llamada a ' . __METHOD__ . '(ArgCount: ' . func_num_args() . ')</pre>' . PHP_EOL;
            }
            
            parent::__destruct();
            
            $this->_view->render($this->_template, $this->_dataCollection);
        }

        public function index()
        {
            if(DEBUG)
            {
                echo '<pre>[LOG ' . date('c') . '] Llamada a ' . __METHOD__ . '(ArgCount: ' . func_num_args() . ')</pre>' . PHP_EOL;
            }
            
            $this->_template = BASE_DIR . "/mvc/templates/login/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                $params = array();
                foreach($_POST as $key => $value)
                {
                    $params[$key] = $value;
                }
                
                $user = new models\UsuarioModel();
                $user->username = $params['txtUser'];
                
                $this->result = $this->_model->SelectByUsername($user);
                
                if(count($this->result) == 1)
                {
                    if($this->result[0]['Contrasena'] == $params['txtPassword'])
                    {
                        // inicio sesion
                        $lifeTime = 600;
                        if(isset($params['chkRememberMe']))
                        {
                            $lifeTime = 3600;
                        }
                        
                        setcookie(session_name(), session_id(), time() + $lifeTime);
                        
                        // pongo inicio de session
                        $_SESSION['user_logged'] = true;
                        $_SESSION['IdUsuario'] = $this->result[0]['IdUsuario'];
                        $_SESSION['Usuario'] = $this->result[0]['Usuario'];
                        
                        // redirecciono
                        header("Location: /dashboard");
                    }
                    else
                    {
                        // no coincidio la contrasena
                        // trigger_error("No coincidio la contrasena del usuario ingresado.", E_USER_NOTICE);
                    }
                }
                else
                {
                    // no encontro a ningun usuario
                    // trigger_error("No ningun usuario con ese nombre.", E_USER_NOTICE);
                }
            }
            else
            {
                // no se emitio post, debe ser la primera vez qe visita la pagina
                // trigger_error("No encontro POST.", E_USER_NOTICE);
            }
        }
    }
}

?>