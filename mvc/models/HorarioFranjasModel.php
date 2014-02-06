<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    class HorarioFranjasModel extends core\AModel
    {
        public function Delete(array $model)
        {
            
        }

        public function Insert(array $model)
        {
            
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

        public function Update(array $model)
        {
            
        }

    }
}

?>