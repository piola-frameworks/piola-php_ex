<?php

namespace piola\db
{
    interface IDatabaseCommand
    {
        public function getCommandText();
        public function setCommandText($commandText);
        public function getCommandTimeout();
        public function setCommandTimeout($commandTimeout);
        public function getCommandType();
        public function setCommandType($commandType);
        public function getConnection();
        public function setConnection(IDatabaseConnection $connection);
        public function getParameters();
        public function getTransaction();
        public function setTransaction($transaction);
    }
}

?>