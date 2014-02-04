<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class UsuarioModel extends core\AModel
    {
        public function Delete(array $model)
        {
            $params = array(
                ':id'   =>  $model->_idUsuario,
            );

            Database::getInstance()->DoQuery("sp_delUsuarios", $params);
        }

        public function Insert(array $model)
        {
            $params = array(
                ":idAlumno"     =>  $model->_idAlumno,
                ":nombre"       =>  $model->_nombre,
                ":apellido"     =>  $model->_apellido,
                ":contrasena"   =>  $model->_contrasena,
                ":comentario"   =>  $model->_comentario,
                ":privilegio"   =>  $model->_privilegio,
            );

            Database::getInstance()->DoScalar("sp_insUsuario", $params);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $params = array(
                    ':id'   =>  $model->_data["_idUsuario"],
                );

                return Database::getInstance()->DoQuery("sp_selUsuario", $params);
            }
            else
            {
                return Database::getInstance()->DoQuery("sp_selUsuarios");
            }
        }

        public function SelectByUsername(core\AModel $model)
        {
            $params = array(
                ':username'   =>  $model->_data["username"],
            );

            return Database::getInstance()->DoQuery("sp_selUsuarioByLegajoOrUsername", $params);
        }
        
        public function Update(array $model)
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

            Database::getInstance()->DoNonQuery("sp_updUsuario", $params);
        }
    }
}

?>