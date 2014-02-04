<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    
    final class DocenteModel extends core\AModel
    {
        public function Delete(core\AModel $model)
        {
            $params = array(
                ':id'   =>  $model->_idUsuario,
            );
            
            $this->_gdb->DoQuery("sp_delUsuarios", $params);
        }

        public function Insert(core\AModel $model)
        {
            $params = array(
                ":idAlumno"     =>  $model->_idAlumno,
                ":nombre"       =>  $model->_nombre,
                ":apellido"     =>  $model->_apellido,
                ":contrasena"   =>  $model->_contrasena,
                ":comentario"   =>  $model->_comentario,
                ":privilegio"   =>  $model->_privilegio,
            );

            $this->_gdb->DoScalar("sp_insUsuario", $params);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $params = array(
                    ':id'   =>  $model->_data["_idUsuario"],
                );

                return $this->_gdb->DoQuery("sp_selUsuario", $params);
            }
            else
            {
                return $this->_gdb->DoQuery("sp_selUsuarios");
            }
        }

        public function Update(core\AModel $model)
        {
            $params = array(
                ":idUsuario"    =>  $model->_idUsuario,
                ":idAlumno"     =>  $model->_idAlumno,
                ":nombre"       =>  $model->_nombre,
                ":apellido"     =>  $model->_apellido,
                ":contrasena"   =>  $model->_contrasena,
                ":comentario"   =>  $model->_comentario,
                ":privilegio"   =>  $model->_privilegio,
            );

            $this->_gdb->DoNonQuery("sp_updUsuario", $params);
        }
    }
}

?>