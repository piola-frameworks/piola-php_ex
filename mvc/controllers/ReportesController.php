<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class ReportesController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\ReportesView();
            }
        }
        
        public function __destruct()
        {
            parent::__destruct();
            
            unset($this->result);
            $this->_view->render($this->_template, $this->_dataCollection);
        }
        
        public function index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/reportes/{$this->_action}.xhtml";
        }
    }
}

?>