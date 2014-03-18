<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    
    final class DocenteModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delDocente");
                array_push($this->_params, array(
                    ':idDocente' =>  (int)$item->_idDocente,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }

        public function Insert(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_insDocente");
                array_push($this->_params, array(
                    ':idPersona'    =>  (int)$item->_idPersona,
                    ':legajo'       =>  (string)$item->_legajo,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $this->_sp = "sp_selDocente";
                $this->_params = array(
                    ':idDocente'   =>  (int)$model->_idDocente,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selDocentes";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }

        public function Update(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_updEstudiante");
                array_push($this->_params, array(
                    ':idDocente'    =>  (int)$item->_idDocente,
                    ':idPersona'    =>  (int)$item->_idPersona,
                    ':legajo'       =>  (string)$item->_legajo,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>