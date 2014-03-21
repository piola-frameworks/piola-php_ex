<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class PersonaModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delPersona");
                array_push($this->_params, array(
                    ':idPersona' =>  (int)$item->_idPersona,
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
                array_push($this->_sp, "sp_insPersona");
                array_push($this->_params, array(
                    ':nombre'       =>  is_null($item->_nombre) ? null : (string)$item->_nombre,
                    ':apellido'     =>  is_null($item->_apellido) ? null : (string)$item->_apellido,
                    ':dni'          =>  (int)$item->_dni,
                    ':telefono'     =>  is_null($item->_telefono) ? null :(int)$item->_telefono,
                    ':celular'      =>  is_null($item->_celular) ? null : (int)$item->_celular,
                    ':email'        =>  is_null($item->_email) ? null : (string)$item->_email,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $this->_sp = "sp_selPersona";
                $this->_params = array(
                    ':idPersona'   =>  (int)$model->_idPersona,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selPersonas";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }

        /*
         * Start custom selects
         */
        
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
                array_push($this->_sp, "sp_updPersona");
                array_push($this->_params, array(
                    ':idPersona'    =>  (int)$item->_idPersona,
                    ':nombre'       =>  is_null($item->_nombre) ? null : (string)$item->_nombre,
                    ':apellido'     =>  is_null($item->_apellido) ? null : (string)$item->_apellido,
                    ':dni'          =>  (int)$item->_dni,
                    ':telefono'     =>  is_null($item->_telefono) ? null :(int)$item->_telefono,
                    ':celular'      =>  is_null($item->_celular) ? null : (int)$item->_celular,
                    ':email'        =>  is_null($item->_email) ? null : (string)$item->_email,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>