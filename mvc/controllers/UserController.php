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
                $this->_model = array(
                    'Usuarios'  =>  new models\UsuarioModel(),
                    'Carreras'  =>  new models\CarreraModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\UserView();
            }
        }
        
        public function __destruct()
        {
            parent::__destruct();
            
            unset($this->result);
            
            $this->_view->render($this->_template, $this->_dataCollection);
        }
        
        public function profile($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/user/{$this->_action}.html";
            
            $modelCarrera = new models\CarreraModel();
            $modelCarrera->_idUsuario = $id;
            $this->result = $this->_model['Carreras']->SelectByIdUsuario($modelCarrera);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/user/combo_carrera.html";
                    $this->combo_carrera .= file_get_contents($filename);

                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_carrera = str_replace('{' . $key . '}', $value, $this->combo_carrera);
                        }
                    }
                }
            }
            unset($this->result);
            
            $param = new models\UsuarioModel();
            $param->_idUsuario = $id;
            $this->result = $this->_model['Usuarios']->Select($param);
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
            header("Location: index.php?do=/login/index");
        }
    }
}

?>