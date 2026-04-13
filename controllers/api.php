<?php

class Api extends Controller
{
    function __construct()
    {
        $this->jwt = new JwtHandler(constant('JWT-Key'));
        $this->allowedOrigins = ["http://10.15.15.151:3000", "http://10.20.20.151:3000", "http://10.20.20.5:3000"];
        parent::__construct();
    }

    function uploadImages($img, $maxSize, $dir = "public/img/eventos-img")
    {
        $formatoImagen = array('.jpeg', '.jpg', '.png', '.gif', '.JPEG', '.JPG', '.PNG', '.GIF');
        $dirImg = $dir;
        $ImagenTemp = $img['tmp_name'];
        $nombreImagen = basename($img['name']);
        $tamañoImagen = $img['size'];
        $extencionImg = substr($nombreImagen, strrpos($nombreImagen, '.'));

        /* error_log($nombreImagen);
        error_log($extencionImg);
        error_log(strlen($nombreImagen));
        error_log(strlen("186468856_1594462044085637_6329377169600101836_n.j")); */

        if (strlen($nombreImagen) > 100) {
            $_nombreImagen = substr($nombreImagen, 0, 100);
            $nombreImagen = $_nombreImagen . $extencionImg;
        }

        if (in_array($extencionImg, $formatoImagen)) {
            if ($tamañoImagen < $maxSize) {
                if (move_uploaded_file($ImagenTemp, "$dirImg/$nombreImagen")) {
                    return array('status' => 200, 'message' => 'Imagen subida exitosamente.');
                } else {
                    return array('status' => 'error', 'message' => 'Error al subir la imagen.');
                }
            } else {
                return array('status' => 'error', 'message' => 'La imagen es demasiado grande.');
            }
        }
    }

