<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class MateriaModel extends core\AModel
    {
        public function Delete(array $model)
        {
            $this->init();
            
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delMateria");
                array_push($this->_params, array(
                    ':idMateria'    =>  (int)$item->_idMateria,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }

        public function Insert(array $model)
        {
            $this->init();
            
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_insMateria");
                array_push($this->_params, array(
                    ':idNivel'      =>  (int)$item->_idNivel,
                    ':codInterno'   =>  (string)$item->_codInterno,
                    ':descripcion'  =>  (string)$item->_descripcion,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            $this->init();
            
            if($model == null)
            {
                $this->_sp = "sp_selMaterias";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
            else
            {
                $this->_sp = "sp_selMateria";
                $this->_params = array(
                    ':idMateria' =>  (int)$model->_idMateria,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
        }

        /*
         * Start custom selects
         */
        
        public function SelectWithMark(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selMateriasWithMark";
            $this->_params = array(
                ':idNivel'  =>  (int)$model->_idNivel,
                ':idMateria'  =>  (int)$model->_idMateria,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectByIdNivel(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selMateriasByIdNivel";
            $this->_params = array(
                ':idNivel'  =>  $model->_idNivel,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        /*
         * End custom selects
         */
        
        public function Update(array $model)
        {
            $this->init();
            
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_updMateria");
                array_push($this->_params, array(
                    ':idMateria'    =>  (int)$item->_idMateria,
                    ':idNivel'      =>  (int)$item->_idNivel,
                    ':codInterno'   =>  (string)$item->_codInterno,
                    ':descripcion'  =>  (string)$item->_descripcion,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}


