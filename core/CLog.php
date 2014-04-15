<?php

namespace CEIT\core
{
    class CLog
    {
        private static $instance = NULL;

        private function __construct()
        {
            
        }
        
        public function __set($name, $value)
        {
            switch($name)
            {
                case 'logfile':
                    if(!file_exists($value) || !is_writeable($value))
                    {
                        throw new Exception("$value is not a valid file path");
                    }
                    $this->logfile = $value;
                    break;

                default:
                    throw new Exception("$name cannot be set");
            }
        }
        
        public function write($message, $file = null, $line = null)
        {
            $message = time() . ' - ' . $message;
            $message .= is_null($file) ? '' : " in $file";
            $message .= is_null($line) ? '' : " on line $line";
            $message .= "\n";
            return file_put_contents($this->logfile, $message, FILE_APPEND);
        }
        
        public static function getInstance()
        {
            if(!self::$instance)
            {
                self::$instance = new self();
            }
            return self::$instance;
        }
        
        private function __clone()
        {
            
        }
    }
}

?>