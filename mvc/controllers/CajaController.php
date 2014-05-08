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
                    'Pedidos'           =>  new models\PedidoModel(),
                    'Caja'              =>  new models\CajaModel(),
                    'CajaItems'         =>  new models\CajaItemModel(),
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
            
            // Bandera necesarias para recargar correctamente la pagina.
            $reloadFlag = false;
            
            // Verifica si el cookie necesario esta escrito.
            if(!isset($_COOKIE['Caja']))
            {
                $tmpArray = array(
                    'Pedidos'   =>  array(),
                    'Items'     =>  array(),
                );
                
                setcookie('Caja', serialize($tmpArray), time() + 3600);
            }
            
            // Verifico si se emitio un POST.
            if(!empty($_POST))
            {
                // Deserializo el cookie.
                $tmpArray = unserialize(filter_input(INPUT_COOKIE, 'Caja'));
                
                // BOTON AGREGAR PEDIDO
                if(isset($_POST['btnAgregarPedido']))
                {
                    // Bandera necesaria para la recarga de la pagina.
                    $reloadFlag = true;
                    
                    // Obtengo los datos de interes.
                    $pedidoId = filter_input(INPUT_POST, 'btnAgregarPedido', FILTER_SANITIZE_NUMBER_INT);
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
                    //$_COOKIE['Caja'] = serialize($tmpArray);
                    setcookie('Caja', serialize($tmpArray), time() + 3600);
                }
                
                // BOTON AGREGAR ITEM
                if(isset($_POST['btnAgregarItem']))
                {
                    // Bandera necesaria para la regarga.
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
                        case 'PES':
                            $variosDesc = "Pedido Especial Simple Faz";
                            break;
                        case 'PED':
                            $variosDesc = "Pedido Especial Doble Faz";
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
                    //$_COOKIE['Caja'] = serialize($tmpArray);
                    setcookie('Caja', serialize($tmpArray), time() + 3600);
                }
                
                // BOTON BORRAR PEDIDO
                if(isset($_POST['btnQuitarPedido']))
                {
                    // Bandera necesaria 
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
                    
                    $_COOKIE['Caja'] = serialize($tmpArray);
                    setcookie('Caja', serialize($tmpArray), time() + 3600);
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
                    
                    $_COOKIE['Caja'] = serialize($tmpArray);
                    setcookie('Caja', serialize($tmpArray), time() + 3600);
                }
                
                if(isset($_POST['btnFiltrar']))
                {
                    //$reloadFlag = true;
                    
                    $modelPedido = new models\PedidoModel();
                    $modelPedido->_id = filter_input(INPUT_POST, 'txtDNI_IDPedido', FILTER_SANITIZE_NUMBER_INT);
                    $this->result = $this->_model['Pedidos']->SelectByIdPedidoOrDNI($modelPedido);
                    unset($modelPedido);
                }
            }
            
            if(!empty($_COOKIE))
            {
                $tmpArray = unserialize(filter_input(INPUT_COOKIE, 'Caja'));
                $this->Total = 0;

                // Agrego los pedidos a la lista temporal a confirmar
                if(count($tmpArray["Pedidos"]) > 0)
                {
                    foreach($tmpArray["Pedidos"] as $item)
                    {
                        $model = new models\PedidoModel();
                        $model->_idPedido = $item["IdPedido"];
                        $this->result2 = $this->_model['Pedidos']->SelectItemCaja($model);
                        
                        if(count($this->result2) == 1)
                        {
                            $filename = BASE_DIR . "/mvc/templates/caja/table_1_content.html";
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
                            // Si entro aca es por que no encontro el pedido.
                        }
                        unset($this->result2);
                    }
                }
                

                // Agrego los item a la lista temporal a confirmar.
                if(count($tmpArray['Items']) > 0)
                {
                    foreach($tmpArray['Items'] as $row)
                    {
                        $filename = BASE_DIR . "/mvc/templates/caja/table_1_content_1.html";
                        $this->table_1_content .= file_get_contents($filename);

                        if(is_array($row))
                        {
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
                header("Location: index.php?do=/caja/index");
            }
        }
        
        public function index_confirm()
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/caja/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                if(isset($_POST['btnCobrar']))
                {
                    // General
                    $modelCaja = new models\CajaModel();
                    $modelCaja->_creadoPor = $_SESSION['IdUsuario'];
                    $modelCaja->_creado = date('Y-m-d H:i:s');
                    $modelCaja->_subTotal = filter_input(INPUT_POST, 'txtSubTotal', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    $modelCaja->_pago = filter_input(INPUT_POST, 'txtPaga', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    $modelCaja->_vuelto = filter_input(INPUT_POST, 'txtVuelto', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                    $modelCaja->_gabinete = false;
                    $idCaja = $this->_model['Caja']->Insert(array($modelCaja)); // 1;
                    
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
                                
                                $pedido = new models\PedidoModel();
                                $pedido->_idPedido = $item["Id"];
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
                                $pedido->_especial = $this->result[0]['Especial'];
                                
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
                    $this->_model['Pedidos']->Update($pedidosAPagar);
                    
                    //$_COOKIE['Caja'] = null;
                    setcookie('Caja', null, -1);
                    
                    header("Location: index.php?do=/caja/index");
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
                
                /*if(count($pedidos) > 0)
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
                                switch($key)
                                {
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
                }*/

                if(count($items) > 0)
                {
                    foreach($items as $element)
                    {
                        $filename = BASE_DIR . "/mvc/templates/caja/{$this->_action}_table_content.html";
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
        
        public function ajax_get_cierre_caja()
        {
            $this->_ajaxRequest = true;
                
            $this->result = $this->_model['Caja']->SelectCierraCaja();
        }
    }
}

?>