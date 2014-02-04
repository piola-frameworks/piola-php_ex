<?php

namespace CEIT\mvc\views
{
    use CEIT\core;
    
    final class WebView extends core\AView
    {
        public function __construct()
        {
            parent::__construct();
        }
        
        public function render($template = null, array $dataCollection)
        {
            parent::render($template, $dataCollection);
        }
        
        public function redirect($location)
        {
            parent::redirect($location);
        }
    }
}

?>