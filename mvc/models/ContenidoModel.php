<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use CEIT\core\CMySQLDatabase as Database;
    
    class ContenidoModel extends core\AModel
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
                array_push($this->_sp, "");
                array_push($this->_params, array(
                    ":idContenido"  =>  (int)$item->_idContenido,
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
                array_push($this->_sp, "");
                array_push($this->_params, array(
                    ":idContenido"  =>  (int)$item->_idContenido,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            $this->init();
            
            if(!empty($model))
            {
                $this->_sp = "sp_selTipoContenido";
                $this->_params = array(
                    ":idContenido"  =>  (int)$model->_idContenido,
                );
            }
            else
            {
                $this->_sp = "sp_selTipoContenidos";
            }
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }

        public function Update(array $model)
        {
            $this->init();
            
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "");
                array_push($this->_params, array(
                    ":idContenido"  =>  (int)$item->_idContenido,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>