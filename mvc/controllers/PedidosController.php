<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class PedidosController extends core\AController implements core\ICrud
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Pedidos'       =>  new models\PedidoModel(),
                    'PedidoItems'   =>  new models\PedidoItemModel(),
                    'PedidoEstados' =>  new models\PedidoEstadosModel(),
                    'Textos'        =>  new models\TextoModel(),
                    'Carreras'      =>  new models\CarreraModel(),
                    'Niveles'       =>  new models\NivelModel(),
                    'Materias'      =>  new models\MateriaModel(),
                    'Estados'       =>  new models\PedidoItemEstadosModel(),
                    'Franjas'       =>  new models\HorarioFranjasModel(),
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
        
        public function create()
        {
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
            
            /*
             * Formato del cookie
             * 
             * 'Pedido' => array(
             *      'AnilladoCompleto' = booleano obligatorio,
             *      'Comentario' = string opcional,
             *      'Items' => array(
             *          'IdTexto' = entero obligatorio,
             *          'SimpleFaz' = booleano obligatorio,
             *          'Anillado' = booleano obligatorio,
             *          'Cantidad' = entero obligatorio,
             *      );
             * );
             * 
             */
            
            if(!empty($_POST))
            {
                // Creo un array temporal para trabajar.
                $tmpArray = array(
                    'AnilladoCompleto'  =>  false,
                    'Comentario'        =>  null,
                    'Items'             =>  array(),
                );
                
                // Si se agrego un texto, va a parar a la cookie.
                if(isset($_POST['btnAgregarTexto']))
                {
                    // Saco los datos de la cookie.
                    $cookie = filter_input(INPUT_COOKIE, 'TextosAgregados');
                    if(!empty($cookie))
                    {
                        // Deserializo y lo guardo en el array temporal.
                        $tmpArray = unserialize($cookie);
                    }
                    
                    // Saco el dato de interes del POST.
                    $postIdTexto = filter_input(INPUT_POST, 'btnAgregarTexto', FILTER_SANITIZE_NUMBER_INT);
                    
                    if(!empty($postIdTexto))
                    {
                        $flagExist = false;
                        
                        // Busco si existe el item.
                        foreach($tmpArray['Items'] as $item)
                        {
                            if($item['IdTexto'] == $postIdTexto)
                            {
                                $flagExist = true;
                                break;
                            }
                        }
                        
                        // Si no existe en el array temporal, lo guardo.
                        if(!$flagExist)
                        {
                            $tmpArray['Items'][] = array(
                                'IdTexto'   =>  (int)$postIdTexto,
                                'SimpleFaz' =>  false,
                                'Anillado'  =>  false,
                                'Cantidad'  =>  1,
                            );
                        }
                    }
                    
                    // Serializo el array temporal y lo guardo en la cookie.
                    $_COOKIE['TextosAgregados'] = serialize($tmpArray);
                    setcookie('TextosAgregados', serialize($tmpArray), time() + 3600);
                }
                
                // Si se quita un detalle, lo quito de la cookie.
                if(isset($_POST['btnQuitarDetalle']))
                {
                    // Saco los datos de la cookie.
                    $cookie = filter_input(INPUT_COOKIE, 'TextosAgregados');
                    if(!empty($cookie))
                    {
                        // Deserializo y lo guardo en el array temporal.
                        $tmpArray = unserialize($cookie);
                    }
                    
                    // Saco el dato de interes del POST.
                    $postIdTexto = filter_input(INPUT_POST, 'btnQuitarDetalle', FILTER_SANITIZE_NUMBER_INT);
                    if(!empty($postIdTexto))
                    {
                        // Si existe en el array temporal, lo borro.
                        foreach($tmpArray['Items'] as $index => $item)
                        {
                            if($postIdTexto == $item['IdTexto'])
                            {
                                unset($tmpArray['Items'][$index]);
                            }
                        }
                    }
                    
                    // Serializo el array temporal y lo guardo en la cookie.
                    $_COOKIE['TextosAgregados'] = serialize($tmpArray);
                    setcookie('TextosAgregados', serialize($tmpArray), time() + 3600);
                }
            }
            
            // Me fijo si la cookie esta vacia.
            if(!empty($_COOKIE))
            {
                $cookie = filter_input(INPUT_COOKIE, 'TextosAgregados');
                if(!empty($cookie))
                {
                    // Obtengo el valor de la cookie.
                    $tmpArray = unserialize($cookie);
                    
                    // Recorro los valores de la cookie.
                    foreach($tmpArray['Items'] as $item)
                    {
                        $modelTexto = new models\TextoModel();
                        $modelTexto->_idTexto = $item['IdTexto'];
                        $this->result = $this->_model['Textos']->Select($modelTexto);
                        //var_dump($this->result);
                        if(count($this->result) == 1)
                        {
                            $filename = BASE_DIR . "/mvc/templates/pedidos/table_text_added_row.html";
                            $this->table_text_added .= file_get_contents($filename);
                            
                            foreach($this->result[0] as $key => $value)
                            {
                                $this->table_text_added = str_replace('{' . $key . '}', htmlentities($value), $this->table_text_added);
                            }
                        }
                        unset($this->result);
                    }
                }
            }
            
            // Cargo las carreras.
            $this->result = $this->_model['Carreras']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/combo_carrera.html";
                    $this->combo_carrera .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_carrera = str_replace('{' . $key .'}', htmlentities($value), $this->combo_carrera);
                        }
                    }
                }
            }
            unset($this->result);
            
            // Cargo la tabla de los resultados.
            if(isset($_POST['ddlMateria']))
            {
                $modelTexto = new models\TextoModel();
                $modelTexto->_idMateria = filter_input(INPUT_POST, 'ddlMateria', FILTER_SANITIZE_NUMBER_INT);
                $this->result = $this->_model['Textos']->SelectByIdMateria($modelTexto);
            }
            else
            {
                $this->result = $this->_model['Textos']->Select();
            }
            
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}_table_row.html";
                    $this->table_content .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->table_content = str_replace('{' . $key . '}', htmlentities($value), $this->table_content);
                        }
                    }
                }
            }
            unset($this->result);
        }

        public function create_confirm()
        {
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                if(isset($_POST['IdTexto']) && isset($_POST['txtCantidadCopias']))
                {
                    // Agarro las variables del POST.
                    $postIdTexto = filter_input(INPUT_POST, 'IdTexto', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                    $postSimpleFaz = filter_input(INPUT_POST, 'chkSimpleFaz', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
                    $postAnillado = filter_input(INPUT_POST, 'chkAnillado', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
                    $postCantCopias = filter_input(INPUT_POST, 'txtCantidadCopias', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                    $postTodoAnillado = filter_input(INPUT_POST, 'chkAnilladoCompleto', FILTER_SANITIZE_STRING);
                    $postComentario = filter_input(INPUT_POST, 'txtComentario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    // Verifico que existan
                    if(!empty($postIdTexto) && !empty($postCantCopias))
                    {
                        // Y que tengan la misma cantidad
                        if(count($postCantCopias) == count($postIdTexto))
                        {
                            for($index = 0; $index < count($postIdTexto); $index++)
                            {
                                $modelTexto = new models\TextoModel();
                                $modelTexto->_idTexto = $postIdTexto[$index];
                                $this->result = $this->_model['Textos']->Select($modelTexto);
                                if(count($this->result) == 1)
                                {
                                    $filename = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}_table_row.html";
                                    $this->table_detail .= file_get_contents($filename);

                                    foreach($this->result[0] as $key => $value)
                                    {
                                        $this->table_detail = str_replace('{' . $key . '}', htmlentities($value), $this->table_detail);
                                    }
                                    $this->table_detail = str_replace('{SimpleFaz}', empty($postSimpleFaz[$postIdTexto[$index]]) ? '' : 'checked', $this->table_detail);
                                    $this->table_detail = str_replace('{Anillado}', empty($postAnillado[$postIdTexto[$index]]) ? '' : 'checked', $this->table_detail);
                                    $this->table_detail = str_replace('{Cantidad}', $postCantCopias[$postIdTexto[$index]], $this->table_detail);
                                    
                                }
                                
                                $this->Comentario = empty($postComentario) ? '' : $postComentario;
                                $this->TodoAnillado = empty($postTodoAnillado) ? '' : 'checked';
                                
                                unset($this->result);
                            }
                        }
                    }
                }

                // Si acepto el pedido, persisto en DB.
                if(isset($_POST['btnSi']))
                {
                    // Agrego el pedido.
                    $modelPedido = new models\PedidoModel();
                    $modelPedido->_idUsuario = $_SESSION['IdUsuario'];
                    $modelPedido->_creadoDia = date("Y-m-d H:i:s");
                    $modelPedido->_creadoPor = $_SESSION['IdUsuario'];
                    $modelPedido->_modificadoDia = null;
                    $modelPedido->_modificadoPor = null;
                    $modelPedido->_anillado = filter_input(INPUT_POST, 'chkAnilladoCompleto', FILTER_SANITIZE_STRING);
                    $modelPedido->_comentario = filter_input(INPUT_POST, 'txtComentario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $modelPedido->_retiro = filter_input(INPUT_POST, 'txtRetiro', FILTER_SANITIZE_STRING);
                    $modelPedido->_idFranja = filter_input(INPUT_POST, 'ddlFranja', FILTER_SANITIZE_NUMBER_INT);
                    $modelPedido->_pagado = false;
                    $modelPedido->_idEstado = 1;
                    $this->lastId = $this->_model['Pedidos']->Insert(array($modelPedido));
                    
                    // Agrego los items del pedido.
                    $modelPedidoItems = array();
                    $items = filter_input(INPUT_POST, 'hidIdTexto', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                    $simpleFaz_items = filter_input(INPUT_POST, 'hidSimpleFaz', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
                    $anillado_items = filter_input(INPUT_POST, 'hidAnillado', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
                    $canttext_items = filter_input(INPUT_POST, 'hidCantidadText', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                    foreach($items as $item)
                    {
                        $tmpItem = new models\PedidoItemModel();
                        $tmpItem->_idPedido = $this->lastId;
                        $tmpItem->_cantidad = $canttext_items[$item];
                        $tmpItem->_idTexto = $item;
                        $tmpItem->_anillado = empty($anillado_items[$item]) ? false : true;
                        $tmpItem->_simpleFaz = empty($simpleFaz_items[$item]) ? false : true;
                        $tmpItem->_idEstado = 1;
                        
                        $modelPedidoItems[] = $tmpItem;
                    }
                    $this->result = $this->_model['PedidoItems']->Insert($modelPedidoItems);
                    
                    // Libero memoria.
                    unset($this->lastId);
                    unset($this->result);
                    
                    // Quito la seleccion de la pagina previa.
                    setcookie('TextosAgregados', null, -1);
                }
            }
            
            $this->result = $this->_model['Franjas']->Select();
            $this->result2 = $this->_model['Pedidos']->SelectDisponibilidad();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/combo_franja.html";
                    $this->combo_franja .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_franja = str_replace('{' . $key . '}', htmlentities($value), $this->combo_franja);
                        }
                        
                        // Fila extra que no viene en el resultado de la base de datos.
                        $this->combo_franja = str_replace('{Seleccionado}', $this->result2[0]['FranjaHoraria'] != $row['IdHorarioFranja'] ? '' : 'selected', $this->combo_franja);
                    }
                }
                $this->DiaRetiro = $this->result2[0]['DiaRetiro'];
            }
            unset($this->result);
            unset($this->result2);
        }

        public function create_tp()
        {
            // indico el template a usar
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
            
            if(!empty($_POST) && !empty($_FILES))
            {
                var_dump($_POST, $_FILES);
                
                switch($_FILES['filArchivo']['error'])
                {
                    case UPLOAD_ERR_OK:
                        $cmd = 'FOR /F "tokens=2*" %a IN ("' . str_replace("", "", BASE_DIR) . '\bin\pdfinfo.exe" "' . $_FILES['filArchivo']['tmp_name'] . '" ^| findstr "Pages:") DO ECHO %a';
                        echo $cmd . "<br />";
                        echo shell_exec($cmd) . "<br />";
                        
                        // Guardo en la db.
                        $modelTP = new models\TextoModel();
                        /*
                         *  ':creadoPor'        =>  (int)$item->_creadoPor,
                            ':creadoDia'        =>  (string)$item->_creadoDia,
                            ':modificadoPor'    =>  (int)$item->_modificadoPor,
                            ':modificadoDia'    =>  (string)$item->_modificadoDia,
                            ':codInterno'       =>  (string)$item->_codInterno,
                            ':idMateria'        =>  (int)$item->_idMateria,
                            ':idTipo'           =>  (int)$item->_idTipo,
                            ':nombre'           =>  (string)$item->_nombre,
                            ':autor'            =>  (string)$item->_autor,
                            ':docente'          =>  (string)$item->_docente,
                            ':cantPaginas'      =>  (int)$item->_cantPaginas,
                         *  ':activo'           =>  (bool)$item->_activo,
                         */
                        
                        $modelTP->_creadoPor = $_SESSION['IdUsuario'];
                        $modelTP->_creadoDia = date("Y-m-d H:i:s");
                        $modelTP->_modificadoPor = null;
                        $modelTP->_modificadoDia = null;
                        $modelTP->_codInterno = null;
                        $modelTP->_idMateria = null;
                        $modelTP->_idTipo = 4;
                        $modelTP->_nombre = filter_input(INPUT_POST, 'txtNombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $modelTP->_autor = null;
                        $modelTP->_docente = null;
                        $modelTP->_cantPaginas = 1/*shell_exec($cmd)*/;
                        $modelTP->_activo = 0;
                        $this->_lastIdTexto = $this->_model['Textos']->Insert(array($modelTP));
                        unset($modelTP);
                        
                        $modelPedido = new models\PedidoModel();
                        /*
                         *  ':idUsuario'    =>  (int)$item->_idUsuario,
                         *  ':creadoDia'    =>  (int)$item->_creadoDia,
                         *  ':creadoPor'    =>  (string)$item->_creadoPor,
                         *  ':anillado'     =>  (bool)$item->_anillado,
                         *  ':comentario'   =>  is_null($item->_comentario) ? null : (string)$item->_comentario,
                         *  ':retiro'       =>  (string)$item->_retiro,
                         *  ':idFranja'     =>  (int)$item->_idFranja,
                         *  ':pagado'       =>  (bool)$item->_pagado,
                         *  ':idEstado'     =>  (int)$item->_idEstado
                         */
                        
                        $modelPedido->_idUsuario = $_SESSION['IdUsuario'];
                        $modelPedido->_creadoPor = $_SESSION['IdUsuario'];
                        $modelPedido->_creadoDia = date("Y-m-d H:i:s");
                        $modelPedido->_modificadoPor = null;
                        $modelPedido->_modificadoDia = null;
                        $modelPedido->_anillado = false;
                        $modelPedido->_comentario = null;
                        $modelPedido->_retiro = filter_input(INPUT_POST, 'hidRetiro', FILTER_SANITIZE_STRING);
                        $modelPedido->_idFranja = filter_input(INPUT_POST, 'hidFranja', FILTER_SANITIZE_NUMBER_INT);
                        $modelPedido->_pagado = false;
                        $modelPedido->_idEstado = 1;
                        $this->_lastIdPedido = $this->_model['Pedidos']->Insert(array($modelPedido));
                        unset($modelPedido);
                        
                        $modelPedidoItem = new models\PedidoItemModel();
                        /*
                         *  ':idPedido'     =>  (int)$item->_idPedido,
                         *  ':cantidad'     =>  (int)$item->_cantidad,
                         *  ':idTexto'      =>  (int)$item->_idTexto,
                         *  ':anillado'     =>  (bool)$item->_anillado,
                         *  ':simpleFaz'    =>  (bool)$item->_simpleFaz,
                         *  ':idEstado'     =>  (int)$item->_idEstado,
                         */
                        $modelPedidoItem->_idPedido = $this->_lastIdPedido;
                        $modelPedidoItem->_cantidad = 1;
                        $modelPedidoItem->_idTexto = $this->_lastIdTexto;
                        $modelPedidoItem->_anillado = false;
                        $modelPedidoItem->_simpleFaz = false;
                        $modelPedidoItem->_idEstado = 1;
                        $this->_model['PedidoItems']->Insert(array($modelPedidoItem));
                        unset($modelPedidoItem);
                        
                        unset($this->lastIdTexto);
                        unset($this->lastIdPedido);
                        
                        // Muevo el archivo al directorio donde van a estar todos los PDFs
                        $uploaddir = BASE_DIR . '/data/tps/';
                        $uploadfile = $uploaddir . basename($_FILES['filArchivo']['name']);

                        if($_FILES['filArchivo']['type'] != 'application/pdf')
                        {
                            trigger_error("Me queres hackear la aplicacion web?", E_USER_NOTICE);
                        }

                        if(move_uploaded_file($_FILES['filArchivo']['tmp_name'], $uploadfile))
                        {
                            //echo "El archivo es válido y fue cargado exitosamente.\n";
                        }
                        else
                        {
                            echo "¡Posible ataque de carga de archivos!\n";
                        }
                        break;
                        
                    case UPLOAD_ERR_INI_SIZE:
                        trigger_error("El archivo subido excede la directiva upload_max_filesize en php.ini.", E_USER_ERROR);
                        break;
                    
                    case UPLOAD_ERR_FORM_SIZE:
                        trigger_error("El archivo subido excede la directiva MAX_FILE_SIZE que fue especificada en el formulario HTML.", E_USER_ERROR);
                        break;
                    
                    case UPLOAD_ERR_PARTIAL:
                        trigger_error("El archivo subido fue sólo parcialmente cargado.", E_USER_ERROR);    
                        break;
                    
                    case UPLOAD_ERR_NO_FILE:
                        trigger_error("Ningún archivo fue subido.", E_USER_WARNING);
                        break;
                    
                    case UPLOAD_ERR_NO_TMP_DIR:
                        trigger_error("Falta la carpeta temporal.", E_USER_ERROR);    
                        break;
                    
                    case UPLOAD_ERR_CANT_WRITE:
                        trigger_error("No se pudo escribir el archivo en el disco.", E_USER_ERROR);    
                        break;
                    
                    case UPLOAD_ERR_EXTENSION:
                        trigger_error("Una extensión de PHP detuvo la carga de archivos.", E_USER_ERROR);    
                        break;
                    
                    default:
                        // Por que entro aca?    
                        break;
                }
            }
            
            $this->result = $this->_model['Pedidos']->SelectDisponibilidad();
            if(count($this->result) == 1)
            {
                foreach($this->result[0] as $key => $value)
                {
                    $this->{$key} = $value;
                }
            }
        }
        
        public function delete($id)
        {
            if(!empty($_POST))
            {
                var_dump($_POST);
            }
            
            // indico el template a usar
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
        }

        public function detail($id)
        {
            // indico el template a usar
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
            
            // seteo el modelo para trabajar con los items del pedido
            $pedidosItems = new models\PedidoModel();
            $pedidosItems->_idPedido = $id;
            $this->result = $this->_model['Pedidos']->SelectItem($pedidosItems);
            //var_dump($this->result, $id);
            // este foreach arma las files de la tabla.
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}_table_row.html";
                    $this->table_rows .= file_get_contents($filename);

                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->table_rows = str_replace("{" . $key . "}", htmlentities($value), $this->table_rows);

                            if($key == "Costo")
                            {
                                $this->PrecioTotal += $value;
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
                $this->{$key} = htmlentities($value);
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
                    $filename = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}_estado_select_option.html";
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
            unset($this->result);
            
            $modelFranja = new models\HorarioFranjasModel();
            $modelFranja->_idPedido = $id;
            $this->result = $this->_model['Franjas']->SelectByIdPedido($modelFranja);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}_franja_select_option.html";
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

        public function detail_item($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
            
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
                    $filename = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}_select_option.html";
                    $this->combo_estado_item .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_estado_item = str_replace('{' . $key . '}', $value, $this->combo_estado_item);
                        }
                    }
                }
            }
            unset($this->result);
        }
        
        public function index()
        {
            // indico el template a usar
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
            
            // elaboro el parametro
            $pedido = new models\PedidoModel();
            $pedido->_idUsuario = $_SESSION['IdUsuario'];
            
            // Verifico el rol que tiene en el sistema para saber qe datos traer
            $hasFullRead = false;
            $fullReadRoles = array(
                'Administrador',
                'Preparador',
            );
            foreach($fullReadRoles as $item)
            {
                if($_SESSION['Roles']['Nombre'] == $item)
                {
                    $hasFullRead = true;
                    break;
                }
            }
            
            if(!empty($_POST))
            {
                $pedido->_idEstado = filter_input(INPUT_POST, 'ddlEstado', FILTER_SANITIZE_NUMBER_INT);
                
                if($hasFullRead)
                {
                    $this->result = $this->_model['Pedidos']->SelectByIdEstado($pedido);
                }
                else
                {
                    $this->result = $this->_model['Pedidos']->SelectByIdEstado($pedido);
                }
            }
            else
            {
                // traigo datos de la db.
                if($hasFullRead)
                {
                    //echo 'tiene lectura completa';
                    $this->result = $this->_model['Pedidos']->Select();
                }
                else
                {
                    //echo 'tiene lectura para sus cosas';
                    $this->result = $this->_model['Pedidos']->Select($pedido);
                }
            }
            
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}_table.html";
                    $this->table_content .= file_get_contents($filename);

                    // verifico si trajo 1 o muchos resultados.
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            if($key == 'IdPedido')
                            {
                                $id_pedido = $value;
                            }
                            
                            $this->table_content = str_replace("{" . $key . "}", htmlentities($value), $this->table_content);
                            
                            if($hasFullRead)
                            {
                                // Si tiene todos los permisos, agrego el boton
                                $file_button = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}_table_button_update.html";
                                $button = file_get_contents($file_button);
                                $button = str_replace('{IdPedido}', $id_pedido, $button);
                                
                                $this->table_content = str_replace('{button_update}', $button, $this->table_content);
                            }
                            else
                            {
                                // Si no, lo quito.
                                $this->table_content = str_replace('{button_update}', "", $this->table_content);
                            }
                        }
                    }
                }
            }
            unset($this->result);
            
            // Cargo el combo de estados de items.
            $this->result = $this->_model['Estados']->Select();
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/combo_estado.html";
                    $this->combo_estados .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_estados = str_replace('{' . $key . '}', htmlentities($value), $this->combo_estados);
                        }
                    }
                }
            }
        }

        public function update($id)
        {
            // indico el template a usar
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
            
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
            
            $modelPedido->_idPedido = $id;
            $this->result = $this->_model['Pedidos']->Select($modelPedido);
            //var_dump($this->result);
            foreach($this->result[0] as $key => $value)
            {
                $this->{$key} = $value;
            }
            unset($this->result);

            $modelEstadoPedido->_idPedido = $id;
            $this->result = $this->_model['PedidoEstados']->SelectByIdPedido($modelEstadoPedido);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/detail_estado_select_option.html";
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
            unset($this->result);

            $modelFranja->_idPedido = $id;
            $this->result = $this->_model['Franjas']->SelectByIdPedido($modelFranja);
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/detail_franja_select_option.html";
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
        public function ajax_get_niveles()
        {
            if(!empty($_POST))
            {
                $this->_ajaxRequest = true;
                
                $nivelModel = new models\NivelModel();
                $nivelModel->_idCarrera = filter_input(INPUT_POST, 'idCarrera', FILTER_SANITIZE_NUMBER_INT);
                $this->result = $this->_model['Niveles']->SelectByIdCarrera($nivelModel);
            }
        }
        
        public function ajax_get_materias()
        {
            if(!empty($_POST))
            {
                $this->_ajaxRequest = true;
                
                $materiaModel = new models\MateriaModel();
                $materiaModel->_idNivel = filter_input(INPUT_POST, 'idNivel', FILTER_SANITIZE_NUMBER_INT);
                $this->result = $this->_model['Materias']->SelectByIdNivel($materiaModel);
            }
        }
    }
}

?>