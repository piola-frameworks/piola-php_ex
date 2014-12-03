<?php

namespace piola\patterns\repository
{
    interface IReadOnlyRepository
    {
        /**
         * @return \ArrayObject Coleccion de objetos
         */
        public function getAll();
        
        /**
         * 
         * @param int $id
         * @return object Retorna un objecto especifico del repositorio.
         */
        public function getById($id);
        
        /**
         * 
         * @param \Closure $function
         * @return \ArrayObject Coleccion de objetos
         */
        public function getBy(\Closure $function);
        
        /**
         * 
         * @param \Closure $function
         * @return \ArrayObject Coleccion de objectos
         */
        public function filterBy(\Closure $function);
        
    }
}

?>