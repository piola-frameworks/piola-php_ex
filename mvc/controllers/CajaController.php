<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class CajaController extends core\AController implements core\ICrud
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Pedidos'  =>  new models\PedidoModel(),
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
            
            $this->_view->render($this->_template, $this->_dataCollection);
        }
        
        public function create()
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/caja/{$this->_action}.html";
        }

        public function delete($id)
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/caja/{$this->_action}.html";
        }

        public function detail($id)
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/caja/{$this->_action}.html";
        }

        public function index()
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/caja/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST, $_COOKIE);
                
                $tmpArray = array();
                
                if(isset($_POST['btnAgregarPedido']))
                {
                    $cookie = filter_input(INPUT_COOKIE, 'PedidosAgregados');
                    if(!empty($cookie))
                    {
                        $tmpArray[] = unserialize($cookie);
                    }
                    
                    $pedidoItem = filter_input(INPUT_POST, 'btnAgregarPedido', FILTER_SANITIZE_NUMBER_INT);
                    
                    if(!empty($pedidoItem))
                    {
                        if(array_search($pedidoItem, $tmpArray) === false)
                        {
                            $tmpArray[] = (int)$pedidoItem;
                        }
                    }
                    
                    $_COOKIE['PedidosAgregados'] = serialize($tmpArray);
                    setcookie('PedidosAgregados', serialize($pedidoItem), time() + 3600);
                }
            }
            
            if(!empty($_COOKIE))
            {
                $cookie = filter_input(INPUT_COOKIE, 'PedidosAgregados');
                if(!empty($cookie))
                {
                    $tmpArray = unserialize($cookie);
                    
                    if(!is_array($tmpArray))
                    {
                        $tmpArray = array($tmpArray);
                    }
                    
                    foreach($tmpArray as $item)
                    {
                        $model = new models\PedidoModel();
                        $model->_idPedido = $item;
                        $this->result = $this->_model['Pedidos']->SelectItemCaja($model);
                        if(count($this->result) == 1)
                        {
                            $filename = BASE_DIR . "/mvc/templates/caja/table_1_content.html";
                            $this->table_1_content .= file_get_contents($filename);

                            foreach($this->result[0] as $key => $value)
                            {
                                $this->table_1_content = str_replace('{' . $key . '}', $value, $this->table_1_content);
                            }
                        }
                    }
                }
            }
            
            $this->result = $this->_model['Pedidos']->SelectFinished();
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
        }

        public function update($id)
        {
            $this->_template = $this->_template = BASE_DIR . "/mvc/templates/caja/{$this->_action}.html";
        }

    }
}

?>