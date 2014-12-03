<?php

namespace piola
{
    interface ICookie
    {
        function status();
        function activate();
        function deactivate();
    }
}

?>