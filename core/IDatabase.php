<?php

namespace CEIT\core
{
    interface IDatabase
    {
        public function DoQuery($sp, array $params = array());
        public function DoNonQuery(array $sp, array $params = array(), $trans = false);
        public function DoScalar(array $sp, array $params = array(), $trans = false);
    }
}

?>