<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class AtpublicoController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Pedidos'  =>  new models\PedidoModel(),
                    'PedidoEstados'    =>  new models\PedidoEstadosModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\AtPublicoView();
            }
        }
        
        public function __destruct()
        {
            unset($this->result);
            
            $this->_view->render($this->_template, $this->_dataCollection);
            
            parent::__destruct();
        }
        
        public function index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                $pedido = new models\PedidoModel();
                $pedido->_id = filter_input(INPUT_POST, 'txtLegajoIdPedido', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
                $this->result = $this->_model['Pedidos']->SelectForDeliver($pedido);
            }
            else
            {
                $this->result = $this->_model['Pedidos']->SelectForDeliver();
            }
            
            
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}_table_content.html";
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
        
        public function update($id)
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            $pedido = new models\PedidoModel();
            $pedido->_idPedido = $id;
            
            $this->result = $this->_model['PedidoEstados']->SelectByIdPedido($pedido);
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/at_publico/combo_estado.html";
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
            
            if(!empty($_POST))
            {
                $this->result1 = $this->_model['Pedidos']->Select($pedido);
                $pedido->_idUsuario = $this->result1[0]['IdUsuario'];
                $pedido->_creado = $this->result1[0]['Creado'];
                $pedido->_creadoPor = $this->result1[0]['CreadoPor'];
                $pedido->_modificado = date("Y-m-d H:i:s");
                $pedido->_modificadoPor = $_SESSION['IdUsuario'];
                $pedido->_anillado = $this->result1[0]['Anillado'];
                $pedido->_comentario = $this->result1[0]['Comentario'];
                $pedido->_posicion = $this->result1[0]['Posicion'];
                $pedido->_retiro = $this->result1[0]['Retiro'];
                $pedido->_idFranja = $this->result1[0]['IdFranja'];
                $pedido->_pagado = $this->result1[0]['Pagado'];
                $pedido->_idEstado = filter_input(INPUT_POST, 'ddlEstado', FILTER_SANITIZE_NUMBER_INT);
                
                $this->_model['Pedidos']->Update(array($pedido));
                unset($this->result1);
            }
        }
    }
}

?>