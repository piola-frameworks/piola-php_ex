<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

