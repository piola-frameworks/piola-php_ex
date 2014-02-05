<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class TextoModel extends core\AModel
    {
        public function Delete(array $model)
        {
            
        }

        public function Insert(array $model)
        {
            
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $this->_sp = "sp_selTexto";
                $this->_params = array(
                    ':idTexto'   =>  $model->_idTexto,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selTextos";

                /*$var = Database::getInstance()->DoQuery($this->_sp);
                var_dump($var);
                return $var;*/
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }
        
        public function SelectByIdMateria(core\AModel $model)
        {
            $this->_sp = "sp_selTextosByIdMateria";
            $this->_params = array(
                ':idMateria' =>  $model->_idMateria,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }

        public function Update(array $model)
        {
            
        }
    }
}

?>