<?php
include_once constant('URL1').'models/galeria.php';
include_once constant('URL1').'models/pdf.php';
include_once constant('URL1').'models/articulo.php';
include_once constant('URL1').'models/subseccion.php';
include_once constant('URL1') . 'models/evento.php';
class mainModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function QUERY_EVENTS_HOME()
    {
        return "CALL sp_ListarEventosHome();";
    }

    public function getEventosHome()
    {
        $conexion = $this->db2024->connect();
        $consulta = $this->QUERY_EVENTS_HOME();
        $resultados = mysqli_query($conexion, $consulta);
        $eventos = [];
        while ($fila = $resultados->fetch_array()) {
            $fechaInicioObj = new DateTime($fila['fechaInicio']);
            $fechaFinObj = new DateTime($fila['fechaFin']);
            $evento = new Evento();
            $evento->nombre = $fila['nombre'];
            $evento->direccion = $fila['direccion'];
            $evento->localidad = $fila['nombreLocalidad'];
            $evento->categoria = $fila['nombreCategoria'];
            $evento->subcategoria = $fila['subCategoriaNombre'];
            $evento->latitud = $fila['latitud'];
            $evento->longitud = $fila['longitud'];
            $evento->descripcion = $fila['descripcion'];
            $evento->fechainicio = $fechaInicioObj->format('d/m/Y');
            $evento->fechafin = $fechaFinObj->format('d/m/Y');
            $evento->horainicio = $fila['horaInicio'];
            $evento->imagen = $fila['imagen'];
            array_push($eventos, $evento);
        }
        mysqli_close($conexion);
        return $eventos;
    }

    public function queryImper($idioma) {
        return "CALL `sp_verImperdibles`($idioma);";
    }
    

    public function getimperdibles($idioma) {

        if ($idioma == null) {
            $idioma = 1;
        } else if ($idioma == 'ES') {
            $idioma = 1;
        } else if ($idioma == 'EN') {
            $idioma = 2;
        } else if ($idioma == 'BR') {
            $idioma = 3;
        }

        $imperdibles = [];
        $conexion = $this->db2024->connect();
        $consulta = $this->queryImper($idioma);
        $resultados = mysqli_query($conexion, $consulta);
        while ($fila = $resultados->fetch_array()) {


            $articulo = new Articulo();

            $articulo->idArticulo = $fila['idArticulo'];
            $articulo->nombre = $fila['nombre'];
            $articulo->copete = $fila['copete'];
            $articulo->cuerpo = "";
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
            $articulo->nomSubseccion = "";

            array_push($imperdibles, $articulo);
        }

        mysqli_close($conexion);
        return $imperdibles;
    }
}
