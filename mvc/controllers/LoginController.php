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
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Usuarios'  =>  new models\UsuarioModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\LoginView();
            }
        }
        
        public function __destruct()
        {
            parent::__destruct();
            
            unset($this->result);
            
            $this->_view->render($this->_template, $this->_dataCollection);
        }

        public function index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/login/{$this->_action}.html";
            
            if(!empty($_SESSION))
            {
                if($_SESSION['user_logged'])
                {
                    header("Location: index.php?do=/dashboard/index");
                }
            }
            
            if(!empty($_POST))
            {
                $user = new models\UsuarioModel();
                $user->_username = filter_input(INPUT_POST, 'txtUser', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $this->result = $this->_model['Usuarios']->SelectByUsername($user);
                
                if(count($this->result) == 1)
                {
                    //var_dump($this->result);
                    
                    $contrasena = filter_input(INPUT_POST, 'txtPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    if(!empty($contrasena) && $this->result[0]['Contrasena'] == $contrasena)
                    {
                        $filledData = true;
                        
                        foreach($this->result[0] as $key => $value)
                        {
                            switch($key)
                            {
                                case 'Usuario':
                                    if(empty($value))
                                    {
                                        $filledData = false;
                                    }
                                    break;
                                case 'Contrasena':
                                    if(empty($value))
                                    {
                                        $filledData = false;
                                    }
                                    break;
                                case 'Nombre':
                                    if(empty($value))
                                    {
                                        $filledData = false;
                                    }
                                    break;
                                case 'Apellido':
                                    if(empty($value))
                                    {
                                        $filledData = false;
                                    }
                                    break;
                                case 'DNI':
                                    if(empty($value))
                                    {
                                        $filledData = false;
                                    }
                                    break;
                                case 'Email':
                                    if(empty($value))
                                    {
                                        $filledData = false;
                                    }
                                    break;
                            }
                            
                            // Salgo del bucle apenas encuentro un dato no encontrado.
                            if(!$filledData)
                            {
                                break;
                            }
                        }
                        
                        // inicio sesion
                        $lifeTime = 0;
                        $checked = filter_input(INPUT_POST, 'chkRememberMe', FILTER_SANITIZE_STRING);
                        if(!empty($checked) && $checked == 'checked')
                        {
                            $lifeTime = time() + 3600;
                        }
                        setcookie(session_name(), session_id(), $lifeTime);
                        
                        // pongo inicio de session
                        $_SESSION['user_logged'] = true;
                        $_SESSION['IdUsuario'] = $this->result[0]['IdUsuario'];
                        $_SESSION['Usuario'] = $this->result[0]['Usuario'];
                        $_SESSION['Roles'] = array();
                        
                        // Traigo los privilegios desde la base de datos
                        $user->_idUsuario = $this->result[0]['IdUsuario'];
                        $this->result2 = $this->_model['Usuarios']->SelectRoles($user);
                        //var_dump($this->result2);
                        if(count($this->result2) == 1)
                        {
                            $_SESSION['Roles'] = $this->result2[0];
                        }
                        else
                        {
                            // Desde cuando un usuario puede tener mas de un rol?
                        }
                        unset($this->result2);
                        
                        // redirecciono
                        if(!$filledData)
                        {
                            switch($this->result[0]['TipoUsuario'])
                            {
                                case 'Estudiante':
                                    header("Location: index.php?do=/user/fill_estudiante/" . $this->result[0]['IdUsuario']);
                                    break;
                                case 'Docente':
                                    header("Location: index.php?do=/user/fill_docente/" . $this->result[0]['IdUsuario']);
                                    break;
                                case 'Operario':
                                    header("Location: index.php?do=/user/fill_operador/" . $this->result[0]['IdUsuario']);
                                    break;
                                default:
                                    //trigger_error("Es otro tipo de operador que no estaba contemplado?", E_USER_ERROR);
                                    break;
                            }
                        }
                        else
                        {
                            header("Location: index.php?do=/dashboard/index");
                        }
                    }
                    else
                    {
                        // no coincidio la contrasena
                        // trigger_error("No coincidio la contrasena del usuario ingresado.", E_USER_NOTICE);
                        $this->_template = BASE_DIR . "/mvc/templates/login/wrongpass.html";
                    }
                }
                else
                {
                    // no encontro a ningun usuario
                    // trigger_error("No ningun usuario con ese nombre.", E_USER_NOTICE);
                    $this->_template = BASE_DIR . "/mvc/templates/login/wronguser.html";
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