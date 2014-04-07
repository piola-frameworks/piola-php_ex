<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class CajaController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Configuraciones'   => new models\WebModel(),
                    'Pedidos'   =>  new models\PedidoModel(),
                    'Caja'      =>  new models\CajaModel(),
                    'CajaItems' =>  new models\CajaItemModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\CajaView();
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
        
        public function index()
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/caja/{$this->_action}.html";

            $reloadFlag = false;
            
            if(!isset($_COOKIE['Caja']))
            {
                $tmpArray = array(
                    'Pedidos'   =>  array(),
                    'Items'     =>  array(),
                );
                
                setcookie('Caja', serialize($tmpArray), time() + 3600);
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
                        
                        foreach($tmpArray['Pedidos'] as $item)
                        {
                            if($item == $pedidoItem)
                            {
                                $flagExist = true;
                                break;
                            }
                        }
                        
                        if(!$flagExist)
                        {
                            $tmpArray['Pedidos'][] = (int)$pedidoItem;
                        }
                    }
                    
                    $_COOKIE['Caja'] = serialize($tmpArray);
                    setcookie('Caja', serialize($tmpArray), time() + 3600);
                }
                
                if(isset($_POST['btnAgregarItem']))
                {
                    $reloadFlag = true;
                    
                    $variosIdItem = filter_input(INPUT_POST, 'ddlItemTipo', FILTER_SANITIZE_SPECIAL_CHARS);
                    switch($variosIdItem)
                    {
                        case 'LIB':
                            $variosDesc = "Art(s) de Libreria";
                            break;
                        case 'FSF':
                            $variosDesc = "Fotocopia(s) Suelta(s) Simple Faz";
                            break;
                        case 'FDF':
                            $variosDesc = "Fotocopia(s) Suelta(s) Doble Faz";
                            break;
                        case 'ANI':
                            $variosDesc = "Anillado";
                            break;
                        default:
                            //filter_input(INPUT_POST, 'txtDescripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                            break;
                    }
                    $variosPU = filter_input(INPUT_POST, 'txtImporte', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $variosCant = filter_input(INPUT_POST, 'txtCantidad', FILTER_SANITIZE_NUMBER_INT);
                    $variosImp = $variosPU * $variosCant;
                    
                    if(!empty($variosIdItem) && !empty($variosDesc) && !empty($variosPU) && !empty($variosCant) && !empty($variosImp))
                    {
                        /*$flagExist = false;
                        
                        foreach($tmpArray['Items'] as $item)
                        {
                            if($item['IdItem'] == $variosIdItem)
                            {
                                $flagExist = true;
                                break;
                            }
                        }
                                                
                        if(!$flagExist)
                        {
                            $tmpArray['Items'][] = array(
                                'IdItem'    =>  (string)$variosIdItem,
                                'Descripcion'      =>  (string)$variosDesc,
                                'PrecioUnitario'   =>  (float)$variosPU,
                                'Cantidad'  =>  (int)$variosCant,
                                'Importe'   =>  (float)$variosImp,
                            );
                        }*/
                        
                        $tmpArray['Items'][] = array(
                            'IdItem'    =>  (string)$variosIdItem,
                            'Descripcion'      =>  (string)$variosDesc,
                            'PrecioUnitario'   =>  (float)$variosPU,
                            'Cantidad'  =>  (int)$variosCant,
                            'Importe'   =>  (float)$variosImp,
                        );
                    }
                    
                    $_COOKIE['Caja'] = serialize($tmpArray);
                    setcookie('Caja', serialize($tmpArray), time() + 3600);
                }
                
                if(isset($_POST['btnQuitarPedido']))
                {
                    $reloadFlag = true;
                    
                    $btnValor = filter_input(INPUT_POST, 'btnQuitarPedido', FILTER_SANITIZE_SPECIAL_CHARS);
                    $hidType = filter_input(INPUT_POST, 'hidType', FILTER_SANITIZE_SPECIAL_CHARS);
                    
                    if(!empty($btnValor) && !empty($hidType))
                    {
                        switch($hidType)
                        {
                            case 'Pedido':
                                foreach($tmpArray['Pedidos'] as $index => $item)
                                {
                                    if($btnValor == $item)
                                    {
                                        unset($tmpArray['Pedidos'][$index]);
                                    }
                                }
                                break;

                            case 'Item':
                                foreach($tmpArray['Items'] as $index => $item)
                                {
                                    if($btnValor == $item['IdItem'])
                                    {
                                        unset($tmpArray['Items'][$index]);
                                    }
                                }
                                break;
                            
                            default:
                                trigger_error("Alguien modifico el valor del campo input hidType!", E_USER_ERROR);
                                break;
                        }
                    }
                    
                    $_COOKIE['Caja'] = serialize($tmpArray);
                    setcookie('Caja', serialize($tmpArray), time() + 3600);
                }
                
                if(isset($_POST['btnFiltrar']))
                {
                    //$reloadFlag = true;
                    
                    $modelPedido = new models\PedidoModel();
                    $modelPedido->_id = filter_input(INPUT_POST, 'txtDNI_IDPedido', FILTER_SANITIZE_NUMBER_INT);
                    $this->result = $this->_model['Pedidos']->SelectByIdPedidoOrDNI($modelPedido);
                    //var_dump($this->result);
                }
            }
            
            if(!empty($_COOKIE))
            {
                $tmpArray = unserialize(filter_input(INPUT_COOKIE, 'Caja'));
                $this->Total = 0;

                if(count($tmpArray['Pedidos']) >= 1)
                {
                    foreach($tmpArray['Pedidos'] as $item)
                    {
                        $model = new models\PedidoModel();
                        $model->_idPedido = $item;
                        $this->result2 = $this->_model['Pedidos']->SelectItemCaja($model);
                        if(count($this->result2) == 1)
                        {
                            $filename = BASE_DIR . "/mvc/templates/caja/table_1_content.html";
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
                    $filename = BASE_DIR . "/mvc/templates/caja/table_1_content.html";
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

                if(count($tmpArray['Items']) >= 1)
                {
                    foreach($tmpArray['Items'] as $row)
                    {
                        $filename = BASE_DIR . "/mvc/templates/caja/table_1_content_1.html";
                        $this->table_1_content .= file_get_contents($filename);

                        if(is_array($row))
                        {
                            foreach($row as $key => $value)
                            {
                                $this->table_1_content = str_replace('{' . $key . '}', $value, $this->table_1_content);

                                if($key == 'Importe')
                                {
                                    $this->Total += floatval($value);
                                }
                            } 
                        }
                    }
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
                    
            if(empty($this->result))
            {
                $this->result = $this->_model['Pedidos']->SelectFinished();
            }
            
            if(count($this->result) >= 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/caja/table_2_content.html";
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
                header("Location: index.php?do=/caja/index");
            }
        }
        
        public function index_confirm()
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/caja/{$this->_action}.html";
            
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
                    $ids = filter_input(INPUT_POST, 'hidId', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    $tipo = filter_input(INPUT_POST, 'hidType', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
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
                        switch($item['Tipo'])
                        {
                            case 'Pedido':
                                $modelCajaItem = new models\CajaItemModel();
                                $modelCajaItem->_idCaja = $idCaja;
                                $modelCajaItem->_idPedido = $index;
                                $modelCajaItem->_idItem = null;
                                $modelCajaItem->_descripcion = $item['Descripcion'];
                                $modelCajaItem->_precioUnitario = $item['PrecioUnitario'];
                                $modelCajaItem->_cantidad = $item['Cantidad'];
                                
                                array_push($cajaItems, $modelCajaItem);
                                
                                $pedido = new models\PedidoModel();
                                $pedido->_idPedido = $index;
                                $this->result = $this->_model['Pedidos']->Select($pedido);
                                //var_dump($this->result);

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
                                
                                array_push($pedidosAPagar, $pedido);
                                break;

                            case 'Item':
                                $modelCajaItem = new models\CajaItemModel();
                                $modelCajaItem->_idCaja = $idCaja;
                                $modelCajaItem->_idPedido = null;
                                $modelCajaItem->_idItem = $index;
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
                    $this->_model['Pedidos']->Update($pedidosAPagar);
                    
                    setcookie('Caja', null, -1);
                    $_COOKIE['Caja'] = null;
                    
                    header("Location: index.php?do=/caja/index");
                }
                
                $pedidos = array();
                $items = array();
                $this->SubTotal = 0;
                
                // armo arrays para poder meterlos en tablas.
                $idPedidos = filter_input(INPUT_POST, 'hidIdPedido', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                $idItems = filter_input(INPUT_POST, 'hidIdItem', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                $descripcion = filter_input(INPUT_POST, 'hidDescripcion', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                $preunit = filter_input(INPUT_POST, 'hidPrecioUnitario', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_REQUIRE_ARRAY);
                $cantidad = filter_input(INPUT_POST, 'hidCantidad', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                $importe = filter_input(INPUT_POST, 'hidImporte', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_REQUIRE_ARRAY);
                
                if(count($idPedidos) > 0)
                {
                    foreach($idPedidos as $item)
                    {
                        if(!empty($item))
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
                }
                
                if(count($idItems) > 0)
                {
                    foreach($idItems as $item)
                    {
                        $items[$item] = array(
                            'Tipo'              =>  "Item",
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
                        $filename = BASE_DIR . "/mvc/templates/caja/{$this->_action}_table_content.html";
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

                if(count($items) >= 1)
                {
                    foreach($items as $itemKey => $itemValue)
                    {
                        $filename = BASE_DIR . "/mvc/templates/caja/{$this->_action}_table_content.html";
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
        
        public function ajax_get_cierre_caja()
        {
            $this->_ajaxRequest = true;
                
            $this->result = $this->_model['Caja']->SelectCierraCaja();
        }
    }
}

?>