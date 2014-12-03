<?php

namespace CEIT\libs
{
    class CHTMLHelper
    {
        public static function createDropDownList($name, array $data = null, $id = "", $class = "", $selectedIds = "")
        {
            if(count($data) >=  1)
            {
                $_return = "<select" . empty($id) ?: " id=\"" . $id . "\"" . empty($class) ?: " class=\"" . $class . "\"" . " name=" . $name . "\">" . PHP_EOL;
                
                foreach($data as $row)
                {
                    if(is_array($row))
                    {
                        $optKey = "";
                        $optValue = "";
                        
                        foreach($row as $key => $value)
                        {
                            if(true)
                            {
                                
                            }
                        }
                        
                        $_return .= "<option value=\"" . $value . "\"" . !empty($selectedIds) && $selectedIds == $value ? " selected=\"selected\"" : "" . ">" . $key . "</option>" . PHP_EOL;
                    }
                    else
                    {
                        trigger_error("Los datos deben ser arreglos anidados.", E_USER_ERROR);
                    }
                }
                
                $_return .= "</select>" . PHP_EOL;
                
                return $_return;
            }
            else
            {
                trigger_error("Los datos deben tener, por lo menos, un arreglo anindado.", E_USER_ERROR);
            }
        }
        
        public static function createTable($id = "", $class = "", array $headers = null, array $data)
        {

        }
        
        public static function createGridView()
        {
            
        }
        
        
    }
}


