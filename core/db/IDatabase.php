<?php

namespace piola\db
{
    interface IDatabase
    {
        public function DoQuery($sp, array $params = array());
        public function DoNonQuery(array $sp, array $params = array(), $trans = false);
        public function DoScalar(array $sp, array $params = array(), $trans = false);
        
        public function DoQuery();
    }
}

?>