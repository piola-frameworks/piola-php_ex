<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    
    class TipoTextoModel extends core\AModel
    {
        private $_idTipoTexto;
        private $_descripcionCorta;
        private $_descripcionLarga;
        
        private $gdb;
        
        public function __construct()
        {
            $this->_gdb = parent::__construct();
        }

        public function Delete()
        {
            try
            {
                
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
                
            }
            catch(Exception $ex)
            {
                echo $ex->getTraceAsString();
            }
        }

        public function Select()
        {
            try
            {
                
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
                
            }
            catch(Exception $ex)
            {
                echo $ex->getTraceAsString();
            }
        }
    }
}

