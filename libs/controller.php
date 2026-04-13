<?php
    class Controller{
        function __construct(){
            $this->view = new View();       //Referencia hacia la vista desde el controlador
            
        }
        /* Funcion que asocia un modelo al controlador */
        function loadModel($model){
            $url = 'models/' . $model . 'Model.php';      //Direccion del archivo modelo
            if(file_exists($url)){
                require $url;                                               //Cargo el archivo modelo
                $modelName = $model.'Model';                                
                $this->model = new $modelName();                            //Inicializo el modelo
            }
        }
    }
?>