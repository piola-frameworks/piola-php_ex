<?php

namespace CEIT\core
{
    interface ICommands
    {
        public function Select(AModel $model = null);
        public function Insert(array $model);
        public function Update(array $model);
        public function Delete(array $model);
    }
}

?>