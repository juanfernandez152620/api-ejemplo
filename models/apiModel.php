<?php
include_once constant('URL1') . 'models/prestador.php';
include_once constant('URL1') . 'models/galeria.php';
include_once constant('URL1') . 'models/pdf.php';

class Destino
{
    public $id_dest;
    public $Nombre;
    public $id_art;
    public $id_Circuito;
    public $CircuitoNombre;
    public $id_Localidad;
    public $LAT;
    public $LON;
    public $CAT;
    public $IMG;
    public $Descripcion;
    public $Tiempo_De_Demora_Recorrido;
    public $activo;
    public $visible;
    public $distancia;
}

class Itinerario
{
    public $id_itinerario;
    public $id_session;
    public $fecha_creacion;
    public $destinos = [];
}

include_once constant('URL1') . 'models/articulo.php';
include_once constant('URL1') . 'models/subseccion.php';
include_once constant('URL1') . 'models/localidad.php';
include_once constant('URL1') . 'models/categoria.php';

class apiModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCupon($id, $clave)
    {
        $cupones = [];
        $conexion = $this->db->connect();
        $consulta = "CALL sp_obtenerCupon($id, $clave)";
        $resultados = mysqli_query($conexion, $consulta);
        while ($fila = $resultados->fetch_array()) {
            $cupon = new CuponPersona();
            $cupon->id = $fila['idpc'];
            $cupon->clave = $fila['clave'];
            $cupon->nombre = $fila['nombre'];
            $cupon->promocion = $fila['promocion'];
            $cupon->descripcion = $fila['descripcion'];
            $cupon->imagen = $fila['imagen'];
            $cupon->fechaVenc = $fila['fechavencimiento'];
            $cupon->estado = $fila['activo'];
            array_push($cupones, $cupon);
        }
        mysqli_close($conexion);
        return $cupones;
    }

    public function quemarCupon($id, $clave)
    {
        $cupones = [];
        $conexion = $this->db->connect();
        $consulta = "CALL sp_cobrarCupon($id, $clave)";
        $resultados = mysqli_query($conexion, $consulta);
        while ($fila = $resultados->fetch_array()) {
            $seccion = $fila;
            array_push($cupones, $seccion);
        }
        mysqli_close($conexion);
        return $cupones;
    }

    // Obtener todos los destinos con paginación y búsqueda
    public function getDestinos($params)
    {
        $destinos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarDestinos(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($consulta);
        $stmt->bind_param("sssssss", ...array_values($params));
        $stmt->execute();
        $resultados = $stmt->get_result();
        while ($fila = $resultados->fetch_assoc()) {
            $destino = new Destino();
            foreach ($fila as $key => $value) {
                $destino->$key = $value;
            }
            array_push($destinos, $destino);
        }
        $stmt->close();
        $conexion->close();
        return $destinos;
    }

    // Obtener un itinerario por su ID
    public function getItinerarioPorID($id_itinerario)
    {
        $itinerario = new Itinerario();
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarItinerarioPorID(?)";
        $stmt = $conexion->prepare($consulta);
        $stmt->bind_param("i", $id_itinerario);
        $stmt->execute();
        $resultados = $stmt->get_result();
        while ($fila = $resultados->fetch_assoc()) {
            if (!$itinerario->id_itinerario) {
                $itinerario->id_itinerario = $id_itinerario;
            }
            $destino = new Destino();
            foreach ($fila as $key => $value) {
                $destino->$key = $value;
            }
            array_push($itinerario->destinos, $destino);
        }
        $stmt->close();
        $conexion->close();
        return $itinerario;
    }

    // Insertar un nuevo itinerario
    public function insertarItinerario($jsonData)
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_InsertarItinerario(?)";
        $stmt = $conexion->prepare($consulta);
        $stmt->bind_param("s", $jsonData);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $row["id_itinerario"];
    }

    public function queryArt($idArt)
    {
        return "CALL sp_TraerArtcId($idArt);";
    }

    public function getArticulo_Id($idArt)
    {

        $articulos = [];
        $conexion = $this->db2025->connect();
        $consulta = $this->queryArt($idArt);
        $resultados = mysqli_query($conexion, $consulta);

        while ($fila = $resultados->fetch_array()) {

            $articulo = new Articulo();

            $articulo->idArticulo = $fila['idArticulo'];
            $articulo->nombre = $fila['nombre'];
            $articulo->copete = $fila['copete'];
            $articulo->cuerpo = $fila['cuerpo'];
            $articulo->destacado = $fila['destacado'];
            $articulo->activa = $fila['activa'];
            $articulo->visible = $fila['visible'];
            $articulo->tipo = $fila['tipo'];
            $articulo->url = $fila['url'];
            $articulo->fecha = $fila['fecha'];
            $articulo->imagen = $fila['imagen'];
            $articulo->imagenMovil = $fila['imagenMovil'];
            $articulo->imagenTexto = $fila['imagenTexto'];
            $articulo->imagenDestacado = $fila['imagenDestacado'];
            $articulo->pieImagen = $fila['pieImagen'];
            $articulo->orden = $fila['orden'];
            $articulo->video = $fila['video'];
            $articulo->idSeccion = $fila['idSeccion'];
            $articulo->idSubseccion = $fila['idSubseccion'];
            $articulo->idioma = $fila['idioma'];
            $articulo->nomSubseccion = $fila['nombreSubseccion'];
            $articulo->iframe = $fila['iframe'];

            array_push($articulos, $articulo);
        }

        mysqli_close($conexion);
        return $articulos;
    }


    public function queryArtAll()
    {
        return "CALL sp_ListarArticulosAll();";
    }

    public function getArticulo_All()
    {
        $articulos = [];
        $conexion = $this->db2025->connect();
        $consulta = $this->queryArtAll();
        $resultados = mysqli_query($conexion, $consulta);
        while ($fila = $resultados->fetch_array()) {

            $articulo = new Articulo();

            $articulo->idArticulo = $fila['idArticulo'];
            $articulo->nombre = $fila['nombre'];

            array_push($articulos, $articulo);
        }
        mysqli_close($conexion);
        return $articulos;
    }

    public function getGaleria($idGaleria)
    {
        $imagenes = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL `sp_ListarImgGalery`('$idGaleria');";
        try {
            $resultados = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            // En caso de cualquier error, retornar null
            mysqli_close($conexion);
            return null;
        }
        while ($fila = $resultados->fetch_array()) {

            $imagen = new Galeria();

            $imagen->idImagen = $fila['idImagen'];
            $imagen->archivo = $fila['archivo'];
            $imagen->activa = $fila['activa'];
            $imagen->texto = $fila['texto'];
            $imagen->idGaleria = $fila['idGaleria'];

            array_push($imagenes, $imagen);
        }

        mysqli_close($conexion);
        return $imagenes;
    }


    public function getPDFs($idPDF)
    {
        $pdfs = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarPdfsArtc($idPDF);";

        try {
            $resultados = mysqli_query($conexion, $consulta);
        } catch (Exception $e) {
            // En caso de cualquier error, retornar null
            mysqli_close($conexion);
            return null;
        }


        while ($fila = $resultados->fetch_array()) {

            $pdf = new pdf();

            $pdf->idPdf = $fila['idPdf'];
            $pdf->titulo = $fila['titulo'];
            $pdf->descripcion = $fila['descripcion'];
            $pdf->archivo = $fila['archivo'];
            $pdf->activo = $fila['activo'];
            $pdf->icono = $fila['icono'];
            $pdf->idListapdf = $fila['idListapdf'];

            array_push($pdfs, $pdf);
        }

        mysqli_close($conexion);
        return $pdfs;
    }

    public function getArticulo_Busqueda($idioma, $busqueda, $limite, $offset, $lol)
    {

        if ($idioma == null) {
            $idioma = 1;
        } else if ($idioma == 'ES') {
            $idioma = 1;
        } else if ($idioma == 'EN') {
            $idioma = 2;
        } else if ($idioma == 'BR') {
            $idioma = 3;
        }

        if ($lol == null) {
            $lol = 'NULL';
        }

        $articulos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarArticulos_Busqueda_LN($idioma, '$busqueda', $limite, $offset, $lol);";
        //$consulta = "CALL sp_ListarArticulos_Busqueda($idioma, '$busqueda', $limite, $offset, $lol);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $articulos[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $articulos;
    }

    //busqueda destinos

    public function getDestino_Busqueda($busqueda, $limite, $offset, $idioma)
    {

        if ($idioma == null) {
            $idioma = 1;
        } else if ($idioma == 'ES') {
            $idioma = 1;
        } else if ($idioma == 'EN') {
            $idioma = 2;
        } else if ($idioma == 'BR') {
            $idioma = 3;
        }

        
        if ($busqueda !== 'NULL') {
            $busqueda = "'$busqueda'";
        } else {
            $busqueda = "''";
        }

        $destinos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_buscadorDeDestinos($busqueda, $limite, $offset, $idioma);";
        // var_dump($consulta);
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $destinos[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $destinos;
    }


    public function getImperdibles($idioma)
    {

        if ($idioma == null) {
            $idioma = 1;
        } else if ($idioma == 'ES') {
            $idioma = 1;
        } else if ($idioma == 'EN') {
            $idioma = 2;
        } else if ($idioma == 'BR') {
            $idioma = 3;
        }

        $articulos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_verImperdibles($idioma);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $articulos[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $articulos;
    }

    public function getBlog($lim, $off, $busqueda, $idioma)
    {

        if ($idioma == null) {
            $idioma = 1;
        } else if ($idioma == 'ES') {
            $idioma = 1;
        } else if ($idioma == 'EN') {
            $idioma = 2;
        } else if ($idioma == 'BR') {
            $idioma = 3;
        }

        $articulos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_obtener_art_blog_busq_etiquetas($lim, $off, '$busqueda', $idioma);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $articulos[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $articulos;
    }

    public function getBlog_Total($busqueda, $idioma)
    {

        if ($idioma == null) {
            $idioma = 1;
        } else if ($idioma == 'ES') {
            $idioma = 1;
        } else if ($idioma == 'EN') {
            $idioma = 2;
        } else if ($idioma == 'BR') {
            $idioma = 3;
        }

        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_obtener_total_art_blog_busq_etiquetas('$busqueda', $idioma);";
        $resultado = mysqli_query($conexion, $consulta);

        if ($resultado && mysqli_num_rows($resultado) > 0) { // Verifica que hubo un resultado
            // --- ¡ESTA ES LA PARTE CRUCIAL! ---
            // 1. Obtén la ÚNICA fila del resultado
            $fila = $resultado->fetch_assoc();

            // 2. Accede al valor usando el alias que le diste al COUNT(*) en tu SQL
            //    (Yo usé 'total_count' en el ejemplo, asegúrate de usar el tuyo si es diferente)
            //    Puede que en tu caso el alias sea simplemente 'total'.
            //    Verifica el alias en tu: SELECT COUNT(*) as TU_ALIAS FROM ...
            $total = (int)$fila['total']; // O $fila['total'] si ese es tu alias

            // ¡NO devuelvas $fila ni [$fila]! Devuelve $total.
            // --- Fin Parte Crucial ---

            mysqli_free_result($resultado);
        }

        mysqli_close($conexion);
        return $total;
    }



    public function getBlogDestacados($idioma)
    {

        if ($idioma == null) {
            $idioma = 1;
        } else if ($idioma == 'ES') {
            $idioma = 1;
        } else if ($idioma == 'EN') {
            $idioma = 2;
        } else if ($idioma == 'BR') {
            $idioma = 3;
        }

        $articulos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_obtener_art_blog_destacados($idioma);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $articulos[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $articulos;
    }

    public function getArticulosPorSubseccion($subseccionParam)
    {
        $articulos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_obtener_articulos_por_subseccion($subseccionParam);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $articulos[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $articulos;
    }


    public function getSubseccion_Id($id)
    {
        var_dump("subseccion5.6");
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_obtener_subseccion_api_2($id);";
        $resultado = mysqli_query($conexion, $consulta);

        while ($subsec = $resultado->fetch_array()) {
            $subseccion = new Subseccion();
            $subseccion->id = $subsec['idSubseccion'];
            $subseccion->nombre = $subsec['nombre'];
            $subseccion->portada = $subsec['portada'];
            $subseccion->portadaMovil = $subsec['portadaMovil'];
            $subseccion->activa = $subsec['activa'];
            $subseccion->visible = $subsec['visible'];
            $subseccion->orden = $subsec['orden'];
            $subseccion->idSeccion = $subsec['idSeccion'];
            $subseccion->idioma = $subsec['idioma'];
        }

        mysqli_close($conexion);
        return $subseccion;
    }

    public function getSubSecciones_All()
    {
        $subsecciones = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_obtener_subsecciones();";
        $resultado = mysqli_query($conexion, $consulta);
        while ($subsec = $resultado->fetch_array()) {
            $subseccion = new Subseccion();
            $subseccion->id = $subsec['idSubseccion'];
            $subseccion->nombre = $subsec['nombre'];

            array_push($subsecciones, $subseccion);
        }
        mysqli_close($conexion);
        return $subsecciones;
    }

    public function getSubSeccionesCircuito($Circuito, $idioma) {
        $subsecciones = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_SubSeccionCircuito($Circuito);";
        $resultados = mysqli_query($conexion, $consulta);
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $subsecciones[] = $fila; // Forma corta de array_push($guias, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $subsecciones;
    }


    public function getNavbar($idioma)
    {

        if ($idioma == null) {
            $idioma = 1;
        } else if ($idioma == 'ES') {
            $idioma = 1;
        } else if ($idioma == 'EN') {
            $idioma = 2;
        } else if ($idioma == 'BR') {
            $idioma = 3;
        }

        $articulos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_verHeaderModel_ordenados($idioma);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $articulos[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $articulos;
    }

    public function getNavbarMenu($idioma)
    {

        if ($idioma == null) {
            $idioma = 1;
        } else if ($idioma == 'ES') {
            $idioma = 1;
        } else if ($idioma == 'EN') {
            $idioma = 2;
        } else if ($idioma == 'BR') {
            $idioma = 3;
        }

        $articulos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_verHeaderModel_Menu($idioma);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $articulos[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $articulos;
    }

    public function getHoteles($busqueda, $categoria, $estrellas, $lol, $serv, $lat, $lon, $distancia, $offset, $limit)
    {

        $hoteles = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarHoteles_Geo('$busqueda', $categoria, $estrellas, $lol, '$serv', $lat, $lon, $distancia, $offset, $limit);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $hoteles[] = $fila; // Forma corta de array_push($hoteles, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $hoteles;
    }

    public function getLocalidades()
    {
        $localidades = [];
        $query = "CALL `sp_ListarLocalidades`();";
        $conexion = $this->db2025->connect();
        $results = mysqli_query($conexion, $query);
        while ($fila = $results->fetch_array()) {
            $localidad = new Localidad();
            $localidad->id = $fila['id'];
            $localidad->nombre = $fila['nombre'];
            array_push($localidades, $localidad);
        }
        mysqli_close($conexion);
        return $localidades;
    }
    public function getCategoriasAlojamientos()
    {
        $categorias = [];
        $query = "CALL `sp_ListarCategorias_Hoteles`();";
        $conexion = $this->db2025->connect();
        $results = mysqli_query($conexion, $query);
        while ($fila = $results->fetch_array()) {
            $localidad = new Categoria();
            $localidad->id = $fila['id'];
            $localidad->nombre = $fila['nombre'];
            array_push($categorias, $localidad);
        }
        mysqli_close($conexion);
        return $categorias;
    }

    // Colectivos
    public function getColectivos()
    {

        $colectivos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarColectivos();";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $colectivos[] = $fila; // Forma corta de array_push($colectivos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $colectivos;
    }

    // Autos 
    public function getAutos($nombre, $lol, $limit, $offset)
    {

        $autos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarAutos('$nombre', $lol, $limit, $offset);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $autos[] = $fila; // Forma corta de array_push($autos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $autos;
    }

    //PRESTADORES

    public function getPrestadores($busqueda, $localidad_id, $actividad_id, $limit, $offset)
    {

        $autos = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarPrestadoresActivos_LN('$busqueda', $localidad_id, $actividad_id, $limit, $offset);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $autos[] = $fila; // Forma corta de array_push($autos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $autos;
    }

    // AGENCIAS

    public function getAgencias($nombre, $localidad, $limit, $offset)
    {

        $agencias = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarAgencias('$nombre', $localidad, $limit, $offset);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $agencias[] = $fila; // Forma corta de array_push($agencias, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $agencias;
    }

    // Guias

    public function getGuias($nombre, $localidad, $limit, $offset) ////
    {

        $guias = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarGuias_LN('$nombre', $localidad, $limit, $offset, 1);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $guias[] = $fila; // Forma corta de array_push($guias, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $guias;
    }

    // Eventos
    public function getEventos(
        $FechaIni,
        $FechaFin,
        $Dia,
        $Busqueda,
        /* $p_latitud,
        $p_longitud,
        $p_distancia, */
        $offset,
        $limit
    ) {

        $eventos = [];
        $conexion = $this->db2025->connect();
        /* $consulta = "CALL sp_ListarEventos_Geo($FechaIni, $FechaFin, '$Dia', '$Busqueda', $p_latitud, $p_longitud, $p_distancia, $offset, $limit);"; */
        $consulta = "CALL sp_ListarEventos_Busqueda_Filter($FechaIni, $FechaFin, $Dia, $Busqueda, $offset, $limit);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $eventos[] = $fila; // Forma corta de array_push($eventos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {

        }

        mysqli_close($conexion);
        return $eventos;
    }

    public function getTotalEventos(
        $FechaIni,
        $FechaFin,
        $Dia,
        $Busqueda,
        /* $p_latitud,
        $p_longitud,
        $p_distancia, */
        $offset,
        $limit
    ) {
        $consulta = "CALL sp_ListarEventos_Busqueda_Total($FechaIni, $FechaFin, $Dia, $Busqueda);";
        $conexion = $this->db2025->connect();
        $result = mysqli_query($conexion, $consulta);
        if (!$result) {
            die('Error en la consulta: ' . mysqli_error($conexion));
        }
        $total = mysqli_fetch_assoc($result)['total'];
        mysqli_close($conexion);
        return $total;
    }

    public function getEventosHome()
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarEventosHome();";
        $resultados = mysqli_query($conexion, $consulta);
        $eventos = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $eventos[] = $fila; // Forma corta de array_push($eventos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $eventos;
    }

    //Evento singular
    public function getEvento($id)
    {

        $evento = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarEventoSingular($id);";
        $resultados = mysqli_query($conexion, $consulta);

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $evento[] = $fila; // Forma corta de array_push($evento, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {

        }

        mysqli_close($conexion);
        return $evento;
    }

    // ITINENARIOS

    //Hoteles it
    public function getHotelesItinerario($Circuito, $limit, $offset)
    {
        $hoteles = [];
        $conexion = $this->db->connect();
        $consulta = "CALL sp_HotelesPorCircuito($Circuito, $limit, $offset);";
        $resultados = mysqli_query($conexion, $consulta);
        $hoteles = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $hoteles[] = $fila; // Forma corta de array_push($hoteles, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            // Opcional: Registrar o manejar el error de la consulta si falla
            // error_log("Error en la consulta SQL: " . mysqli_error($conexion));
            // Podrías devolver un array vacío o lanzar una excepción si lo prefieres
        }

        mysqli_close($conexion);
        return $hoteles;
    }

    //PRESTADORES ITINENARIO

    public function getPrestadoresItinerario($Circuito, $offset, $limit)
    {
        $prestadores = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_prestadoresPorCircuito($Circuito, $offset, $limit);";
        $resultados = mysqli_query($conexion, $consulta);
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $prestadores[] = $fila; // Forma corta de array_push($prestadores, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $prestadores;
    }

    //guias itinerario
    public function getGuiasItinerario($Circuito, $offset, $limit)
    {
        $guias = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_guiasPorCircuito($Circuito, $offset, $limit);";
        $resultados = mysqli_query($conexion, $consulta);
        $guias = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $guias[] = $fila; // Forma corta de array_push($guias, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $guias;
    }

    //hoteles itinerario
    public function getHotelesRegistro($Circuito)
    {
        $hoteles = [];
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_InsertarApiDesdeString(4, CONCAT('circuito,', $Circuito));";
        $resultados = mysqli_query($conexion, $consulta);
        $hoteles = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $hoteles[] = $fila; // Forma corta de array_push($hoteles, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $hoteles;
    }

    public function getSession($direc, $lat, $lon, $idioma, $busqueda)
    {
        if ($busqueda !== 'NULL') {
            $busqueda = "'$busqueda'";
        }
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_insertar_sesion('$direc', $lat, $lon, $idioma, $busqueda);";
        // var_dump($consulta);
        $resultados = mysqli_query($conexion, $consulta);
        $session = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $session[] = $fila; // Forma corta de array_push($session, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $session;
    }

    public function getVisita($id, $direc, $lat, $lon, $idioma, $busqueda)
    {
        if ($busqueda !== 'NULL') {
            $busqueda = "'$busqueda'";
        }
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_insertar_visita($id, '$direc', $lat, $lon, $idioma, $busqueda);";
        // var_dump($consulta);
        $resultados = mysqli_query($conexion, $consulta);
        $visita = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $visita[] = $fila; // Forma corta de array_push($visita, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $visita;
    }

    public function getItinerario($id)
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarItinenario2($id);";
        $resultados = mysqli_query($conexion, $consulta);
        $itinerario = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $itinerario[] = $fila; // Forma corta de array_push($itinerario, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $itinerario;
    }

    public function getEventosItinerario($Circuito, $lol, $offset, $limit)
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarEventosPorCircuito($Circuito, $lol, $offset, $limit);";
        $resultados = mysqli_query($conexion, $consulta);
        $eventos = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $eventos[] = $fila; // Forma corta de array_push($eventos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $eventos;
    }

    public function getColectivosItinerario($localidad, $circuito, $busqueda, $offset, $limit)
    {
        if ($busqueda !== 'NULL') {
            $busqueda = "'$busqueda'";
        }
        
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarColectivosPorCircuito($localidad, $circuito, $busqueda, $limit, $offset);";
        // var_dump($consulta);
        $resultados = mysqli_query($conexion, $consulta);
        $colectivos = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $colectivos[] = $fila; // Forma corta de array_push($colectivos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            throw new Exception("Error en la base de datos al insertar el itinerario: " . $conexion);
        }

        mysqli_close($conexion);
        return $colectivos;
    }

    // lugares

    public function getLugares()
    {
        
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarLugares();";
        $resultados = mysqli_query($conexion, $consulta);
        $lugares = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $lugares[] = $fila; // Forma corta de array_push($lugares, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $lugares;
    }

    // circuitos

    public function getCircuitos()
    {
        
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarCircuitosTuristicos();";
        $resultados = mysqli_query($conexion, $consulta);
        $circuitos = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $circuitos[] = $fila; // Forma corta de array_push($circuitos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $circuitos;
    }

    // NO OFICINAL MANTENER EN LA 10

    public function getItinenarioEstadisticas()
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarItinenarioJSON();";
        $resultados = mysqli_query($conexion, $consulta);
        $itinerarios = [];
        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $itinerarios[] = $fila; // Forma corta de array_push($itinerarios, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $itinerarios;
    }

    // LISTAS

    public function getListas($lista, $limit, $offset) 
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarCardsPorListadoFront($lista, $limit, $offset);";
        $resultados = mysqli_query($conexion, $consulta);
        
        // Variable para guardar el resultado final
        $json_final = null; 

        // Verificar si la consulta fue exitosa
        if ($resultados) {
            // --- INICIO DEL CAMBIO ---

            // 1. Obtenemos la única fila de resultados
            $fila = $resultados->fetch_assoc();

            // 2. Si la fila existe, extraemos el valor de su primera columna
            //    Usamos current() para obtener el primer valor del array asociativo,
            //    sin importar cómo se llame la columna en el Stored Procedure.
            if ($fila) {
                $json_final = current($fila);
            }
            
            // --- FIN DEL CAMBIO ---

            // Liberamos la memoria del resultado
            mysqli_free_result($resultados);
        } else {
            // Aquí puedes manejar el error si la consulta falla
            // Por ejemplo: error_log("Error en la consulta: " . mysqli_error($conexion));
        }

        mysqli_close($conexion);

        // Devolvemos directamente el string JSON o null si no hubo resultado
        return $json_final;
    }

    public function getLista($lista) 
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ObtenerListado($lista);";
        $resultados = mysqli_query($conexion, $consulta);
        
        // Variable para guardar el resultado final
        $json_final = null; 

        // Verificar si la consulta fue exitosa
        if ($resultados) {
            // --- INICIO DEL CAMBIO ---

            // 1. Obtenemos la única fila de resultados
            $fila = $resultados->fetch_assoc();

            // 2. Si la fila existe, extraemos el valor de su primera columna
            //    Usamos current() para obtener el primer valor del array asociativo,
            //    sin importar cómo se llame la columna en el Stored Procedure.
            if ($fila) {
                $json_final = current($fila);
            }
            
            // --- FIN DEL CAMBIO ---

            // Liberamos la memoria del resultado
            mysqli_free_result($resultados);
        } else {
            // Aquí puedes manejar el error si la consulta falla
            // Por ejemplo: error_log("Error en la consulta: " . mysqli_error($conexion));
        }

        mysqli_close($conexion);

        // Devolvemos directamente el string JSON o null si no hubo resultado
        return $json_final;
    }

    // IA --- EXPERIMENTAL

    public function getProductosLocalidad($localidad, $limit, $offset) 
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_productoPorLocalidad($localidad, $limit, $offset);";
        $resultados = mysqli_query($conexion, $consulta);
        $productos = [];

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $productos[] = $fila; // Forma corta de array_push($productos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $productos;
    } 

    public function getArticulosLocalidad($localidad) 
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_listarArticulo_localidad($localidad);";
        //var_dump($consulta);
        $resultados = mysqli_query($conexion, $consulta);
        $articulos = [];

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $articulos[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $articulos;
    }

    public function getInfoBuscadorIA($busqueda) 
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_buscar_info_ia('$busqueda');";
        $resultados = mysqli_query($conexion, $consulta);
        $info = [];

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $info[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $info;
    }

    public function getDestinosLocalidad($localidad, $limit, $offset) 
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_listarDestinos_localidad($localidad, $limit, $offset);";
        $resultados = mysqli_query($conexion, $consulta);
        $destinos = [];

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $destinos[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $destinos;
    }

    public function getWaikyFunFact()
    {
        $conexion = $this->db2025->connect();
        $consulta = "CALL sp_ListarRecomendacionWaiky();";
        $resultados = mysqli_query($conexion, $consulta);
        $funfact = [];

        // Verificar si la consulta fue exitosa antes de intentar obtener resultados
        if ($resultados) {
            // --- Inicio: Cambio Principal ---
            // Usar fetch_assoc() para obtener solo un array asociativo (nombre_columna => valor)
            while ($fila = $resultados->fetch_assoc()) {
                // Ya no creamos un objeto Articulo()
                // La variable $fila YA CONTIENE un array asociativo con exactamente
                // las columnas y valores devueltos por la consulta para esta fila.
                // Simplemente añadimos este array $fila directamente al array $articulos.
                $funfact[] = $fila; // Forma corta de array_push($articulos, $fila);
            }
            // --- Fin: Cambio Principal ---

            // Liberar el conjunto de resultados si estás usando mysqli
            mysqli_free_result($resultados);

            // Si tu procedimiento puede devolver múltiples resultados (poco común para SELECTs),
            // necesitarías manejarlo con mysqli_next_result(). Para un único SELECT, no es necesario.

        } else {
            
        }

        mysqli_close($conexion);
        return $funfact;
    }

    public function insertarItinerarioTouch($Edad, $Dias, $Cantidad, $procedencia, $Email, $Circuito)
    {
        // 1. Obtener la conexión
        $conexion = $this->db2025->connect();

        // 2. ---- IMPORTANTE: Sanitizar las entradas ----
        // Para números, forzarlos a ser (int) es la mejor sanitización.
        $Edad_sql = (int)$Edad;
        $Dias_sql = (int)$Dias;
        $Cantidad_sql = (int)$Cantidad;
        $Circuito_sql = (int)$Circuito;

        // Para strings, debemos escaparlos para prevenir Inyección SQL.
        // Esto se debe hacer DESPUÉS de conectar a la DB.
        $procedencia_sql = mysqli_real_escape_string($conexion, $procedencia);
        $Email_sql = mysqli_real_escape_string($conexion, $Email);
        // ---- Fin de la sanitización ----


        // Nota: Los strings ($procedencia_sql, $Email_sql) DEBEN ir entre comillas simples (')
        $consulta = "CALL sp_insertar_itinerarios_touch(
            $Edad_sql, 
            $Dias_sql, 
            $Cantidad_sql, 
            '$procedencia_sql', 
            '$Email_sql', 
            $Circuito_sql
        );";

        // 4. Ejecutar la consulta
        $resultados = mysqli_query($conexion, $consulta);

        // 5. Preparar la variable de respuesta
        $respuesta = null;

        // 6. Manejar el resultado
        if ($resultados) {
            // Si la consulta fue exitosa, el SP nos devuelve una fila con el 'nuevo_id'
            // Usamos fetch_assoc() para obtenerla
            $fila = $resultados->fetch_assoc();
            
            // $fila será un array como [ "nuevo_id" => 123 ]
            $respuesta = $fila; 
            
            // Liberar el resultado
            mysqli_free_result($resultados);

        } else {
            // 7. Si mysqli_query falló (retornó false), lanzamos una excepción
            // Esta excepción será capturada por el bloque "catch (Exception $e)" en tu controlador.
            $error_msg = mysqli_error($conexion);
            mysqli_close($conexion); // Nos aseguramos de cerrar la conexión antes de salir
            throw new Exception("Error en la base de datos al insertar el itinerario: " . $error_msg);
        }

        // 8. Cerrar la conexión y devolver la respuesta
        mysqli_close($conexion);
        
        // Devolvemos el array [ "nuevo_id" => 123 ] al controlador
        return $respuesta; 
    }

    public function getDestinosBuscadorIA($circuitos, $localidades) 
    {
        $conexion = $this->db2025->connect();
        
        // Escapar las cadenas para evitar inyección SQL básica, aunque el SP espera VARCHARs
        $circuitosEscapados = mysqli_real_escape_string($conexion, $circuitos);
        $localidadesEscapadas = mysqli_real_escape_string($conexion, $localidades);

        // Construir la consulta. NOTA: Los parámetros VARCHAR deben ir entre comillas simples dentro del SQL.
        $consulta = "CALL sp_buscar_destinos_IA('$circuitosEscapados', '$localidadesEscapadas');";
        
        $resultados = mysqli_query($conexion, $consulta);
        $destinos = [];

        // Verificar si la consulta fue exitosa
        if ($resultados) {
            // Usar fetch_assoc() para obtener el array asociativo directo
            while ($fila = $resultados->fetch_assoc()) {
                // $fila contiene: id, titulo, tags, localidad_id, localidad_nombre, circuito_id, circuito_nombre
                $destinos[] = $fila; 
            }

            // Liberar el conjunto de resultados
            mysqli_free_result($resultados);
            
            // Consumir cualquier resultado extra del procedimiento almacenado 
            // (buena práctica para evitar errores "Commands out of sync" en futuras consultas)
            while (mysqli_more_results($conexion) && mysqli_next_result($conexion)) { 
                // Loop vacío solo para limpiar el buffer
            }

        } else {
            // Manejo de error silencioso o podrías lanzar una excepción aquí si prefieres
        }

        mysqli_close($conexion);
        return $destinos;
    }

    public function Insertar_IaMensaje($user_id, $prompt)
    {
        // 1. Obtener la conexión
        $conexion = $this->db2025->connect();
    
        // 2. ---- Sanitización de entradas ----
        // Forzamos el ID a entero y escapamos el string del prompt
        $user_id_sql = (int)$user_id;
        $prompt_sql = mysqli_real_escape_string($conexion, $prompt);
    
        // 3. Preparar la consulta llamando al SP
        // El prompt va entre comillas simples por ser un string
        $consulta = "CALL sp_Insertar_IaMensajes($user_id_sql, '$prompt_sql');";
    
        // 4. Ejecutar la consulta
        $resultados = mysqli_query($conexion, $consulta);
    
        // 5. Preparar la variable de respuesta
        $respuesta = null;
    
        // 6. Manejar el resultado
        if ($resultados) {
            // En este caso, si el SP solo hace un INSERT, 
            // simplemente confirmamos que se ejecutó devolviendo true o el resultado
            $respuesta = $resultados; 
            
            // Si tu SP no devuelve un SELECT, simplemente liberamos si es necesario
            if (!is_bool($resultados)) {
                mysqli_free_result($resultados);
            }
        } else {
            // 7. Error handling
            $error_msg = mysqli_error($conexion);
            mysqli_close($conexion);
            throw new Exception("Error en la base de datos al insertar el mensaje de IA: " . $error_msg);
        }
    
        // 8. Cerrar conexión y retornar
        mysqli_close($conexion);
        return $respuesta;
    }

}
