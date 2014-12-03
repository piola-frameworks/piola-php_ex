<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class DashboardController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                //$this->_model = new ...
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\DashboardView();
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
            
        }

        public function delete($id)
        {
            
        }

        public function detail($id)
        {
            
        }

        public function index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/dashboard/{$this->_action}.html";
        }

        public function update($id)
        {
            
        }
    }
}

?>