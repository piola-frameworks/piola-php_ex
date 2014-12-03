<?php

namespace piola\patterns\repository
{
    abstract class ADatabaseRepository implements IRepository
    {
        /**
         *
         * @var \piola\db\IDatabaseConnection 
         */
        protected $_connection;
        
        /**
         * 
         * @return \piola\db\IDatabaseConnection
         */
        protected function getConnection()
        {
            return $this->_connection;
        }
        
        /**
         * 
         * @param \piola\db\IDatabaseConnection $connection
         */
        protected function setConnection(\piola\db\IDatabaseConnection $connection)
        {
            $this->_connection = $connection;
        }
        
        /**
         * 
         * @param \piola\db\IDatabaseConnection $connection
         */
        public function __construct(\piola\db\IDatabaseConnection $connection)
        {
            $this->setConnection($connection);
        }
    }
}

?>