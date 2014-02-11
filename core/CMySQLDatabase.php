<?php

namespace CEIT\core
{
    use CEIT\core;
    
    final class CMySQLDatabase implements core\IDatabase
    {
        private static $_instance;
        private $_pdoExtended;
        
        private static $servidor = 'localhost';
        private static $usuario = 'root';
        private static $contrasena = 'qs33712503829';
        private static $basedato = 'ceit';
        
        public function __construct()
        {
            $this->_pdoExtended = new CPDOExtended(CPDOExtended::DB_MYSQL, self::$servidor, self::$usuario, self::$contrasena, self::$basedato);
            $this->_pdoExtended->setAttribute(CPDOExtended::MYSQL_ATTR_COMPRESS, true);
            $this->_pdoExtended->setAttribute(CPDOExtended::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }

        public function __clone()
        {
            throw new RuntimeException("Metodo no desarrollado.");
        }
        
        public function __wakeup()
        {
            throw new RuntimeException("Metodo no desarrollado.");
        }
        
        public static function getInstance()
        {
            if(!self::$_instance instanceof self)
            {
                self::$_instance = new self();
            }
            
            return self::$_instance;
        }

        public function DoNonQuery(array $sp, array $params = array(), $trans = false)
        {
            try
            {
                if(!$this->_pdoExtended->inTransaction())
                {
                    $this->_pdoExtended->beginTransaction();
                }
                
                $rowCount = 0;
                foreach ($sp as $index => $sp_name)
                {
                    $sentencia = $this->_pdoExtended->prepare("CALL " . $sp_name . self::GenerateParenthesis($params[$index]));
                    foreach ($params[$index] as $key => $value)
                    {
                        $sentencia->bindValue($key, $value, self::GetParameterType($value));
                    }
                    
                    $sentencia->execute();
                    //echo '<pre>' . $sentencia->debugDumpParams() . '</pre>';
                    
                    $rowCount += $sentencia->rowCount();
                }
                
                if($trans || $this->_pdoExtended->inTransaction())
                {
                    $this->_pdoExtended->commit();
                }
                
                return $rowCount;
            }
            catch (Exception $ex)
            {
                if($trans || $this->_pdoExtended->inTransaction())
                {
                    $this->_pdoExtended->rollBack();
                }
                
                throw $ex;
            }
        }

        public function DoQuery($sp, array $params = array())
        {
            try
            {
                $sentencia = $this->_pdoExtended->prepare("CALL " . $sp . self::GenerateParenthesis($params));

                if(!empty($params))
                {
                    foreach($params as $key => $value)
                    {
                        $sentencia->bindValue($key, $value, self::GetParameterType($value));
                    }
                }
                
                $sentencia->execute();
                //echo '<pre>' . $sentencia->debugDumpParams() . '</pre>';
                
                return $sentencia->fetchAll();
            }
            catch (Exception $ex)
            {
                throw $ex;
            }
        }

        public function DoScalar(array $sp, array $params = array(), $trans = false)
        {
            $lastId = 0;
            
            try
            {
                if(!$this->_pdoExtended->inTransaction())
                {
                    $this->_pdoExtended->beginTransaction();
                }
                
                foreach ($sp as $index => $sp_name)
                {
                    $sentencia = $this->_pdoExtended->prepare("CALL " . $sp_name . self::GenerateParenthesis($params[$index]));
                    
                    foreach($params[$index] as $key => $value)
                    {
                        //echo 'key = (' . $key . ') value = (' . $value . ') type = (' . self::GetParameterType($value) . ')<br />';
                        $sentencia->bindValue($key, $value, self::GetParameterType($value));
                    }
                    
                    $sentencia->execute();
                    //echo '<pre>' . $sentencia->debugDumpParams() . '</pre>';
                }
                
                // busco la ultima clave. no funciona a traves de pdo::lastinsertedid()
                $sentencia = $this->_pdoExtended->prepare("SELECT LAST_INSERT_ID();");
                $sentencia->execute();
                
                // por que solo debe devovler 1 fila.
                if($sentencia->rowCount() == 1)
                {
                    $row = $sentencia->fetchAll(CPDOExtended::FETCH_ASSOC);
                    $lastId = $row[0]['LAST_INSERT_ID()'];
                }
                else
                {
                    trigger_error("Hubo un error en la obtencion el Id", E_USER_ERROR);
                }
                
                if($trans || $this->_pdoExtended->inTransaction())
                {
                    $this->_pdoExtended->commit();
                }
                
                return $lastId;
            }
            catch (Exception $ex)
            {
                if($trans || $this->_pdoExtended->inTransaction())
                {
                    $this->_pdoExtended->rollBack();
                }
                
                throw $ex;
            }
        }
        
        private static function GenerateParenthesis(array $params = array())
        {
            $parenthesis = "";
                
            if(!empty($params))
            {
                $parenthesis = "(";
                $keys = array_keys($params);

                for($index = 0; $index < count($params); $index++)
                {
                    if($index < count($params) - 1)
                    {
                        $parenthesis .= $keys[$index] . ", ";
                    }
                    else
                    {
                        $parenthesis .= $keys[$index];
                    }
                }

                $parenthesis .= ")";
            }
            
            return $parenthesis;
        }
        
        private static function GetParameterType($parameter)
        {
            if(is_array($parameter))
            {
                trigger_error("Por que el parametro es un array?", E_USER_ERROR);
            }
            
            if(is_bool($parameter))
            {
                return CPDOExtended::PARAM_BOOL;
            }
            else if(is_null($parameter))
            {
                return CPDOExtended::PARAM_NULL;
            }
            else if(is_int($parameter))
            {
                return CPDOExtended::PARAM_INT;
            }
            else if(is_string($parameter))
            {
                return CPDOExtended::PARAM_STR;
            }
            else if(is_float($parameter))
            {
                return CPDOExtended::PARAM_STR;
            }
            else if(is_object($parameter))
            {
                return CPDOExtended::PARAM_LOB;
            }
            else
            {
                // TODO: fijarse el tipo correcto a regresar.
                return CPDOExtended::PARAM_STR;
            }
        }
    }
}

?>