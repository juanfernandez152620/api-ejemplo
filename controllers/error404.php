<?php
    class Error404 extends Controller{
        function __construct(){
            parent::__construct();
            $this->view->mensaje = "Error al cargar";
        }

        function render(){
            $this->view->render('error404/index');
        }
    }
?>