<?php

namespace piola\db
{
    interface IDatabaseConnection
    {
        /**
         * @return string Obtiene la cadena que se utiliza para abrir una base de datos.
         */
        public function getConnectionString();
        
        /**
         * 
         * @param string $connectionString Establece la cadena que se utiliza para abrir una base de datos.
         */
        public function setConnectionString($connectionString);
        
        /**
         * @return int Obtiene el tiempo de espera para intentar establecer una conexión antes de detenerse y generar un error.
         */
        public function getConnectionTimeout();
        
        /**
         * @return string Obtiene el nombre de la base de datos actual o de la que se va a utilizar una vez que se abre la conexión.
         */
        public function getDatabase();
        
        /**
         * @return EConnectionState Obtiene el estado actual de la conexión.
         */
        public function getState();
        
        /**
         * 
         * @param \piola\db\EIsolationLevel $isolationLevel Uno de los valores de IsolationLevel.
         * @return IDatabaseTransaction Objeto que representa la nueva transacción.
         */
        public function BeginTransaction(EIsolationLevel $isolationLevel = EIsolationLevel::ReadCommitted);
        
        /**
         * 
         * @param string $database
         */
        public function ChangeDatabase($database);
        
        /**
         * 
         */
        public function Close();
        
        /**
         * @return IDatabaseCommand Objeto Command asociado a una conexión.
         */
        public function CraeteCommand();
        
        /**
         * 
         */
        public function Open();
    }
}

?>