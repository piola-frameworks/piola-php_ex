<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class ReportesController extends core\AController
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    "Reportes"  => new models\ReporteModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\ReportesView();
            }
        }
        
        public function __destruct()
        {
            parent::__destruct();
            
            $this->_view->render($this->_template, $this->_dataCollection);
            
            unset($this->result);
        }
        
        public function index()
        {
            $this->_template = BASE_DIR . "/mvc/templates/reportes/{$this->_action}.xhtml";
        }
        
        public function fotocopias()
        {
            $this->_template = BASE_DIR . "/mvc/templates/reportes/{$this->_action}.html";
            
            $this->Resultado = "";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                if(isset($_POST["btnGenerar"]))
                {
                    $desde = filter_input(INPUT_POST, "txtDesde", FILTER_SANITIZE_SPECIAL_CHARS);
                    $hasta = filter_input(INPUT_POST, "txtHasta", FILTER_SANITIZE_SPECIAL_CHARS);
                    $simple = filter_input(INPUT_POST, "chkSimple", FILTER_SANITIZE_NUMBER_INT);
                    $doble = filter_input(INPUT_POST, "chkDoble", FILTER_SANITIZE_NUMBER_INT);
                    $turno = filter_input(INPUT_POST, "chkTurno", FILTER_SANITIZE_NUMBER_INT);
                    $sistema = filter_input(INPUT_POST, "chkSistema", FILTER_SANITIZE_NUMBER_INT);
                    $especial = filter_input(INPUT_POST, "chkEspecial", FILTER_SANITIZE_NUMBER_INT);
                    $suelta = filter_input(INPUT_POST, "chkSuelta", FILTER_SANITIZE_NUMBER_INT);
                    
                    $modelReporte = new models\ReporteModel();
                    $modelReporte->_desde = $desde;
                    $modelReporte->_hasta = $hasta;
                    $modelReporte->_simple = $simple;
                    $modelReporte->_doble = $doble;
                    $modelReporte->_turno = $turno;
                    $modelReporte->_sistema = $sistema;
                    $modelReporte->_especial = $especial;
                    $modelReporte->_suelta = $suelta;
                    
                    $this->result = $this->_model["Reportes"]->SelectFotocopia($modelReporte);
                    unset($modelReporte);
                    //var_dump($this->result);
                    if(count($this->result) > 0)
                    {
                        $this->Resultado = $this->result[0]["CantPaginas"];
                        //$this->Consulta = $this->result[0]["ConsultaFinal"];
                    }
                }
            }
        }
        
        public function pedidos()
        {
            $this->_template = BASE_DIR . "/mvc/templates/reportes/{$this->_action}.html";
        }
        
        public function caja()
        {
            $this->_template = BASE_DIR . "/mvc/templates/reportes/{$this->_action}.html";
        }
        
        public function facturacion()
        {
            $this->_template = BASE_DIR . "/mvc/templates/reportes/{$this->_action}.html";
        }
        
        public function usuarios()
        {
            $this->_template = BASE_DIR . "/mvc/templates/reportes/{$this->_action}.html";
        }
        
        public function textos()
        {
            $this->_template = BASE_DIR . "/mvc/templates/reportes/{$this->_action}.html";
        }
        
        public function carreras()
        {
            $this->_template = BASE_DIR . "/mvc/templates/reportes/{$this->_action}.html";
        }
    }
}

?>