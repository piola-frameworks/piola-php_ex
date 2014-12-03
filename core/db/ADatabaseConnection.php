<?php

namespace piola\db
{
    abstract class ADatabaseConnection implements IDatabaseConnection
    {
        protected $_connectionString;
        protected $_connectionTimeout;
        protected $_database;
        protected $_state;
        
        public function getConnectionString()
        {
            return $this->_connectionString;
        }
        
        public function setConnectionString($connectionString)
        {
            $this->_connectionString = $connectionString;
        }
        
        public function getConnectionTimeout()
        {
            return $this->_connectionTimeout;
        }
        
        public function getDatabase()
        {
            return $this->_database;
        }
        
        public function getState()
        {
            return $this->_state;
        }
    }
}

?>