    function cupones()
    {
        // Permitir solicitudes desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Permitir métodos GET, POST, OPTIONS
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // Permitir los encabezados que el cliente incluya en la solicitud
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        // Establece la respuesta como JSON
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Recibe el ID desde la solicitud GET
            $codigo = $_GET['codigo'];
            // Convertir el código completo a cadena
            $codigoCompletoStr = (string)$codigo;
            // Determinar la longitud del número

            $idParte = substr($codigoCompletoStr, 0, -5);
            $codigoParte = substr($codigoCompletoStr, -5);

            //Enviar y verificar codigo
            $totalCupones = $this->model->getCupon($idParte, $codigoParte);

            // Verifica si se encontraron resultados
            if (count($totalCupones) > 0) {
                echo json_encode(array('status' => 200, 'result' => $totalCupones[0], 'codigo' => $idParte . "-" .  $codigoParte));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'No se encontraron cupones para el código proporcionado.', 'codigo' => $idParte . "-" .  $codigoParte));
            }
        }
    }

    function imagenes()
    {
        // Verificar el origen de la solicitud usando el Origin
        $origin = isset(apache_request_headers()["Origin"]) ? apache_request_headers()["Origin"] : null;
        if ($origin && in_array($origin, $this->allowedOrigins)) {
            // El Origin está en la lista de orígenes permitidos, establecer Access-Control-Allow-Origin
            header("Access-Control-Allow-Origin: $origin");
        } else {
            // El Origin no está en la lista de orígenes permitidos, devolver un error
            echo json_encode(array('status' => 401, 'message' => 'El origen de la solicitud no está permitido.'));
            return;
        }
        header('Access-Control-Allow-Credentials: true');
        // Permitir métodos POST y encabezados específicos
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');

        // Establecer la respuesta como JSON
        header('Content-Type: application/json');

        // Verificar si se ha enviado el encabezado de autorización
        $jwt_token = $_COOKIE['token'];
        try {
            $jwtValidation = $this->jwt->validateJwtToken($jwt_token);
            if ($jwtValidation["status"] === "error") {
                echo json_encode(array('status' => 401, 'message' => $jwtValidation['message']));
                return;
            }
        } catch (Exception $e) {
            echo json_encode(array('status' => 401, 'message' => 'Token inválido', 'cook' => $_COOKIE));
            return;
        }

        // Verificar si se ha enviado un archivo
        if (!isset($_FILES['imagen'])) {
            echo json_encode(array('status' => 401, 'message' => 'No se recibió ninguna imagen.'));
            return;
        }

        // Procesar la subida de la imagen
        $result = $this->uploadImages($_FILES['imagen'], 500000); // Tamaño máximo de 500kb (en bytes)
        if ($result['status'] == 200) {
            echo json_encode(array('status' => 200, 'message' => 'Imagen subida exitosamente.'));
        } else {
            echo json_encode(array('status' => 401, 'message' => $result['message']));
        }
    }

    ///////////////////////ITINERARIOS///////////////////////////////////

    function destinos()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $params = [
                $_GET['p_busqueda'],
                $_GET['p_lat'],
                $_GET['p_lon'],
                $_GET['p_distancia'],
                $_GET['p_circuito'],
                $_GET['p_localidad'],
                $_GET['p_offset']
            ];

            $destinos = $this->model->getDestinos($params);

            echo json_encode(["status" => 200, "result" => $destinos], JSON_PRETTY_PRINT);
        }
    }

    function itinerarios()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!isset($_GET['id_itinerario'])) {
                echo json_encode(["status" => "error", "message" => "Falta parámetro id_itinerario"], JSON_PRETTY_PRINT);
                return;
            }

            $id_itinerario = $_GET['id_itinerario'];
            $itinerario = $this->model->getItinerarioPorID($id_itinerario);

            echo json_encode(["status" => 200, "result" => $itinerario], JSON_PRETTY_PRINT);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jsonData = file_get_contents("php://input");
            $id_itinerario = $this->model->insertarItinerario($jsonData);

            echo json_encode(["status" => 200, "message" => "Itinerario insertado", "id_itinerario" => $id_itinerario], JSON_PRETTY_PRINT);
        }
    }

    ///////////////////////FIN ITINERARIOS///////////////////////////////////

    // articulo singular

    function articulo($idArtc) ////
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['id']) || isset($idArtc[0])) {
                $id = isset($_GET['id']) ? [$_GET['id'][0]] : [$idArtc[0]];
                $articulos = $this->model->getArticulo_Id($id[0]);
                if (isset($articulos[0])) {
                    $articulo = $articulos[0];
                    echo json_encode(["status" => 200, "result" => $articulo], JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(["status" => 400, "result" => 'El articulo no existe'], JSON_PRETTY_PRINT);
                }
            } else {
                echo json_encode(["status" => 400, "result" => 'Error no id'], JSON_PRETTY_PRINT);
            }
        } else {
            echo json_encode(["status" => 400, "result" => 'Error method error'], JSON_PRETTY_PRINT);
        }
    }

    function galeria_art($_idArt) ////
    {
        // Permitir solicitudes desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Permitir métodos GET, POST, OPTIONS
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // Permitir los encabezados que el cliente incluya en la solicitud
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        // Establece la respuesta como JSON
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Capturás los parámetros
            $idArt = isset($_GET['id']) ? $_GET['id'] : (isset($_idArt[0]) ? $_idArt[0] : null);
            $idioma = isset($_GET['idioma']) ? $_GET['idioma'] : null;
            $galeria = $this->model->getGaleria($idArt);
            // Verifica si se encontraron resultados
            if ($galeria) {
                echo json_encode(array('status' => 200, 'result' => $galeria));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Hubo un error inesperado con la consulta de galerias'));
            }
        }
    }

    function pdfs_art($_idArt) ////
    {
        // Permitir solicitudes desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Permitir métodos GET, POST, OPTIONS
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // Permitir los encabezados que el cliente incluya en la solicitud
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        // Establece la respuesta como JSON
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Capturás los parámetros
            $idArt = isset($_GET['id']) ? $_GET['id'] : (isset($_idArt[0]) ? $_idArt[0] : null);
            $idioma = isset($_GET['idioma']) ? $_GET['idioma'] : null;

            $pdfs = $this->model->getPDFs($idArt);
            // Verifica si se encontraron resultados
            if ($pdfs) {
                echo json_encode(array('status' => 200, 'result' => $pdfs));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Hubo un error inesperado con la consulta de pdfs'));
            }
        }
    }

    // articulos BUSQUEDA

    // articulos todos
    function articulos() ////
    {
        // Permitir solicitudes desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Permitir métodos GET, POST, OPTIONS
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // Permitir los encabezados que el cliente incluya en la solicitud
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        // Establece la respuesta como JSON
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Capturás los parámetros

            $articulo = $this->model->getArticulo_All();

            /* var_dump($articulo); */
            // Verifica si se encontraron resultados
            if ($articulo) {
                echo json_encode(array('status' => 200, 'result' => $articulo, "ok" => true));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Hubo un error inesperado con la consulta de articulo', 'result' => $articulo));
            }
        }
    }

    function buscador() ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET'); // Solo permitimos GET para la búsqueda
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // Verificar si los parámetros obligatorios están presentes
            if (true) {

                // Recuperar y castear/sanitizar los parámetros de la URL
                $idIdioma = isset($_GET['idioma']) ? (string)$_GET['idioma'] : 'ES'; // Asegurar que sea entero
                $busqueda = isset($_GET['busqueda']) ? (string)$_GET['busqueda'] : ''; // Asegurar que sea string (podrías añadir sanitización extra si es necesario)

                // Recuperar parámetros opcionales con valores por defecto
                $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 10; // Default 10
                $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0; // Default 0
                $lol    = isset($_GET['localidad'])    ? (int)$_GET['localidad']    : null; // Default 0 (o el valor que consideres apropiado)

                // Validar que limite y offset no sean negativos (opcional pero recomendado)
                if ($limite < 0) {
                    $limite = 10;
                }
                if ($offset < 0) {
                    $offset = 0;
                }

                // Llamar a la función del modelo con los parámetros obtenidos
                // Asegúrate de que el objeto $this->model esté disponible en este contexto
                try {
                    $resultados = $this->model->getArticulo_Busqueda($idIdioma, $busqueda, $limite, $offset, $lol);

                    // Devolver los resultados en formato JSON
                    echo json_encode(["status" => 200, "result" => $resultados], JSON_PRETTY_PRINT);
                } catch (Exception $e) {
                    // Capturar posibles errores durante la ejecución de la búsqueda
                    http_response_code(500); // Internal Server Error
                    echo json_encode(["status" => 500, "result" => 'Error interno del servidor al realizar la búsqueda: ' . $e->getMessage()], JSON_PRETTY_PRINT);
                }
            } else {
                // Si faltan parámetros obligatorios, devolver un error 400
                http_response_code(400); // Bad Request
                echo json_encode(["status" => 400, "result" => 'Error: Faltan parámetros requeridos (idIdioma, busqueda)'], JSON_PRETTY_PRINT);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode(["status" => 405, "result" => 'Error: Método no permitido. Utilice GET.'], JSON_PRETTY_PRINT);
        }
    }

    //Buscador_Destinos

    function buscador_destinos() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET'); // Solo permitimos GET para la búsqueda
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //      IN `p_busqueda` VARCHAR(255), 
            //      IN `p_limite` INT, 
            //      IN `p_offset` INT

            // Recuperar y castear/sanitizar los parámetros de la URL
            $busqueda = isset($_GET['busqueda']) ? (string)$_GET['busqueda'] : 'NULL'; // Asegurar que sea string (podrías añadir sanitización extra si es necesario)
            $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 10; // Default 10
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0; // Default 0
            $idioma = isset($_GET['idioma']) ? (string)$_GET['idioma'] : 'ES';

            // Validar que limite y offset no sean negativos (opcional pero recomendado)
            if ($limite < 0) {
                $limite = 10;
            }
            if ($offset < 0) {
                $offset = 0;
            }

            // Llamar a la función del modelo con los parámetros obtenidos
            // Asegúrate de que el objeto $this->model esté disponible en este contexto
            try {
                $resultados = $this->model->getDestino_Busqueda($busqueda, $limite, $offset, $idioma);

                // Devolver los resultados en formato JSON
                echo json_encode(["status" => 200, "result" => $resultados], JSON_PRETTY_PRINT);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución de la búsqueda
                http_response_code(500); // Internal Server Error
                echo json_encode(["status" => 500, "result" => 'Error interno del servidor al realizar la búsqueda: ' . $e->getMessage()], JSON_PRETTY_PRINT);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode(["status" => 405, "result" => 'Error: Método no permitido. Utilice GET.'], JSON_PRETTY_PRINT);
        }
    }

    // imperdibles

    function imperdibles() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET'); // Solo permitimos GET para obtener datos
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 'ES';

            // No necesitamos una validación específica como if ($_GET['id']) del ejemplo,
            // ya que el único parámetro es opcional y siempre tendrá un valor (el de la URL o el default).

            // Llamar a la función del modelo con el parámetro de idioma
            // Asegúrate de que el objeto $this->model esté disponible
            try {
                // Llama a la nueva función del modelo 'getImperdibles'
                $resultados = $this->model->getImperdibles($idioma);

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode(["status" => 200, "result" => $resultados], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); // Añadido JSON_UNESCAPED_UNICODE para mejor visualización de tildes/eñes

            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución de la búsqueda en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode(["status" => 500, "result" => 'Error interno del servidor al obtener los imperdibles: ' . $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode(["status" => 405, "result" => 'Error: Método no permitido. Utilice GET.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // blog busqueda / total

    function blog() ////
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // --- Recuperar Parámetros (sin cambios) ---
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            if ($limit < 0) {
                $limit = 10;
            }
            if ($offset < 0) {
                $offset = 0;
            }
            $busqueda = isset($_GET['busqueda']) ? (string)$_GET['busqueda'] : '';
            $idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 'ES';
            // --- Fin Recuperar Parámetros ---

            try {
                // --- INICIO: Cambios ---
                // 1. Obtener los resultados paginados (como antes)
                $resultados = $this->model->getBlog($limit, $offset, $busqueda, $idioma);

                // 2. Obtener el total de artículos que coinciden con la búsqueda y el idioma (SIN paginación)
                $totalArticulos = $this->model->getBlog_Total($busqueda, $idioma);
                // --- FIN: Cambios ---

                // Devolver los resultados Y el total en formato JSON
                http_response_code(200); // OK
                // Añadir la clave 'total' al array codificado
                echo json_encode([
                    "status" => 200,
                    "total" => $totalArticulos,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los artículos del blog: ' . $e->getMessage(),
                    "total" => 0 // Podrías poner 0 o null en caso de error
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.',
                "total" => 0
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // Blog Destacados

    function blogDestacados() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET'); // Solo GET para obtener datos
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // Recuperar el parámetro opcional 'idioma' con un valor por defecto (ej: 1 para 'ES').
            // El modelo (getBlogDestacados) deberá manejar la conversión si es necesario.
            $idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 'ES';

            // No se necesita validación extra de parámetros requeridos aquí.

            // Llamar a la función del modelo correspondiente
            try {
                // Llama a la nueva función del modelo 'getBlogDestacados'
                $resultados = $this->model->getBlogDestacados($idioma);

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los artículos destacados del blog: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // subsecciones

    function subseccion($_idSecc) // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET'); // Solo GET para obtener datos
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        var_dump("subseccion");

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            var_dump("subseccion2");
            // Verificar si los parámetros REQUERIDOS están presentes en la URL
            if (isset($_GET['subseccion']) || isset($_idSecc[0])) {
                var_dump("subseccion3");
                // Recuperar los parámetros
                $subseccionParam = isset($_GET['subseccion']) ? $_GET['subseccion'] : $_idSecc[0]; // Puede ser ID o nombre/slug
                var_dump("subseccion4: ");
                // Llamar a la función del modelo correspondiente
                try {
                    var_dump("subseccion5");
                    // Llama a la nueva función del modelo 'getArticulosPorSubseccion'
                    $articulos = $this->model->getArticulosPorSubseccion($subseccionParam);
                    var_dump("subseccion5.5");
                    $subseccion = $this->model->getSubseccion_Id($subseccionParam);
                    var_dump("subseccion6");
                    // Devolver los resultados en formato JSON
                    http_response_code(200); // OK
                    if (isset($subseccion)) {
                        echo json_encode([
                            "status" => 200,
                            'result' => ["articulos" => $articulos, "subseccion" => $subseccion]
                        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    } else {
                        // Capturar si no encuentra la subsección
                        http_response_code(404); // Not Found
                        echo json_encode([
                            "status" => 404,
                            "result" => 'Error: Subsección no encontrada'
                        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }
                    
                } catch (Exception $e) {
                    // Capturar posibles errores durante la ejecución en el modelo
                    http_response_code(500); // Internal Server Error
                    echo json_encode([
                        "status" => 500,
                        "result" => 'Error interno del servidor al obtener los artículos por subsección: ' . $e->getMessage()
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }
            } else {
                // Si faltan parámetros requeridos, devolver un error 400
                http_response_code(400); // Bad Request
                echo json_encode([
                    "status" => 400,
                    "result" => 'Error: Faltan parámetros requeridos (idIdioma, Subseccion)'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function subseccion_all() ////
    {
        // Permitir solicitudes desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Permitir métodos GET, POST, OPTIONS
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // Permitir los encabezados que el cliente incluya en la solicitud
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        // Establece la respuesta como JSON
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Capturás los parámetros

            $subsecciones = $this->model->getSubSecciones_All();

            /* var_dump($subsecciones); */
            // Verifica si se encontraron resultados
            if ($subsecciones) {
                echo json_encode(array('status' => 200, 'result' => $subsecciones, "ok" => true));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Hubo un error inesperado con la consulta de articulo', 'result' => $articulo));
            }
        }
    }

    // Subsecciones por Circuito

    function subseccion_circuito() ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET'); // Solo GET para obtener datos
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // Recuperar el parámetro opcional 'idioma' con un valor por defecto (ej: 1 para 'ES').
            // El modelo (getBlogDestacados) deberá manejar la conversión si es necesario.
            $idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 'ES';
            $Circuito = isset($_GET['circuito']) ? $_GET['circuito'] : 1;

            // No se necesita validación extra de parámetros requeridos aqui.

            // Llamar a la función del modelo correspondiente
            try {
                // Llama a la nueva función del modelo 'getBlogDestacados'
                $resultados = $this->model->getSubSeccionesCircuito($Circuito, $idioma);

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los artículos destacados: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // navbar

    function navbar() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET'); // Solo GET para obtener datos
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // Recuperar el parámetro opcional 'idioma' con un valor por defecto (ej: 1 para 'ES').
            // El modelo (getBlogDestacados) deberá manejar la conversión si es necesario.
            $idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 'ES';

            // No se necesita validación extra de parámetros requeridos aquí.

            // Llamar a la función del modelo correspondiente
            try {
                // Llama a la nueva función del modelo 'getBlogDestacados'
                $resultados = $this->model->getNavbar($idioma);

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los artículos destacados del blog: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function navbar_menu() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET'); // Solo GET para obtener datos
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // Recuperar el parámetro opcional 'idioma' con un valor por defecto (ej: 1 para 'ES').
            // El modelo (getBlogDestacados) deberá manejar la conversión si es necesario.
            $idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 'ES';

            // No se necesita validación extra de parámetros requeridos aquí.

            // Llamar a la función del modelo correspondiente
            try {
                // Llama a la nueva función del modelo 'getBlogDestacados'
                $resultados = $this->model->getNavbarMenu($idioma);

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los artículos destacados del blog: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // Hoteles

    function hoteles() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET'); // Solo GET para obtener datos
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // --- Recuperar Parámetros con Defaults ---
            $busqueda = isset($_GET['search']) ? (string)$_GET['search'] : '';
            $categoria = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
            $estrellas = isset($_GET['estrellas']) ? (int)$_GET['estrellas'] : 0; // $_GET es case-sensitive con las keys
            $lol = isset($_GET['localidad']) ? (int)$_GET['localidad'] : 0;
            $serv = isset($_GET['serv']) ? (string)$_GET['serv'] : '';

            // Para parámetros que pueden ser NULL explícitamente
            $lat = isset($_GET['lat']) && $_GET['lat'] !== '' ? (float)$_GET['lat'] : 'NULL';
            $lon = isset($_GET['lon']) && $_GET['lon'] !== '' ? (float)$_GET['lon'] : 'NULL';
            $distancia = isset($_GET['distancia']) && $_GET['distancia'] !== '' ? (float)$_GET['distancia'] : 'NULL';

            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            if ($offset < 0) {
                $offset = 0;
            } // Asegurar que no sea negativo

            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            if ($limit < 1) {
                $limit = 10;
            } // Asegurar un límite mínimo razonable
            // Podrías añadir un límite máximo si lo deseas: if ($limit > 100) { $limit = 100; }

            // --- Fin Recuperar Parámetros ---

            // No se necesita validación de parámetros "requeridos" aquí
            // ya que todos tienen valores por defecto.

            // Llamar a la función del modelo correspondiente
            try {
                // Llama a la nueva función del modelo 'getHoteles'
                $resultados = $this->model->getHoteles($busqueda, $categoria, $estrellas, $lol, $serv, $lat, $lon, $distancia, $offset, $limit);

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los hoteles: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function alojamientos_filters() ////
    {
        // Permitir solicitudes desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Permitir métodos GET, POST, OPTIONS
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // Permitir los encabezados que el cliente incluya en la solicitud
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        // Establece la respuesta como JSON
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $alojamientosLocacalidades = $this->model->getLocalidades();
            $alojamientosCategorias = $this->model->getCategoriasAlojamientos();
            // Verifica si se encontraron resultados
            if (isset($alojamientosCategorias)) {
                echo json_encode(array('status' => 200, 'categorias' => $alojamientosCategorias, 'localidades' => $alojamientosLocacalidades));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Hubo un error inesperado con la consulta alojamientos', 'result' => $alojamientosCategorias));
            }
        }
    }

    // Colectivos

    function colectivos() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET'); // Solo GET para obtener datos
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // Llamar a la función del modelo correspondiente
            try {
                // Llama a la nueva función del modelo 'getBlogDestacados'
                $resultados = $this->model->getColectivos();

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los artículos destacados del blog: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // Autos
    function autos() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        // Solo GET para obtener datos, ya que POST no se mencionó como necesario para este endpoint
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // --- Recuperar Parámetros con Defaults ---
            $nombre = isset($_GET['nombre']) ? (string)$_GET['nombre'] : '';
            $lol = isset($_GET['localidad']) ? (int)$_GET['localidad'] : 'NULL';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            if ($limit < 1) { // Asegurar un límite mínimo razonable, por ejemplo 1.
                $limit = 10;
            }
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            if ($offset < 0) { // Asegurar que no sea negativo
                $offset = 0;
            }
            // --- Fin Recuperar Parámetros ---

            // Llamar a la función del modelo correspondiente
            try {
                // Llama a la nueva función del modelo 'getAutos'
                // El orden de los parámetros aquí debe coincidir con la definición de getAutos en el modelo
                $resultados = $this->model->getAutos($nombre, $lol, $limit, $offset);

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener las agencias de autos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    //PRESTADORES
    function prestadores() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        // Solo GET para obtener datos
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // --- Recuperar Parámetros con Defaults ---
            // Los nombres de las variables en PHP coincidirán con los parámetros IN del SP para claridad.
            $busqueda = isset($_GET['busqueda']) ? (string)$_GET['busqueda'] : '';
            $localidad_id = isset($_GET['localidad']) ? (int)$_GET['localidad'] : 'NULL';
            $actividad_id = isset($_GET['actividad']) ? (int)$_GET['actividad'] : 'NULL';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            if ($limit < 1) {
                $limit = 10; // Asegurar un límite mínimo razonable
            }
            // Opcional: establecer un límite máximo
            // if ($limit > 100) { $limit = 100; }

            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            if ($offset < 0) {
                $offset = 0; // Asegurar que no sea negativo
            }
            // --- Fin Recuperar Parámetros ---

            // Llamar a la función del modelo correspondiente
            try {
                // Llama a la nueva función del modelo 'getPrestadores'
                // El orden de los parámetros aquí debe coincidir con la definición de getPrestadores en el modelo
                $resultados = $this->model->getPrestadores($busqueda, $localidad_id, $actividad_id, $limit, $offset);

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los prestadores: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // AGENCIAS
    function agencias() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        // Solo GET para obtener datos
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // --- Recuperar Parámetros con Defaults ---
            $nombre = isset($_GET['nombre']) ? (string)$_GET['nombre'] : '';
            $localidad = isset($_GET['localidad']) ? (int)$_GET['localidad'] : 'NULL';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            if ($limit < 1) {
                $limit = 10; // Asegurar un límite mínimo razonable, o el default si es inválido.
            }
            // Opcional: establecer un límite máximo si se desea
            // if ($limit > 200) { $limit = 200; }

            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            if ($offset < 0) {
                $offset = 0; // Asegurar que no sea negativo
            }
            // --- Fin Recuperar Parámetros ---

            // Llamar a la función del modelo correspondiente
            try {
                // Llama a la nueva función del modelo 'getAgencias'
                // El orden de los parámetros aquí debe coincidir con la definición de getAgencias en el modelo
                $resultados = $this->model->getAgencias($nombre, $localidad, $limit, $offset);

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener las agencias: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // guias
    function guias() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        // Solo GET para obtener datos
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // --- Recuperar Parámetros con Defaults ---
            $nombre = isset($_GET['nombre']) ? (string)$_GET['nombre'] : '';
            $localidad = isset($_GET['localidad']) ? (int)$_GET['localidad'] : 'NULL';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            if ($limit < 1) {
                $limit = 10; // Asegurar un límite mínimo razonable, o el default si es inválido.
            }
            // Opcional: establecer un límite.maxcdn si se desea
            // if ($limit > 200) { $limit = 200; }

            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            if ($offset < 0) {
                $offset = 0; // Asegurar que no sea negativo
            }
            // --- Fin Recuperar Parámetros ---

            // Llamar a la función del modelo correspondiente
            try {
                // Llama a la nueva función del modelo 'getGuias'
                // El orden de los parámetros aquí debe coincidir con la definición de getGuias en el modelo
                $resultados = $this->model->getGuias($nombre, $localidad, $limit, $offset);

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener las guias: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // eventos
    function eventos() ////
    {

        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $fechaHoy = date("Y-m-d");
            // --- Recuperar Parámetros con Defaults ---
            // Para parámetros que pueden ser NULL si no se envían o están vacíos:
            //$FechaIni = (isset($_GET['FechaIni']) && $_GET['FechaIni'] !== '') ? (string)$_GET['FechaIni'] : "'$fechaHoy'";
            $FechaIni = (isset($_GET['FechaIni']) && $_GET['FechaIni'] !== '') ? (string)$_GET['FechaIni'] : null;
            $FechaFin = (isset($_GET['FechaFin']) && $_GET['FechaFin'] !== '') ? (string)$_GET['FechaFin'] : null;
            $Dia = (isset($_GET['Dia']) && $_GET['Dia'] !== '') ? (string)$_GET['Dia'] : null;
            $Busqueda = (isset($_GET['Busqueda']) && $_GET['Busqueda'] !== '') ? (string)$_GET['Busqueda'] : '';

            // Para parámetros numéricos con defaults específicos:
            // Si se enía un string acío para estos, (float)'' es 0.0, (int)'' es 0.
            $p_latitud = isset($_GET['p_latitud']) ? (float)$_GET['p_latitud'] : 0.0;
            $p_longitud = isset($_GET['p_longitud']) ? (float)$_GET['p_longitud'] : 0.0;
            $p_distancia = isset($_GET['p_distancia']) ? (int)$_GET['p_distancia'] : 999999;

            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            if ($offset < 0) {
                $offset = 0;
            }
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            if ($limit < 1) {
                $limit = 10;
            }
            // --- Fin Recuperar Parámetros ---

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getEventos(
                    $FechaIni !== null ? "'$FechaIni'" : 'null',
                    $FechaFin !== null ? "'$FechaFin'" : 'null',
                    $Dia !== null ? "'$Dia'" : 'null',
                    $Busqueda!== null ? "'$Busqueda'" : 'null',
                    /* $p_latitud,
                    $p_longitud,
                    $p_distancia, */
                    $offset,
                    $limit
                );
                $resultadosAll = $this->model->getTotalEventos(
                    $FechaIni !== null ? "'$FechaIni'" : 'null',
                    $FechaFin !== null ? "'$FechaFin'" : 'null',
                    $Dia !== null ? "'$Dia'" : 'null',
                    $Busqueda!== null ? "'$Busqueda'" : 'null',
                    /* $p_latitud,
                    $p_longitud,
                    $p_distancia, */
                    $offset,
                    $limit
                );

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados,
                    "total" => $resultadosAll
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los eventos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function eventos_destacados() // Nombre de la nueva función ////
    {
        // Establecer las cabeceras para permitir el acceso CORS y definir el tipo de contenido
        header('Access-Control-Allow-Origin: *');
        // Solo GET para obtener datos
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                // Llama a la nueva función del modelo 'getAgencias'
                // El orden de los parámetros aquí debe coincidir con la definición de getAgencias en el modelo
                $resultados = $this->model->getEventosHome();

                // Devolver los resultados en formato JSON
                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                // Capturar posibles errores durante la ejecución en el modelo
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener las agencias: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Si el método no es GET, devolver un error 405
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // Envento Singular
    function evento() ////
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o están vacíos:
            $id = (isset($_GET['id']) && $_GET['id'] !== '') ? (int)$_GET['id'] : 'NULL';

            if (isset($_GET['id'])) {
                // Llamar a la función del modelo correspondiente
                try {
                    $resultados = $this->model->getEvento($id);

                    http_response_code(200); // OK
                    echo json_encode([
                        "status" => 200,
                        "result" => $resultados
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } catch (Exception $e) {
                    http_response_code(500); // Internal Server Error
                    echo json_encode([
                        "status" => 500,
                        "result" => 'Error interno del servidor al obtener el evento: ' . $e->getMessage()
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }
            } else {
                http_response_code(400); // Bad Request
                echo json_encode([
                    "status" => 400,
                    "result" => 'Error: Falta el parámetro id.'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function session() ////
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros:
            $direc = (isset($_GET['direccion']) && $_GET['direccion'] !== '') ? $_GET['direccion'] : 'NULL';
            $lat = (isset($_GET['lat']) && $_GET['lat'] !== '') ? $_GET['lat'] : 'NULL';
            $lon = (isset($_GET['lon']) && $_GET['lon'] !== '') ? $_GET['lon'] : 'NULL';
            $idioma = (isset($_GET['idioma']) && $_GET['idioma'] !== '') ? $_GET['idioma'] : 'NULL';
            $busqueda = (isset($_GET['busqueda']) && $_GET['busqueda'] !== '') ? $_GET['busqueda'] : 'NULL';

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getSession($direc, $lat, $lon, $idioma, $busqueda);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener el evento: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function visita() ////
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros:
            $id = (isset($_GET['id']) && $_GET['id'] !== '') ? (int)$_GET['id'] : 'NULL';
            $direc = (isset($_GET['direccion']) && $_GET['direccion'] !== '') ? $_GET['direccion'] : 'NULL';
            $lat = (isset($_GET['lat']) && $_GET['lat'] !== '') ? $_GET['lat'] : 'NULL';
            $lon = (isset($_GET['lon']) && $_GET['lon'] !== '') ? $_GET['lon'] : 'NULL';
            $idioma = (isset($_GET['idioma']) && $_GET['idioma'] !== '') ? $_GET['idioma'] : 'NULL';
            $busqueda = (isset($_GET['busqueda']) && $_GET['busqueda'] !== '') ? $_GET['busqueda'] : 'NULL';

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getVisita($id, $direc, $lat, $lon, $idioma, $busqueda);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener el evento: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // ITINERARIO -----------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------------

    function hoteles_it() ////
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o están vacíos:
            $Circuito = (isset($_GET['circuito']) && $_GET['circuito'] !== '') ? (int)$_GET['circuito'] : 1;
            $limit = (isset($_GET['limit']) && $_GET['limit'] !== '') ? (int)$_GET['limit'] : 10;
            $offset = (isset($_GET['offset']) && $_GET['offset'] !== '') ? (int)$_GET['offset'] : 0;

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getHotelesItinerario($Circuito, $limit, $offset);
                $report = $this->model->getHotelesRegistro($Circuito);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los hoteles: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    //Prestadores Itinerario
    function prestadores_it()
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o está vacíos:
            $Circuito = (isset($_GET['circuito']) && $_GET['circuito'] !== '') ? (int)$_GET['circuito'] : 1;
            $limit = (isset($_GET['limit']) && $_GET['limit'] !== '') ? (int)$_GET['limit'] : 10;
            $offset = (isset($_GET['offset']) && $_GET['offset'] !== '') ? (int)$_GET['offset'] : 0;

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getPrestadoresItinerario($Circuito, $offset, $limit);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los prestadores: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    //guias itinerario
    function guias_it()
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o está vacíos:
            $Circuito = (isset($_GET['circuito']) && $_GET['circuito'] !== '') ? (int)$_GET['circuito'] : 1;
            $limit = (isset($_GET['limit']) && $_GET['limit'] !== '') ? (int)$_GET['limit'] : 10;
            $offset = (isset($_GET['offset']) && $_GET['offset'] !== '') ? (int)$_GET['offset'] : 0;

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getGuiasItinerario($Circuito, $offset, $limit);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los guias: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // Itinenario
    function itinerario() 
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o está vacíos:
            $id = (isset($_GET['id']) && $_GET['id'] !== '') ? (int)$_GET['id'] : 1;

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getItinerario($id);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener el itinerario: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // Eventos Itinerario
    function eventos_it() 
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o está vacíos:
            $Circuito = (isset($_GET['circuito']) && $_GET['circuito'] !== '') ? (int)$_GET['circuito'] : 'NULL';
            $limit = (isset($_GET['limit']) && $_GET['limit'] !== '') ? (int)$_GET['limit'] : 10;
            $offset = (isset($_GET['offset']) && $_GET['offset'] !== '') ? (int)$_GET['offset'] : 0;
            $localidad = isset($_GET['localidad']) ? (int)$_GET['localidad'] : 'NULL';

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getEventosItinerario($Circuito, $localidad, $offset, $limit);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los eventos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function colectivos_it() 
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o está vacíos:

            $localidad = (isset($_GET['localidad']) && $_GET['localidad'] !== '') ? (int)$_GET['localidad'] : 'NULL';
            $circuito = (isset($_GET['circuito']) && $_GET['circuito'] !== '') ? (int)$_GET['circuito'] : 'NULL';
            $busqueda = (isset($_GET['busqueda']) && $_GET['busqueda'] !== '') ? (string)$_GET['busqueda'] : 'NULL';
            $limit = (isset($_GET['limit']) && $_GET['limit'] !== '') ? (int)$_GET['limit'] : 9;
            $offset = (isset($_GET['offset']) && $_GET['offset'] !== '') ? (int)$_GET['offset'] : 0;

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getColectivosItinerario($localidad, $circuito, $busqueda, $offset, $limit);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los colectivos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    //lugares
    function lugares()
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getLugares();

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los lugares: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // circuitos
    function circuitos()
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getCircuitos();

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los circuitos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // NO OFICINAL MANTENER SOLO EN LA 10
    function itinenario_estadisticas() 
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getItinenarioEstadisticas();
                $array_resultado = $resultados[0];
                
                // 3. Decodificas el string que se encuentra dentro de la propiedad 'resultado_json'.
                //    Esto lo convierte de un string a un objeto/array PHP.
                $resultado_final = json_decode($array_resultado['resultado_json']);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultado_final
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los circuitos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // LISTAS
    function listas() 
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o está vacíos:
            $lista = (isset($_GET['lista']) && $_GET['lista'] !== '') ? (int)$_GET['lista'] : 1;
            $limit = (isset($_GET['limit']) && $_GET['limit'] !== '') ? (int)$_GET['limit'] : 9;
            $offset = (isset($_GET['offset']) && $_GET['offset'] !== '') ? (int)$_GET['offset'] : 0;

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getListas($lista, $limit, $offset);
                $lista = $this->model->getLista($lista);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => [
                        "lista" => $lista,       // Atributos de la lista principal
                        "cards" => $resultados   // Array de resultados (tarjetas)
                    ]
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los circuitos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    //IA -- ARTICULOS ESPECIALES

    function producto_localidad()
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o está vacíos:
            $localidad = (isset($_GET['localidad']) && $_GET['localidad'] !== '') ? (int)$_GET['localidad'] : 1;
            $limit = (isset($_GET['limit']) && $_GET['limit'] !== '') ? (int)$_GET['limit'] : 9;
            $offset = (isset($_GET['offset']) && $_GET['offset'] !== '') ? (int)$_GET['offset'] : 0;

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getProductosLocalidad($localidad, $limit, $offset);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los circuitos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function articulos_localidad()
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o está vacíos:
            $localidad = (isset($_GET['localidad']) && $_GET['localidad'] !== '') ? (int)$_GET['localidad'] : 1;

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getArticulosLocalidad($localidad);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los circuitos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function destinos_localidad()
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Para parámetros que pueden ser NULL si no se envían o está vacíos:
            $localidad = (isset($_GET['localidad']) && $_GET['localidad'] !== '') ? (int)$_GET['localidad'] : 1;
            $limit = (isset($_GET['limit']) && $_GET['limit'] !== '') ? (int)$_GET['limit'] : 9;
            $offset = (isset($_GET['offset']) && $_GET['offset'] !== '') ? (int)$_GET['offset'] : 0;

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getDestinosLocalidad($localidad, $limit, $offset);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los circuitos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function wayki()
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getWaikyFunFact();

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al obtener los circuitos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    /**
    * Endpoint para crear un nuevo itinerario 'touch'.
    * Recibe los datos por POST en formato JSON.
    */
    function itinerario_touch()
    {
        // 1. Establecer las cabeceras
        // Es importante permitir POST y OPTIONS (para pre-flight requests de CORS)
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS'); 
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // 2. Manejar la solicitud OPTIONS (pre-flight)
        // Navegadores envían esto antes de una solicitud POST con JSON
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200); // OK
            exit();
        }

        // 3. Verificar que el método sea POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 4. Leer el cuerpo (body) de la solicitud, que viene en JSON
            $json_data = file_get_contents('php://input');
            $data = json_decode($json_data);

            try {
                // 5. Validar que el JSON sea correcto y contenga los datos
                if ($data === null) {
                    throw new Exception("Datos JSON no válidos o vacíos.");
                }

                // Verificamos que los campos necesarios existan
                if (
                    !isset($data->Edad) || !isset($data->Dias) || !isset($data->Cantidad) ||
                    !isset($data->procedencia) || !isset($data->Email) || !isset($data->Circuito)
                ) {
                    http_response_code(400); // Bad Request
                    echo json_encode([
                        "status" => 400,
                        "result" => "Error: Faltan parámetros obligatorios."
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    return; // Salimos del script
                }

                // 6. (Opcional pero recomendado) Validar el email
                if (!filter_var($data->Email, FILTER_VALIDATE_EMAIL)) {
                     http_response_code(400); // Bad Request
                     echo json_encode([
                        "status" => 400,
                        "result" => "Error: El formato del email no es válido."
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    return; // Salimos del script
                }

                // 7. Llamar a la función del modelo con los datos
                // Asumimos que $this->model->insertarItinerarioTouch llama al SP
                // y que el SP devuelve el ID insertado.
                $resultado = $this->model->insertarItinerarioTouch(
                    (int)$data->Edad,
                    (int)$data->Dias,
                    (int)$data->Cantidad,
                    (string)$data->procedencia, // El modelo/SP debe sanear esto
                    (string)$data->Email,
                    (int)$data->Circuito
                );

                // 8. Responder con 201 Created (éxito al crear)
                http_response_code(201); // Created
                echo json_encode([
                    "status" => 201,
                    "result" => $resultado // Debería ser el { "nuevo_id": X } del SP
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            } catch (Exception $e) {
                // 9. Manejar errores del servidor
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al crear el itinerario: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            // 10. Método no permitido (ej. si intentan con GET)
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice POST.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function buscador_destinos_ia()
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Obtener parámetros de la URL. 
            // Se espera un formato string separado por comas (ej: ?circuitos=1,2&localidades=5)
            // Si no se envían, se pasan como cadenas vacías '' para que el SP maneje la lógica de "Mostrar Todo".
            
            $circuitos = (isset($_GET['circuitos']) && $_GET['circuitos'] !== '') ? $_GET['circuitos'] : '';
            $localidades = (isset($_GET['localidades']) && $_GET['localidades'] !== '') ? $_GET['localidades'] : '';

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getDestinosBuscadorIA($circuitos, $localidades);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al buscar destinos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function buscador_info_ia()
    {
        // Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');

        // Verificar que el método de la solicitud sea GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Obtener parámetros de la URL. 
            // Se espera un formato string separado por comas (ej: ?circuitos=1,2&localidades=5)
            // Si no se envían, se pasan como cadenas vacías '' para que el SP maneje la lógica de "Mostrar Todo".
            
            $busqueda = (isset($_GET['busqueda']) && $_GET['busqueda'] !== '') ? (string)$_GET['busqueda'] : 'NULL';

            // Llamar a la función del modelo correspondiente
            try {
                $resultados = $this->model->getInfoBuscadorIA($busqueda);

                http_response_code(200); // OK
                echo json_encode([
                    "status" => 200,
                    "result" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al buscar destinos: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice GET.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    function ia_mensaje()
    {
        // 1. Establecer las cabeceras
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header('Content-Type: application/json');
    
        // 2. Verificar que el método sea POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            /**
             * Obtenemos los datos. 
             * Si vienen por un formulario estándar usamos $_POST.
             * Si vienen como JSON (común en APIs), usamos file_get_contents("php://input").
             */
            $json = json_decode(file_get_contents("php://input"), true);
            
            $user_id = isset($json['user_id']) ? $json['user_id'] : (isset($_POST['user_id']) ? $_POST['user_id'] : null);
            $prompt  = isset($json['prompt']) ? $json['prompt'] : (isset($_POST['prompt']) ? $_POST['prompt'] : null);
        
            // Validar que los campos obligatorios existan
            if (!$user_id || !$prompt) {
                http_response_code(400); // Bad Request
                echo json_encode([
                    "status" => 400,
                    "result" => "Error: Faltan parámetros obligatorios (user_id, prompt)."
                ]);
                return;
            }
        
            // 3. Llamar a la función del modelo
            try {
                // Usamos la función que creamos anteriormente en el modelo
                $resultados = $this->model->Insertar_IaMensaje($user_id, $prompt);
            
                http_response_code(201); // 201 Created es ideal para inserciones exitosas
                echo json_encode([
                    "status" => 201,
                    "result" => "Mensaje guardado correctamente",
                    "data" => $resultados
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                
            } catch (Exception $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode([
                    "status" => 500,
                    "result" => 'Error interno del servidor al guardar el mensaje: ' . $e->getMessage()
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode([
                "status" => 405,
                "result" => 'Error: Método no permitido. Utilice POST.'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

}

//---------------------------

