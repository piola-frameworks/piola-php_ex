<?php

namespace CEIT\core
{
    interface IView
    {
        public function render($template = null, array $dataCollection);
        public function redirect($location);
    }
}

?>