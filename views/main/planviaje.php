<?php
$idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 1;

if ($idioma == 'ES') {
    $idioma = 1;
}

$botonesArray = [
    [
        'svg' => "public/icons/svg/planviaje/Alojamientos.php",
        'id' => "nav-Alojamientos-tab",
        'id2' => "art-Alojamientos-tab",
        'titulo' => "Alojamientos",
        'direccionImg' => "public/img/planviaje/alojamiento.webp",
        'texto' => "Tucumán cuenta con una gran variedad de Hoteles, Cabañas, Estancias Rurales, etc., que ofrecen una gama de opciones de acuerdo a tu presupuesto y preferencias. Podés consultar el listado de alojamientos.",
        'link' => "alojamientos"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Transporte.php",
        'id' => "nav-Transport-tab",
        'id2' => "art-Transport-tab",
        'titulo' => "Transporte",
        'direccionImg' => "public/img/planviaje/transportes.webp",
        'texto' => "¿Querés conocer todo lo que Tucumán tiene para ofrecerte y no tenés vehículo propio? Conocé los horarios y valores de los viajes en micro hacia todos los puntos turísticos de la provincia.",
        'link' => "transporte"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Alquiler.php",
        'id' => "nav-Alquiler-Autos-tab",
        'id2' => "art-Alquiler-Autos-tab",
        'titulo' => "Alquiler de Autos",
        'direccionImg' => "public/img/planviaje/alquilerautos.webp",
        'texto' => "Conocé todas las agencias de alquiler de vehículos para armar tu propio itinerario en la provincia.",
        'link' => "autos"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Prestadores.php",
        'id' => "nav-Prestadores-activos-tab",
        'id2' => "art-Prestadores-activos-tab",
        'titulo' => "Prestadores activos",
        'direccionImg' => "public/img/planviaje/activos.webp",
        'texto' => "Conocé a nuestros prestadores de turismo activo habilitados y aventurate en nuestros paisajes de manera segura.",
        'link' => "prestadores"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Agencias.php",
        'id' => "nav-Agencias-tab",
        'id2' => "art-Agencias-tab",
        'titulo' => "Agencias",
        'direccionImg' => "public/img/planviaje/agencias.webp",
        'texto' => "Conocé todas las agencias de viajes receptivas para emprender un viaje por la provincia.",
        'link' => "agencias"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Guias.php",
        'id' => "nav-Guias-Turismo-tab",
        'id2' => "art-Guias-Turismo-tab",
        'titulo' => "Guías de Turismo",
        'direccionImg' => "public/img/planviaje/guias.webp",
        'texto' => "Conocé a nuestros guías de turismo habilitados y viví una experiencia inolvidable y segura.",
        'link' => "guias"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Itinerarios.php",
        'id' => "nav-Itinerarios-tab",
        'id2' => "art-Itinerarios-tab",
        'titulo' => "Itinerarios",
        'direccionImg' => "public/img/planviaje/eventos.webp",
        'texto' => "¿Tenés pensado visitar Tucumán y todavía no sabes cómo organizar tu viaje? Conocé los diferentes itinerarios que te proponemos para aprovechar al máximo tu estadía.",
        'link' => "subsecciones/lista/45"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Mapas.php",
        'id' => "nav-Mapas-folletos-tab",
        'id2' => "art-Mapas-folletos-tab",
        'titulo' => "Mapas y Folletos",
        'direccionImg' => "public/img/planviaje/mapas.webp",
        'texto' => "Descargá aquí los folletos y mapas de los diferentes circuitos turísticos de la provincia de Tucumán.",
        'link' => "subsecciones/lista/46"
    ]
];

