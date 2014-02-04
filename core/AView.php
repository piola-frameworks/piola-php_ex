<?php

namespace CEIT\core
{
    abstract class AView implements IView
    {
        public function __construct()
        {
            ;
        }
        
        public function render($template = null, array $dataCollection)
        {            
            // construyo la barra de navegacion
            $filename = BASE_DIR . "/mvc/templates/nav_menu.html";
            $dataCollection['nav_links'] = file_get_contents($filename);
            
            // hago la parte del logeo del usuario
            if(!empty($_SESSION['Usuario']))
            {
                $dataCollection['Usuario'] = $_SESSION['Usuario'];
            }
            
            if(!empty($_SESSION['IdUsuario']))
            {
                $dataCollection['IdUsuario'] = $_SESSION['IdUsuario'];
            }
            
            $filename = BASE_DIR . "/mvc/templates/index.html";
            if(is_readable($filename))
            {
                $return = file_get_contents($filename);
                
                foreach($dataCollection as $key => $value)
                {
                    if(!is_array($value))
                    {
                        $return = str_replace('{' . $key . '}', $value, $return);
                    }
                }
                
                echo $return;
            }
            else
            {
                throw new \InvalidArgumentException("No se puede cargar la plantilla principal.");
            }
        }
        
        public function redirect($location)
        {
            header("Location: " . $location);
        }
        
        public function json($object)
        {
            var_dump($object);
            
            if(!is_resource($object))
            {
                if($asd = json_encode($object, JSON_PRETTY_PRINT))
                {
                   echo $asd; 
                }
                else
                {
                    switch(json_last_error())
                    {
                        case JSON_ERROR_NONE:
                            trigger_error("No ocurrió ningún error", E_USER_ERROR);
                            break;
                        case JSON_ERROR_DEPTH:
                            trigger_error("Se ha excedido la profundidad máxima de la pila", E_USER_ERROR);
                            break;
                        case JSON_ERROR_STATE_MISMATCH:
                            trigger_error("JSON con formato incorrecto o inválido", E_USER_ERROR);
                            break;
                        case JSON_ERROR_CTRL_CHAR:
                            trigger_error("Error del carácter de control, posiblemente se ha codificado de forma incorrecta", E_USER_ERROR);
                            break;
                        case JSON_ERROR_SYNTAX:
                            trigger_error("Error de sintaxis", E_USER_ERROR);
                            break;
                        case JSON_ERROR_UTF8:
                            trigger_error("Caracteres UTF-8 mal formados, posiblemente codificados de forma incorrecta", E_USER_ERROR);
                            break;
                        case JSON_ERROR_RECURSION:
                            trigger_error("Una o más referencias recursivas en el valor a codificar", E_USER_ERROR);
                            break;
                        case JSON_ERROR_INF_OR_NAN:
                            trigger_error("Uno o más valores NAN o INF en el valor a codificar", E_USER_ERROR);
                            break;
                        case JSON_ERROR_UNSUPPORTED_TYPE:
                            trigger_error("Se proporcionó un valor de un tipo que no se puede codificar", E_USER_ERROR);
                            break;
                        default:
                            break;
                    }
                }
            }
            else
            {
                trigger_error("El objecto a JSONificar no debe ser un recurso de PHP", E_USER_ERROR);
            }
        }
    }
}

?>