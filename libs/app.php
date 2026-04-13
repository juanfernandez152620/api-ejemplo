<?php
require_once 'controllers/error404.php';

class App {
    public $PAGES_EXCLUDES = ['api', 'audioguias', 'ejemploPDF'];
    
    function __construct() {
        $url = isset($_GET['url']) ? $_GET['url'] : null; // Guardo lo que hay en la barra de direcciones
        $url = rtrim($url, '/');
        $url = explode('/', $url); // Guardo cada subdominio en un arreglo

        if (empty($url[0])) {
            $this->loadController('header', $url);
            $this->loadController('main', $url);
            $this->loadController('footer', $url);
            return false;
        }

        $archivoController = 'controllers/' . $url[0] . '.php';

        if (file_exists($archivoController)) {
            if (!in_array($url[0], $this->PAGES_EXCLUDES)) {
                $this->loadController('header', $url);
            }

            require_once $archivoController;
            $controller = new $url[0];
            $controller->loadModel($url[0]);
            $nparam = sizeof($url);

            if ($nparam > 1) {
                if ($nparam > 2) {
                    $param = [];
                    for ($i = 2; $i < $nparam; $i++) {
                        array_push($param, $url[$i]);
                    }
                    $controller->{$url[1]}($param);
                } else {
                    $controller->{$url[1]}();
                }
            } else {
                $controller->render();
            }

            if (!in_array($url[0], $this->PAGES_EXCLUDES)) {
                $this->loadController('footer', $url);
            }
            return false;
        } else {
            $this->loadController('header', $url);
            $this->loadController('error404', $url);
            $this->loadController('footer', $url);
        }
    }

    private function loadController($controllerName, $url) {
        $archivoController = 'controllers/' . $controllerName . '.php';
        require_once $archivoController;
        $controller = new $controllerName($url);
        $controller->loadModel($controllerName);
        $controller->render();
    }
}
