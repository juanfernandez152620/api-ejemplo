<?php
$idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 1;
$detect = new Mobile_Detect();
$isMobile = $detect->isMobile();
?>
<div class="d-flex header-home justify-content-center">
    <img class="w-100 img-fluid" id="preload-image" style="z-index: -1; display: none;" src="<?php echo constant('URL'); ?>public/img/main/header-home.png" alt="header-home">
    <video autoplay muted loop id="video" class="margen-video w-100" style="z-index: -1; display: none;">
        <source src="<?php echo constant('URL') . 'public/video/' . ($isMobile ? 'tucumanTieneTodoMobile.mp4' : 'tucumanTieneTodo.mp4'); ?>" type="video/mp4">
        Tu navegador no soporta el elemento de video.
    </video>
</div>

<main>
    <div class="col-11 col-lg-11 col-xl-11 col-xxl-9 mx-auto mb-md-4">
        <div>
            <?php include("planviaje.php") ?>
        </div>
    </div>

</main>

<section id="semanaSanta" class="parallax d-flex align-items-lg-stretch flex-column flex-md-row mb-4" style="width: 100%; height: autopx;">
    <?php
    include("semanaSanta.php")
    ?>
</section>

<section>
    <?php
    include("turismoSustentable.php")
    ?>
</section>
<div style="background-color: #e8e8e8;">
    <section class="col-11 col-lg-11 col-xl-11 col-xxl-9 mx-auto py-3">
        <div tabindex="0" role="region" id="imperdibles-titulo" class="my-4 text-center fs-2 text-gris"><?php echo $idioma == 1 ? "CONOCE TUCUMAN" : "MUST-SEES"; ?></div>
        <?php
        include("blogHome.php")
        ?>
    </section>
</div>

<!-- banner 1 -->
<section id="bannerGatronomia" class="parallax d-flex align-items-lg-stretch flex-column flex-md-row" style="width: 100%; height: 400px; background-image: url('<?php echo constant('URL'); ?>public/img/banners/locro.jpg');">
    <a href="<?php echo constant('URL'); ?><?php echo $idioma == 1 ? "subsecciones/lista/37/Sabores%20Tucumanos" : "subsecciones/lista/79/Tucumán%20Flavors%20?idioma=EN"; ?>" class=" text-decoration-none col fw-semibold text-end d-flex justify-content-center align-items-center order-1 order-md-0 px-3 px-md-0">
        <div class="col-md-10 col d-flex justify-content-center align-items-md-end align-items-center flex-column">
            <h1 id="bannerGastTitle" class="text-shadow text-white fst-italic font-size-2 mb-3 text-center text-md-end">Disfrutá de la Empanada más rica del país</h1>
            <button aria-labelledby="bannerGastTitle" id="bannerGastButton" class="plan-articulo-boton rounded-3 p-2" onclick="window.location.href='<?php echo constant('URL'); ?>"><?php echo $idioma == 1 ? "Conocé más aquí" : "Learn more"; ?></button>
        </div>
    </a>
    <a href="<?php echo constant('URL'); ?><?php echo $idioma == 1 ? "subsecciones/lista/37/Sabores%20Tucumanos" : "subsecciones/lista/79/Tucumán%20Flavors%20?idioma=EN"; ?>" class="col d-flex justify-content-center align-items-center order-0 order-md-1 px-3 px-md-0">
        <div class="col col-md-10">
            <?php include("public/icons/svg/entumesa.php") ?>
        </div>
    </a>
</section>

<div class="col-11 col-lg-11 col-xl-11 col-xxl-9 mx-auto mb-md-4">
    <section>
        <?php
        include("imperdibles.php")
        ?>
    </section>
</div>



<div class="col-11 col-lg-11 col-xl-11 col-xxl-9 mx-auto mb-md-4">
    <section>
        <?php
        include("eventos.php")
        ?>
    </section>

    <section class="col-11 col-lg-11 col-xl-11 col-xxl-9 mx-auto mb-md-4 py-3">

        <div tabindex="0" role="region" id="imperdibles-titulo" class="my-4 text-center fs-2 text-gris"><?php echo $idioma == 1 ? "NOTICIAS" : "NEWS"; ?>
            <?php

            ?>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            // URL del feed RSS o la ruta al archivo XML local
            $rssFeedUrl = "https://www.institucionalturismotuc.gob.ar/RSS/sitemap";

            // Cargar el contenido del RSS XML
            $rssXml = simplexml_load_file($rssFeedUrl);

            // Verificar si el contenido se cargó correctamente
            if ($rssXml === false) {
                echo "Error al cargar el feed RSS.";
                exit; // Salir del script si hay un error
            }

            // Inicializar un array para almacenar los artículos
            $items = [];

            // Recorrer los elementos <item>
            foreach ($rssXml->channel->item as $item) {
                // Crear un nuevo array para cada artículo
                $article = [
                    'title' => (string) $item->title, // Obtener el título
                    'description' => (string) $item->description, // Obtener la descripción
                    'link' => (string) $item->link, // Enlace
                    'guid' => (string) $item->guid, // GUID
                    'pubDate' => (string) $item->pubDate, // Fecha de publicación
                    'image' => (string) $item->enclosure['url'], // URL de la imagen
                    'creator' => (string) $item->children('dc', true)->creator // Obtener el creador
                ];

                // Agregar el artículo al array de items
                $items[] = $article;
            }

            // Convertir el array de artículos en JSON
            $jsonOutput = json_encode($items);

            // Contador para limitar a 6 cards
            $count = 0;

            // Iterar sobre cada item del arreglo
            foreach ($items as $item) {
                if ($count >= 6) break; // Limitar a 6 cards
            ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="<?php echo $item['image']; ?>" class="card-img-top" alt="Imagen de Noticia">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                            <a href="<?php echo htmlspecialchars($item['link']); ?>" class="btn btn-primary">Leer más</a>
                        </div>
                    </div>
                </div>
            <?php
                $count++;
            }
            ?>

        </div>

    </section>

    <section id="bannerRutas" class="parallax d-flex align-items-lg-stretch flex-column flex-md-row gap-4" style="width: 100%;">
        <div class="col-md-4 col d-flex justify-content-center flex-column gap-3">
            <div class="col">
                <img id="iconoRuta" class="img-fluid" src="public/icons/svg/rutas/artesano.svg" alt="">
            </div>
            <h1 id="bannerRutaTitle" class="text-dark fst-italic font-size-3 mb-0 ps-2">Viví una experiencia artesanal</h1>
            <div class="ps-2">
                <button id="bannerRutaButton" aria-labelledby="bannerRutaTitle" class="plan-articulo-boton rounded-3 p-2" onclick="window.location.href='<?php echo constant('URL'); ?>"><?php echo $idioma == 1 ? "Conocé más aquí" : "Learn more"; ?></button>
            </div>
        </div>
        <div class="col d-none d-md-block">
            <img id="img-rutas" class="img-fluid w-100" src="<?php echo constant('URL'); ?>public/img/Banners/fe.jpg" alt="">
        </div>
    </section>

    <section id="bannerRutas" class="parallax d-flex" style="width: 100%;">
        <img class="w-100 img-fluid" id="preload-image" style="z-index: -1; display: none;" src="<?php echo constant('URL'); ?>public/img/main/header-home.png" alt="header-home">
    </section>
</div>