<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class ReporteModel extends core\AModel
    {
        public function Delete(array $model)
        {
            
        }

        public function Insert(array $model)
        {
            
        }

        public function Select(\CEIT\core\AModel $model = null)
        {
            
        }

        /*
         * Start custom selects
         */
        
        public function SelectFotocopia(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_rptFotocopias";
            $this->_params = array(
                ':desde'    =>  is_null($model->_desde) || strlen($model->_desde) == 0 ? null : (string)$model->_desde,
                ':hasta'    =>  is_null($model->_hasta) || strlen($model->_hasta) == 0 ? null : (string)$model->_hasta,
                ':turno'    =>  is_null($model->_turno) ? null : (int)$model->_turno,
                ':simple'   =>  is_null($model->_simple) ? null : (bool)$model->_simple,
                ':doble'    =>  is_null($model->_doble) ? null : (bool)$model->_doble,
                ':sistema'  =>  is_null($model->_sistema) ? null : (bool)$model->_sistema,
                ':especial' =>  is_null($model->_especial) ? null : (bool)$model->_especial,
                ':suelta'   =>  is_null($model->_suelta) ? null : (bool)$model->_suelta,
            );

            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        /*
         * End custom selects
         */
        
        public function Update(array $model)
        {
            
        }
    }
}

?>