<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;

    final class EstudianteModel extends core\AModel
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
                array_push($this->_sp, "sp_delEstudiante");
                array_push($this->_params, array(
                    ':idEstudiante' =>  (int)$item->_idEstudiante,
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
                array_push($this->_sp, "sp_insEstudiante");
                array_push($this->_params, array(
                    ':idPersona'    =>  (int)$item->_idPersona,
                    ':legajo'       =>  (string)$item->_legajo,
                    ':idCarrera'    =>  is_null($item->_idCarrera) ? null : (int)$item->_idCarrera,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            $this->init();
            
            if($model != null)
            {
                $this->_sp = "sp_selEstudiante";
                $this->_params = array(
                    ':idEstudiante'   =>  (int)$model->_idEstudiante,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selEstudiantes";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }

        /*
         * Start custom selects
         */
        
        public function SelectByIdUsuario(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selEstudianteByIdUsuario";
            $this->_params = array(
                ':idUsuario'   =>  (int)$model->_idUsuario,
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
                array_push($this->_sp, "sp_updEstudiante");
                array_push($this->_params, array(
                    ':idEstudiante' =>  (int)$item->_idEstudiante,
                    ':idPersona'    =>  (int)$item->_idPersona,
                    ':legajo'       =>  (string)$item->_legajo,
                    ':idCarrera'    =>  is_null($item->_idCarrera) ? null : (int)$item->_idCarrera,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
        
        /*
         * Start custom updates
         */
        
        public function UpdateByIdPersona(core\AModel $model)
        {
            $this->init();
            
            array_push($this->_sp, "sp_updEstudianteByIdPersona");
            array_push($this->_params, array(
                ':idPersona'    =>  (int)$model->_idPersona,
                ':legajo'       =>  (string)$model->_legajo,
                ':idCarrera'    =>  is_null($model->_idCarrera) ? null : (int)$model->_idCarrera,
            ));
            
            //var_dump($this->_sp, $this->_params, $this->_trans);
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params);
        }
        
        /*
         * End custom updates
         */
    }
}

?>