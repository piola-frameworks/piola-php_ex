<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase AS Database;
    
    final class TipoContenidoModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_del");
                array_push($this->_params, array(
                    ''  =>  $item->_,
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
                array_push($this->_sp, "sp_ins");
                array_push($this->_params, array(
                    ''  =>  $item->_,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if(is_null($model))
            {
                $this->_sp = "sp_selTipoContenidos";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
            else
            {
                $this->_sp = "sp_selTipoContenido";
                $this->_params = array(
                    ':idContenido'  =>  (int)$item->_idContenido,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
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
                array_push($this->_sp, "sp_upd");
                array_push($this->_params, array(
                    ''  =>  $item->_,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>