<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class AdminController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Usuarios'          =>  new models\UsuarioModel(),
                    "UsuarioRoles"      =>  new models\UsuarioRolesModel(),
                    "Personas"          =>  new models\PersonaModel(),
                    "Estudiantes"       =>  new models\EstudianteModel(),
                    "Docentes"          =>  new models\DocenteModel(),
                    'Roles'             =>  new models\RolModel(),
                    'Permisos'          =>  new models\PermisoModel(),
                    'RolPermisos'       =>  new models\RolPermisosModel(),
                    'Configuraciones'   =>  new models\WebModel(),
                    "Carreras"          =>  new models\CarreraModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\AdminView();
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
            // Seteo el template a usar para el action.
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            // Modifico si hubo algun cambio de datos.
            if(!empty($_POST))
            {
                /*$models = array();
                
                $post = filter_input(INPUT_POST, 'txtNumFotocopias', FILTER_SANITIZE_NUMBER_INT);
                if($post !== null)
                {
                    $model = new models\WebModel();
                    $model->_clave = "FotocopiasXMinuto";
                    $model->_valor = $post;
                    
                    array_push($models, $model);
                }
                
                $post = filter_input(INPUT_POST, 'txtLimiteGabinete', FILTER_SANITIZE_NUMBER_INT);
                if($post !== null)
                {
                    $model = new models\WebModel();
                    $model->_clave = "LimiteGabinete";
                    $model->_valor = $post;
                    
                    array_push($models, $model);
                }
                
                $post = filter_input(INPUT_POST, 'txtPrecioCEIT', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                if($post !== null)
                {
                    $model = new models\WebModel();
                    $model->_clave = "PrecioCEIT";
                    $model->_valor = $post;
                    
                    array_push($models, $model);
                }
                
                $post = filter_input(INPUT_POST, 'txtPrecioSimpleFaz', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                if($post !== null)
                {
                    $model = new models\WebModel();
                    $model->_clave = "PrecioSimpleFaz";
                    $model->_valor = $post;
                    
                    array_push($models, $model);
                }
                
                $post = filter_input(INPUT_POST, 'txtPrecioGabinete', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                if($post !== null)
                {
                    $model = new models\WebModel();
                    $model->_clave = "PrecioGabinete";
                    $model->_valor = $post;
                    
                    array_push($models, $model);
                }
                
                $post = filter_input(INPUT_POST, 'txtPrecioAnillado', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                if($post !== null)
                {
                    $model = new models\WebModel();
                    $model->_clave = "PrecioAnillado";
                    $model->_valor = $post;
                    
                    array_push($models, $model);
                }
                
                $this->_model['Configuraciones']->Update($models);*/
            }
            
            // Cargo la lista de usuarios.
            /*$this->result = $this->_model['Usuarios']->Select();
            foreach($this->result as $row)
            {
                $filename = BASE_DIR . "/mvc/templates/admin/{$this->_action}_user_row.html";
                $this->table_user_content .= file_get_contents($filename);

                if(is_array($row))
                {
                    foreach($row as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $this->table_user_content = str_replace('{' . $key . '}', $value, $this->table_user_content);
                        }
                    }
                }
            }
            unset($this->result);*/
            
            // Cargo la lista de roles.
            $this->result = $this->_model['Roles']->Select();
            foreach($this->result as $row)
            {
                $filename = BASE_DIR . "/mvc/templates/admin/{$this->_action}_role_row.html";
                $this->table_rol_content .= file_get_contents($filename);

                if(is_array($row))
                {
                    foreach($row as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $this->table_rol_content = str_replace('{' . $key . '}', $value, $this->table_rol_content);
                        }
                    }
                }
            }
            unset($this->result);
            
            // Cargo la lista de permisos.
            $this->result = $this->_model['Permisos']->Select();
            foreach($this->result as $row)
            {
                $filename = BASE_DIR . "/mvc/templates/admin/{$this->_action}_perm_row.html";
                $this->table_perm_content .= file_get_contents($filename);

                if(is_array($row))
                {
                    foreach($row as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $this->table_perm_content = str_replace('{' . $key . '}', $value, $this->table_perm_content);
                        }
                    }
                }
            }
            unset($this->result);
            
            // Cargo los parametros del sistema.
            /*$this->result = $this->_model['Configuraciones']->Select();
            foreach($this->result as $row)
            {
                $this->{$row['Clave']} = $row['Valor']; 
            }
            unset($this->result);*/
        }
        
        public function create_user()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
            }
        }

        public function delete_user($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
            }
            
            $var = new models\UsuarioModel();
            $var->_idUsuario = $id;
            $this->result = $this->_model['Usuarios']->Select($var);

            // Construyo la pagina
            foreach($this->result[0] as $key => $value)
            {
                $this->{$key} = $value;
            }
        }

        public function detail_user($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            $var = new models\UsuarioModel();
            $var->_idUsuario = $id;
            $this->result = $this->_model['Usuarios']->Select($var);
            if(count($this->result) > 0)
            {
                foreach($this->result[0] as $key => $value)
                {
                    $this->{$key} = $value;
                }
            }
            unset($this->result);
            
            $modelUsuario = new models\UsuarioModel();
            $modelUsuario->_idUsuario = $id;
            $this->result = $this->_model['Usuarios']->SelectAllRolesAndMarkByUser($modelUsuario);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/controls/role_option.html";
                        $this->combo_rol .= file_get_contents($filename);
                        
                        foreach($row as $key => $value)
                        {
                            $this->combo_rol = str_replace('{' . $key . '}', $value, $this->combo_rol);
                        }
                    }
                }
            }
            unset($this->result);
        }

        /*
         * Usuarios
         */
        public function estydoc_index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/estydoc/{$this->_action}.html";
            
            $this->result = $this->_model['Usuarios']->SelectEstudiantesYDocentes();
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/estydoc/{$this->_action}_table.html";
                        $this->table_user_content .= file_get_contents($filename);

                        foreach($row as $key => $value)
                        {
                            if(!is_array($value))
                            {
                                $this->table_user_content = str_replace('{' . $key . '}', $value, $this->table_user_content);
                            }
                        }
                    }
                }
            }
            else
            {
                $this->table_user_content = "";
            }
            unset($this->result);
        }
        
        public function estydoc_create()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/estydoc/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST["btnGuardar"]))
                {
                    $usuario = filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_SPECIAL_CHARS);
                    $contrasena = filter_input(INPUT_POST, "txtContrasena", FILTER_SANITIZE_SPECIAL_CHARS);
                    $nombre = filter_input(INPUT_POST, "txtNombre", FILTER_SANITIZE_SPECIAL_CHARS);
                    $apellido = filter_input(INPUT_POST, "txtApellido", FILTER_SANITIZE_SPECIAL_CHARS);
                    $legajo = filter_input(INPUT_POST, "txtLegajo", FILTER_SANITIZE_NUMBER_INT);
                    $dni = filter_input(INPUT_POST, "txtDNI", FILTER_SANITIZE_NUMBER_INT);
                    $telefono = filter_input(INPUT_POST, "txtTelefono", FILTER_SANITIZE_NUMBER_INT);
                    $celular = filter_input(INPUT_POST, "txtCelular", FILTER_SANITIZE_NUMBER_INT);
                    $email = filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_EMAIL);
                    $emailValido = filter_input(INPUT_POST, "chkEmailValido", FILTER_SANITIZE_STRING);
                    $comentario = filter_input(INPUT_POST, "txtComentario", FILTER_SANITIZE_SPECIAL_CHARS);
                    $carrera = filter_input(INPUT_POST, "ddlCarrera", FILTER_SANITIZE_NUMBER_INT);
                    $rol = filter_input(INPUT_POST, "ddlRol", FILTER_SANITIZE_NUMBER_INT);
                    
                    $modelPersona = new models\PersonaModel();
                    $modelPersona->_nombre = $nombre;
                    $modelPersona->_apellido = $apellido;
                    $modelPersona->_dni = $dni;
                    $modelPersona->_email = $email;
                    $modelPersona->_telefono = $telefono;
                    $modelPersona->_celular = $celular;
                    $this->resultPersona = $this->_model["Personas"]->Insert(array($modelPersona));
                    //var_dump($this->resultPersona);
                    unset($modelPersona);
                    
                    if(count($this->resultPersona) == 1)
                    {
                        $modelUsuario = new models\UsuarioModel();
                        $modelUsuario->_idPersona = $this->resultPersona;
                        $modelUsuario->_usuario = $usuario;
                        $modelUsuario->_contrasena = $contrasena;
                        $modelUsuario->_comentario = $comentario;
                        $modelUsuario->_emailValido = $emailValido == "on" ? true : false;
                        $this->resultUsuario = $this->_model["Usuarios"]->Insert(array($modelUsuario));
                        //var_dump($this->resultUsuario);
                        unset($modelUsuario);
                        
                        $modelUsuarioRol = new models\UsuarioRolesModel();
                        $modelUsuarioRol->_idUsuario = $this->resultUsuario;
                        $modelUsuarioRol->_idRol = $rol;
                        $this->resultRol = $this->_model["UsuarioRoles"]->Insert(array($modelUsuarioRol));
                        var_dump($this->resultRol);
                        unset($modelUsuarioRol);
                        
                        switch($rol)
                        {
                            case 7:
                                $modelDocente = new models\DocenteModel();
                                $modelDocente->_idPersona = $this->resultPersona;
                                $modelDocente->_legajo = $legajo;
                                $this->result = $this->_model["Docentes"]->Insert(array($modelDocente));
                                unset($modelDocente);
                                break;
                            case 8:
                                $modelEstadiante = new models\EstudianteModel();
                                $modelEstadiante->_idPersona = $this->resultPersona;
                                $modelEstadiante->_legajo = $legajo;
                                $modelEstadiante->_idCarrera = $carrera;
                                $this->result = $this->_model["Estudiantes"]->Insert(array($modelEstadiante));
                                unset($modelEstadiante);
                                break;
                            default:
                                trigger_error("Tipo de rol no existente para esta operacion.", E_USER_ERROR);
                                break;
                        }
                        
                        unset($this->resultUsuario);
                        unset($this->resultRol);
                    }
                    else
                    {
                        trigger_error("No se pudo insertar la persona", E_USER_ERROR);
                    }
                    unset($this->resultPersona);
                    
                    header("Location: index.php?do=/admin/estydoc_index");
                }
            }
            
            // Cargo la lista de roles.
            $this->result = $this->_model["Roles"]->Select();
            //var_dump($this->result);
            foreach($this->result as $row)
            {
                if(is_array($row))
                {
                    if($row["Nombre"] == "Estudiante" || $row["Nombre"] == "Docente")
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/controls/role_option.html";
                        $this->combo_roles .= file_get_contents($filename);

                        foreach($row as $key => $value)
                        {
                            if(!is_array($value))
                            {
                                $this->combo_roles = str_replace('{' . $key . '}', $value, $this->combo_roles);
                            }
                        }
                    }
                }
            }
            unset($this->result);
            
            // Cargo la lista de carreras.
            $this->result = $this->_model["Carreras"]->Select();
            //var_dump($this->result);
            foreach($this->result as $row)
            {
                if(is_array($row))
                {
                    $filename = BASE_DIR . "/mvc/templates/admin/controls/carrera_option.html";
                    $this->combo_carreras .= file_get_contents($filename);
                    
                    foreach($row as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $this->combo_carreras = str_replace('{' . $key . '}', $value, $this->combo_carreras);
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function estydoc_detail($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/estydoc/{$this->_action}.html";
            
            $modelEstYDoc = new models\UsuarioModel();
            $modelEstYDoc->_idUsuario = $id;
            $this->result = $this->_model["Usuarios"]->Select($modelEstYDoc);
            unset($modelEstYDoc);
            //var_dump($this->result);
            if(count($this->result) == 1)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->{$key} = $value;
                        }
                    }
                }
            }
            unset($this->result);
            
            $modelRol = new models\RolModel();
            $modelRol->_idUsuario = $id;
            $this->result = $this->_model["Usuarios"]->SelectAllRolesAndMarkByUser($modelRol);
            unset($modelRol);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/controls/role_option.html";
                        $this->combo_rol .= file_get_contents($filename);
                        
                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "Descripcion":
                                    $this->combo_rol = str_replace('{Nombre}', $value, $this->combo_rol);
                                    break;
                                default:
                                    $this->combo_rol = str_replace('{' . $key . '}', $value, $this->combo_rol);
                                    break;
                            }
                        }
                    }
                }
            }
            unset($this->result);
            
            $modelCarrera = new models\CarreraModel();
            $modelCarrera->_idUsuario = $id;
            $this->result = $this->_model["Carreras"]->SelectByIdUsuario($modelCarrera);
            unset($modelCarrera);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/controls/carrera_option.html";
                        $this->combo_carrera .= file_get_contents($filename);
                        
                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "EmailValidado":
                                    $this->combo_carrera = str_replace('{' . $key . '}', $value == 1 ? "checked" : "", $this->combo_carrera);
                                    break;
                                default:
                                    $this->combo_carrera = str_replace('{' . $key . '}', $value, $this->combo_carrera);
                                    break;
                            }
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function estydoc_update($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/estydoc/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST["btnGuardar"]))
                {
                    $usuario = filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_SPECIAL_CHARS);
                    $contrasena = filter_input(INPUT_POST, "txtContrasena", FILTER_SANITIZE_SPECIAL_CHARS);
                    $nombre = filter_input(INPUT_POST, "txtNombre", FILTER_SANITIZE_SPECIAL_CHARS);
                    $apellido = filter_input(INPUT_POST, "txtApellido", FILTER_SANITIZE_SPECIAL_CHARS);
                    $legajo = filter_input(INPUT_POST, "txtLegajo", FILTER_SANITIZE_NUMBER_INT);
                    $dni = filter_input(INPUT_POST, "txtDNI", FILTER_SANITIZE_NUMBER_INT);
                    $carrera = filter_input(INPUT_POST, "ddlCarrera", FILTER_SANITIZE_NUMBER_INT);
                    $rol = filter_input(INPUT_POST, "ddlRol", FILTER_SANITIZE_NUMBER_INT);
                    $telefono = filter_input(INPUT_POST, "txtTelefono", FILTER_SANITIZE_NUMBER_INT);
                    $celular = filter_input(INPUT_POST, "txtCelular", FILTER_SANITIZE_NUMBER_INT);
                    $email = filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_EMAIL);
                    $emailValidado = filter_input(INPUT_POST, "chkEmailValido", FILTER_SANITIZE_SPECIAL_CHARS);
                    $comentario = filter_input(INPUT_POST, "txtComentario", FILTER_SANITIZE_SPECIAL_CHARS);
                    
                    $modelUsuario = new models\UsuarioModel();
                    $modelUsuario->_idUsuario = $id;
                    $this->_resultUsuarioNeeded = $this->_model["Usuarios"]->Select($modelUsuario);
                    unset($modelUsuario);
                    var_dump($this->_resultUsuarioNeeded);
                    
                    $modelUsuarioRol = new models\UsuarioRolesModel();
                    $modelUsuarioRol->_idUsuario = $id;
                    $this->resultRolNeeded = $this->_model["UsuarioRoles"]->Select($modelUsuarioRol);
                    unset($modelUsuarioRol);
                    var_dump($this->resultRolNeeded);
                    
                    $modelPersona = new models\PersonaModel();
                    $modelPersona->_idPersona = null;
                    $modelPersona->_nombre = $nombre;
                    $modelPersona->_apellido = $apellido;
                    $modelPersona->_dni = $dni;
                    $modelPersona->_telefono = $telefono;
                    $modelPersona->_celular = $celular;
                    $modelPersona->_email = $email;
                    $this->_model["Personas"]->Update(array($modelPersona));
                    unset($modelPersona);
                    
                    $modelUsuario = new models\UsuarioModel();
                    $modelUsuario->_idUsuario = $id;
                    $modelUsuario->_idPersona = $this->_resultUsuarioNeeded[0]["IdPersona"];
                    $modelUsuario->_usuario = $usuario;
                    $modelUsuario->_contrasena = $contrasena;
                    $modelUsuario->_comentario = $comentario;
                    $modelUsuario->_emailValido = $emailValidado == "on" ? true : false;
                    $this->_model["Usuarios"]->Update(array($modelUsuario));
                    
                    if($this->resultRolNeeded[0]["IdRol"] != $rol)
                    {
                        $modelUsuarioRol = new models\UsuarioRolesModel();
                        $modelUsuarioRol->_idUsuario = $id;
                        $modelUsuarioRol->_idRol = $rol;
                        $this->_model["UsuarioRoles"]->Update(array($modelUsuarioRol));
                        unset($modelUsuarioRol);
                        
                        switch($rol)
                        {
                            case 7:
                                $modelDocente = new models\DocenteModel();
                                $modelDocente->_idUsuario = $id;
                                $this->resultDocente = $this->_model["Docentes"]->SelectByIdUsuario($modelDocente);
                                if(count($this->resultDocente) == 1)
                                {
                                    $modelDocente->_idDocente = $this->resultDocente[0]["IdDocente"];
                                    $this->_model["Docentes"]->Delete(array($modelDocente));
                                    unset($this->resultDocente);

                                    $modelEstudiante = new models\EstudianteModel();
                                    $modelEstudiante->_idPersona = $this->_resultUsuarioNeeded[0]["IdPersona"];
                                    $modelEstudiante->_legajo = $legajo;
                                    $modelEstudiante->_carrera = $carrera;
                                    $this->_model["Estudiantes"]->Insert(array($modelEstudiante));
                                    unset($modelEstudiante);
                                }
                                unset($this->resultDocente);
                                break;
                            case 8:
                                $modelEstudiante = new models\EstudianteModel();
                                $modelEstudiante->_idUsuario = $id;
                                $this->resultEstudiante = $this->_model["Estudiantes"]->SelectByIdUsuario($modelDocente);
                                if(count($this->resultEstudiante) == 1)
                                {
                                    $modelEstudiante->_idDocente = $this->resultEstudiante[0]["IdEstudiante"];
                                    $this->_model["Docentes"]->Delete(array($modelEstudiante));
                                    unset($this->resultEstudiante);

                                    $modelDocente = new models\DocenteModel();
                                    $modelDocente->_idPersona = $this->_resultUsuarioNeeded[0]["IdPersona"];
                                    $modelDocente->_legajo = $legajo;
                                    $this->result = $this->_model["Estudiantes"]->Insert(array($modelDocente));
                                    unset($modelDocente);
                                }
                                unset($this->resultEstudiante);
                                break;
                            default:
                                trigger_error("Rol no permitido para esta actualizacion.", E_USER_ERROR);
                                break;
                        }
                    }
                    else
                    {
                        switch($rol)
                        {
                            case 7:
                                $modelDocente = new models\DocenteModel();
                                $modelDocente->_idUsuario = $id;
                                $this->resultDocenteNeeded = $this->_model["Docentes"]->SelectByIdUsuario($modelDocente);
                                var_dump($this->resultDocenteNeeded);
                                $modelDocente->_idDocente = $this->resultDocenteNeeded[0]["IdDocente"];
                                $modelDocente->_idPersona = $this->_resultUsuarioNeeded[0]["IdPersona"];
                                $modelDocente->_legajo = $legajo;
                                $this->_model["Docentes"]->Update(array($modelDocente));
                                unset($this->resultDocenteNeeded);
                                break;
                            case 8:
                                $modelEstudiante = new models\EstudianteModel();
                                $modelEstudiante->_idUsuario = $id;
                                $this->resultEstudianteNeeded = $this->_model["Estudiantes"]->SelectByIdUsuario($modelEstudiante);
                                var_dump($this->resultEstudianteNeeded);
                                $modelEstudiante->_idEstudiante = $this->resultEstudianteNeeded[0]["IdEstudiante"];
                                $modelEstudiante->_legajo = $legajo;
                                $modelEstudiante->_idCarrera = $carrera;
                                $this->_model["Estudiantes"]->Update(array($modelEstudiante));
                                unset($modelEstudiante);
                                break;
                            default:
                                trigger_error("Rol no permitido para esta actualizacion.", E_USER_ERROR);
                                break;
                        }
                    }
                    
                    //header("Location: index.php?do=/admin/estydoc_index");
                }
            }
            
            $modelEstYDoc = new models\UsuarioModel();
            $modelEstYDoc->_idUsuario = $id;
            $this->result = $this->_model["Usuarios"]->Select($modelEstYDoc);
            unset($modelEstYDoc);
            //var_dump($this->result);
            if(count($this->result) == 1)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "EmailValidado":
                                    $this->{$key} = $value ? "checked=\"checked\"" : "";
                                    break;
                                default:
                                    $this->{$key} = $value;
                                    break;
                            }
                        }
                    }
                }
            }
            unset($this->result);

            $modelRol = new models\RolModel();
            $modelRol->_idUsuario = $id;
            $this->result = $this->_model["Usuarios"]->SelectAllRolesAndMarkByUser($modelRol);
            unset($modelRol);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/controls/role_option.html";
                        $this->combo_rol .= file_get_contents($filename);

                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "Descripcion":
                                    $this->combo_rol = str_replace('{Nombre}', $value, $this->combo_rol);
                                    break;
                                default:
                                    $this->combo_rol = str_replace('{' . $key . '}', $value, $this->combo_rol);
                                    break;
                            }
                        }
                    }
                }
            }
            unset($this->result);

            $modelCarrera = new models\CarreraModel();
            $modelCarrera->_idUsuario = $id;
            $this->result = $this->_model["Carreras"]->SelectByIdUsuario($modelCarrera);
            unset($modelCarrera);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/controls/carrera_option.html";
                        $this->combo_carrera .= file_get_contents($filename);

                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "EmailValidado":
                                    $this->combo_carrera = str_replace('{' . $key . '}', $value == 1 ? "checked=\"checked\"" : "", $this->combo_carrera);
                                    break;
                                default:
                                    $this->combo_carrera = str_replace('{' . $key . '}', $value, $this->combo_carrera);
                                    break;
                            }
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function estydoc_delete($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/estydoc/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST["btnBorrar"]))
                {
                    $modelPersona = new models\PersonaModel();
                    $modelPersona->_idPersona = $id;
                    $this->result = $this->_model["Usuarios"]->Delete(array($modelPersona));
                    unset($modelPersona);
                    
                    header("Location: index.php?do=/admin/estydoc_index");
                }
            }
            
            $modelUsuario = new models\UsuarioModel();
            $modelUsuario->_idUsuario = $id;
            $this->result = $this->_model["Usuarios"]->Select($modelUsuario);
            unset($modelUsuario);
            //var_dump($this->result);
            if(count($this->result) == 1)
            {
                foreach($this->result as $row)
                {
                    if($row)
                    {
                        foreach($row as $key => $value)
                        {
                            $this->{$key} = $value;
                        }
                    }
                }
            }
            else
            {
                trigger_error("No se encontro el usuario en el sistema.", E_USER_ERROR);
            }
            unset($this->result);
        }
        
        /*
         * Empleados
         */
        public function oper_index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/oper/{$this->_action}.html";
            
            $this->result = $this->_model["Usuarios"]->SelectOperarios();
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/oper/{$this->_action}_table.html";
                        $this->table_user_content .= file_get_contents($filename);

                        foreach($row as $key => $value)
                        {
                            if(!is_array($value))
                            {
                                $this->table_user_content = str_replace('{' . $key . '}', $value, $this->table_user_content);
                            }
                        }
                    }
                }
            }
            else
            {
                $this->table_user_content = "";
            }
            unset($this->result);
        }
        
        public function oper_create()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/oper/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST["btnGuardar"]))
                {
                    $usuario = filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_SPECIAL_CHARS);
                    $contrasena = filter_input(INPUT_POST, "txtContrasena", FILTER_SANITIZE_SPECIAL_CHARS);
                    $nombre = filter_input(INPUT_POST, "txtNombre", FILTER_SANITIZE_SPECIAL_CHARS);
                    $apellido = filter_input(INPUT_POST, "txtApellido", FILTER_SANITIZE_SPECIAL_CHARS);
                    $dni = filter_input(INPUT_POST, "txtDNI", FILTER_SANITIZE_NUMBER_INT);
                    $rol = filter_input(INPUT_POST, "ddlRol", FILTER_SANITIZE_NUMBER_INT);
                    $telefono = filter_input(INPUT_POST, "txtTelefono", FILTER_SANITIZE_NUMBER_INT);
                    $celular = filter_input(INPUT_POST, "txtCelular", FILTER_SANITIZE_NUMBER_INT);
                    $email = filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_EMAIL);
                    $emailValidado = filter_input(INPUT_POST, "chkEmailValido", FILTER_SANITIZE_SPECIAL_CHARS);
                    $comentario = filter_input(INPUT_POST, "txtComentario", FILTER_SANITIZE_SPECIAL_CHARS);
                    
                    $modelPersona = new models\PersonaModel();
                    $modelPersona->_nombre = $nombre;
                    $modelPersona->_apellido = $apellido;
                    $modelPersona->_dni = $dni;
                    $modelPersona->_telefono = $telefono;
                    $modelPersona->_celular = $celular;
                    $modelPersona->_email = $email;
                    $this->resultPersona = $this->_model["Personas"]->Insert(array($modelPersona));
                    unset($modelPersona);
                    //var_dump($this->resultPersona);
                    
                    if(count($this->resultPersona) == 1)
                    {
                        $modelUsuario = new models\UsuarioModel();
                        $modelUsuario->_idPersona = $this->resultPersona;
                        $modelUsuario->_usuario = $usuario;
                        $modelUsuario->_contrasena = $contrasena;
                        $modelUsuario->_comentario = $comentario;
                        $modelUsuario->_emailValido = $emailValidado == "on" ? true : false;
                        $this->resultUsuario = $this->_model["Usuarios"]->Insert(array($modelUsuario));
                        unset($modelUsuario);
                        //var_dump($this->resultUsuario);
                        
                        if(count($this->resultUsuario) == 1)
                        {
                            $modelUsuarioRol = new models\UsuarioRolesModel();
                            $modelUsuarioRol->_idUsuario = $this->resultUsuario;
                            $modelUsuarioRol->_idRol = $rol;
                            $this->_model["UsuarioRoles"]->Insert(array($modelUsuarioRol));
                            unset($modelUsuarioRol);
                            
                            header("Location: index.php?do=/admin/oper_index");
                        }
                        else
                        {
                            trigger_error("No se pudo insertar el rol para el usuario.", E_USER_ERROR);
                        }
                        
                        unset($this->resultUsuario);
                    }
                    else
                    {
                        trigger_error("No se pudo insertar a la persona.", E_USER_ERROR);
                    }
                    
                    unset($this->resultPersona);
                }
            }
            
            // Cargo la lista de roles.
            $this->result = $this->_model["Roles"]->Select();
            //var_dump($this->result);
            foreach($this->result as $row)
            {
                if(is_array($row))
                {
                    if(!($row["Nombre"] == "Estudiante" || $row["Nombre"] == "Docente"))
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/controls/role_option.html";
                        $this->combo_roles .= file_get_contents($filename);

                        foreach($row as $key => $value)
                        {
                            if(!is_array($value))
                            {
                                $this->combo_roles = str_replace('{' . $key . '}', $value, $this->combo_roles);
                            }
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function oper_detail($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/oper/{$this->_action}.html";
            
            $modelUsuario = new models\UsuarioModel();
            $modelUsuario->_idUsuario = $id;
            $this->result = $this->_model["Usuarios"]->Select($modelUsuario);
            unset($modelUsuario);
            //var_dump($this->result);
            if(count($this->result) == 1)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "EmailValidado":
                                    $this->{$key} = $value == 1 ? "checked=\"checked\"" : "";
                                    break;
                                default:
                                    $this->{$key} = $value;
                                    break;
                            }
                        }
                    }
                }
            }
            
            $modelRol = new models\RolModel();
            $modelRol->_idUsuario = $id;
            $this->result = $this->_model["Usuarios"]->SelectAllRolesAndMarkByUser($modelRol);
            unset($modelRol);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/controls/role_option.html";
                        $this->combo_rol .= file_get_contents($filename);

                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "Descripcion":
                                    $this->combo_rol = str_replace('{Nombre}', $value, $this->combo_rol);
                                    break;
                                default:
                                    $this->combo_rol = str_replace('{' . $key . '}', $value, $this->combo_rol);
                                    break;
                            }
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function oper_update($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/oper/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST["btnGuardar"]))
                {
                    $usuario = filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_SPECIAL_CHARS);
                    $contrasena = filter_input(INPUT_POST, "txtContrasena", FILTER_SANITIZE_SPECIAL_CHARS);
                    $nombre = filter_input(INPUT_POST, "txtNombre", FILTER_SANITIZE_SPECIAL_CHARS);
                    $apellido = filter_input(INPUT_POST, "txtApellido", FILTER_SANITIZE_SPECIAL_CHARS);
                    $dni = filter_input(INPUT_POST, "txtDNI", FILTER_SANITIZE_NUMBER_INT);
                    $rol = filter_input(INPUT_POST, "ddlRol", FILTER_SANITIZE_NUMBER_INT);
                    $telefono = filter_input(INPUT_POST, "txtTelefono", FILTER_SANITIZE_NUMBER_INT);
                    $celular = filter_input(INPUT_POST, "txtCelular", FILTER_SANITIZE_NUMBER_INT);
                    $email = filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_EMAIL);
                    $emailValidado = filter_input(INPUT_POST, "chkEmailValido", FILTER_SANITIZE_SPECIAL_CHARS);
                    $comentario = filter_input(INPUT_POST, "txtComentario", FILTER_SANITIZE_SPECIAL_CHARS);
                    
                    $modelUsuario = new models\UsuarioModel();
                    $modelUsuario->_idUsuario = $id;
                    $this->_resultUsuarioNeeded = $this->_model["Usuarios"]->Select($modelUsuario);
                    unset($modelUsuario);
                    //var_dump($this->_resultUsuarioNeeded);
                    
                    $modelUsuarioRol = new models\UsuarioRolesModel();
                    $modelUsuarioRol->_idUsuario = $id;
                    $this->resultRolNeeded = $this->_model["UsuarioRoles"]->Select($modelUsuarioRol);
                    unset($modelUsuarioRol);
                    //var_dump($this->resultRolNeeded);
                    
                    $modelPersona = new models\PersonaModel();
                    $modelPersona->_idPersona = $this->_resultUsuarioNeeded[0]["IdPersona"];
                    $modelPersona->_nombre = $nombre;
                    $modelPersona->_apellido = $apellido;
                    $modelPersona->_dni = $dni;
                    $modelPersona->_telefono = $telefono;
                    $modelPersona->_celular = $celular;
                    $modelPersona->_email = $email;
                    $this->resultPersona = $this->_model["Personas"]->Update(array($modelPersona));
                    unset($modelPersona);
                    //var_dump($this->resultPersona);
                    
                    $modelUsuario = new models\UsuarioModel();
                    $modelUsuario->_idUsuario = $id;
                    $modelUsuario->_idPersona = $this->_resultUsuarioNeeded[0]["IdPersona"];
                    $modelUsuario->_usuario = $usuario;
                    $modelUsuario->_contrasena = $contrasena;
                    $modelUsuario->_comentario = $comentario;
                    $modelUsuario->_emailValidado = $emailValidado == "on" ? true : false;
                    $this->resultUsuario = $this->_model["Usuarios"]->Update(array($modelUsuario));
                    unset($modelUsuario);
                    //var_dump($this->resultUsuario);
                    unset($this->resultUsuario);
                    
                    if($this->resultRolNeeded[0]["IdRol"] != $rol)
                    {
                        $modelUsuarioRol = new models\UsuarioRolesModel();
                        $modelUsuarioRol->_idUsuario = $id;
                        $modelUsuarioRol->_idRol = $rol;
                        $this->_model["UsuarioRoles"]->Update(array($modelUsuarioRol));
                        unset($modelUsuarioRol);
                    }
                    unset($this->resultPersona);
                    
                    unset($this->resultPersonaNeeded);
                    unset($this->resultRolNeeded);
                    
                    header("Location: index.php?do=/admin/oper_index");
                }
            }
            
            $modelUsuario = new models\UsuarioModel();
            $modelUsuario->_idUsuario = $id;
            $this->result = $this->_model["Usuarios"]->Select($modelUsuario);
            unset($modelUsuario);
            //var_dump($this->result);
            if(count($this->result) == 1)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "EmailValidado":
                                    $this->{$key} = $value == 1 ? "checked=\"checked\"" : "";
                                    break;
                                default:
                                    $this->{$key} = $value;
                                    break;
                            }
                        }
                    }
                }
            }
            
            $modelRol = new models\RolModel();
            $modelRol->_idUsuario = $id;
            $this->result = $this->_model["Usuarios"]->SelectAllRolesAndMarkByUser($modelRol);
            unset($modelRol);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/admin/controls/role_option.html";
                        $this->combo_rol .= file_get_contents($filename);

                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "Descripcion":
                                    $this->combo_rol = str_replace('{Nombre}', $value, $this->combo_rol);
                                    break;
                                default:
                                    $this->combo_rol = str_replace('{' . $key . '}', $value, $this->combo_rol);
                                    break;
                            }
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function oper_delete($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/oper/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST["btnBorrar"]))
                {
                    $modelUsuario = new models\UsuarioModel();
                    $modelUsuario->_idUsuario = $id;
                    $this->_model["Usuarios"]->Delete(array($modelUsuario));
                    unset($modelUsuario);
                }
            }
            
            $modelUsuario = new models\UsuarioModel();
            $modelUsuario->_idUsuario = $id;
            $this->result = $this->_model["Usuarios"]->Select($modelUsuario);
            unset($modelUsuario);
            //var_dump($this->result);
            if(count($this->result) == 1)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "EmailValidado":
                                    $this->{$key} = $value == 1 ? "checked=\"checked\"" : "";
                                    break;
                                default:
                                    $this->{$key} = $value;
                                    break;
                            }
                        }
                    }
                }
            }
        }
        
        /*
         * Roles
         */
        public function roles_index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/roles/{$this->_action}.html";
        }
        
        public function roles_create()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/roles/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST[""]))
                {
                    
                }
            }
        }
        
        public function roles_detail($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/roles/{$this->_action}.html";
        }
        
        public function roles_update($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/roles/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST[""]))
                {
                    
                }
            }
        }
        
        public function roles_delete($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/roles/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST[""]))
                {
                    
                }
            }
        }
        
        /*
         * Permisos
         */
        public function perms_index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/perms/{$this->_action}.html";
        }
        
        public function perms_create()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/perms/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST[""]))
                {
                    
                }
            }
        }
        
        public function perms_detail($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/perms/{$this->_action}.html";
        }
        
        public function perms_update($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/perms/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST[""]))
                {
                    
                }
            }
        }
        
        public function perms_delete($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/perms/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST[""]))
                {
                    
                }
            }
        }
        
        /*
         * Configuracion
         */
        
        public function conf_index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/conf/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST["btnGuardarFotocopias"]))
                {
                    $models = array();
                    
                    $post = filter_input(INPUT_POST, 'txtNumFotocopias', FILTER_SANITIZE_NUMBER_INT);
                    if($post !== null)
                    {
                        $model = new models\WebModel();
                        $model->_clave = "FotocopiasXMinuto";
                        $model->_valor = $post;

                        array_push($models, $model);
                    }

                    $post = filter_input(INPUT_POST, 'txtLimiteGabinete', FILTER_SANITIZE_NUMBER_INT);
                    if($post !== null)
                    {
                        $model = new models\WebModel();
                        $model->_clave = "LimiteGabinete";
                        $model->_valor = $post;

                        array_push($models, $model);
                    }
                    
                    $this->_model['Configuraciones']->Update($models);
                }
                
                if(isset($_POST["btnGuardarPrecios"]))
                {
                    $models = array();
                    
                    $post = filter_input(INPUT_POST, 'txtPrecioCEIT', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    if($post !== null)
                    {
                        $model = new models\WebModel();
                        $model->_clave = "PrecioCEIT";
                        $model->_valor = $post;

                        array_push($models, $model);
                    }

                    $post = filter_input(INPUT_POST, 'txtPrecioSimpleFaz', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    if($post !== null)
                    {
                        $model = new models\WebModel();
                        $model->_clave = "PrecioSimpleFaz";
                        $model->_valor = $post;

                        array_push($models, $model);
                    }

                    $post = filter_input(INPUT_POST, 'txtPrecioGabinete', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    if($post !== null)
                    {
                        $model = new models\WebModel();
                        $model->_clave = "PrecioGabinete";
                        $model->_valor = $post;

                        array_push($models, $model);
                    }

                    $post = filter_input(INPUT_POST, 'txtPrecioAnillado', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    if($post !== null)
                    {
                        $model = new models\WebModel();
                        $model->_clave = "PrecioAnillado";
                        $model->_valor = $post;

                        array_push($models, $model);
                    }
                    
                    $this->_model['Configuraciones']->Update($models);
                }
            }
            
            // Cargo los parametros del sistema.
            $this->result = $this->_model['Configuraciones']->Select();
            foreach($this->result as $row)
            {
                $this->{$row['Clave']} = $row['Valor']; 
            }
            unset($this->result);
        }
        
        public function update_user($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
            }
            
            $var = new models\UsuarioModel();
            $var->_idUsuario = $id;
            $this->result = $this->_model['Usuarios']->Select($var);

            // Construyo la pagina
            foreach($this->result[0] as $key => $value)
            {
                $this->{$key} = $value;
            }
        }
        
        public function create_role()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                // creo el rol y lo inserto.
                $modelRole = array(
                    0   =>  new models\RolModel(),
                );
                        
                $modelRole[0]->_descripcion = filter_input(INPUT_POST, "txtDescripcion", FILTER_SANITIZE_STRING);
                $lastRoleId = $this->_model['Roles']->Insert($modelRole);
                
                // creo los permisos y los inserto
                $modelRolePerms = array();
                
                $post = filter_input(INPUT_POST, "ddlPermisos", FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                if($post != null)
                {
                    foreach($post as $key => $value)
                    {
                        $modelRolePerms[$key] = new models\RolPermisosModel();
                        $modelRolePerms[$key]->_idRol = $lastRoleId;
                        $modelRolePerms[$key]->_idPermiso = $value;
                    }

                    $this->_model['RolPermisos']->Insert($modelRolePerms);
                }
            }
            
            $this->result = $this->_model['Permisos']->Select();
            foreach($this->result as $row)
            {
                $filename = BASE_DIR . "/mvc/templates/admin/{$this->_action}_row.html";
                $this->list_perm_item .= file_get_contents($filename);

                if(is_array($row))
                {
                    foreach($row as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $this->list_perm_item = str_replace('{' . $key . '}', $value, $this->list_perm_item);
                        }
                    }
                }
                
                $this->list_perm_item .= PHP_EOL;
            }
        }
        
        public function detail_role($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            $var = new models\RolModel();
            $var->_idRol = $id;
            $this->result = $this->_model['Roles']->Select($var);
            
            // Construyo la pagina
            foreach($this->result[0] as $key => $value)
            {
                $this->{$key} = $value;
            }
            unset($this->result);
            
            $this->result = $this->_model['Roles']->SelectAssignedPermissions($var);
            foreach($this->result as $row)
            {
                $filename = BASE_DIR . "/mvc/templates/admin/{$this->_action}_row.html";
                $this->list_perm_item .= file_get_contents($filename);

                if(is_array($row))
                {
                    foreach($row as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $this->list_perm_item = str_replace('{' . $key . '}', $value, $this->list_perm_item);
                        }
                    }
                }
                
                $this->list_perm_item .= PHP_EOL;
            }
        }
        
        public function update_role($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            if(!empty($_POST))
            {                
                $item = array(
                    0   =>  new models\RolPermisosModel(),
                );
                $item[0]->_idRol = $id;
                $this->_model['RolPermisos']->DeleteByIdRol($item);
                
                $var = array();
                $post = filter_input(INPUT_POST, "ddlPermisos", FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                foreach($post as $key => $value)
                {
                    $var[$key] = new models\RolPermisosModel();
                    $var[$key]->_idRol = $id;
                    $var[$key]->_idPermiso = $value;
                }
                $this->_model['RolPermisos']->Insert($var);
            }
            
            $var = new models\RolModel();
            $var->_idRol = $id;
            $this->result = $this->_model['Roles']->Select($var);

            // Construyo la pagina
            foreach($this->result[0] as $key => $value)
            {
                $this->{$key} = $value;
            }
            unset($this->result);

            $this->result = $this->_model['Roles']->SelectAssignedPermissions($var);
            foreach($this->result as $row)
            {
                $filename = BASE_DIR . "/mvc/templates/admin/detail_role_row.html";
                $this->list_perm_item .= file_get_contents($filename);

                if(is_array($row))
                {
                    foreach($row as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $this->list_perm_item = str_replace('{' . $key . '}', $value, $this->list_perm_item);
                        }
                    }
                }

                $this->list_perm_item .= PHP_EOL;
            }
        }
        
        public function delete_role($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                $post = filter_input(INPUT_POST, "btnAceptar", FILTER_SANITIZE_STRING);
                if($post !== null)
                {
                    // Borro los permisos del rol.
                    $itemRolPermiso = array(
                        0   =>  new models\RolPermisosModel(),
                    );
                    $itemRolPermiso[0]->_idRol = $id;

                    $this->_model['RolPermisos']->DeleteByIdRol($itemRolPermiso);

                    // Borro el rol.
                    $itemRol = array(
                        0   =>  new models\RolModel(),
                    );
                    $itemRol[0]->_idRol = $id;

                    $this->_model['Roles']->Delete($itemRol);
                }
            }
            
            $itemRol = new models\RolModel();
            $itemRol->_idRol = $id;
            $this->result = $this->_model['Roles']->Select($itemRol);
            
            if(count($this->result) > 1)
            {
                foreach($this->result[0] as $key => $value)
                {
                    $this->{$key} = $value;
                }
            }
        }
        
        public function create_perm()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                $modelPermiso = new models\PermisoModel();
                $modelPermiso->_descripcion = filter_input(INPUT_POST, 'txtDescripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $modelPermiso->_controlador = filter_input(INPUT_POST, 'txtControlador', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $modelPermiso->_accion = filter_input(INPUT_POST, 'txtAccion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $this->result = $this->_model['Permisos']->Insert(array($modelPermiso));
                //var_dump($this->result);
                unset($this->result);
            }
        }
        
        public function detail_perm($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            $var = new models\PermisoModel();
            $var->_idPermiso = $id;
            $this->result = $this->_model['Permisos']->Select($var);
            
            // Construyo la pagina
            foreach($this->result[0] as $key => $value)
            {
                $this->{$key} = $value;
            }
            unset($this->result);
            
            $this->result = $this->_model['Permisos']->SelectAssignedRoles($var);
            
            foreach($this->result as $row)
            {
                $filename = BASE_DIR . "/mvc/templates/admin/{$this->_action}_row.html";
                $this->list_role_item .= file_get_contents($filename);

                if(is_array($row))
                {
                    foreach($row as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $this->list_role_item = str_replace('{' . $key . '}', $value, $this->list_role_item);
                        }
                    }
                }
                
                $this->list_role_item .= PHP_EOL;
            }
        }
        
        public function update_perm($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            $modelPermiso = new models\PermisoModel();
            $modelPermiso->_idPermiso = $id;
            
            if(!empty($_POST))
            {    
                $modelPermiso->_descripcion = filter_input(INPUT_POST, 'txtDescripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $modelPermiso->_controlador = filter_input(INPUT_POST, 'txtControlador', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $modelPermiso->_accion = filter_input(INPUT_POST, 'txtAccion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $this->result = $this->_model['Permisos']->Update(array($modelPermiso));
                unset($this->result);
            }
            
            $this->result = $this->_model['Permisos']->Select($modelPermiso);
            if(count($this->result) > 0)
            {
                foreach($this->result[0] as $key => $value)
                {
                    $this->{$key} = $value;
                }
            }
            unset($this->result);
        }
        
        public function delete_perm($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
        }
    }
}

?>