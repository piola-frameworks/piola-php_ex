<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    class HorarioFranjasModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_");
                array_push($this->_params, array(
                    ':'  =>  $item,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }

        public function Insert(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_");
                array_push($this->_params, array(
                    ':'  =>  $item,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if(!empty($model))
            {
                $this->_sp = "sp_selHorarioFranja";
                $this->_params = array(
                    ':IdFranja' =>  $model->_idFranja,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selHorarioFranjas";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }
        
        /*
         * Start custom selects
         */
        
        public function SelectByIdPedido(core\AModel $model)
        {
            $this->_sp = "sp_selHorarioFranajasByIdPedido";
            $this->_params = array(
                ':idPedido' =>  $model->_idPedido,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectRangos()
        {
            $this->init();
            
            $this->_sp = "sp_selHorarioFranajasJson";
            
            return Database::getInstance()->DoQuery($this->_sp);
        }
        
        /*
         * End custom selects
         */
        
        public function Update(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_");
                array_push($this->_params, array(
                    ':'  =>  $item,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>