<?php

namespace piola
{
    class Html
    {
        public static function ContainerElement($elem, array $attr = array(), array $innElem = array())
        {
            $atributes = new StringBuilder();
            foreach ($attr as $key => $value)
            {
                $atributes->Append(" $key=\'$value\'");
            }
            
            $innerElements = new StringBuilder();
            foreach ($innElem as $value)
            {
                $innerElements->AppendLine($value);
            }
            
            $domElement = new StringBuilder();
            $domElement->AppendLine("<$elem $atributes>");
            $domElement->AppendLine($innerElements);
            $domElement->AppendLine("</$elem>");
            
            return $domElement;
        }
        
        public static function InlineElement($elem, array $attr = array())
        {
            $atributes = new StringBuilder();
            foreach ($attr as $key => $value)
            {
                $atributes->Append(" $key=\'$value\'");
            }
            
            $domElement = new StringBuilder();
            $domElement->AppendLine("<$elem $atributes />");
            
            return $domElement;
        }
    }
}

?>