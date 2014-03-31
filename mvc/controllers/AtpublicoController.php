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
                    'Usuarios'      =>  new models\UsuarioModel(),
                    'Textos'        =>  new models\TextoModel(),
                    'Pedidos'       =>  new models\PedidoModel(),
                    'PedidoItems'   =>  new models\PedidoItemModel(),
                    'PedidoEstados' =>  new models\PedidoEstadosModel(),
                    'Franjas'       =>  new models\HorarioFranjasModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\AtPublicoView();
            }
        }
        
        public function __destruct()
        {
            if($this->ajaxRequest)
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
        
        public function index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                $pedido = new models\PedidoModel();
                $pedido->_idPedidoLegajo = filter_input(INPUT_POST, 'txtLegajoIdPedido', FILTER_SANITIZE_SPECIAL_CHARS);
                
                $this->result = $this->_model['Pedidos']->SelectForDeliverByIdPedidoOrLegajo($pedido);
            }
            else
            {
                $this->result = $this->_model['Pedidos']->SelectForDeliver();
            }
            
            
            if(count($this->result) > 0)
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
            else
            {
                $this->table_content = "";
            }
            unset($this->result);
        }
        
        public function create()
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            $this->IdUsuario = "";
            $this->Legajo = "";
            $this->DNI = "";
            $this->Nombre = "";
            $this->Apellido = "";
            $this->Telefono = "";
            $this->Celular = "";
            $this->Creado = date("Y-m-d H:i:s");
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                if(isset($_POST['btnFiltrar']))
                {
                    $dni_legajo = filter_input(INPUT_POST, 'txtDNILegajo', FILTER_SANITIZE_NUMBER_INT);
                    
                    // Panel Usuario
                    $modelUsuario = new models\UsuarioModel();
                    $modelUsuario->_legajoDNI = $dni_legajo;
                    $this->result = $this->_model['Usuarios']->SelectByLegajoOrDNI($modelUsuario);
                    //var_dump($this->result);
                    if(count($this->result) == 1)
                    {
                        foreach($this->result[0] as $key => $value)
                        {
                            $this->{$key} = $value;
                        }
                    }
                }
            }
            
            // Combo de franjas horarias
            $this->result = $this->_model['Franjas']->Select();
            //var_dump($this->result);
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/at_publico/combo_franja.html";
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
        
        public function print_create()
        {
            $this->_template = BASE_DIR . "/mvc/templates/at_publico/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                $idUsuario = filter_input(INPUT_POST, 'hidIdUsuario', FILTER_SANITIZE_NUMBER_INT);
                $this->Creado = $creado = filter_input(INPUT_POST, 'txtCreado', FILTER_SANITIZE_SPECIAL_CHARS);
                $this->Retiro = $retiro = filter_input(INPUT_POST, 'txtRetiro', FILTER_SANITIZE_SPECIAL_CHARS);
                $idFranja = filter_input(INPUT_POST, 'ddlFranja', FILTER_SANITIZE_NUMBER_INT);
                $this->Descripcion = $descripcion = filter_input(INPUT_POST, 'txtDescripcionItem', FILTER_SANITIZE_SPECIAL_CHARS);
                $this->CantidadHojas = $hojas = filter_input(INPUT_POST, 'txtHojas', FILTER_SANITIZE_NUMBER_INT);
                $anillado = filter_input(INPUT_POST, 'chkAnillado', FILTER_SANITIZE_SPECIAL_CHARS);
                $this->Anillado = !empty($anillado) ? "Si" : "No";
                $abrochado = filter_input(INPUT_POST, 'chkAbrochado', FILTER_SANITIZE_SPECIAL_CHARS);
                $this->Abrochado = !empty($abrochado) ? "Si" : "No";
                $simplefaz = filter_input(INPUT_POST, 'chkSimpleFaz', FILTER_SANITIZE_SPECIAL_CHARS);
                $this->SimpleFaz = !empty($simplefaz) ? "Si" : "No";
                
                $modelUsuario = new models\UsuarioModel();
                $modelUsuario->_idUsuario = $idUsuario;
                $this->result = $this->_model['Usuarios']->Select($modelUsuario);
                //var_dump($this->result);
                if(count($this->result) == 1)
                {
                    foreach($this->result[0] as $key => $value)
                    {
                        $this->{$key} = $value;
                    }
                }
                
                $modelFranjas = new models\HorarioFranjasModel();
                $modelFranjas->_idFranja = $idFranja;
                $this->result = $this->_model['Franjas']->Select($modelFranjas);
                //var_dump($this->result);
                if(count($this->result) == 1)
                {
                    $this->Franja = $this->result[0]['Desde'] . " hs.";
                }
                
                if(isset($_POST['btnGuardar']))
                {
                    if(!empty($idUsuario) && !empty($creado) && !empty($retiro) && !empty($idFranja) && !empty($descripcion) && !empty($hojas))
                    {
                        // Guardo en la db.
                        $modelTextoEspecial = new models\TextoModel();
                        $modelTextoEspecial->_creadoPor = $_SESSION['IdUsuario'];
                        $modelTextoEspecial->_creadoDia = $creado;
                        $modelTextoEspecial->_modificadoPor = null;
                        $modelTextoEspecial->_modificadoDia = null;
                        $modelTextoEspecial->_codInterno = null;
                        $modelTextoEspecial->_idMateria = null;
                        $modelTextoEspecial->_idTipoTexto = 1;
                        $modelTextoEspecial->_idTipoContenido = null;
                        $modelTextoEspecial->_nombre = $descripcion;
                        $modelTextoEspecial->_autor = null;
                        $modelTextoEspecial->_docente = null;
                        $modelTextoEspecial->_cantPaginas = $hojas;
                        $modelTextoEspecial->_activo = 0;
                        $this->_lastIdTexto = $this->_model['Textos']->Insert(array($modelTextoEspecial));                        
                        
                        $modelPedido = new models\PedidoModel();
                        $modelPedido->_idUsuario = $idUsuario;
                        $modelPedido->_creadoPor = $_SESSION['IdUsuario'];
                        $modelPedido->_creadoDia = date("Y-m-d H:i:s");
                        $modelPedido->_modificadoPor = null;
                        $modelPedido->_modificadoDia = null;
                        $modelPedido->_anillado = false;
                        $modelPedido->_comentario = null;
                        $modelPedido->_retiro = $retiro;
                        $modelPedido->_idFranja = $idFranja;
                        $modelPedido->_pagado = false;
                        $modelPedido->_idEstado = 1;
                        $this->_lastIdPedido = $this->_model['Pedidos']->Insert(array($modelPedido));
                        
                        $modelPedidoItem = new models\PedidoItemModel();
                        $modelPedidoItem->_idPedido = $this->_lastIdPedido;
                        $modelPedidoItem->_cantidad = 1;
                        $modelPedidoItem->_idTexto = $this->_lastIdTexto;
                        $modelPedidoItem->_anillado = !empty($anillado) ? true : false;
                        $modelPedidoItem->_abrochado = !empty($abrochado) ? true : false;
                        $modelPedidoItem->_simpleFaz = !empty($simplefaz) ? true : false;
                        $modelPedidoItem->_idEstado = 1;
                        $this->_model['PedidoItems']->Insert(array($modelPedidoItem));
                    }
                    else
                    {
                        // falta completar campos obligatorios
                    }
                }
            }
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
        
        // AJAX actions        
        public function ajax_detail_item_change_status()
        {
            if(!empty($_POST))
            {
                $this->ajaxRequest = true;
                
                $modelPedido = new models\PedidoModel();
                $modelPedido->_idPedido = filter_input(INPUT_POST, "idPedido", FILTER_SANITIZE_NUMBER_INT);
                $modelPedido->_idEstado = filter_input(INPUT_POST, "toValue", FILTER_SANITIZE_STRING) == "true" ? 5 : 4;
                
                $this->result = $this->_model['Pedidos']->UpdateEstado(array($modelPedido));
            }
        }
    }
}

?>