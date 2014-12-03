<?php

namespace piola\db
{
    class EConnectionState extends \SplEnum 
    {
        const __default = self::Closed;
    
        /**
         * Se ha perdido la conexión con el origen de datos. Esto sólo puede
         * ocurrir tras abrir la conexión. Una conexión en este estado se puede
         * cerrar y volver a abrir. Este valor se reserva para versiones futuras
         * del producto.
         */
        const Broken = 1;
        
        /**
         * La conexión está cerrada. 
         */
        const Closed = 2;
        
        /**
         * El objeto de conexión está conectando con el origen de datos. Este
         * valor se reserva para versiones futuras del producto. 
         */
        const Connecting = 4;
        
        /**
         * El objeto de conexión está ejecutando un comando. Este valor se
         * reserva para versiones futuras del producto.
         */
        const Executing = 8;
        
        /**
         * El objeto de conexión está recuperando datos. Este valor se reserva
         * para versiones futuras del producto.
         */
        const Fetching = 16;
        
        /**
         * La conexión está abierta.
         */
        const Open = 32;
    }
}

?>