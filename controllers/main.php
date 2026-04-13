<?php

    class Main extends Controller{
        function __construct(){
            parent::__construct();
        }
        
        function render(){
            $idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 1;
            $eventos = $this->model->getEventosHome();
            $imperdibles = $this->model->getimperdibles($idioma);
            
            $this->view->imperdibles = $imperdibles;
            $this->view->eventos = $eventos;
            $this->view->nombre = "Home";
            $this->view->render('main/index');
        }
    }
