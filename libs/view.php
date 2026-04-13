<?php
    class View{
        function __construct(){
            
        }

        /* Funcion para dibujar (cargar) el archivo vista */
        function render($nombre){
            require 'views/' . $nombre . '.php';
        }
    }