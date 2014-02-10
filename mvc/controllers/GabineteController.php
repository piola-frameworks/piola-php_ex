<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class GabineteController extends core\AController implements core\ICrud
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Gabinete'  =>  new models\GabineteModel(),
                    'Carreras'  =>  new models\CarreraModel(),
                    'Estados'   =>  new models\PedidoItemEstadosModel(),
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
        
        public function create()
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
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

        public function index()
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
            }
            
            // traigo datos de la db.
            $this->result = $this->_model['Carreras']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}_combo_carrera.html";
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
                    $filename = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}_combo_estado.html";
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

        public function update($id)
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/gabinete/{$this->_action}.html";
        }

    }
}

?>