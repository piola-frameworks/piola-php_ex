<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class AtpublicoController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Usuarios'      =>  new models\UsuarioModel(),
                    'Textos'        =>  new models\TextoModel(),
                    'Pedidos'       =>  new models\PedidoModel(),
                    'PedidoItems'   =>  new models\PedidoItemModel(),
                    'PedidoEstados' =>  new models\PedidoEstadosModel(),
                    'Franjas'       =>  new models\HorarioFranjasModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\AtPublicoView();
            }
        }
        
        public function __destruct()
        {
            if($this->ajaxRequest)
            {
                $this->_view->json($this->result);
            }
            else 
            {
                $this->_view->render($this->_template, $this->_dataCollection);
            }
            
            unset($this->result);
            
            parent::__destruct();
        }
        
        public function index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                $pedido = new models\PedidoModel();
                $pedido->_idPedidoLegajo = filter_input(INPUT_POST, 'txtLegajoIdPedido', FILTER_SANITIZE_SPECIAL_CHARS);
                
                $this->result = $this->_model['Pedidos']->SelectForDeliverByIdPedidoOrLegajo($pedido);
            }
            else
            {
                $this->result = $this->_model['Pedidos']->SelectForDeliver();
            }
            
            
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}_table_content.html";
                    $this->table_content .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->table_content = str_replace('{' . $key . '}', $value, $this->table_content);
                        }
                    }
                }
            }
            else
            {
                $this->table_content = "";
            }
            unset($this->result);
        }
        
        public function especiales_index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                $pedido = new models\PedidoModel();   
                $pedido->_idEstado = filter_input(INPUT_POST, 'ddlEstado', FILTER_SANITIZE_NUMBER_INT);
                
                $this->result = $this->_model['Pedidos']->SelectEspecialesByIdEstado($pedido);
            }
            else
            {
                $this->result = $this->_model['Pedidos']->SelectEspeciales();
            }
            
            // Cargo tabla con items
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}_table.html";
                    $this->table_content .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            if($key == 'IdPedido')
                            {
                                $id_pedido = $value;
                            }
                            
                            $this->table_content = str_replace("{" . $key . "}", htmlentities($value), $this->table_content);
                            
                            // BOTON BORRAR PEDIDO
                            if(in_array($_SESSION['Roles']['Nombre'], array('Administrador')))
                            {
                                $file_button_delete = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}_table_button_delete.html";
                                $button = file_get_contents($file_button_delete);
                                $button = str_replace('{IdPedido}', $id_pedido, $button);
                                
                                $this->table_content = str_replace('{button_delete}', $button, $this->table_content);
                            }
                            else
                            {
                                $this->table_content = str_replace('{button_delete}', "", $this->table_content);
                            }
                        }   
                    }
                }
            }
            else
            {
                $this->table_content = "";
            }
            unset($this->result);
            
            // Cargo el combo de estados de items.
            $this->result = $this->_model['PedidoEstados']->Select();
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        if(!($row['Descripcion'] == "Entregado" || $row['Descripcion'] == "Cancelado"))
                        {
                            $filename = BASE_DIR . "/mvc/templates/preparador/combo_estado.html";
                            $this->combo_estados .= file_get_contents($filename);
                            
                            foreach($row as $key => $value)
                            {   
                                $this->combo_estados = str_replace('{' . $key . '}', htmlentities($value), $this->combo_estados);
                            }
                        }
                    }
                }
            }
            else
            {
                $this->combo_estados = "";
            }
        }
        
        public function especiales_detail($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                if(isset($_POST['btnTerminar']))
                {
                    $modelPedido = new models\PedidoModel();
                    $modelPedido->_idPedido = $id;
                    $this->result = $this->_model['Pedidos']->Select($modelPedido);
                    //var_dump($this->result);
                    if(count($this->result) > 0)
                    {
                        $modelPedido->_idUsuario = $this->result[0]['IdUsuario'];
                        $modelPedido->_creado = $this->result[0]['Creado'];
                        $modelPedido->_creadoPor = $this->result[0]['CreadoPor'];
                        $modelPedido->_modificado = date("Y-m-d H:i:s");
                        $modelPedido->_modificadoPor = $_SESSION['IdUsuario'];
                        $modelPedido->_anillado = $this->result[0]['Anillado'];
                        $modelPedido->_comentario = null;
                        $modelPedido->_posicionX = null;
                        $modelPedido->_posicionY = null;
                        $modelPedido->_retiro = $this->result[0]['Retiro'];
                        $modelPedido->_idFranja = $this->result[0]['IdFranja'];
                        $modelPedido->_pagado = $this->result[0]['Pagado'];
                        $modelPedido->_idEstado = 4;
                        $modelPedido->_especial = true;

                        $this->_model['Pedidos']->Update(array($modelPedido));
                    }
                }
            }
            
            // seteo el modelo para trabajar con los items del pedido
            $pedidosItems = new models\PedidoModel();
            $pedidosItems->_idPedido = $id;
            $this->result = $this->_model['Pedidos']->SelectItem($pedidosItems);
            //var_dump($this->result);
            
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}_table_row.html";
                    $this->table_rows .= file_get_contents($filename);

                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case 'Anillado':
                                    $this->table_rows = str_replace("{" . $key . "}", $value == 1 ? "checked=\"checked\"" : "", $this->table_rows);
                                    break;
                                case 'Abrochado':
                                    $this->table_rows = str_replace("{" . $key . "}", $value == 1 ? "checked=\"checked\"" : "", $this->table_rows);
                                    break;
                                case 'SimpleFaz':
                                    $this->table_rows = str_replace("{" . $key . "}", $value == 1 ? "checked=\"checked\"" : "", $this->table_rows);
                                    break;
                                case 'IdEstadoItem':
                                    $this->table_rows = str_replace("{Impreso}", $value == 3 ? "checked=\"checked\"" : "", $this->table_rows);
                                    break;
                                default:
                                    $this->table_rows = str_replace("{" . $key . "}", htmlentities($value), $this->table_rows);
                                    break;
                            }
                        }
                    }
                }
            }
            unset($this->result);
            
            // elaboro el parametro y traigo los datos
            $pedido = new models\PedidoModel();
            $pedido->_idPedido = $id;
            $this->result = $this->_model['Pedidos']->Select($pedido);
            //var_dump($this->result);
            foreach($this->result[0] as $key => $value)
            {
                switch($key)
                {
                    case "Pagado":
                        $this->Pagado = $value ? "checked=\"checked\"" : '';
                        break;

                    case "Anillado":
                        $this->Anillado = $value ? "checked=\"checked\"" : "";
                        break;
                    
                    default:
                        $this->{$key} = htmlentities($value);
                        break;
                }
            }
            unset($this->result);
            
            $modelEstadoPedido = new models\PedidoEstadosModel();
            $modelEstadoPedido->_idPedido = $id;
            $this->result = $this->_model['PedidoEstados']->SelectByIdPedido($modelEstadoPedido);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if($row['Descripcion'] == "Entregado" || $row['Descripcion'] == "Cancelado")
                    {
                        break;
                    }
                    else
                    {
                        $filename = BASE_DIR . "/mvc/templates/at_publico/combo_estado.html";
                        $this->combo_estado_pedido .= file_get_contents($filename);

                        if(is_array($row))
                        {
                            foreach($row as $key => $value)
                            {
                                $this->combo_estado_pedido = str_replace('{' . $key . '}', $value, $this->combo_estado_pedido);
                            }
                        }
                    }
                }
            }
            unset($this->result);
            
            $modelFranja = new models\HorarioFranjasModel();
            $modelFranja->_idPedido = $id;
            $this->result = $this->_model['Franjas']->SelectByIdPedido($modelFranja);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/at_publico/combo_franja.html";
                    $this->combo_franja_horario .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_franja_horario = str_replace('{' . $key . '}', $value, $this->combo_franja_horario);
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function create()
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            $this->IdUsuario = "";
            $this->Legajo = "";
            $this->DNI = "";
            $this->Nombre = "";
            $this->Apellido = "";
            $this->Telefono = "";
            $this->Celular = "";
            $this->Creado = date("Y-m-d H:i:s");
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                if(isset($_POST['btnFiltrar']))
                {
                    $dni_legajo = filter_input(INPUT_POST, 'txtDNILegajo', FILTER_SANITIZE_NUMBER_INT);
                    
                    $modelUsuario = new models\UsuarioModel();
                    $modelUsuario->_legajoDNI = $dni_legajo;
                    $this->result = $this->_model['Usuarios']->SelectByLegajoOrDNI($modelUsuario);
                    unset($modelUsuario);
                    //var_dump($this->result);
                    
                    if(count($this->result) == 1)
                    {
                        foreach($this->result[0] as $key => $value)
                        {
                            $this->{$key} = $value;
                        }
                    }
                }
                
                if(isset($_POST["btnGuardar"]))
                {
                    $idUsuario = filter_input(INPUT_POST, 'hidIdUsuario', FILTER_SANITIZE_NUMBER_INT);
                    $creado = filter_input(INPUT_POST, 'txtCreado', FILTER_SANITIZE_SPECIAL_CHARS);
                    $retiro = filter_input(INPUT_POST, 'txtRetiro', FILTER_SANITIZE_SPECIAL_CHARS);
                    $idFranja = filter_input(INPUT_POST, 'ddlFranja', FILTER_SANITIZE_NUMBER_INT);
                    $descripcion = filter_input(INPUT_POST, 'txtDescripcionItem', FILTER_SANITIZE_SPECIAL_CHARS);
                    $hojas = filter_input(INPUT_POST, 'txtHojas', FILTER_SANITIZE_NUMBER_INT);
                    $anillado = filter_input(INPUT_POST, 'chkAnillado', FILTER_SANITIZE_SPECIAL_CHARS);
                    $abrochado = filter_input(INPUT_POST, 'chkAbrochado', FILTER_SANITIZE_SPECIAL_CHARS);
                    $simplefaz = filter_input(INPUT_POST, 'chkSimpleFaz', FILTER_SANITIZE_SPECIAL_CHARS);
                    
                    $modelTextoEspecial = new models\TextoModel();
                    $modelTextoEspecial->_creadoPor = $_SESSION['IdUsuario'];
                    $modelTextoEspecial->_creadoDia = $creado;
                    $modelTextoEspecial->_modificadoPor = null;
                    $modelTextoEspecial->_modificadoDia = null;
                    $modelTextoEspecial->_codInterno = null;
                    $modelTextoEspecial->_idMateria = null;
                    $modelTextoEspecial->_idTipoTexto = 1;
                    $modelTextoEspecial->_idTipoContenido = null;
                    $modelTextoEspecial->_nombre = $descripcion;
                    $modelTextoEspecial->_autor = null;
                    $modelTextoEspecial->_docente = null;
                    $modelTextoEspecial->_cantPaginas = $hojas;
                    $modelTextoEspecial->_activo = 0;
                    $this->_lastIdTexto = $this->_model['Textos']->Insert(array($modelTextoEspecial));      
                    unset($modelTextoEspecial);

                    $modelPedido = new models\PedidoModel();
                    $modelPedido->_idUsuario = $idUsuario;
                    $modelPedido->_creadoPor = $_SESSION['IdUsuario'];
                    $modelPedido->_creadoDia = date("Y-m-d H:i:s");
                    $modelPedido->_modificadoPor = null;
                    $modelPedido->_modificadoDia = null;
                    $modelPedido->_anillado = false;
                    $modelPedido->_comentario = null;
                    $modelPedido->_retiro = $retiro;
                    $modelPedido->_idFranja = $idFranja;
                    $modelPedido->_pagado = false;
                    $modelPedido->_idEstado = 1;
                    $modelPedido->_especial = true;
                    $this->_lastIdPedido = $this->_model['Pedidos']->Insert(array($modelPedido));
                    unset($modelPedido);

                    $modelPedidoItem = new models\PedidoItemModel();
                    $modelPedidoItem->_idPedido = $this->_lastIdPedido;
                    $modelPedidoItem->_cantidad = 1;
                    $modelPedidoItem->_idTexto = $this->_lastIdTexto;
                    $modelPedidoItem->_anillado = !empty($anillado) ? true : false;
                    $modelPedidoItem->_abrochado = !empty($abrochado) ? true : false;
                    $modelPedidoItem->_simpleFaz = !empty($simplefaz) ? true : false;
                    $modelPedidoItem->_idEstado = 1;
                    $this->_model['PedidoItems']->Insert(array($modelPedidoItem));
                    unset($modelPedidoItem);
                    
                    header("Location: index.php?do=/atpublico/print_create/" . $this->_lastIdPedido);
                }
            }
            
            // Combo de franjas horarias
            $this->result = $this->_model['Franjas']->Select();
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/at_publico/combo_franja.html";
                    $this->combo_franja_horario .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_franja_horario = str_replace('{' . $key . '}', $value, $this->combo_franja_horario);
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function print_create($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            $modelPedido = new models\PedidoModel();
            $modelPedido->_idPedido = $id;
            $this->result = $this->_model["Pedidos"]->Select($modelPedido);
            unset($modelPedido);
            //var_dump($this->result);
            if(count($this->result) == 1)
            {
                foreach($this->result[0] as $key => $value)
                {
                    switch($key)
                    {
                        case "IdPedido":
                            $this->IdPedido = $value;
                            break;
                        case "Creado":
                            $this->Creado = $value;
                            break;
                        case "Retiro":
                            $this->Retiro = $value;
                            break;
                        /*case "IdUsuario":
                            $this->_idUsuario = $value;
                            break;*/
                        case "IdFranja":
                            $this->_idFranja = $value;
                            break;
                        default:
                            break;
                    }
                }
            }
            unset($this->result);
            
            $modelFranjas = new models\HorarioFranjasModel();
            $modelFranjas->_idFranja = $this->_idFranja;
            $this->result = $this->_model["Franjas"]->Select($modelFranjas);
            unset($modelFranjas);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                $this->Franja = $this->result[0]['Desde'] . " hs.";
            }
            unset($this->result);
            
            $modelPedidoItems = new models\PedidoItemModel();
            $modelPedidoItems->_idPedido = $id;
            $this->result = $this->_model["PedidoItems"]->Select($modelPedidoItems);
            unset($modelPedidoItems);
            //var_dump($this->result);
            if(count($this->result) == 1)
            {
                foreach($this->result[0] as $key => $value)
                {
                    switch($key)
                    {
                        case "Nombre":
                            $this->Descripcion = $value;
                            break;
                        /*case "Anillado":
                            $this->Anillado = $value == 0 ? "No" : "Si";
                            break;
                        case "Abrochado":
                            $this->Abrochado = $value == 0 ? "No" : "Si";
                            break;
                        case "SimpleFaz":
                            $this->SimpleFaz = $value == 0 ? "No" : "Si";
                            break;
                        case "Hojas":
                            $this->CantidadHojas = $value;
                            break;*/
                        default:
                            break;
                    }
                }
            }
            unset($this->result);
        }
        
        public function update($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            $pedido = new models\PedidoModel();
            $pedido->_idPedido = $id;
            
            $this->result = $this->_model['PedidoEstados']->SelectByIdPedido($pedido);
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/at_publico/combo_estado.html";
                    $this->combo_estados .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_estados = str_replace('{' . $key . '}', $value, $this->combo_estados);
                        }
                    }
                }
            }
            unset($this->result);
            
            if(!empty($_POST))
            {
                $this->result1 = $this->_model['Pedidos']->Select($pedido);
                $pedido->_idUsuario = $this->result1[0]['IdUsuario'];
                $pedido->_creado = $this->result1[0]['Creado'];
                $pedido->_creadoPor = $this->result1[0]['CreadoPor'];
                $pedido->_modificado = date("Y-m-d H:i:s");
                $pedido->_modificadoPor = $_SESSION['IdUsuario'];
                $pedido->_anillado = $this->result1[0]['Anillado'];
                $pedido->_comentario = $this->result1[0]['Comentario'];
                $pedido->_posicion = $this->result1[0]['Posicion'];
                $pedido->_retiro = $this->result1[0]['Retiro'];
                $pedido->_idFranja = $this->result1[0]['IdFranja'];
                $pedido->_pagado = $this->result1[0]['Pagado'];
                $pedido->_idEstado = filter_input(INPUT_POST, 'ddlEstado', FILTER_SANITIZE_NUMBER_INT);
                
                $this->_model['Pedidos']->Update(array($pedido));
                unset($this->result1);
            }
        }
        
        // AJAX actions        
        public function ajax_detail_item_change_status()
        {
            if(!empty($_POST))
            {
                $this->ajaxRequest = true;
                
                $modelPedido = new models\PedidoModel();
                $modelPedido->_idPedido = filter_input(INPUT_POST, "idPedido", FILTER_SANITIZE_NUMBER_INT);
                $modelPedido->_idEstado = filter_input(INPUT_POST, "toValue", FILTER_SANITIZE_STRING) == "true" ? 5 : 4;
                
                $this->result = $this->_model['Pedidos']->UpdateEstado(array($modelPedido));
            }
        }
        
        public function ajax_pedido_estado()
        {
            if(!empty($_POST))
            {
                $this->ajaxRequest = true;
                
                $modelPedido = new models\PedidoModel();
                $modelPedido->_idPedido = filter_input(INPUT_POST, "idPedido", FILTER_SANITIZE_NUMBER_INT);
                
                $this->result = $this->_model['Pedidos']->SelectPedidoEstado($modelPedido);
            }
        }
    }
}

?>