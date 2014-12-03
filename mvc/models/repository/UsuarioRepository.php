<?php

namespace dn\models\repositories
{
    class UsuarioRepository extends \piola\patterns\repository\ADatabaseRepository
    {
        public function create($entity)
        {
            $conexion = $this->getConnection();
            $comando = $conexion->CraeteCommand();
        }

        public function filterBy(\Closure $function)
        {
            $resultado = $this->getAll();
            return array_filter($resultado, $function);
        }

        public function getAll()
        {
            return $this->getSession()->Obtain();
        }

        public function getBy(\Closure $function)
        {
            $resultado = $this->filterBy($function);
            return $resultado->count() > 0 ? $resultado->offsetGet(0) : null; 
        }

        public function getById($id)
        {
            return $this->getSession()->Obtain($id);
        }

        public function modify($entity)
        {
            $this->getSession()->Update($entity);
        }

        public function remove($entity)
        {
            $this->getSession()->Delete($entity);
        }
    }
}

?>