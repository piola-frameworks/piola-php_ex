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
                    'Pedidos'   =>  new models\PedidoModel(),
                    'Textos'    =>  new models\TextoModel(),
                    'Carreras'  =>  new models\CarreraModel(),
                    'Niveles'   =>  new models\NivelModel(),
                    'Materias'  =>  new models\MateriaModel(),
                    'Estados'   =>  new models\PedidoItemEstadosModel(),
                    'Franjas'   =>  new models\HorarioFranjasModel(),
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
                var_dump($_POST);
                
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
                    var_dump($_POST);
                    
                    // Agrego el pedido.
                    $modelPedido = new models\PedidoModel();
                    $modelPedido->_idUsuario = $_SESSION['idUsuario'];
                    $modelPedido->_creado = date("Y-m-d H:i:s");
                    $modelPedido->_creadoPor = $_SESSION['idUsuario'];
                    $modelPedido->_anillado = filter_input(INPUT_POST, 'hidAnillado', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
                    $this->result = $this->_model['Pedidos']->Insert($modelPedido);
                    
                    // Agrego los items del pedido.
                    
                    // Quito la seleccion de la pagina previa.
                    //unset($_COOKIE['TextosAgregados']);
                    //setcookie('TextosAgregados', null, -1);
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
            
            if(!empty($_POST))
            {
                var_dump($_POST);
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
            $pedidosItems->idPedido = $id;
            $this->result = $this->_model['Pedidos']->SelectItem($pedidosItems);
            
            // este foreach arma las files de la tabla.
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
            
            // elaboro el parametro y traigo los datos
            $pedido = new models\PedidoModel();
            $pedido->idPedido = $_SESSION['IdUsuario'];
            $this->result = $this->_model['Pedidos']->Select($pedido);
            
            foreach($this->result[0] as $key => $value)
            {
                $this->{$key} = htmlentities($value);
            }
        }

        public function index()
        {
            if(!empty($_POST))
            {
                var_dump($_POST);
            }
            
            // indico el template a usar
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
            
            // elaboro el parametro
            $pedido = new models\PedidoModel();
            $pedido->_idUsuario = $_SESSION['IdUsuario'];
            
            // traigo datos de la db.
            $this->result = $this->_model['Pedidos']->Select($pedido);
            foreach($this->result as $row)
            {
                $filename = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}_table.html";
                $this->table_content .= file_get_contents($filename);
                
                // verifico si trajo 1 o muchos resultados.
                if(is_array($row))
                {
                    foreach($row as $key => $value)
                    {
                        $this->table_content = str_replace("{" . $key . "}", htmlentities($value), $this->table_content);
                    }
                }
            }
            unset($this->result);
            
            // Cargo el combo de carreras.
            $this->result = $this->_model['Carreras']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/combo_carrera.html";
                    $this->combo_carreras .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_carreras = str_replace('{' . $key . '}', htmlentities($value), $this->combo_carreras);
                        }
                    }
                }
            }
            unset($this->result);
            
            // Cargo el combo de estados de items.
            $this->result = $this->_model['Estados']->Select();
            if(count($this->result) > 1)
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
            if(!empty($_POST))
            {
                var_dump($_POST);
            }
            
            // indico el template a usar
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
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