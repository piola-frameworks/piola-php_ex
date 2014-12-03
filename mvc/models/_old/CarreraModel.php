<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class CarreraModel extends core\AModel
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
                array_push($this->_sp, "sp_delCarrera");
                array_push($this->_params, array(
                    ':idCarrera' =>  (int)$item->_idCarrera,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params);
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
                array_push($this->_sp, "sp_insCarrera");
                array_push($this->_params, array(
                    ':codInterno'   =>  (int)$item->_codInterno,
                    ':descripcion'  =>  (string)$item->_descripcion,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params);
        }

        public function Select(core\AModel $model = null)
        {
            $this->init();
            
            if($model == null)
            {
                $this->_sp = "sp_selCarreras";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
            else
            {
                $this->_sp = "sp_selCarrera";
                $this->_params = array(
                    ':idCarrera' => (int)$model->_idCarrera,
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
            
            $this->_sp = "sp_selCarreraWithMark";
            $this->_params = array(
                ':idCarrera' => (int)$model->_idCarrera,
            );

            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectByIdUsuario(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selCarrerasByIdUsuario";
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
            $this->init();
            
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_updCarrera");
                
                array_push($this->_params, array(
                    ':idCarrera'    =>  (int)$item->_idCarrera,
                    ':codInterno'   =>  (int)$item->_codInterno,
                    ':descripcion'  =>  (string)$item->_descripcion,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params);
        }

    }
}

?>