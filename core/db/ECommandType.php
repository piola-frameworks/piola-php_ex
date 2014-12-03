<?php

namespace piola\db
{
    class ECommandType extends \SplEnum
    {
        const _default = self::Text;
        
        /**
         * Nombre del procedimiento almacenado.
         */
        const StoredProcedure = 1;
        
        /**
         * Nombre de una tabla.
         */
        const TableDirect = 2;
        
        /**
         * Comando de texto SQL. Predeterminado.  
         */
        const Text = 3;
    }
}

?>
