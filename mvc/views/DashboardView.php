<?php

namespace CEIT\mvc\views
{
    use \CEIT\core;
    
    final class DashboardView extends core\AView
    {
        public function __construct()
        {
            parent::__construct();
        }
        
        public function render($template, array $dataCollection)
        {
            if(is_readable($template))
            {
                $var = file_get_contents($template);
                                
                foreach($dataCollection as $key => $value)
                {
                    if(!is_array($value))
                    {
                        $var = str_replace("{" . $key . "}", $value, $var);
                    }
                }
                
                $dataCollection['page_content'] = $var;
            }
            else
            {
                throw new \InvalidArgumentException("No se peude cargar la plantilla: " . $template);
            }
            
            parent::render($template, $dataCollection);
        }
    }
}

?>