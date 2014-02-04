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
            if(!empty($_POST))
            {
                var_dump($_POST);
            }
            
            // indico el template a usar
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
            
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
                            $this->combo_carrera = str_replace('{' . $key .'}', $value, $this->combo_carrera);
                        }
                    }
                }
            }
            unset($this->result);
            
            $this->result = $this->_model['Niveles']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/combo_nivel.html";
                    $this->combo_nivel .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_nivel = str_replace('{' . $key . '}', $value, $this->combo_nivel);
                        }
                    }
                }
            }
            unset($this->result);
            
            $this->result = $this->_model['Materias']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/pedidos/combo_materia.html";
                    $this->combo_materia .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_materia = str_replace('{' . $key . '}', $value, $this->combo_materia);
                        }
                    }
                    
                }
            }
            unset($this->result);
            
            $this->result = $this->_model['Textos']->Select();
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
                            $this->table_content = str_replace('{' . $key . '}', $value, $this->table_content);
                        }
                    }
                }
            }
            unset($this->result);
        }

        public function create_tp()
        {
            // indico el template a usar
            $this->_template = BASE_DIR . "/mvc/templates/pedidos/{$this->_action}.html";
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
                $htmlRow = file_get_contents($filename);
                
                if(is_array($row))
                {
                    foreach($row as $innerKey => $innerValue)
                    {
                        $htmlRow = str_replace("{" . $innerKey . "}", $innerValue, $htmlRow);
                        
                        if($innerKey == "Costo")
                        {
                            $this->PrecioTotal += $innerValue;
                        }
                    }
                }
                
                $this->table_rows .= $htmlRow;
            }
            
            // elaboro el parametro y traigo los datos
            $pedido = new models\PedidoModel();
            $pedido->idPedido = $_SESSION['IdUsuario'];
            $this->result = $this->_model['Pedidos']->Select($pedido);
            
            foreach($this->result[0] as $key => $value)
            {
                $this->{$key} = $value;
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
                    foreach($row as $innerKey => $innerValue)
                    {
                        $this->table_content = str_replace("{" . $innerKey . "}", $innerValue, $this->table_content);
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
                            $this->combo_carreras = str_replace('{' . $key . '}', $value, $this->combo_carreras);
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
                            $this->combo_estados = str_replace('{' . $key . '}', $value, $this->combo_estados);
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
    }
}

?>