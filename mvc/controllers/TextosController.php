<?php

namespace CEIT\mvc\controllers
{
    use \CEIT\core;
    use \CEIT\mvc\models;
    use \CEIT\mvc\views;
    
    final class TextosController extends core\AController implements core\ICrud
    {
        public function __construct()
        {
            parent::__construct();
            
            if(empty($this->_model))
            {
                $this->_model = array(
                    'Textos'    =>  new models\TextoModel(),
                    'Carreras'  =>  new models\CarreraModel(),
                    'Niveles'   =>  new models\NivelModel(),
                    'Materias'  =>  new models\MateriaModel(),
                );
            }
            
            if(empty($this->_view))
            {
                $this->_view = new views\TextosView();
            }
        }
        
        public function __destruct()
        {
            if($this->_ajaxRequest)
            {
                $this->_view->json($this->result);
            }
            else if($this->_renderRaw)
            {
                $this->_view->renderRaw($this->result);
            }
            else
            {
                $this->_view->render($this->_template, $this->_dataCollection);
            }
            
            parent::__destruct();
            
            unset($this->result);
        }
        
        public function create()
        {
            $this->_template = BASE_DIR . "/mvc/templates/textos/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                var_dump($_POST);
                
                
            }
            else
            {
                $this->UniqueID = uniqid();
            }
        }

        public function ajax_progress_upload()
        {
            
            $progressKey = ini_get("session.upload_progress.prefix") . ini_get("session.upload_progress.name");
            $current = $_SESSION[$progressKey]["bytes_processed"];
            $total = $_SESSION[$progressKey]["content_length"];
            
            echo $current < $total ? ceil($current / $total * 100) : 100;
        }
        
        public function delete($id)
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/textos/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
            }
        }

        public function detail($id)
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/textos/{$this->_action}.html";
        }

        public function index()
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/textos/{$this->_action}.html";
            
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                $this->paginator_total_pages = 0;
                
                $materia = filter_input(INPUT_POST, "ddlMateria", FILTER_SANITIZE_NUMBER_INT);
                $textos = filter_input(INPUT_POST, "txtTexto", FILTER_SANITIZE_SPECIAL_CHARS);
                
                $modelTexto = new models\TextoModel();
                $modelTexto->_idMateria = $materia;
                
                if(empty($textos))
                {
                    $modelTexto->_descripcion = $textos;
                    
                    $this->resultTextos = $this->_model['Textos']->SelectByIdMateriaAndDescripcion($modelTexto);
                }
                else
                {
                    $this->resultTextos = $this->_model['Textos']->SelectByIdMateria($modelTexto);
                }
            }
            else
            {
                $this->resultTotalTextos = $this->_model['Textos']->SelectCountTotal();
                $this->paginator_total_pages = $this->resultTotalTextos[0]["Total"];

                $this->resultTextos = $this->_model['Textos']->Select();
            }
            
            if(count($this->resultTextos) > 1)
            {
                foreach($this->resultTextos as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/textos/{$this->_action}_table_row.html";
                        $this->table_content .= file_get_contents($filename);

                        foreach($row as $key => $value)
                        {
                            if(!is_array($value))
                            {
                                switch($key)
                                {
                                    case "Activo":
                                        $this->table_content = str_replace('{' . $key . '}', $value == 1 ? "checked=\"checked\"" : "", $this->table_content);
                                        break;
                                    default:
                                        $this->table_content = str_replace('{' . $key . '}', $value, $this->table_content);
                                        break;
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                $this->table_content = "";
            }
            unset($this->resultTextos);
            
            $this->result = $this->_model['Carreras']->Select();
            if(count($this->result) > 1)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/textos/{$this->_action}_combo_carrera.html";
                    $this->combo_carreras .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_carreras = str_replace('{' . $key . '}', $value, $this->combo_carreras);
                        }
                    }
                }
            }
            unset($this->result);
        }

        public function update($id)
        {
            // seteo el template
            $this->_template = BASE_DIR . "/mvc/templates/textos/{$this->_action}.html";
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
            }
        }
        
        public function ajax_get_textos_table_content()
        {
            $this->_renderRaw = true;
            
            if(!empty($_POST))
            {
                //var_dump($_POST);
                
                $return = "";
                
                $modelTexto = new models\TextoModel();
                $modelTexto->_page = filter_input(INPUT_POST, "page", FILTER_SANITIZE_NUMBER_INT);
                $modelTexto->_quantity = filter_input(INPUT_POST, "quantity", FILTER_SANITIZE_NUMBER_INT);
                $this->resultTextos = $this->_model['Textos']->SelectPagination($modelTexto);
                //var_dump($this->resultTextos);
                
                foreach($this->resultTextos as $row)
                {
                    if(is_array($row))
                    {
                        $filename = BASE_DIR . "/mvc/templates/textos/index_table_row.html";
                        $return .= file_get_contents($filename);

                        foreach($row as $key => $value)
                        {
                            if(!is_array($value))
                            {
                                switch($key)
                                {
                                    case "Activo":
                                        $return = str_replace('{' . $key . '}', $value == 1 ? "checked=\"checked\"" : "", $return);
                                        break;
                                    default:
                                        $return = str_replace('{' . $key . '}', $value, $return);
                                        break;
                                }
                            }
                        }
                    }
                }
                
                $this->result = $return;
            }
        }
        
        public function ajax_get_niveles()
        {
            if(!empty($_POST))
            {
                $this->_ajaxRequest = true;
                
                $nivelModel = new models\NivelModel();
                $nivelModel->_idCarrera = filter_input(INPUT_POST, 'idCarrera', FILTER_SANITIZE_NUMBER_INT);
                $this->result = $this->_model['Niveles']->SelectByIdCarrera($nivelModel);
            }
        }
        
        public function ajax_get_materias()
        {
            if(!empty($_POST))
            {
                $this->_ajaxRequest = true;
                
                $materiaModel = new models\MateriaModel();
                $materiaModel->_idNivel = filter_input(INPUT_POST, 'idNivel', FILTER_SANITIZE_NUMBER_INT);
                $this->result = $this->_model['Materias']->SelectByIdNivel($materiaModel);
            }
        }
    }
}

?>