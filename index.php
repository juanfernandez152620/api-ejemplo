
    <?php
    require_once 'config/config.php';                     //cargo el archivo config
    require_once 'libs/utils.php';
    require_once 'libs/Mobile_Detect.php';          //cargo el archivo de conexion a la base de datos
    require_once 'libs/auth.php';          //cargo el archivo de conexion a la base de datos
    require_once 'libs/database.php';          //cargo el archivo de conexion a la base de datos
    require_once 'libs/controller.php';        //cargo el archivo general de controlador
    require_once 'libs/view.php';              //cargo el archivo general de vista
    require_once 'libs/model.php';             //cargo el archivo general de modelo
    require_once 'libs/app.php';               //cargo el archivo que determina el comportamiento de la webapp
    //require_once 'libs/cookies.php';               //cargo el archivo que determina el comportamiento de la webapp
    
    function generarTituloSeguro($titulo)
    {
        // Reemplaza "/" con "-" antes de codificar
        $titulo_sanitizado = str_replace("/", "_", $titulo);
        $titulo_sanitizado = str_replace(" ", "-", $titulo);
        // Codifica el título para que sea seguro en una URL
        $titulo_codificado = rawurlencode($titulo_sanitizado);

        return $titulo_codificado;
    }
    
    function decodeTituloSeguro($titulo)
    {
        // Reemplaza "/" con "-" antes de codificar
        $titulo_sanitizado = str_replace("_", "/", $titulo);
        $titulo_sanitizado = str_replace("-", " ", $titulo);
        // Codifica el título para que sea seguro en una URL
        $titulo_codificado = rawurldecode($titulo_sanitizado);

        return $titulo_codificado;
    }

    function convertirArrayAJson(array $arrayDeStrings): string
    {
        $resultado = [];
        $totalElementos = count($arrayDeStrings);

        for ($i = 0; $i < $totalElementos; $i += 2) {
            $clave = $arrayDeStrings[$i];
            // Verifica si existe un valor para la clave actual
            $valor = isset($arrayDeStrings[$i + 1]) ? $arrayDeStrings[$i + 1] : null;
            $resultado[$clave] = $valor;
        }

        // Codifica el array asociativo a JSON con formato "pretty"
        // JSON_UNESCAPED_UNICODE es útil para mostrar caracteres como "ñ" o acentos correctamente.
        return json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }


    $app = new App();                                           //incializo la app
    ?>
