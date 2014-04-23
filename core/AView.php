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
            /*$filename = BASE_DIR . "/mvc/templates/nav_menu.html";
            $dataCollection['nav_links'] = file_get_contents($filename);*/
            
            $navArray = array(
                'Estudiante'        =>  '<li><a href="index.php?do=/estudiante/index">Mis pedidos</a></li>',
                'Preparador'        =>  '<li><a href="index.php?do=/preparador/index">Pedidos</a></li>',
                'Textos'            =>  '<li><a href="index.php?do=/textos/index">Textos</a></li>',
                'Gabinete'          =>  '<li class="dropdown">'
                                        . '<a class="dropdown-toggle" href="#" data-toggle="dropdown">Gabinete <b class="caret"></b></a>'
                                            . '<ul class="dropdown-menu">'
                                                . '<li><a href="index.php?do=/gabinete/pedidos_index">'
                                                    . '<span class="glyphicon glyphicon-inbox"></span> Pedidos'
                                                . '</a></li>'
                                                . '<li><a href="index.php?do=/gabinete/caja_index">'
                                                    . '<span class="glyphicon glyphicon-credit-card"></span> Caja'
                                                . '</a></li>'
                                            . '</ul>'
                                        . '</li>',
                'AtPublico'         =>  '<li class="dropdown">'
                                        . '<a class="dropdown-toggle" href="#" data-toggle="dropdown">At. Publico <b class="caret"></b></a>'
                                            . '<ul class="dropdown-menu">'
                                                . '<li><a href="index.php?do=/atpublico/index">'
                                                    . '<span class="glyphicon glyphicon-file"></span> A retirar'
                                                . '</a></li>'
                                                . '<li><a href="index.php?do=/atpublico/especiales_index">'
                                                    . '<span class="glyphicon glyphicon-star"></span> Especiales'
                                                . '</a></li>'
                                            . '</ul>'
                                        . '</li>',
                'Caja'              =>  '<li><a href="index.php?do=/caja/index">Caja</a></li>',
                'Reportes'          =>  '<li class="dropdown">'
                                        . '<a class="dropdown-toggle" href="index.php?do=/reportes/index" data-toggle="dropdown">Reportes <b class="caret"></b></a>'
                                            . '<ul class="dropdown-menu">'
                                                . '<li><a href="index.php?do=/reportes/fotocopias">Fotocopias</a></li>'
                                                . '<li><a href="index.php?do=/reportes/pedidos">Pedidos</a></li>'
                                                . '<li><a href="index.php?do=/reportes/caja">Caja</a></li>'
                                                . '<li><a href="index.php?do=/reportes/facturacion">Facturacion</a></li>'
                                                . '<li><a href="index.php?do=/reportes/usuarios">Usuarios</a></li>'
                                                . '<li><a href="index.php?do=/reportes/textos">Textos</a></li>'
                                                . '<li><a href="index.php?do=/reportes/carreras">Carreras</a></li>'
                                            . '</ul>'
                                        . '</li>',
                'Administracion'    =>  '<li><a href="index.php?do=/admin/index">Administraci&oacute;n</a></li>'
            );
            
            if(isset($_SESSION['Roles']))
            {
                foreach($_SESSION['Roles'] as $role)
                {
                    switch($role)
                    {
                        case "Preparador":
                            $dataCollection['nav_links'] = $navArray['Preparador'] . "\n";
                            break;
                        case "Administrador":
                            $dataCollection['nav_links'] = $navArray['Estudiante'] . "\n" . 
                                                            $navArray['Preparador'] . "\n" . 
                                                            $navArray['Textos'] . "\n" . 
                                                            $navArray['Gabinete'] . "\n" . 
                                                            $navArray['AtPublico'] . "\n" . 
                                                            $navArray['Caja'] . "\n" .
                                                            $navArray['Reportes'] . "\n" .
                                                            $navArray['Administracion'] . "\n";
                            break;
                        case "Encargado de catalogo":
                            $dataCollection['nav_links'] = $navArray['Textos'] . "\n";
                            break;
                        case "Gabinete":
                            $dataCollection['nav_links'] = $navArray['Gabinete'] . "\n";
                            break;
                        case "Cajero":
                            $dataCollection['nav_links'] = $navArray['Caja'] . "\n";
                            break;
                        case "Atencion al publico":
                            $dataCollection['nav_links'] = $navArray['AtPublico'] . "\n";
                            break;
                        case "Docente":
                            $dataCollection['nav_links'] = $navArray['Estudiante'] . "\n" .
                                                            $navArray['Textos'] . "\n";
                            break;
                        case "Estudiante":
                            $dataCollection['nav_links'] = $navArray['Estudiante'] . "\n";
                            break;
                        
                    }
                }    
            }
            else
            {
                $dataCollection['nav_links'] = "";
            }
            
            // hago la parte del logeo del usuario
            if(!empty($_SESSION['Usuario']))
            {
                $dataCollection['Usuario'] = $_SESSION['Usuario'];
            }
            else
            {
                $dataCollection['Usuario'] = "Visitante";
            }
            
            if(!empty($_SESSION['IdUsuario']))
            {
                $dataCollection['IdUsuario'] = $_SESSION['IdUsuario'];
            }
            else
            {
                $dataCollection['IdUsuario'] = null;
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
        
        public function renderRaw($rawData = null)
        {
            echo $rawData;
        }
        
        public function redirect($location)
        {
            header("Location: " . $location);
        }
        
        public function json($object)
        {
            if(is_array($object))
            {
                echo self::customJsonEncode($object);
            }
            else
            {
                trigger_error("Solo funciona con arrays, por el momento...", E_USER_NOTICE);
            }
            
            /*if(!is_resource($object))
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
            }*/
        }
        
        private static function customJsonEncode(array $data)
        {
            /*if(function_exists('json_encode'))
            {
                return json_encode($data); //Lastest versions of PHP already has this functionality. 
            }*/
            
            $parts = array();
            $is_list = false;

            //Find out if the given array is a numerical array 
            $keys = array_keys($data);
            $max_length = count($data) - 1;
            if(($keys[0] == 0) && ($keys[$max_length] == $max_length)) //See if the first key is 0 and last key is length - 1
            {
                $is_list = true; 
                for($index = 0; $index < count($keys); $index++) //See if each key correspondes to its position 
                {
                    if($index != $keys[$index]) //A key fails at position check. 
                    {
                        $is_list = false; //It is an associative array. 
                        break; 
                    }
                } 
            } 

            foreach($data as $key => $value)
            { 
                if(is_array($value))
                { //Custom handling for arrays 
                    if($is_list)
                    {
                        $parts[] = self::customJsonEncode($value); /* :RECURSION: */ 
                    }
                    else
                    {
                        $parts[] = '"' . $key . '":' . self::customJsonEncode($value); /* :RECURSION: */ 
                    }
                }
                else
                { 
                    $str = ''; 
                    if(!$is_list)
                    {
                        $str = '"' . $key . '":'; 
                    }

                    //Custom handling for multiple data types 
                    if(is_numeric($value))
                    {
                        $str .= $value; //Numbers 
                    }
                    else if($value === false)
                    {
                        $str .= 'false'; //The booleans 
                    }
                    else if($value === true)
                    {
                        $str .= 'true'; 
                    }
                    /*else if(is_string($value))
                    {
                        $str .= '"' . addslashes(htmlentities($value)) . '"'; //The strings
                    }*/
                    else
                    {
                        $str .= '"' . addslashes($value) . '"'; //All other things 
                    }
                    // :TODO: Is there any more datatype we should be in the lookout for? (Object?) 

                    $parts[] = $str; 
                } 
            } 
            
            $json = implode(',', $parts); 

            if($is_list)
            {
                return '[' . $json . ']';//Return numerical JSON 
            }
            
            return '{' . $json . '}';//Return associative JSON 
        }
    }
}

?>