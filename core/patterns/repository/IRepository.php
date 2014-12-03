<?php

namespace piola\patterns\repository
{
    interface IRepository extends IReadOnlyRepository
    {
        /**
         * 
         * @param \piola\patterns\IEntity $entity
         * @return int Devuelve el identificador del objeto generado.
         */
        public function create($entity);
        
        /**
         * 
         * @param \piola\patterns\IEntity $entity
         */
        public function remove($entity);
        
        /**
         * 
         * @param \piola\patterns\IEntity $entity
         */
        public function modify($entity);
    }    
}

?>