$botonesArrayEN = [
    [
        'svg' => "public/icons/svg/planviaje/Alojamientos.php",
        'id' => "nav-Alojamientos-tab",
        'id2' => "art-Alojamientos-tab",
        'titulo' => "Accommodations",
        'direccionImg' => "public/img/planviaje/alojamiento.webp",
        'texto' => "Tucumán offers a wide variety of Hotels, Cabins, Rural Estates, and more, providing a range of options to suit your budget and preferences. You can check the list of accommodations here.",
        'link' => "alojamientos"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Transporte.php",
        'id' => "nav-Transport-tab",
        'id2' => "art-Transport-tab",
        'titulo' => "Urban Transport",
        'direccionImg' => "public/img/planviaje/transportes.webp",
        'texto' => "Do you want to know everything that Tucumán has to offer and you don't have your vehicle? Find out the schedules and prices of bus trips to all the tourist spots in the province.",
        'link' => "transporte"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Alquiler.php",
        'id' => "nav-Alquiler-Autos-tab",
        'id2' => "art-Alquiler-Autos-tab",
        'titulo' => "Car Rentals",
        'direccionImg' => "public/img/planviaje/alquilerautos.webp",
        'texto' => "Meet all the car rental agencies to assemble your itinerary in the province.",
        'link' => "autos"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Prestadores.php",
        'id' => "nav-Prestadores-activos-tab",
        'id2' => "art-Prestadores-activos-tab",
        'titulo' => "Active Tourism Providers",
        'direccionImg' => "public/img/planviaje/activos.webp",
        'texto' => "Meet our authorized active tourism providers and venture into our landscapes safely.",
        'link' => "prestadores"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Agencias.php",
        'id' => "nav-Agencias-tab",
        'id2' => "art-Agencias-tab",
        'titulo' => "Agencies",
        'direccionImg' => "public/img/planviaje/agencias.webp",
        'texto' => "Meet all the incoming travel agencies to embark on a trip through the province.",
        'link' => "agencias"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Guias.php",
        'id' => "nav-Guias-Turismo-tab",
        'id2' => "art-Guias-Turismo-tab",
        'titulo' => "Tourist Guides",
        'direccionImg' => "public/img/planviaje/guias.webp",
        'texto' => "Meet our qualified tour guides and live an unforgettable and safe experience.",
        'link' => "guias"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Itinerarios.php",
        'id' => "nav-Itinerarios-tab",
        'id2' => "art-Itinerarios-tab",
        'titulo' => "Itineraries",
        'direccionImg' => "public/img/planviaje/eventos.webp",
        'texto' => "Are you planning to visit Tucumán and still don't know how to organize your trip? Please get to know the different itineraries we propose to make the most of your stay.",
        'link' => "subsecciones/lista/67/Itineraries?idioma=EN"
    ],
    [
        'svg' => "public/icons/svg/planviaje/Mapas.php",
        'id' => "nav-Mapas-folletos-tab",
        'id2' => "art-Mapas-folletos-tab",
        'titulo' => "Maps and Brochures",
        'direccionImg' => "public/img/planviaje/mapas.webp",
        'texto' => "Download here the brochures and maps of the different tourist circuits in the province of Tucumán.",
        'link' => "articulos/articulo/317?idioma=EN"
    ]
];
?>

<section class="mb-5 d-flex justify-content-center">
    <div class="d-flex position-relative">
        <div class=" position-relative overflow-hidden">

            <div tabindex="0" aria-label="PLANIFICA TU VIAJE, Seleccione una de las secciones" role="region" class="my-4 text-center fs-2 text-gris"><?php echo $idioma == 1 ? "PLANIFICA TU VIAJE" : "PLAN YOUR TRIP"; ?></div>
            
            <div class="mb-4 nav d-flex">
                <?php if ($idioma != 1) { $botonesArray = $botonesArrayEN; }; ?>
                <?php foreach ($botonesArray as $item) : ?>
                    <div class="p-0 col-6 col-md-3 col-lg planviaje-btn mb-2 mb-md-0">
                        <div class=" d-flex justify-content-center h-100 w-100">
                            <button aria-live="assertive" aria-label="Seleccionar <?php echo $item['titulo']; ?>" class="w-100 fs-6 plan-boton P-0 d-flex flex-column align-items-center justify-content-center gap-2" id="<?php echo $item['id']; ?>" type="button" onclick="mostrarPlanViaje('<?php echo $item['id2']; ?>', '<?php echo $item['id']; ?>')">
                                <div class="col-3">
                                    <?php include($item['svg']); ?>
                                </div>
                                <span class="textoPlan transition">
                                    <p class="m-0 textoPlan fs-6"><?php echo $item['titulo']; ?></p>
                                </span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="d-flex" id="contenedor-plan">
                <!-- for -->

                <?php foreach ($botonesArray as $item) : ?>

                    <div class="position-relative hide col-12 shadow-sm" id="<?php echo $item['id2']; ?>">
                        <div id="elemento" class="p-4 img-viaje1 img-viaje2">
                            <h2 class="m-0 mb-lg-3"><?php echo $item['titulo']; ?></h2>
                            <p><?php echo $item['texto']; ?></p>

                            <button aria-label="<?php echo $item['titulo']; ?> <?php echo $item['texto']; ?> Conocé más aquí" class="plan-articulo-boton rounded-3 p-2" onclick="window.location.href='<?php echo constant('URL'); ?><?php echo $item['link']; ?>' "><?php echo $idioma == 1 ? "Conocé más aquí" : "Learn more"; ?></button>
                        </div>

                        <a href="<?php echo constant('URL'); ?><?php echo $item['link']; ?>" class="d-flex justify-content-end">
                            <img src="<?php echo $item['direccionImg']; ?>" alt="" class="img-fluid img-viaje col-md-10" id="transicion-plan">
                        </a>
                    </div>

                <?php endforeach; ?>

                <!-- endfor -->
            </div>

        </div>
    </div>
</section>