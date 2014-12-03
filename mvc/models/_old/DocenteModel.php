<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
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

        /*
         * Start custom selects
         */
        
        public function SelectByIdUsuario(core\AModel $model)
        {
            $this->_sp = "sp_selDocenteByIdUsuario";
            $this->_params = array(
                ':idUsuario'    =>  $model->_idUsuario,
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
                array_push($this->_sp, "sp_updDocente");
                array_push($this->_params, array(
                    ':idDocente'    =>  (int)$item->_idDocente,
                    ':idPersona'    =>  (int)$item->_idPersona,
                    ':legajo'       =>  is_null($item->_legajo) ? null : (string)$item->_legajo,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }
        
        /*
         * Start custom updates
         */
        
        public function UpdateByIdPersona(core\AModel $model)
        {
            array_push($this->_sp, "sp_updDocenteByIdPersona");
            array_push($this->_params, array(
                ':idPersona'    =>  (int)$model->_idPersona,
                ':legajo'       =>  is_null($model->_legajo) ? null : (string)$model->_legajo,
            ));
            
            //var_dump($this->_sp, $this->_params);
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params);
        }
        
        /*
         * End custom updates
         */
    }
}

?>