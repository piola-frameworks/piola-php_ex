<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class WebModel extends core\AModel
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
                array_push($this->_sp, "sp_delConfiguracion");
                array_push($this->_params, array(
                    ':clave'    =>  (string)$item->_clave,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }

        public function Insert(array $model)
        {
            $this->init();
            
            if(count($model) > 1 && count($model) < 0)
            {
                $this->_trans = true;
            }
            else
            {
                throw new LogicException("Falta, por lo menos, un modelo para realizar la operacion.");
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_insConfiguracion");
                array_push($this->_params, array(
                    ':clave'    =>  (string)$item->_clave,
                    ':valor'    =>  (float)$item->_valor,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            $this->init();
            
            if($model != null)
            {
                $this->_sp = "sp_selConfiguracion";
                $this->_params = array(
                    ':clave'   =>  (string)$model->_clave,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selConfiguraciones";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }

        /*
         * Start custom selects
         */
        
        public function SelectFeriados()
        {
            $this->init();
            
            $this->_sp = "sp_selFeriados";
                
            return Database::getInstance()->DoQuery($this->_sp);
        }
        
        /*
         * End custom selects
         */
        
        public function Update(array $model)
        {
            $this->init();
            
            if(count($model) > 1 && count($model) < 0)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_updConfiguracion");
                array_push(
                    $this->_params,
                    array(
                        ':clave'    =>  (string)$item->_clave,
                        ':valor'    =>  (float)$item->_valor,
                    )
                );
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>