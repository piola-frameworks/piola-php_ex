<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class EstudianteController extends core\AController implements core\ICrud
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
                    'Contenidos'    =>  new models\TipoContenidoModel(),
                    'Carreras'      =>  new models\CarreraModel(),
                    'Niveles'       =>  new models\NivelModel(),
                    'Materias'      =>  new models\MateriaModel(),
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
        
        public function create()
        {
            $this->_template = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}.html";
            
            $reloadFlag = false;
            
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
            
            if(!isset($_COOKIE['TextosAgregados'])) // Si la cookie no esta, creo un cookie esqueleto.
            {
                $tmpArray = array(
                    'AnilladoCompleto'  =>  false,
                    'Comentario'        =>  null,
                    'Items'             =>  array(),
                );
                
                setcookie('TextosAgregados', serialize($tmpArray), time() + 3600);
            }
            else // Si esta la cookie, la leo.
            {
                $tmpArray = unserialize(filter_input(INPUT_COOKIE, 'TextosAgregados'));
                
                if(count($tmpArray['Items']) > 0)
                {
                    foreach($tmpArray['Items'] as $item)
                    {
                        $modelTexto = new models\TextoModel();
                        $modelTexto->_idTexto = $item['IdTexto'];
                        $this->result = $this->_model['Textos']->Select($modelTexto);
                        //var_dump($this->result);
                        if(count($this->result) == 1)
                        {
                            $filename = BASE_DIR . "/mvc/templates/estudiantes/table_text_added_row.html";
                            $this->table_text_added .= file_get_contents($filename);
                            
                            foreach($this->result[0] as $key => $value)
                            {
                                $this->table_text_added = str_replace('{' . $key . '}', htmlentities($value), $this->table_text_added);
                            }
                        }
                        else
                        {
                            $this->table_text_added = "";
                        }
                        unset($this->result);
                    }
                }
                else
                {
                    $this->table_text_added = "";
                }
            }
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                // Creo un array temporal para trabajar.
                $tmpArray = unserialize(filter_input(INPUT_COOKIE, "TextosAgregados"));
                
                // Si se agrego un texto, va a parar a la cookie.
                if(isset($_POST['btnAgregarTexto']))
                {
                    $reloadFlag = true;
                    
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
                    //$_COOKIE['TextosAgregados'] = serialize($tmpArray);
                    setcookie('TextosAgregados', serialize($tmpArray), time() + 3600);
                }
                
                // Si se quita un detalle, lo quito de la cookie.
                if(isset($_POST['btnQuitarDetalle']))
                {
                    $reloadFlag = true;
                    
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
                    //$_COOKIE['TextosAgregados'] = serialize($tmpArray);
                    setcookie('TextosAgregados', serialize($tmpArray), time() + 3600);
                }
                
                // Cargo la tabla de los resultados.
                if(isset($_POST['ddlMateria']))
                {
                    $modelTexto = new models\TextoModel();
                    $modelTexto->_idMateria = filter_input(INPUT_POST, 'ddlMateria', FILTER_SANITIZE_NUMBER_INT);

                    if(isset($_POST['ddlContenido']))
                    {
                        $modelTexto->_idTipoContenido = filter_input(INPUT_POST, 'ddlContenido', FILTER_SANITIZE_NUMBER_INT);
                        $this->result = $this->_model['Textos']->SelectByIdMateriaAndContenido($modelTexto);
                    }
                    else
                    {
                        $this->result = $this->_model['Textos']->SelectByIdMateria($modelTexto);
                    }
                    //var_dump($this->result);

                    if(count($this->result) > 1)
                    {
                        foreach($this->result as $row)
                        {
                            $filename = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}_table_row.html";
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
                    else
                    {
                        $this->table_content = "";
                    }
                    unset($this->result);
                }
                else
                {
                    $this->table_content = "";
                }
            }
            else
            {
                $this->table_content = "";
            }
            
            // Cargo las carreras.
            $this->result = $this->_model['Carreras']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/estudiantes/combo_carrera.html";
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
            else
            {
                $this->combo_carrera = "";
            }
            unset($this->result);
            
            // Cargo el precio del anillado.
            $modelConf = new models\WebModel();
            $modelConf->_clave = "PrecioAnillado";
            $this->result = $this->_model['Web']->Select($modelConf);
            $this->PrecioAnillado = $this->result[0]['Valor'];
            unset($this->result);
            
            // Cargo el precio del Simple Faz.
            $modelConf = new models\WebModel();
            $modelConf->_clave = "PrecioSimpleFaz";
            $this->result = $this->_model['Web']->Select($modelConf);
            $this->PrecioSimpleFaz = $this->result[0]['Valor'];
            unset($this->result);
            
            // Cargo los tipos de contenido
            $this->result = $this->_model['Contenidos']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/estudiantes/combo_contenido.html";
                    $this->combo_contenido .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_contenido = str_replace('{' . $key .'}', htmlentities($value), $this->combo_contenido);
                        }
                    }
                }
            }
            else
            {
                $this->combo_contenido = "";
            }
            unset($this->result);
            
            if($reloadFlag)
            {
                header("Location: index.php?do=/estudiante/create");
            }
        }

        public function create_confirm()
        {
            $this->_template = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                if(isset($_POST['IdTexto']))
                {
                    // Agarro las variables del POST.
                    $postIdTexto = filter_input(INPUT_POST, 'IdTexto', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                    $postSimpleFaz = filter_input(INPUT_POST, 'chkSimpleFaz', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
                    $postAnillado = filter_input(INPUT_POST, 'chkAnillado', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
                    $postAbrochado = filter_input(INPUT_POST, 'chkAbrochado', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
                    //$postCantCopias = filter_input(INPUT_POST, 'txtCantidadCopias', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                    $postTodoAnillado = filter_input(INPUT_POST, 'chkAnilladoCompleto', FILTER_SANITIZE_STRING);
                    //$postComentario = filter_input(INPUT_POST, 'txtComentario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $postSubTotal = filter_input(INPUT_POST, 'txtSubTotal', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);

                    // Verifico que existan
                    if(!empty($postIdTexto))
                    {
                        /*// Y que tengan la misma cantidad
                        if(count($postCantCopias) == count($postIdTexto))
                        {*/
                            for($index = 0; $index < count($postIdTexto); $index++)
                            {
                                $modelTexto = new models\TextoModel();
                                $modelTexto->_idTexto = $postIdTexto[$index];
                                $this->result = $this->_model['Textos']->Select($modelTexto);
                                if(count($this->result) == 1)
                                {
                                    $filename = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}_table_row.html";
                                    $this->table_detail .= file_get_contents($filename);

                                    foreach($this->result[0] as $key => $value)
                                    {
                                        $this->table_detail = str_replace('{' . $key . '}', htmlentities($value), $this->table_detail);
                                    }
                                    $this->table_detail = str_replace('{SimpleFaz}', empty($postSimpleFaz[$postIdTexto[$index]]) ? '' : 'checked', $this->table_detail);
                                    $this->table_detail = str_replace('{Anillado}', empty($postAnillado[$postIdTexto[$index]]) ? '' : 'checked', $this->table_detail);
                                    $this->table_detail = str_replace('{Abrochado}', empty($postAbrochado[$postIdTexto[$index]]) ? '' : 'checked', $this->table_detail);
                                    //$this->table_detail = str_replace('{Cantidad}', $postCantCopias[$postIdTexto[$index]], $this->table_detail);
                                    
                                }
                                
                                //$this->Comentario = empty($postComentario) ? '' : $postComentario;
                                $this->TodoAnillado = empty($postTodoAnillado) ? '' : 'checked';
                                $this->SubTotal = $postSubTotal;
                                
                                unset($this->result);
                            }
                        /*}*/
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
                    $asd = filter_input(INPUT_POST, 'chkAnilladoCompleto', FILTER_SANITIZE_STRING);
                    $modelPedido->_anillado = empty($asd) ? true : false;
                    $modelPedido->_comentario = null; //filter_input(INPUT_POST, 'txtComentario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
                    $abrochado_items = filter_input(INPUT_POST, 'hidAbrochado', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
                    //$canttext_items = filter_input(INPUT_POST, 'hidCantidadText', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                    foreach($items as $item)
                    {
                        $tmpItem = new models\PedidoItemModel();
                        $tmpItem->_idPedido = $this->lastId;
                        $tmpItem->_cantidad = 1; //$canttext_items[$item];
                        $tmpItem->_idTexto = $item;
                        $tmpItem->_anillado = empty($anillado_items[$item]) ? false : true;
                        $tmpItem->_abrochado = empty($abrochado_items[$item]) ? false : true;
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
                    
                    // Redirecciono para ir al inicio de la seccion.
                    header("Location: index.php?do=/estudiante/index");
                }
            }
            
            $this->result = $this->_model['Franjas']->Select();
            $this->result2 = $this->_model['Pedidos']->SelectDisponibilidad();
            //var_dump($this->result2);
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/estudiantes/combo_franja.html";
                    $this->combo_franja .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_franja = str_replace('{' . $key . '}', htmlentities($value), $this->combo_franja);
                        }
                        
                        // Fila extra que no viene en el resultado de la base de datos.
                        if($this->result2[0]['FranjaHoraria'] > $row['IdHorarioFranja'])
                        {
                            $texto = "disabled";
                        }
                        else if($this->result2[0]['FranjaHoraria'] == $row['IdHorarioFranja'])
                        {
                            $texto = "selected";
                        }
                        else
                        {
                            $texto = "";
                        }
                        $this->combo_franja = str_replace('{Seleccionado}', $texto, $this->combo_franja);
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
            $this->_template = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}.html";
            
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
                        $modelTP->_idTipoTexto = 4;
                        $modelTP->_idTipoContenido = 2;
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
            $this->_template = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}.html";
        }

        public function detail($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                if(isset($_POST['btnTerminar']))
                {
                    $pedido = new models\PedidoModel();
                    $pedido->_idPedido = filter_input(INPUT_POST, "txtIdPedido", FILTER_SANITIZE_NUMBER_INT);
                    $this->result = $this->_model['Pedidos']->Select($pedido);

                    /*
                     *  'IdPedido' => int 1
                        'IdUsuario' => int 1
                        'Creado' => string '2014-02-06 16:12:05' (length=19)
                        'CreadoPor' => int 1
                        'Modificado' => string '2014-03-10 12:50:08' (length=19)
                        'ModificadoPor' => int 1
                        'Anillado' => int 0
                        'Comentario' => string 'Comentario' (length=10)
                        'Posicion' => string '' (length=0)
                        'Retiro' => string '2014-02-06' (length=10)
                        'IdFranja' => int 1
                        'Pagado' => int 1
                        'IdEstado' => int 4
                     */

                    $pedido->_idUsuario = $this->result[0]['IdUsuario'];
                    $pedido->_creado = $this->result[0]['Creado'];
                    $pedido->_creadoPor = $this->result[0]['CreadoPor'];
                    $pedido->_modificado = date("Y-m-d H:i:s");
                    $pedido->_modificadoPor = $_SESSION['IdUsuario'];
                    $pedido->_anillado = $this->result[0]['Anillado'];
                    $pedido->_comentario = $this->result[0]['Comentario'];
                    $pedido->_posicion = filter_input(INPUT_POST, "txtPosicion", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $pedido->_retiro = $this->result[0]['Retiro'];
                    $pedido->_idFranja = $this->result[0]['IdFranja'];
                    $pedido->_pagado = $this->result[0]['Pagado'];
                    $pedido->_idEstado = 4;

                    $this->result = $this->_model['Pedidos']->Update(array($pedido));
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
                    $filename = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}_table_row.html";
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
                        $filename = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}_estado_select_option.html";
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
                    $filename = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}_franja_select_option.html";
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
        
        public function detail2($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}.html";
            
            // seteo el modelo para trabajar con los items del pedido
            $pedidosItems = new models\PedidoModel();
            $pedidosItems->_idPedido = $id;
            $this->result = $this->_model['Pedidos']->SelectItem($pedidosItems);
            //var_dump($this->result, $id);
            
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/estudiantes/detail2_table_row.html";
                    $this->table_rows .= file_get_contents($filename);

                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            switch($key)
                            {
                                case 'IdEstadoItem':
                                    $this->table_rows = str_replace("{Impreso}", $value == 3 ? "checked=\"checked\"" : "", $this->table_rows);
                                    break;
                                case 'Anillado':
                                    $this->table_rows = str_replace("{" . $key . "}", $value == 1 ? "checked=\"checked\"" : "", $this->table_rows);
                                    break;
                                case 'Abrochado':
                                    $this->table_rows = str_replace("{" . $key . "}", $value == 1 ? "checked=\"checked\"" : "", $this->table_rows);
                                    break;
                                case 'SimpleFaz':
                                    $this->table_rows = str_replace("{" . $key . "}", $value == 1 ? "checked=\"checked\"" : "", $this->table_rows);
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
                        $filename = BASE_DIR . "/mvc/templates/estudiantes/detail_estado_select_option.html";
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
                    $filename = BASE_DIR . "/mvc/templates/estudiantes/detail_franja_select_option.html";
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
            $this->_template = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}.html";
            
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
                    $filename = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}_select_option.html";
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
        }
        
        public function index()
        {
            // indico el template a usar
            $this->_template = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}.html";
            
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
                $this->result = $this->_model['Pedidos']->SelectByIdEstado($pedido);
            }
            else
            {
                $this->result = $this->_model['Pedidos']->Select($pedido);
            }
            
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}_table.html";
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
                            
                            // BOTON ACTUALIZAR PEDIDO
                            /*if($hasFullRead)
                            {
                                // Si tiene todos los permisos, agrego el boton
                                $file_button = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}_table_button_update.html";
                                $button = file_get_contents($file_button);
                                $button = str_replace('{IdPedido}', $id_pedido, $button);
                                
                                $this->table_content = str_replace('{button_update}', $button, $this->table_content);
                            }
                            else
                            {
                                // Si no, lo quito.
                                $this->table_content = str_replace('{button_update}', "", $this->table_content);
                            }*/
                            
                            // BOTON BORRAR PEDIDO
                            if(in_array($_SESSION['Roles']['Nombre'], array('Administrador')))
                            {
                                $file_button_delete = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}_table_button_delete.html";
                                $button = file_get_contents($file_button_delete);
                                $button = str_replace('{IdPedido}', $id_pedido, $button);
                                
                                $this->table_content = str_replace('{button_delete}', $button, $this->table_content);
                            }
                            elseif(in_array($_SESSION['Roles']['Nombre'], array('Estudiante', 'Docente')) && $row['Estado'] == 'Pendiente')
                            {
                                $file_button_delete = BASE_DIR . "/mvc/templates/estudiantes/{$this->_action}_table_button_delete.html";
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
                    $filename = BASE_DIR . "/mvc/templates/estudiantes/combo_estado.html";
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
            else
            {
                $this->combo_estados = "";
            }
        }

        public function update($id)
        {
            // Un estudiante o docente no puede modificar el pedido realizado.
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