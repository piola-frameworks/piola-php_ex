<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class TextosController extends core\AController implements core\ICrud
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Textos'    =>  new models\TextoModel(),
                    'Carreras'  =>  new models\CarreraModel(),
                    'Materias'  =>  new models\MateriaModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\TextosView();
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
            $this->_template = BASE_DIR . "/mvc/templates/textos/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
            }
            
            
        }

        public function delete($id)
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/textos/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
            }
        }

        public function detail($id)
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/textos/{$this->_action}.html";
        }

        public function index()
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/textos/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
            }
            
            $this->result = $this->_model['Textos']->Select();
            foreach($this->result as $row)
            {
                $filename = BASE_DIR . "/mvc/templates/textos/{$this->_action}_table_row.html";
                $this->table_content .= file_get_contents($filename);
                
                if(is_array($row))
                {
                    foreach($row as $key => $value)
                    {
                        if(!is_array($value))
                        {
                            $this->table_content = str_replace('{' . $key . '}', $value, $this->table_content);
                        }
                    }
                }
            }
            unset($this->result);
            
            $this->result = $this->_model['Carreras']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/textos/{$this->_action}_combo_carrera.html";
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
            
            $this->result = $this->_model['Materias']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/textos/{$this->_action}_combo_materia.html";
                    $this->combo_materias .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_materias = str_replace('{' . $key . '}', $value, $this->combo_materias);
                        }
                    }
                }
            }
        }

        public function update($id)
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/textos/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
            }
        }
    }
}

?>