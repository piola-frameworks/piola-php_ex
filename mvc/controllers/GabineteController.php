<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class GabineteController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    "Configuraciones"   =>  new models\WebModel(),
                    "Usuarios"          =>  new models\UsuarioModel(),
                    'Gabinete'          =>  new models\GabineteModel(),
                    'PedidoEstados'     =>  new models\PedidoEstadosModel(),
                    "Caja"              =>  new models\CajaModel(),
                    "CajaItems"         =>  new models\CajaItemModel(),
                    'Franjas'           =>  new models\HorarioFranjasModel(),
                    'Carreras'          =>  new models\CarreraModel(),
                    'Estados'           =>  new models\PedidoEstadosModel(),
                    'PosicionX'         =>  new models\PedidoPosicionXModel(),
                    'PosicionY'         =>  new models\PedidoPosicionYModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\GabineteView();
            }
        }
        
        public function __destruct()
        {
            if($this->_ajaxRequest)
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

        public function pedidos_index()
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                if(isset($_POST["btnFiltrar"]))
                {
                    $estado = filter_input(INPUT_POST, "ddlEstado", FILTER_SANITIZE_NUMBER_INT);
                    
                    $pedido = new models\GabineteModel();   
                    $pedido->_idEstado = $estado;

                    $this->result = $this->_model['Gabinete']->SelectByIdEstado($pedido);
                }
            }
            else
            {
                $this->result = $this->_model['Gabinete']->Select();
            }
            
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}_table.html";
                        $this->table_content .= file_get_contents($filename);
                        
                        foreach($row as $key => $value)
                        {
                            $this->table_content = str_replace("{" . $key . "}", $value, $this->table_content);
                            
                            // Boton de borrar pedido digital.
                            if(in_array($_SESSION['Roles']['Nombre'], array('Administrador')))
                            {
                                $file_button_delete = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}_table_button_delete.html";
                                $button = file_get_contents($file_button_delete);
                                $button = str_replace('{IdGabinetePedido}', $row["IdGabinetePedido"], $button);
                                
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
            
            $this->result = $this->_model['Carreras']->Select();
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/gabinete/combo_carrera.html";
                        $this->combo_carreras .= file_get_contents($filename);
                        
                        foreach($row as $key => $value)
                        {
                            $this->combo_carreras = str_replace('{' . $key . '}', $value, $this->combo_carreras);
                        }
                    }
                }
            }
            unset($this->result);
            
            $this->result = $this->_model['Estados']->Select();
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        if($row["Descripcion"] != "Entregado" && $row["Descripcion"] != "Cancelado")
                        {
                            $filename = BASE_DIR . "/mvc/templates/gabinete/combo_estado.html";
                            $this->combo_estados .= file_get_contents($filename);

                            foreach($row as $key => $value)
                            {
                                $this->combo_estados = str_replace('{' . $key . '}', $value, $this->combo_estados);
                            }
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function pedidos_detail($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                if(isset($_POST["btnTerminar"]))
                {
                    $posicionX = filter_input(INPUT_POST, "ddlPosicionX", FILTER_SANITIZE_NUMBER_INT);
                    $posicionY = filter_input(INPUT_POST, "ddlPosicionY", FILTER_SANITIZE_NUMBER_INT);
                    
                    $pedido = new models\PedidoModel();
                    $pedido->_idGabinetePedido = $id;

                    $this->result = $this->_model["Gabinete"]->Select($pedido);
                    //var_dump($this->result);
                    
                    $pedido->_idUsuario = $this->result[0]["IdUsuario"];
                    $pedido->_creado = $this->result[0]["Creado"];
                    $pedido->_creadoPor = $this->result[0]["CreadoPor"];
                    $pedido->_modificado = date("Y-m-d H:i:s");
                    $pedido->_modificadoPor = $_SESSION["IdUsuario"];
                    $pedido->_anillado = $this->result[0]["Anillado"];
                    $pedido->_posicionX = $posicionX;
                    $pedido->_posicionY = $posicionY;
                    $pedido->_retiro = $this->result[0]["Retiro"];
                    $pedido->_idFranja = $this->result[0]["IdFranja"];
                    $pedido->_pagado = $this->result[0]["Pagado"];
                    $pedido->_idEstado = 4;
                    
                    $this->result = $this->_model["Gabinete"]->Update(array($pedido));
                    
                    header("Location: index.php?do=/gabinete/pedidos_detail_print/" . $id);
                }
            }
            
            $pedido = new models\GabineteModel();
            $pedido->_idGabinetePedido = $id;
            $this->result = $this->_model['Gabinete']->Select($pedido);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result[0] as $key => $value)
                {
                    switch($key)
                    {
                        case "Pagado":
                            $this->Pagado = $value ? "checked=\"checked\"" : "";
                            break;

                        case "Anillado":
                            $this->Anillado = $value ? "checked=\"checked\"" : "";
                            break;

                        default:
                            $this->{$key} = htmlentities($value);
                            break;
                    }
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
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/gabinete/combo_estado.html";
                        $this->combo_estado_pedido .= file_get_contents($filename);
                        
                        foreach($row as $key => $value)
                        {
                            $this->combo_estado_pedido = str_replace('{' . $key . '}', $value, $this->combo_estado_pedido);
                        }
                    }
                }
            }
            unset($this->result);
            
            // seteo el modelo para trabajar con los items del pedido
            $pedidosItems = new models\GabineteModel();
            $pedidosItems->_idPedido = $id;
            $this->result = $this->_model['Gabinete']->SelectItem($pedidosItems);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}_table_row.html";
                        $this->table_rows .= file_get_contents($filename);
                        
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
            
            $modelFranja = new models\HorarioFranjasModel();
            $modelFranja->_idPedido = $id;
            $this->result = $this->_model['Franjas']->SelectByIdPedido($modelFranja);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/gabinete/combo_franja.html";
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
                    $filename = BASE_DIR . "/mvc/templates/gabinete/combo_posicion_x.html";
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
                    $filename = BASE_DIR . "/mvc/templates/gabinete/combo_posicion_y.html";
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
        
        public function pedidos_detail_print($id)
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
            
            $modelPedido = new models\PedidoModel();
            $modelPedido->_idGabinetePedido = $id;
            $this->result = $this->_model["Gabinete"]->Select($modelPedido);
            if(count($this->result) == 1)
            {
                $this->Legajo = $this->result[0]["Legajo"];
                $this->DNI = $this->result[0]["DNI"];
                $this->IdPedido = $this->result[0]["IdPedido"];
                $this->Retiro = $this->result[0]["Retiro"];
                $this->Creado = $this->result[0]["Creado"];
                $this->Anillado = $this->result[0]["Anillado"] == 1 ? "Si" : "No";
                
                // Datos del usuario
                $modelUsuario = new models\UsuarioModel();
                $modelUsuario->_idUsuario = $this->result[0]["IdUsuario"];
                $this->result2 = $this->_model["Usuarios"]->Select($modelUsuario);
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

                // Franjas
                $modelFranja = new models\HorarioFranjasModel();
                $modelFranja->_idFranja = $this->result[0]['IdFranja'];
                $this->result2 = $this->_model['Franjas']->Select($modelFranja);
                //var_dump($this->result2);
                if(count($this->result2) == 1)
                {
                    $this->Franja = $this->result2[0]['Desde'] . " hs.";
                }
                unset($this->result2);
                
                // Posicion
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
                
                // Items del pedido
                $modelGabineteItems = new models\GabineteModel();
                $modelGabineteItems->_idPedido = $id;
                $this->result2 = $this->_model["Gabinete"]->SelectItem($modelGabineteItems);
                //var_dump($this->result2);
                if(count($this->result2) > 0)
                {
                    foreach($this->result2 as $row)
                    {
                        if(is_array($row))
                        {
                            $filename = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}_table.html";
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
            else
            {
                // No encontro ningun resultado?
            }
            unset($this->result);
        }
        
        public function pedidos_delete($id)
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);                
                
                if(isset($_POST["btnBorrar"]))
                {
                    $idGabinetPedido = filter_input(INPUT_POST, "hidIdGabinetePedido", FILTER_SANITIZE_NUMBER_INT);
                    
                    $modelPedido = new models\PedidoModel();
                    $modelPedido->_idPedido = $idGabinetPedido;
                    $this->result = $this->_model["Gabinete"]->Delete(array($modelPedido));
                    unset($modelPedido);
                    unset($this->result);
                }
            }
            
            $this->IdGabinetePedido = $id;
        }
        
        public function ajax_pedido_estado()
        {
            if(!empty($_POST))
            {
                $this->_ajaxRequest = true;
                
                $modelPedido = new models\PedidoModel();
                $modelPedido->_idPedido = filter_input(INPUT_POST, "idPedido", FILTER_SANITIZE_NUMBER_INT);
                
                $this->result = $this->_model["Gabinete"]->SelectEstado($modelPedido);
            }
        }
        
        
        public function caja_index()
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
            
            $reloadFlag = false;
            
            if(!isset($_COOKIE["GabineteCaja"]))
            {
                $tmpArray = array(
                    'Pedidos'   =>  array(),
                    'Items'     =>  array(),
                );
                
                setcookie("GabineteCaja", serialize($tmpArray), time() + 3600);
            }
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                $tmpArray = unserialize(filter_input(INPUT_COOKIE, "GabineteCaja"));
                
                if(isset($_POST["btnAgregarPedido"]))
                {
                    $reloadFlag = true;
                    
                    // Obtengo los datos de interes.
                    $pedidoId = filter_input(INPUT_POST, "btnAgregarPedido", FILTER_SANITIZE_NUMBER_INT);
                    $uniqueId = filter_input(INPUT_POST, "hidIdUniquePedido", FILTER_SANITIZE_STRING);
                    
                    // Si no esta vacio el id del Pedido.
                    if(!empty($pedidoId))
                    {
                        // Marco la bandera de Existente en falso.
                        $flagExist = false;
                        
                        // Rocorro los pedidos ya cargados.
                        foreach($tmpArray["Pedidos"] as $item)
                        {
                            // Compruebo si el item a agregar ya existe en los items cargados.
                            if($item["IdPedido"] == $pedidoId)
                            {
                                // Si existe, marco en verdadero la bandera de Existente.
                                $flagExist = true;
                                break;
                            }
                        }
                        
                        // Pregunto por el estado de la Existencia.
                        if(!$flagExist)
                        {
                            // Si no existe, agrego el pedido a la lista de cargados.
                            $tmpArray["Pedidos"][] = array(
                                "IdUnique"      =>  (string)$uniqueId,
                                "IdPedido"      =>  (int)$pedidoId,
                            ); 
                        }
                    }
                    
                    // Guardo los cambios realizados.
                    setcookie("GabineteCaja", serialize($tmpArray), time() + 3600);
                }
                
                // BOTON AGREGAR ITEM
                if(isset($_POST['btnAgregarItem']))
                {
                    $reloadFlag = true;
                    
                    // Traigo los datos enviados del POST.
                    $variosIdItem = filter_input(INPUT_POST, 'ddlItemTipo', FILTER_SANITIZE_SPECIAL_CHARS);
                    $variosIdUnique = filter_input(INPUT_POST, "hidIdUniqueItem", FILTER_SANITIZE_STRING);
                    $variosPU = filter_input(INPUT_POST, 'txtImporte', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $variosCant = filter_input(INPUT_POST, 'txtCantidad', FILTER_SANITIZE_NUMBER_INT);
                    $variosImp = $variosPU * $variosCant;
                    
                    // Dependiendo del tipo de item, coloco la descripcion correcta.
                    switch($variosIdItem)
                    {
                        case 'ANI':
                            $variosDesc = "Anillado";
                            break;
                        case 'LIB':
                            $variosDesc = "Art(s) de Libreria";
                            break;
                        case 'FSF':
                            $variosDesc = "Fotocopia(s) Suelta(s) Simple Faz";
                            break;
                        case 'FDF':
                            $variosDesc = "Fotocopia(s) Suelta(s) Doble Faz";
                            break;
                        default:
                            //filter_input(INPUT_POST, 'txtDescripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                            break;
                    }
                    
                    // Verfiico que esten enviado todos los datos y que ademas sean correctos.
                    if(!empty($variosIdItem) && !empty($variosDesc) && !empty($variosPU) && !empty($variosCant) && !empty($variosImp))
                    {
                        // Los agrego la lista de items.
                        $tmpArray['Items'][] = array(
                            'IdUnique'          =>  (string)$variosIdUnique,
                            'IdItem'            =>  (string)$variosIdItem,
                            'Descripcion'       =>  (string)$variosDesc,
                            'PrecioUnitario'    =>  (float)$variosPU,
                            'Cantidad'          =>  (int)$variosCant,
                            'Importe'           =>  (float)$variosImp,
                        );
                    }
                    
                    // Guardo los cambios realizados.
                    setcookie("GabineteCaja", serialize($tmpArray), time() + 3600);
                }
                
                // BOTON BORRAR PEDIDO
                if(isset($_POST['btnQuitarPedido']))
                {
                    $reloadFlag = true;
                    
                    $btnValor = filter_input(INPUT_POST, 'btnQuitarPedido', FILTER_SANITIZE_SPECIAL_CHARS);
                    if(!empty($btnValor))
                    {
                        foreach($tmpArray['Pedidos'] as $index => $item)
                        {
                            if($btnValor == $item["IdPedido"])
                            {
                                unset($tmpArray['Pedidos'][$index]);
                            }
                        }
                    }
                    
                    setcookie("GabineteCaja", serialize($tmpArray), time() + 3600);
                }
                
                if(isset($_POST["btnQuitarItem"]))
                {
                    $reloadFlag = true;
                    
                    $btnValor = filter_input(INPUT_POST, "btnQuitarItem", FILTER_SANITIZE_STRING);
                    
                    if(!empty($btnValor))
                    {
                        foreach($tmpArray['Items'] as $index => $item)
                        {
                            if($btnValor == $item['IdUnique'])
                            {
                                unset($tmpArray['Items'][$index]);
                            }
                        }
                    }
                    
                    setcookie("GabineteCaja", serialize($tmpArray), time() + 3600);
                }
                
                if(isset($_POST['btnFiltrar']))
                {
                    //$reloadFlag = true;
                    
                    $modelPedido = new models\PedidoModel();
                    $modelPedido->_id = filter_input(INPUT_POST, 'txtDNI_IDPedido', FILTER_SANITIZE_NUMBER_INT);
                    $this->result = $this->_model["Gabinete"]->SelectByIdPedidoOrDNI($modelPedido);
                    unset($modelPedido);
                }
            }
            
            if(!empty($_COOKIE))
            {
                $tmpArray = unserialize(filter_input(INPUT_COOKIE, "GabineteCaja"));
                $this->Total = 0;

                // Agrego los pedidos a la lista temporal a confirmar
                if(count($tmpArray["Pedidos"]) > 0)
                {
                    foreach($tmpArray["Pedidos"] as $item)
                    {
                        $model = new models\PedidoModel();
                        $model->_idGabinetePedido = $item["IdPedido"];
                        $this->result2 = $this->_model["Gabinete"]->SelectCajaItem($model);
                        
                        if(count($this->result2) == 1)
                        {
                            $filename = BASE_DIR . "/mvc/templates/gabinete/caja_table_1_content.html";
                            $this->table_1_content .= file_get_contents($filename);

                            foreach($this->result2[0] as $key => $value)
                            {
                                switch($key)
                                {
                                    case "Importe":
                                        $this->table_1_content = str_replace('{' . $key . '}', $value, $this->table_1_content);
                                        $this->Total += floatval($value);
                                        break;
                                    default:
                                        $this->table_1_content = str_replace('{' . $key . '}', $value, $this->table_1_content);
                                        break;
                                }
                            }
                            
                            $this->table_1_content = str_replace("{IdUnique}", $item["IdUnique"], $this->table_1_content);
                        }
                        else
                        {
                            trigger_error("No se encontro el pedido seleccionado.", E_USER_ERROR);
                        }
                        unset($this->result2);
                    }
                }

                // Agrego los item a la lista temporal a confirmar.
                if(count($tmpArray['Items']) > 0)
                {
                    foreach($tmpArray['Items'] as $row)
                    {
                        if(is_array($row))
                        {
                            $filename = BASE_DIR . "/mvc/templates/gabinete/caja_table_1_content_item.html";
                            $this->table_1_content .= file_get_contents($filename);
                            
                            foreach($row as $key => $value)
                            {
                                switch($key)
                                {
                                    case "Importe":
                                        $this->table_1_content = str_replace('{' . $key . '}', $value, $this->table_1_content);
                                        $this->Total += floatval($value);
                                        break;
                                    default:
                                        $this->table_1_content = str_replace('{' . $key . '}', $value, $this->table_1_content);
                                        break;
                                }
                            } 
                        }
                        
                        $this->table_1_content = str_replace("{IdUnique}", $row["IdUnique"], $this->table_1_content);
                    }
                }
                
                if(strlen($this->table_1_content) == 0)
                {
                    $this->table_1_content = "";
                }
            }
            
            // Cargo datos de los precios.
            $modelConfiguracion = new models\WebModel();
            $modelConfiguracion->_clave = "PrecioAnillado";
            $this->result2 = $this->_model['Configuraciones']->Select($modelConfiguracion);
            //var_dump($this->result);
            if(count($this->result2) > 0)
            {
                $this->Anillado = $this->result2[0]['Valor'];
            }
            
            $modelConfiguracion->_clave = "PrecioSimpleFaz";
            $this->result2 = $this->_model['Configuraciones']->Select($modelConfiguracion);
            //var_dump($this->result);
            if(count($this->result2) > 0)
            {
                $this->SimpleFaz = $this->result2[0]['Valor'];
            }
            
            $modelConfiguracion->_clave = "PrecioCEIT";
            $this->result2 = $this->_model['Configuraciones']->Select($modelConfiguracion);
            //var_dump($this->result);
            if(count($this->result2) > 0)
            {
                $this->PrecioCEIT = $this->result2[0]['Valor'];
            }
            unset($this->result2);
            
            $this->IdUniqueItem = uniqid("Item_", true);
            
            if(empty($this->result))
            {
                $this->result = $this->_model["Gabinete"]->SelectCaja();
            }
            
            if(count($this->result) >= 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/gabinete/caja_table_2_content.html";
                    $this->table_2_content .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->table_2_content = str_replace('{' . $key . '}', $value, $this->table_2_content);
                        }
                        
                        $this->IdUniquePedido = uniqid("Pedido_", true);
                    }
                }
            }
            else
            {
                $this->table_2_content = "";
            }
            unset($this->result);
            
            if($reloadFlag)
            {
                header("Location: index.php?do=/gabinete/caja_index");
            }
        }
        
        public function caja_confirm()
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                if(isset($_POST['btnCobrar']))
                {
                    // General
                    $modelCaja = new models\CajaModel();
                    $modelCaja->_creadoPor = $_SESSION['IdUsuario'];
                    $modelCaja->_creado = date('Y-m-d H:i:s');
                    $modelCaja->_subTotal = filter_input(INPUT_POST, 'txtSubTotal', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    $modelCaja->_pago = filter_input(INPUT_POST, 'txtPaga', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    $modelCaja->_vuelto = filter_input(INPUT_POST, 'txtVuelto', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    $modelCaja->_gabinete = true;
                    $idCaja = $this->_model["Caja"]->Insert(array($modelCaja)); // 1;
                    
                    // Items
                    $idUniques = filter_input(INPUT_POST, 'hidIdUnique', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
                    $ids = filter_input(INPUT_POST, 'hidId', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    $tipo = filter_input(INPUT_POST, 'hidType', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    $descripcion = filter_input(INPUT_POST, 'hidDescripcion', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    $preunit = filter_input(INPUT_POST, 'hidPrecioUnitario', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_REQUIRE_ARRAY);
                    $cantidad = filter_input(INPUT_POST, 'hidCantidad', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                    $importe = filter_input(INPUT_POST, 'hidImporte', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_REQUIRE_ARRAY);
                    
                    $pedidos = array();
                    
                    foreach($idUniques as $key => $value)
                    {
                        $pedidos[$key] = array(
                            "Id"                =>  $ids[$value],
                            'Tipo'              =>  $tipo[$value],
                            'Descripcion'       =>  $descripcion[$value],
                            'PrecioUnitario'    =>  $preunit[$value],
                            'Cantidad'          =>  $cantidad[$value],
                            'Importe'           =>  $importe[$value],
                        );
                    }
                    
                    $cajaItems = array();
                    $pedidosAPagar = array();
                    
                    foreach($pedidos as $item)
                    {
                        switch($item['Tipo'])
                        {
                            case 'Pedido':
                                $modelCajaItem = new models\CajaItemModel();
                                $modelCajaItem->_idCaja = $idCaja;
                                $modelCajaItem->_idPedido = $item["Id"];
                                $modelCajaItem->_idItem = null;
                                $modelCajaItem->_descripcion = $item['Descripcion'];
                                $modelCajaItem->_precioUnitario = $item['PrecioUnitario'];
                                $modelCajaItem->_cantidad = $item['Cantidad'];
                                
                                array_push($cajaItems, $modelCajaItem);
                                
                                $pedido = new models\GabineteModel();
                                $pedido->_idGabinetePedido = $item["Id"];
                                $this->result = $this->_model["Gabinete"]->Select($pedido);
                                var_dump($this->result);

                                $pedido->_idUsuario = $this->result[0]['IdUsuario'];
                                $pedido->_creado = $this->result[0]['Creado'];
                                $pedido->_creadoPor = $this->result[0]['CreadoPor'];
                                $pedido->_modificado = date('Y-m-d H:i:s');
                                $pedido->_modificadoPor = $_SESSION['IdUsuario'];
                                $pedido->_anillado = $this->result[0]['Anillado'];
                                $pedido->_comentario = $this->result[0]['Comentario'];
                                $pedido->_posicionX = $this->result[0]['PosicionX'];
                                $pedido->_posicionY = $this->result[0]['PosicionY'];
                                $pedido->_retiro = $this->result[0]['Retiro'];
                                $pedido->_idFranja = $this->result[0]['IdFranja'];
                                $pedido->_pagado = true;
                                $pedido->_idEstado = $this->result[0]['IdEstado'];
                                //$pedido->_especial = $this->result[0]['Especial'];
                                
                                array_push($pedidosAPagar, $pedido);
                                break;

                            case 'Item':
                                $modelCajaItem = new models\CajaItemModel();
                                $modelCajaItem->_idCaja = $idCaja;
                                $modelCajaItem->_idPedido = null;
                                $modelCajaItem->_idItem = $item["Id"];
                                $modelCajaItem->_descripcion = $item['Descripcion'];
                                $modelCajaItem->_precioUnitario = $item['PrecioUnitario'];
                                $modelCajaItem->_cantidad = $item['Cantidad'];
                                
                                array_push($cajaItems, $modelCajaItem);
                                break;
                            
                            default:
                                break;
                        }
                    }
                    
                    $this->_model['CajaItems']->Insert($cajaItems);
                    $this->_model["Gabinete"]->Update($pedidosAPagar);
                    
                    setcookie("GabineteCaja", null, -1);
                    
                    header("Location: index.php?do=/gabinete/caja_index");
                }
                
                $pedidos = array();
                $items = array();
                $this->SubTotal = 0;
                
                // armo arrays para poder meterlos en tablas.
                $idUnique = filter_input(INPUT_POST, "hidIdUnique", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
                $idPedidos = filter_input(INPUT_POST, 'hidIdPedido', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                $idItems = filter_input(INPUT_POST, 'hidIdItem', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                $descripcion = filter_input(INPUT_POST, 'hidDescripcion', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                $preunit = filter_input(INPUT_POST, 'hidPrecioUnitario', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_REQUIRE_ARRAY);
                $cantidad = filter_input(INPUT_POST, 'hidCantidad', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                $importe = filter_input(INPUT_POST, 'hidImporte', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_REQUIRE_ARRAY);
                
                if(count($idUnique) > 0)
                {
                    foreach($idUnique as $item)
                    {
                        if(strpos($item, "Pedido") !== false)
                        {
                            //var_dump($idPedidos[$item], $descripcion[$item], $preunit[$item], $cantidad[$item], $importe[$item]);
                            
                            $items[$item] = array(
                                "IdUnique"          =>  $item,
                                'IdItem'            =>  (int)$idPedidos[$item],
                                'Tipo'              =>  "Pedido",
                                'Descripcion'       =>  $descripcion[$item],
                                'PrecioUnitario'    =>  $preunit[$item],
                                'Cantidad'          =>  $cantidad[$item],
                                'Importe'           =>  $importe[$item],
                            );
                        }
                        
                        if(strpos($item, "Item") !== false)
                        {
                            //var_dump($idItems[$item], $descripcion[$item], $preunit[$item], $cantidad[$item], $importe[$item]);
                            
                            $items[$item] = array(
                                "IdUnique"          =>  $item,
                                'IdItem'            =>  (string)$idItems[$item],
                                'Tipo'              =>  "Item",
                                'Descripcion'       =>  $descripcion[$item],
                                'PrecioUnitario'    =>  $preunit[$item],
                                'Cantidad'          =>  $cantidad[$item],
                                'Importe'           =>  $importe[$item],
                            );
                        }
                    }
                }
                if(count($items) > 0)
                {
                    foreach($items as $element)
                    {
                        $filename = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}_table.html";
                        $this->table_content .= file_get_contents($filename);

                        if(is_array($element))
                        {
                            foreach($element as $key => $value)
                            {
                                switch($key)
                                {
                                    case "Id":
                                        $this->table_content = str_replace('{IdUnique}', $value, $this->table_content);
                                        break;
                                    case "IdItem":
                                        $this->table_content = str_replace('{Id}', $value, $this->table_content);
                                        break;
                                    case "Importe":
                                        $this->table_content = str_replace('{' . $key . '}', $value, $this->table_content);
                                        $this->SubTotal += floatval($value);
                                        break;
                                    default:
                                        $this->table_content = str_replace('{' . $key . '}', $value, $this->table_content);
                                        break;
                                }
                            } 
                        }
                    }
                }
                
                if(strlen($this->table_content) == 0)
                {
                    $this->table_content = "";
                }
            }
            else
            {
                // por que entro sin hacer mandado datos?
            }
        }
    }
}

?>