<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class UserController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = new models\UsuarioModel();
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\UserView();
            }
        }
        
        public function __destruct()
        {
            parent::__destruct();
            
            $this->result = null;
            $this->_view->render($this->_template, $this->_dataCollection);
        }
        
        public function profile($id)
        {
            // seteo template
            $this->_template = BASE_DIR . "/mvc/templates/user/{$this->_action}.html";
            
            $param = new models\UsuarioModel();
            $param->_idUsuario = $id;
            $this->result = $this->_model->Select($param);
            
            if(count($this->result, COUNT_NORMAL))
            {
                foreach($this->result[0] as $key => $value)
                {
                    $this->{$key} = $value;
                }
            }
            else
            {
                trigger_error("No se encontro un registro para el Id de usuario " . $id, E_USER_WARNING);
            }
        }
        
        public function logout()
        {
            // cierro session
            if(session_status() == PHP_SESSION_ACTIVE)
            {
                session_destroy();
            }
            
            // redirijo
            header("Location: /login/index");
        }
    }
}

?>