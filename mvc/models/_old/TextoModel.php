<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class TextoModel extends core\AModel
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
                array_push($this->_sp, "sp_delTexto");
                array_push($this->_params, array(
                    ':idTexto'        =>  (int)$item->_idTexto,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
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
                array_push($this->_sp, "sp_insTexto");
                array_push($this->_params, array(
                    ':creadoPor'        =>  (int)$item->_creadoPor,
                    ':creadoDia'        =>  (string)$item->_creadoDia,
                    ':modificadoPor'    =>  is_null($item->_modificadoPor) ? null : (int)$item->_modificadoPor,
                    ':modificadoDia'    =>  is_null($item->_modificadoDia) ? null : (string)$item->_modificadoDia,
                    ':codInterno'       =>  is_null($item->_codInterno) ? null : (int)$item->_codInterno,
                    ':idMateria'        =>  is_null($item->_idMateria) ? null : (int)$item->_idMateria,
                    ':idTipoTexto'      =>  (int)$item->_idTipoTexto,
                    ':idTipoContenido'  =>  is_null($item->_idTipoContenido) ? null : (int)$item->_idTipoContenido,
                    ':nombre'           =>  (string)$item->_nombre,
                    ':autor'            =>  is_null($item->_autor) ? null : (string)$item->_autor,
                    ':docente'          =>  is_null($item->_docente) ? null : (string)$item->_docente,
                    ':cantPaginas'      =>  (int)$item->_cantPaginas,
                    ':activo'           =>  (bool)$item->_activo,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            $this->init();
            
            if($model != null)
            {
                $this->_sp = "sp_selTexto";
                $this->_params = array(
                    ':idTexto'   =>  (int)$model->_idTexto,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selTextos";

                return Database::getInstance()->DoQuery($this->_sp);
            }
        }
        
        /*
         * Start custom selects
         */
        
        public function SelectCountTotal()
        {
            $this->init();
            
            $this->_sp = "sp_selTextosTotal";
            
            return Database::getInstance()->DoQuery($this->_sp);
        }
        
        public function SelectPagination(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selTextosPagination";
            $this->_params = array(
                ":page"     =>  (int)$model->_page,
                ":quantity" =>  (int)$model->_quantity,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectByIdMateria(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selTextosByIdMateria";
            $this->_params = array(
                ':idMateria' =>  $model->_idMateria,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectByIdMateriaAndDescripcion(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selTextosByIdMateriaAndDescripcion";
            $this->_params = array(
                ':idMateria' =>  $model->_idMateria,
                ':descripcion' =>  $model->_descripcion,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectByIdMateriaAndContenido(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selTextosByIdMateriaAndIdTipoContenido";
            $this->_params = array(
                ':idMateria'        =>  $model->_idMateria,
                ':idTipoContenido'  =>  $model->_idTipoContenido
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
                array_push($this->_sp, "sp_updTexto");
                array_push($this->_params, array(
                    ':idTexto'          =>  (int)$item->_idTexto,
                    ':creadoPor'        =>  (int)$item->_creadoPor,
                    ':creadoDia'        =>  (string)$item->_creadoDia,
                    ':modificadoPor'    =>  is_null($item->_modificadoPor) ? null : (int)$item->_modificadoPor,
                    ':modificadoDia'    =>  is_null($item->_modificadoDia) ? null : (string)$item->_modificadoDia,
                    ':codInterno'       =>  is_null($item->_codInterno) ? null : (int)$item->_codInterno,
                    ':idMateria'        =>  is_null($item->_idMateria) ? null : (int)$item->_idMateria,
                    ':idTipoTexto'      =>  (int)$item->_idTipoTexto,
                    ':idTipoContenido'  =>  is_null($item->_idTipoContenido) ? null : (int)$item->_idTipoContenido,
                    ':nombre'           =>  (string)$item->_nombre,
                    ':autor'            =>  is_null($item->_autor) ? null : (string)$item->_autor,
                    ':docente'          =>  is_null($item->_docente) ? null : (string)$item->_docente,
                    ':cantPaginas'      =>  (int)$item->_cantPaginas,
                    ':activo'           =>  (bool)$item->_activo,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>