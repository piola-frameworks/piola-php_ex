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
                    'Gabinete'      =>  new models\GabineteModel(),
                    'PedidoEstados' =>  new models\PedidoEstadosModel(),
                    "Caja"          =>  new models\CajaModel(),
                    "CajaItems"     =>  new models\CajaItemModel(),
                    'Franjas'       =>  new models\HorarioFranjasModel(),
                    'Carreras'      =>  new models\CarreraModel(),
                    'Estados'       =>  new models\PedidoItemEstadosModel(),
                    'PosicionX'     =>  new models\PedidoPosicionXModel(),
                    'PosicionY'     =>  new models\PedidoPosicionYModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\GabineteView();
            }
        }
        
        public function __destruct()
        {
            parent::__destruct();
            
            unset($this->result);
            $this->_view->render($this->_template, $this->_dataCollection);
        }
        
        public function delete($id)
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
        }

        public function detail($id)
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
        }

        public function pedidos_index()
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
            }
            
            // traigo datos de la db.
            $this->result = $this->_model['Carreras']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/gabinete/combo_carrera.html";
                    $this->combo_carreras .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_carreras = str_replace('{' . $key . '}', $value, $this->combo_carreras);
                        }
                    }
                }
            }
            unset($this->result);
            
            $this->result = $this->_model['Estados']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/gabinete/combo_estado.html";
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
            
            $this->result = $this->_model['Gabinete']->Select();
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}_table.html";
                    $this->table_content .= file_get_contents($filename);

                    // verifico si trajo 1 o muchos resultados.
                    if(is_array($row))
                    {
                        foreach($row as $innerKey => $innerValue)
                        {
                            $this->table_content = str_replace("{" . $innerKey . "}", $innerValue, $this->table_content);
                        }
                    }
                }
            }
            else
            {
                $this->table_content = "";
            }
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
                    
                    header("Location: index.php?do=/atpublico/pedidos_detail_print/" . $id);
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
                    if($row['Descripcion'] == "Entregado" || $row['Descripcion'] == "Cancelado")
                    {
                        break;
                    }
                    else
                    {
                        $filename = BASE_DIR . "/mvc/templates/gabinete/combo_estado.html";
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
            $modelPedido = new models\PedidoModel();
            $modelPedido->_idGabinetePedido = $id;
            $this->result = $this->_model["Gabinete"]->Select($modelPedido);
            var_dump($this->result);
        }
        
        public function pedidos_delete($id)
        {
            
        }
        
        public function caja_index()
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";

            $reloadFlag = false;
            
            if(!isset($_COOKIE['Caja']))
            {
                $tmpArray = array(
                    'TPs'   =>  array(),
                );
                
                setcookie('GabineteCaja', serialize($tmpArray), time() + 3600);
            }
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                $tmpArray = unserialize(filter_input(INPUT_COOKIE, 'Caja'));
                
                // Si se apreto el boton de Agregar un item del sistema.
                if(isset($_POST['btnAgregarPedido']))
                {
                    $reloadFlag = true;
                    
                    $pedidoItem = filter_input(INPUT_POST, 'btnAgregarPedido', FILTER_SANITIZE_NUMBER_INT);
                    
                    if(!empty($pedidoItem))
                    {
                        $flagExist = false;
                        
                        foreach($tmpArray['TPs'] as $item)
                        {
                            if($item == $pedidoItem)
                            {
                                $flagExist = true;
                                break;
                            }
                        }
                        
                        if(!$flagExist)
                        {
                            $tmpArray['TPs'][] = (int)$pedidoItem;
                        }
                    }
                    
                    $_COOKIE['GabineteCaja'] = serialize($tmpArray);
                    setcookie('GabineteCaja', serialize($tmpArray), time() + 3600);
                }
                
                if(isset($_POST['btnQuitarPedido']))
                {
                    $reloadFlag = true;
                    
                    $btnValor = filter_input(INPUT_POST, 'btnQuitarPedido', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    
                    if(!empty($btnValor))
                    {
                        foreach($tmpArray['TPs'] as $index => $item)
                        {
                            if($btnValor == $item)
                            {
                                unset($tmpArray['TPs'][$index]);
                            }
                        }
                    }
                    
                    $_COOKIE['GabineteCaja'] = serialize($tmpArray);
                    setcookie('GabineteCaja', serialize($tmpArray), time() + 3600);
                }
                
                if(isset($_POST['btnFiltrar']))
                {
                    //$reloadFlag = true;
                    
                    $modelPedido = new models\PedidoModel();
                    $modelPedido->_id = filter_input(INPUT_POST, 'txtLegajo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $this->result = $this->_model['Gabinete']->SelectByIdPedidoOrDNI($modelPedido);
                }
            }
            
            if(!empty($_COOKIE))
            {
                $tmpArray = unserialize(filter_input(INPUT_COOKIE, 'GabineteCaja'));
                $this->Total = 0;

                if(count($tmpArray['TPs']) >= 1)
                {
                    foreach($tmpArray['TPs'] as $item)
                    {
                        $model = new models\PedidoModel();
                        $model->_idPedido = $item;
                        $this->result2 = $this->_model['Gabinete']->SelectCajaItem($model);
                        if(count($this->result2) == 1)
                        {
                            $filename = BASE_DIR . "/mvc/templates/gabinete/caja_table_1_content.html";
                            $this->table_1_content .= file_get_contents($filename);

                            foreach($this->result2[0] as $key => $value)
                            {
                                $this->table_1_content = str_replace('{' . $key . '}', $value, $this->table_1_content);

                                if($key == 'Importe')
                                {
                                    $this->Total += floatval($value);
                                }
                            }
                        }
                    }
                    unset($this->result2);
                }
                else
                {
                    $filename = BASE_DIR . "/mvc/templates/gabinete/caja_table_1_content.html";
                    $this->table_1_content .= file_get_contents($filename);

                    $default = array(
                        'IdPedido'  =>  '',
                        'Descripcion'   =>  '',
                        'PrecioUnitario'    =>  '',
                        'Cantidad'  =>  '',
                        'Importe'   =>  '',
                    );

                    foreach($default as $key => $value)
                    {
                        $this->table_1_content = str_replace('{' . $key . '}', $value, $this->table_1_content);
                    }
                }
            }
            
            if(empty($this->result))
            {
                $this->result = $this->_model['Gabinete']->SelectCaja();
            }
            
            //var_dump($this->result);
            
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
                if(isset($_POST['btnCobrar']))
                {
                    //var_dump($_POST);
                    
                    // General
                    $modelCaja = new models\CajaModel();
                    $modelCaja->_creadoPor = $_SESSION['IdUsuario'];
                    $modelCaja->_creado = date('Y-m-d H:i:s');
                    $modelCaja->_subTotal = filter_input(INPUT_POST, 'txtSubTotal', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    $modelCaja->_pago = filter_input(INPUT_POST, 'txtPaga', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    $modelCaja->_vuelto = filter_input(INPUT_POST, 'txtVuelto', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    $idCaja = $this->_model['Caja']->Insert(array($modelCaja));
                    
                    // Items
                    $ids = filter_input(INPUT_POST, 'hidId', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    $tipo = filter_input(INPUT_POST, 'hidType', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    $descripcion = filter_input(INPUT_POST, 'hidDescripcion', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    $preunit = filter_input(INPUT_POST, 'hidPrecioUnitario', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_REQUIRE_ARRAY);
                    $cantidad = filter_input(INPUT_POST, 'hidCantidad', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                    $importe = filter_input(INPUT_POST, 'hidImporte', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_REQUIRE_ARRAY);
                    
                    $pedidos = array();
                    
                    foreach($ids as $item)
                    {
                        $pedidos[$item] = array(
                            'Tipo'              =>  $tipo[$item],
                            'Descripcion'       =>  $descripcion[$item],
                            'PrecioUnitario'    =>  $preunit[$item],
                            'Cantidad'          =>  $cantidad[$item],
                            'Importe'           =>  $importe[$item],
                        );
                    }
                    
                    $cajaItems = array();
                    $pedidosAPagar = array();
                    
                    foreach($pedidos as $index => $item)
                    {
                        $modelCajaItem = new models\CajaItemModel();
                        $modelCajaItem->_idCaja = $idCaja;
                        $modelCajaItem->_idPedido = $index;
                        $modelCajaItem->_idItem = null;
                        $modelCajaItem->_descripcion = $item['Descripcion'];
                        $modelCajaItem->_precioUnitario = $item['PrecioUnitario'];
                        $modelCajaItem->_cantidad = $item['Cantidad'];
                        array_push($cajaItems, $modelCajaItem);

                        $pedido = new models\PedidoModel();
                        $pedido->_idGabinetePedido = $index;
                        $this->result = $this->_model['Gabinete']->Select($pedido);
                        var_dump($this->result);

                        $pedido->_idUsuario = $this->result[0]['IdUsuario'];
                        $pedido->_creado = $this->result[0]['Creado'];
                        $pedido->_creadoPor = $this->result[0]['CreadoPor'];
                        $pedido->_modificado = date('Y-m-d H:i:s');
                        $pedido->_modificadoPor = $_SESSION['IdUsuario'];
                        $pedido->_anillado = $this->result[0]['Anillado'];
                        $pedido->_posicionX = $this->result[0]['PosicionX'];
                        $pedido->_posicionY = $this->result[0]['PosicionY'];
                        $pedido->_retiro = $this->result[0]['Retiro'];
                        $pedido->_idFranja = $this->result[0]['IdFranja'];
                        $pedido->_pagado = true;
                        $pedido->_idEstado = $this->result[0]['IdEstado'];
                        array_push($pedidosAPagar, $pedido);
                    }
                    
                    $this->_model['CajaItems']->Insert($cajaItems);
                    $this->_model['Gabinete']->Update($pedidosAPagar);
                    
                    setcookie('Caja', null, -1);
                    $_COOKIE['Caja'] = null;
                    
                    //header("Location: index.php?do=/gabinete/caja_index");
                }
                
                $pedidos = array();
                $this->SubTotal = 0;
                
                // armo arrays para poder meterlos en tablas.
                $idPedidos = filter_input(INPUT_POST, 'hidIdPedido', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                $descripcion = filter_input(INPUT_POST, 'hidDescripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                $preunit = filter_input(INPUT_POST, 'hidPrecioUnitario', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_REQUIRE_ARRAY);
                $cantidad = filter_input(INPUT_POST, 'hidCantidad', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                $importe = filter_input(INPUT_POST, 'hidImporte', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_REQUIRE_ARRAY);
                
                if(count($idPedidos) > 0)
                {
                    foreach($idPedidos as $item)
                    {
                        $pedidos[$item] = array(
                            'Tipo'              =>  "Pedido",
                            'Descripcion'       =>  $descripcion[$item],
                            'PrecioUnitario'    =>  $preunit[$item],
                            'Cantidad'          =>  $cantidad[$item],
                            'Importe'           =>  $importe[$item],
                        );
                    }
                }
                
                if(count($pedidos) >= 1)
                {
                    foreach($pedidos as $itemKey => $itemValue)
                    {
                        $filename = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}_table.html";
                        $this->table_content .= file_get_contents($filename);

                        $this->table_content = str_replace('{Id}', $itemKey, $this->table_content);
                        
                        if(is_array($itemValue))
                        {
                            foreach($itemValue as $key => $value)
                            {
                                $this->table_content = str_replace('{' . $key . '}', $value, $this->table_content);
                                
                                if($key == 'Importe')
                                {
                                    $this->SubTotal += floatval($value);
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                // por que entro sin hacer mandado datos?
            }
        }
        
        public function update($id)
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
        }

    }
}

?>