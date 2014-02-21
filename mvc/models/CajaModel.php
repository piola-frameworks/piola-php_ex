<?php
namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class CajaModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delCaja");
                array_push($this->_params, array(
                    ':idCaja'  =>  (int)$item->_idCaja,
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
                array_push($this->_sp, "sp_insCaja");
                array_push($this->_params, array(
                    ':creadoPor'    =>  (int)$item->_creadoPor,
                    ':creado'       =>  (string)$item->_creado,
                    ':subTotal'     =>  (float)$item->_subTotal,
                    ':pago'         =>  (float)$item->_pago,
                    ':vuelto'       =>  (float)$item->_vuelto,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if(!empty($model))
            {
                $this->_sp = "sp_sel";
                $this->_params = array(
                    ':idCaja'   =>  (int)$model->_idCaja,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_sel";
                
                return Database::getInstance()->DoQuery($this->_sp);
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
                array_push($this->_sp, "sp_");
                array_push($this->_params, array(
                    ''  =>  $item,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>