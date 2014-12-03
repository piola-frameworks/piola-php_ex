<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class PreparadorController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Usuarios'      =>  new models\UsuarioModel(),
                    'Pedidos'       =>  new models\PedidoModel(),
                    'PedidoItems'   =>  new models\PedidoItemModel(),
                    'PedidoEstados' =>  new models\PedidoEstadosModel(),
                    'Textos'        =>  new models\TextoModel(),
                    'PosicionX'     =>  new models\PedidoPosicionXModel(),
                    'PosicionY'     =>  new models\PedidoPosicionYModel(),
                    'Estados'       =>  new models\PedidoEstadosModel(),
                    'Franjas'       =>  new models\HorarioFranjasModel(),
                    'Web'           =>  new models\WebModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\PedidosView();
            }
        }
        
        public function __destruct()
        {
            parent::__destruct();
            
            if($this->_ajaxRequest)
            {
                $this->_view->json($this->result);
            }
            else
            {
                $this->_view->render($this->_template, $this->_dataCollection);
            }
            
            unset($this->result);
        }

        public function detail($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/preparador/{$this->_action}.html";
            
            // seteo el modelo para trabajar con los items del pedido
            $pedidosItems = new models\PedidoModel();
            $pedidosItems->_idPedido = $id;
            $this->result = $this->_model['Pedidos']->SelectItem($pedidosItems);
            //var_dump($this->result);
            
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/preparador/{$this->_action}_table_row.html";
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
                                case 'IdTipoTexto':
                                    $this->table_rows = str_replace("{link_enabled}", $value == 1 ? "onclick=\"return false;\"" : "", $this->table_rows);
                                    $this->table_rows = str_replace("{button_print}", $value == 1 ? "btn-danger" : "btn-default", $this->table_rows);
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
                        $filename = BASE_DIR . "/mvc/templates/preparador/{$this->_action}_estado_select_option.html";
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
                    $filename = BASE_DIR . "/mvc/templates/preparador/{$this->_action}_franja_select_option.html";
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
            
            // Posicion X
            $modelPosX = new models\PedidoPosicionXModel();
            $modelPosX->_idPedido = $id;
            $this->result = $this->_model['PosicionX']->SelectWithMarkByIdPedido($modelPosX);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/preparador/combo_posicion_x.html";
                    $this->combo_posicion_x .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_posicion_x = str_replace('{' . $key . '}', $value, $this->combo_posicion_x);
                        }
                    }
                }
            }
            unset($this->result);
            
            // Posicion Y
            $modelPosY = new models\PedidoPosicionYModel();
            $modelPosY->_idPedido = $id;
            $this->result = $this->_model['PosicionY']->SelectWithMarkByIdPedido($modelPosY);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/preparador/combo_posicion_y.html";
                    $this->combo_posicion_y .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_posicion_y = str_replace('{' . $key . '}', $value, $this->combo_posicion_y);
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function print_detail()
        {
            $this->_template = BASE_DIR . "/mvc/templates/preparador/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                $posicionX = filter_input(INPUT_POST, "ddlPosicionX", FILTER_SANITIZE_NUMBER_INT);
                $posicionY = filter_input(INPUT_POST, "ddlPosicionY", FILTER_SANITIZE_NUMBER_INT);
                
                $idPedido = filter_input(INPUT_POST, 'txtIdPedido', FILTER_SANITIZE_NUMBER_INT);
                $pedido = new models\PedidoModel();
                $pedido->_idPedido = $idPedido;
                $this->result = $this->_model['Pedidos']->Select($pedido);
                //var_dump($this->result);
                
                if(isset($_POST['btnTerminar']))
                {
                    $pedido->_idUsuario = $this->result[0]['IdUsuario'];
                    $pedido->_creado = $this->result[0]['Creado'];
                    $pedido->_creadoPor = $this->result[0]['CreadoPor'];
                    $pedido->_modificado = date("Y-m-d H:i:s");
                    $pedido->_modificadoPor = $_SESSION['IdUsuario'];
                    $pedido->_anillado = $this->result[0]['Anillado'];
                    $pedido->_comentario = $this->result[0]['Comentario'];
                    $pedido->_posicionX = $posicionX;
                    $pedido->_posicionY = $posicionY;
                    $pedido->_retiro = $this->result[0]['Retiro'];
                    $pedido->_idFranja = $this->result[0]['IdFranja'];
                    $pedido->_pagado = $this->result[0]['Pagado'];
                    $pedido->_idEstado = 4;

                    $this->_model['Pedidos']->Update(array($pedido));
                }
                
                if(count($this->result) == 1)
                {
                    $this->IdPedido = $this->result[0]['IdPedido'];
                    $this->Creado = $this->result[0]['Creado'];
                    $this->Retiro = $this->result[0]['Retiro'];
                    $this->AnilladoCompleto = $this->result[0]['Anillado'] == 0 ? "No" : "Si";
                    
                    $usuario = new models\UsuarioModel();
                    $usuario->_idUsuario = $this->result[0]['IdUsuario'];
                    $this->result2 = $this->_model['Usuarios']->Select($usuario);
                    //var_dump($this->result2);
                    if(count($this->result2) == 1)
                    {
                        $this->Legajo = $this->result2[0]['Legajo'];
                        $this->DNI = $this->result2[0]['DNI'];
                        $this->Nombre = $this->result2[0]['Nombre'];
                        $this->Apellido = $this->result2[0]['Apellido'];
                        $this->Telefono = $this->result2[0]['Telefono'];
                        $this->Celular = $this->result2[0]['Celular'];
                    }
                    unset($this->result2);
                    
                    $modelFranja = new models\HorarioFranjasModel();
                    $modelFranja->_idFranja = $this->result[0]['IdFranja'];
                    $this->result2 = $this->_model['Franjas']->Select($modelFranja);
                    //var_dump($this->result2);
                    if(count($this->result2) == 1)
                    {
                        $this->Franja = $this->result2[0]['Desde'] . " hs.";
                    }
                    unset($this->result2);
                    
                    $modelPosicionX = new models\PedidoPosicionXModel();
                    $modelPosicionX->_idPosicionX = !empty($this->result[0]['PosicionX']) ? $this->result[0]['PosicionX'] : $posicionX;
                    $this->result2 = $this->_model['PosicionX']->Select($modelPosicionX);
                    //var_dump($this->result2);
                    if(count($this->result2) == 1)
                    {
                        $this->PosicionX = $this->result2[0]['Descripcion'];
                    }
                    unset($this->result2);
                    
                    $modelPosicionY = new models\PedidoPosicionYModel();
                    $modelPosicionY->_idPosicionY = !empty($this->result[0]['PosicionY']) ? $this->result[0]['PosicionY'] : $posicionY;
                    $this->result2 = $this->_model['PosicionY']->Select($modelPosicionY);
                    //var_dump($this->result2);
                    if(count($this->result2) == 1)
                    {
                        $this->PosicionY = $this->result2[0]['Descripcion'];
                    }
                    unset($this->result2);
                    
                    $modelPedidoItems = new models\PedidoModel();
                    $modelPedidoItems->_idPedido = $idPedido;
                    $this->result2 = $this->_model['Pedidos']->SelectItem($modelPedidoItems);
                    //var_dump($this->result2);
                    if(count($this->result2) > 0)
                    {
                        foreach($this->result2 as $row)
                        {
                            if(is_array($row))
                            {
                                $filename = BASE_DIR . "/mvc/templates/preparador/{$this->_action}_table_row.html";
                                $this->table_content .= file_get_contents($filename);
                                
                                foreach($row as $key => $value)
                                {
                                    switch($key)
                                    {
                                        case 'Nombre':
                                            $this->table_content = str_replace("{Descripcion}", $value, $this->table_content);
                                            break;
                                        case 'Hojas':
                                            $this->table_content = str_replace("{CantidadHojas}", $value, $this->table_content);
                                            break;
                                        case 'Anillado':
                                            $this->table_content = str_replace("{" . $key . "}", $value == 1 ? "Si" : "No", $this->table_content);
                                            break;
                                        case 'Abrochado':
                                            $this->table_content = str_replace("{" . $key . "}", $value == 1 ? "Si" : "No", $this->table_content);
                                            break;
                                        case 'SimpleFaz':
                                            $this->table_content = str_replace("{" . $key . "}", $value == 1 ? "Si" : "No", $this->table_content);
                                            break;
                                        default:
                                            $this->table_content = str_replace("{" . $key . "}", $value, $this->table_content);
                                            break;
                                    }
                                }
                            }
                        }
                    }
                    unset($this->result2);
                }
                unset($this->result);
            }
        }
        
        /*public function detail_item($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/preparador/{$this->_action}.html";
            
            $modelPedidoItem = new models\PedidoItemModel();
            $modelPedidoItem->_idItem = $id;
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                $modelPedidoItem->_idEstado = filter_input(INPUT_POST, 'ddlEstadoItem', FILTER_SANITIZE_NUMBER_INT);
                $this->result = $this->_model['PedidoItems']->UpdateEstado(array($modelPedidoItem));
            }
            
            $this->result = $this->_model['PedidoItems']->SelectEstadosAndMarkByIdPedidoItem($modelPedidoItem);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/preparador/{$this->_action}_select_option.html";
                    $this->combo_estado_item .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case "Entregado":
                                    // Esta opcion no debe aparecer.
                                    break;
                                
                                case "Cancelado":
                                    // Esta opcion no debe aparecer.
                                    break;
                                
                                default:
                                    $this->combo_estado_item = str_replace('{' . $key . '}', $value, $this->combo_estado_item);
                                    break;
                            }
                        }
                    }
                }
                
                $this->IdPedido = $id;
            }
            unset($this->result);
        }*/
        
        public function index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/preparador/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                $pedido = new models\PedidoModel();
                $pedido->_idEstado = filter_input(INPUT_POST, 'ddlEstado', FILTER_SANITIZE_NUMBER_INT);
                
                $this->result = $this->_model['Pedidos']->SelectByIdEstado($pedido);
            }
            else
            {
                $this->result = $this->_model['Pedidos']->Select();
            }
            
            // Cargo tabla con items
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/preparador/{$this->_action}_table.html";
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
                                $file_button_delete = BASE_DIR . "/mvc/templates/preparador/{$this->_action}_table_button_delete.html";
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
            $this->result = $this->_model['Estados']->Select();
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

        public function update($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/preparador/{$this->_action}.html";
            
            $modelPedido = new models\PedidoModel();
            $modelEstadoPedido = new models\PedidoEstadosModel();
            $modelFranja = new models\HorarioFranjasModel();
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                // Traigo el pedido por que me faltan datos.
                $modelPedido = new models\PedidoModel();
                $modelPedido->_idPedido = $id;
                
                $pedidoATrabajar = $this->_model['Pedidos']->Select($modelPedido);
                
                $modelPedido->_idUsuario = $pedidoATrabajar[0]['IdUsuario'];
                $modelPedido->_creado = $pedidoATrabajar[0]['Creado'];
                $modelPedido->_creadoPor = $pedidoATrabajar[0]['CreadoPor'];
                $modelPedido->_modificado = date("Y-m-d H:i:s");
                $modelPedido->_modificadoPor = $_SESSION['IdUsuario'];
                $modelPedido->_anillado = $pedidoATrabajar[0]['Anillado'] == 1 ? true : false;
                $modelPedido->_comentario = $pedidoATrabajar[0]['Comentario'];
                $modelPedido->_posicion = filter_input(INPUT_POST, 'txtPosicion', FILTER_SANITIZE_SPECIAL_CHARS);
                $modelPedido->_retiro = $pedidoATrabajar[0]['Retiro'];
                $modelPedido->_idFranja = $pedidoATrabajar[0]['IdFranja'];
                $modelPedido->_pagado = $pedidoATrabajar[0]['Pagado'] == 1 ? true : false;
                $modelPedido->_idEstado = filter_input(INPUT_POST, 'ddlEstado', FILTER_SANITIZE_NUMBER_INT);
                $this->result = $this->_model['Pedidos']->Update(array($modelPedido));
            }
            
            if(isset($modelPedido->_idUsuario))
            {
                unset($modelPedido->_idUsuario);
            }
            
            $modelPedido->_idPedido = $id;
            $this->result = $this->_model['Pedidos']->Select($modelPedido);
            //var_dump($this->result);
            foreach($this->result[0] as $key => $value)
            {
                if($key == 'Anillado')
                {
                    $this->Anillado = $value == 1 ? 'checked="checked"' : '';
                }
                else
                {
                    $this->{$key} = $value;
                }
            }
            unset($this->result);

            $modelEstadoPedido->_idPedido = $id;
            $this->result = $this->_model['PedidoEstados']->SelectByIdPedido($modelEstadoPedido);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        if($row['Descripcion'] == "Entregado" || $row['Descripcion'] == "Cancelado")
                        {
                            break;
                        }
                        else
                        {
                            $filename = BASE_DIR . "/mvc/templates/preparador/detail_estado_select_option.html";
                            $this->combo_estado_pedido .= file_get_contents($filename);
                            
                            foreach($row as $key => $value)
                            {
                                $this->combo_estado_pedido = str_replace('{' . $key . '}', $value, $this->combo_estado_pedido);
                            }
                        }
                    }
                }
            }
            unset($this->result);

            $modelFranja->_idPedido = $id;
            $this->result = $this->_model['Franjas']->SelectByIdPedido($modelFranja);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/preparador/detail_franja_select_option.html";
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
        
        // AJAX actions        
        public function ajax_detail_item_change_status()
        {
            if(!empty($_POST))
            {
                $this->ajaxRequest = true;
                
                $modelPedidoItem = new models\PedidoItemModel();
                $modelPedidoItem->_idItem = filter_input(INPUT_POST, "idItem", FILTER_SANITIZE_NUMBER_INT);
                $modelPedidoItem->_idEstado = filter_input(INPUT_POST, "toValue", FILTER_SANITIZE_STRING) == "true" ? 3 : 2;
                
                $this->result = $this->_model['PedidoItems']->UpdateEstado(array($modelPedidoItem));
            }
        }
    }
}

?>