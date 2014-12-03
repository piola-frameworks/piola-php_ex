<?php

namespace CEIT\core
{
    use PDO;
    
    final class CPDOExtended extends \PDO
    {
        const DB_MYSQL = "mysql";
        const DB_POSTG = "postgree";
        const DB_ORACL = "oracle";
        const DB_MSSQL = "mssql";
        const DB_SQLIT = "sqlite";
        const DB_INFOR = "informix";
        const DB_IBM = "ibm";
        const DB_ODBC = "odbc";

        private $dsn;
        private $usuario;
        private $contrasena;
        private $opciones = array();
        
        public function __construct($dbtype = DB_MYSQL, $servidor, $usuario, $contrasena, $basedato, $options = null)
        {
            $this->usuario = $usuario;
            $this->contrasena = $contrasena;
            
            // dsn example: 'mysql:host=localhost;dbname=test'
            $this->dsn = $dbtype . ':host=' . $servidor . ';dbname=' . $basedato;

            if (!isset($options) || empty($options))
            {
                $this->opciones = array(
                    PDO::ATTR_AUTOCOMMIT => 0,
                    PDO::ATTR_TIMEOUT => 3,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => false,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                );
            }

            parent::__construct($this->dsn, $this->usuario, $this->contrasena, $this->opciones);
        }
    }
}

?>