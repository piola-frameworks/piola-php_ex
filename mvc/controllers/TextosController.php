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
                    'Textos'        =>  new models\TextoModel(),
                    'Carreras'      =>  new models\CarreraModel(),
                    'Niveles'       =>  new models\NivelModel(),
                    'Materias'      =>  new models\MateriaModel(),
                    "Contenidos"    =>  new models\ContenidoModel(),
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
                
                if(isset($_POST["btnGuardar"]))
                {
                    $nombre = filter_input(INPUT_POST, "txtNombre", FILTER_SANITIZE_SPECIAL_CHARS);
                    $paginas = filter_input(INPUT_POST, "txtPaginas", FILTER_SANITIZE_NUMBER_INT);
                    $carrera = filter_input(INPUT_POST, "ddlCarrera", FILTER_SANITIZE_NUMBER_INT);
                    $nivel = filter_input(INPUT_POST, "ddlNivel", FILTER_SANITIZE_NUMBER_INT);
                    $materia = filter_input(INPUT_POST, "ddlMateria", FILTER_SANITIZE_NUMBER_INT);
                    $autor = filter_input(INPUT_POST, "txtAutor", FILTER_SANITIZE_SPECIAL_CHARS);
                    $docente = filter_input(INPUT_POST, "txtDocente", FILTER_SANITIZE_SPECIAL_CHARS);
                    $activo = filter_input(INPUT_POST, "chkActivo", FILTER_SANITIZE_STRING);
                    
                    if(!empty($_FILES))
                    {
                        var_dump($_FILES);

                        switch($_FILES['fileToUpload']['error'])
                        {
                            case UPLOAD_ERR_OK:
                                //$cmd = 'FOR /F "tokens=2*" %a IN ("' . str_replace("", "", BASE_DIR) . '\bin\pdfinfo.exe" "' . $_FILES['filArchivo']['tmp_name'] . '" ^| findstr "Pages:") DO ECHO %a';
                                //echo shell_exec($cmd) . "<br />";

                                $modelTP = new models\TextoModel();
                                $modelTP->_creadoPor = $_SESSION['IdUsuario'];
                                $modelTP->_creadoDia = date("Y-m-d H:i:s");
                                $modelTP->_modificadoPor = null;
                                $modelTP->_modificadoDia = null;
                                $modelTP->_codInterno = null;
                                $modelTP->_idMateria = $materia;
                                $modelTP->_idTipoTexto = 4;
                                $modelTP->_idTipoContenido = null;
                                $modelTP->_nombre = $nombre;
                                $modelTP->_autor = $autor;
                                $modelTP->_docente = $docente;
                                $modelTP->_cantPaginas = $paginas; //shell_exec($cmd);
                                $modelTP->_activo = $activo;
                                $this->_lastIdTexto = $this->_model['Textos']->Insert(array($modelTP));

                                // Muevo el archivo al directorio donde van a estar todos los PDFs
                                $uploaddir = BASE_DIR . '/data/texts/asd/';
                                $uploadfile = $uploaddir . $this->_lastIdTexto . ".pdf";//basename($_FILES['filArchivo']['name']);

                                unset($modelTP);

                                if($_FILES['fileToUpload']['type'] != 'application/pdf')
                                {
                                    trigger_error("Me queres hackear la aplicacion web?", E_USER_NOTICE);
                                }

                                if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile))
                                {
                                    //echo "El archivo es válido y fue cargado exitosamente.\n";
                                }
                                else
                                {
                                    echo "¡Posible ataque de carga de archivos!\n";
                                }                    
                                break;

                            case UPLOAD_ERR_INI_SIZE:
                                trigger_error("El archivo subido excede la directiva upload_max_filesize en php.ini.", E_USER_ERROR);
                                break;

                            case UPLOAD_ERR_FORM_SIZE:
                                trigger_error("El archivo subido excede la directiva MAX_FILE_SIZE que fue especificada en el formulario HTML.", E_USER_ERROR);
                                break;

                            case UPLOAD_ERR_PARTIAL:
                                trigger_error("El archivo subido fue sólo parcialmente cargado.", E_USER_ERROR);    
                                break;

                            case UPLOAD_ERR_NO_FILE:
                                trigger_error("Ningún archivo fue subido.", E_USER_WARNING);
                                break;

                            case UPLOAD_ERR_NO_TMP_DIR:
                                trigger_error("Falta la carpeta temporal.", E_USER_ERROR);    
                                break;

                            case UPLOAD_ERR_CANT_WRITE:
                                trigger_error("No se pudo escribir el archivo en el disco.", E_USER_ERROR);    
                                break;

                            case UPLOAD_ERR_EXTENSION:
                                trigger_error("Una extensión de PHP detuvo la carga de archivos.", E_USER_ERROR);    
                                break;

                            default:
                                // Por que entro aca?    
                                break;
                        }
                    }
                }
            }
            
            $this->UniqueID = uniqid();
            $this->UploadVar = ini_get("session.upload_progress.name");
            
            $this->result = $this->_model['Carreras']->Select();
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/textos/combo_carrera.html";
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
            
            $this->result = $this->_model["Contenidos"]->Select();
            if(count($this->result) > 0)
            {
                foreach($this->result as $row)
                {
                    $filename = BASE_DIR . "/mvc/templates/textos/combo_contenido.html";
                    $this->combo_contenidos .= file_get_contents($filename);
                    
                    if(is_array($row))
                    {
                        foreach($row as $key => $value)
                        {
                            $this->combo_contenidos = str_replace('{' . $key . '}', $value, $this->combo_contenidos);
                        }
                    }
                }
            }
            unset($this->result);
        }

        public function ajax_upload_progress()
        {
            if(!empty($_POST))
            {
                var_dump($_POST, $_SESSION, $_FILES);
                
                $progressKey = ini_get("session.upload_progress.prefix") . "frmCrearTexto";
                $current = $_SESSION[$progressKey]["bytes_processed"];
                $total = $_SESSION[$progressKey]["content_length"];

                echo $current < $total ? ceil($current / $total * 100) : 100;
            }
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
                    $filename = BASE_DIR . "/mvc/templates/textos/combo_carrera.html";
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