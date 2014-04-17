<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class NivelModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delNivel");
                
                array_push($this->_params, array(
                    ':idNivel'    =>  (int)$item->_idNivel,
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
                array_push($this->_sp, "sp_insNivel");
                
                array_push($this->_params, array(
                    ':idCarrera'    =>  (int)$item->_idCarrera,
                    ':ano'          =>  (int)$item->_ano,
                    ':descripcion'  =>  (string)$item->_descripcion,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if($model == null)
            {
                $this->_sp = "sp_selNiveles";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
            else
            {
                $this->_sp = "sp_selNivel";
                $this->_params = array(
                    ':idNivel' =>  (int)$model->_idNivel,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
        }
        
        /*
         * Start custom selects
         */
        
        public function SelectWithMark(core\AModel $model)
        {
            $this->_sp = "sp_selNivelesWithMark";
            $this->_params = array(
                ":idCarrera" =>  (int)$model->_idCarrera,
                ":idNivel" =>  (int)$model->_idNivel,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectByIdCarrera(core\AModel $model)
        {
            $this->_sp = "sp_selNivelByIdCarrera";
            $this->_params = array(
                ':idCarrera' =>  $model->_idCarrera,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
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
                array_push($this->_sp, "sp_updNivel");
                
                array_push($this->_params, array(
                    ':idNivel'      =>  (int)$item->_idNivel,
                    ':idCarrera'    =>  (int)$item->_idCarrera,
                    ':ano'          =>  (string)$item->_ano,
                    ':descripcion'  =>  (string)$item->_descripcion,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}


