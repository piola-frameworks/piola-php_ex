<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class MateriaModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delMateria");
                
                array_push($this->_params, array(
                    ':idMateria'    =>  (int)$item->_idMateria,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }

        public function Insert(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_insMateria");
                
                array_push($this->_params, array(
                    ':idNivel'      =>  (int)$item->_idNivel,
                    ':codInterno'   =>  (string)$item->_codInterno,
                    ':descripcion'  =>  (string)$item->_descripcion,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if($model == null)
            {
                $this->_sp = "sp_selMaterias";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
            else
            {
                $this->_sp = "sp_selMateria";
                $this->_params = array(
                    'idMateria' =>  $model->_idMateria,
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
                array_push($this->_sp, "sp_updMateria");
                
                array_push($this->_params, array(
                    ':idMateria'    =>  (int)$item->_idMateria,
                    ':idNivel'      =>  (int)$item->_idNivel,
                    ':codInterno'   =>  (string)$item->_codInterno,
                    ':descripcion'  =>  (string)$item->_descripcion,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}


