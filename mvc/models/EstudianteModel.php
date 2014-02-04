<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;

    final class EstudianteModel extends core\AModel
    {
        public function Delete()
        {
            try
            {
                $this->gdb->DoNonQuery($sp, $params, $trans);
            }
            catch(Exception $ex)
            {
                echo $ex->getTraceAsString();
            }
        }

        public function Insert()
        {
            try
            {
                $this->gdb->DoScalar();
            }
            catch(Exception $ex)
            {
                echo $ex->getTraceAsString();
            }
        }

        public function Select($id = null)
        {
            try
            {
                $this->gdb->DoQuery();
            }
            catch(Exception $ex)
            {
                echo $ex->getTraceAsString();
            }
        }

        public function Update()
        {
            try
            {
                $this->gdb->DoNonQuery($sp, $params, $trans);
            }
            catch(Exception $ex)
            {
                echo $ex->getTraceAsString();
            }
        }
    }
}

?>