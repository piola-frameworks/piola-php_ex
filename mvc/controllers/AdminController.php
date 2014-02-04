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
                    'Roles'             =>  new models\RolModel(),
                    'Permisos'          =>  new models\PermisoModel(),
                    'RolPermisos'       =>  new models\RolPermisosModel(),
                    'Configuraciones'   =>  new models\WebModel(),
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
                $models = array();
                
                $post = filter_input(INPUT_POST, 'txtNumFotocopias', FILTER_SANITIZE_NUMBER_INT);
                if($post !== null)
                {
                    $model = new models\WebModel();
                    $model->_clave = "FotocopiasXMinuto";
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
                
                $post = filter_input(INPUT_POST, 'txtPrecioGabinete', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                if($post !== null)
                {
                    $model = new models\WebModel();
                    $model->_clave = "PrecioGabinete";
                    $model->_valor = $post;
                    
                    array_push($models, $model);
                }
                
                $this->_model['Configuraciones']->Update($models);
            }
            
            // Cargo la lista de usuarios.
            $this->result = $this->_model['Usuarios']->Select();
            
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
            unset($this->result);
            
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
            $this->result = $this->_model['Configuraciones']->Select();
            foreach($this->result as $row)
            {
                $this->{$row['Clave']} = $row['Valor']; 
            }
            unset($this->result);
        }
        
        public function create_user()
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
            }
        }

        public function delete_user($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
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
            
            // Construyo la pagina
            foreach($this->result[0] as $key => $value)
            {
                $this->{$key} = $value;
            }
        }

        public function update_user($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                
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
                var_dump($_POST);
                
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
        }
        
        public function delete_perm($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/admin/{$this->_action}.html";
        }
    }
}

?>