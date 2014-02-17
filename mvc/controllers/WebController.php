<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\views;
    
    final class WebController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_view))
            {
                $this->_view = new views\WebView();
            }
        }
        
        public function __destruct()
        {
            parent::__destruct();
            
            $this->_view->render($this->_template, $this->_dataCollection);
        }
        
        public function help()
        {
            $this->_template = BASE_DIR . "/mvc/templates/web/{$this->_action}.html";
        }
        
        public function contact()
        {
            $this->_template = BASE_DIR . "/mvc/templates/web/{$this->_action}.html";
        }
        
        public function error404($text)
        {
            $this->_template = BASE_DIR . "/mvc/templates/web/{$this->_action}.html";
            
            $this->error_description = $text;
        }
    }
}

?>
