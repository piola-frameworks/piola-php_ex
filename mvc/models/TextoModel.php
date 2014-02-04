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
            try
            {
                if($model != null)
                {
                    $params = array(
                        ':idTexto'   =>  $model->_data["idTexto"],
                    );
                    
                    return Database::getInstance()->DoQuery("sp_selTexto", $params);
                }
                else
                {
                    return Database::getInstance()->DoQuery("sp_selTextos");
                }
            }
            catch(Exception $ex)
            {
                echo $ex->getTraceAsString();
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