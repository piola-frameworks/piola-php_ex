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
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_insTexto");
                
                array_push($this->_params, array(
                    ':creadoPor'        =>  (int)$item->_creadoPor,
                    ':creadoDia'        =>  (string)$item->_creadoDia,
                    ':modificadoPor'    =>  is_null($item->_modificadoPor) ? null : (int)$item->_modificadoPor,
                    ':modificadoDia'    =>  is_null($item->_modificadoDia) ? null : (string)$item->_modificadoDia,
                    ':codInterno'       =>  is_null($item->_codInterno) ? null : (string)$item->_codInterno,
                    ':idMateria'        =>  is_null($item->_idMateria) ? null : (int)$item->_idMateria,
                    ':idTipo'           =>  (int)$item->_idTipo,
                    ':nombre'           =>  (string)$item->_nombre,
                    ':autor'            =>  is_null($item->_autor) ? null : (string)$item->_autor,
                    ':docente'          =>  is_null($item->_docente) ? null : (string)$item->_docente,
                    ':cantPaginas'      =>  (int)$item->_cantPaginas,
                    ':activo'           =>  (bool)$item->_activo,
                ));
            }
            
            var_dump($this->_sp, $this->_params, $this->_trans);
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
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