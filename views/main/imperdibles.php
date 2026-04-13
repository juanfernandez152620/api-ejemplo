<?php
$idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 1;
if ($idioma == 'ES') {
    $idioma = 1;
}
?>

<section class="align-items-center justify-content-center mb-5">

    <div tabindex="0" role="region" id="imperdibles-titulo" class="my-4 text-center fs-2 text-gris"><?php echo $idioma == 1 ? "IMPERDIBLES" : "MUST-SEES"; ?></div>

    <?php
    $imperdibles = $this->imperdibles;
    ?>
    <div class="imperdilesCompu col-11 mx-auto">
        <div class="row">
            <div id="carruselImperdiblesCompu" class="carousel slide">
                <div class="carousel-inner">
                    <?php
                    $esElPrimero = 0;
                    $contadorImper = 0;
                    $imperdiblesTemp = [];
                    foreach ($imperdibles as $i => $imperdibleTemp) {
                        $contadorImper++;
                        array_push($imperdiblesTemp, $imperdibleTemp);
                        if ($contadorImper == 3) {
                            # code...

                    ?>
                            <div class="carousel-item <?php echo $esElPrimero == 0 ? 'active' : ''; ?> justify-content-center">
                                <div class="d-flex justify-content-evenly align-items-stretch">
                                    <!-- contenido de a 3 -->


                                    <?php
                                    foreach ($imperdiblesTemp as $e => $imperdible) {
                                        //for de las 3 cards
                                    ?>

                                        <div class="col-3 position-relative">

                                            <a href="<?php echo constant('URL') . 'articulos/articulo/' . $imperdible->idArticulo; ?>?idioma=<?php echo $idioma == 1 ? 'ES' : 'EN';?>" class="position-relative d-flex h-100">
                                                <div class="w-100 h-auto">
                                                    <img class="img-fluid shadow h-100 object-fit-cover" src="<?php echo constant("URLApi") ?>public/img/<?php echo $imperdible->imagenDestacado; ?>" alt="">
                                                </div>
                                                <div class="position-absolute h-200" style="z-index: 4;">
                                                    <h1 class="ps-3 pt-3 text-shadow text-break overlay-text fw-bold text-white"><?php echo str_replace(' ', '<br>', $imperdible->nombre); ?></h1>
                                                </div>
                                            </a>

                                        </div>

                                    <?php
                                    };
                                    //endfor;
                                    ?>

                                </div>
                            </div>

                        <?php
                            //reset de temp
                            $esElPrimero++;
                            $imperdiblesTemp = [];
                            $contadorImper = 0;
                        }; //endif
                        ?>
                    <?php
                    }; //end foreach
                    ?>
                </div>
                <button class="carousel-control-next custom-control-next h-100" style="width: 10vw;" type="button" data-bs-target="#carruselImperdiblesCompu" data-bs-slide="next">
                    <i class="bi bi-arrow-right-circle-fill text-black"></i> <!-- Icono del botón "Next" -->
                    <span class="visually-hidden">Grupo de Imperdibles Siguiente</span>
                </button>
                <button class="carousel-control-prev custom-control-prev h-100" style="width: 10vw;" type="button" data-bs-target="#carruselImperdiblesCompu" data-bs-slide="prev">
                    <i class="bi bi-arrow-left-circle-fill text-black"></i> <!-- Icono del botón "Previous" -->
                    <span class="visually-hidden">Grupo de Imperdibles Anterior</span>
                </button>
            </div>
        </div>
    </div>

    <!-- eventos movile -->
    <div class="px-0 container-fluid container-carousel eventsMobile imperdiblesmobile">
        <section class="mb-5 justify-content-center d-block d-lg-none">
            <div class="col">
                <div id="carruselImperMobile" class="carousel slide carruEvent">
                    <div class="carousel-inner">
                        <?php
                        foreach ($imperdibles as $i => $imperdible) {
                        ?>
                            <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>">
                                <div class="d-flex justify-content-center">
                                    <!-- contenido de las cards -->
                                    <div class="col-10 position-relative" style="height: 65vh;">
                                        <a href="<?php echo constant('URL') . 'articulos/articulo/' . $imperdible->idArticulo; ?>" class="position-relative d-flex h-100">
                                            <div class="w-100 h-auto">
                                                <img class="img-fluid shadow h-100 object-fit-cover" style="min-width: 100%; min-height: 100%;" src="<?php echo constant("URLApi") ?>public/img/<?php echo $imperdible->imagenDestacado; ?>" alt="">
                                            </div>
                                            <div class="position-absolute h-200" style="z-index: 4;">
                                                <h1 class="ps-3 pt-3 text-shadow text-break overlay-text fw-bold text-white" style="font-size: 2.5rem !important;"><?php echo str_replace(' ', '<br>', $imperdible->nombre); ?></h1>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        <?php }; //endfor; 
                        ?>
                    </div>
                    <button class="carousel-control-prev h-100" style="width: 7vw;" type="button" data-bs-target="#carruselImperMobile" data-bs-slide="prev">
                        <i class="bi bi-arrow-left-circle-fill text-black"></i> <!-- Icono del botón "Previous" -->
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next h-100" style="width: 7vw;" type="button" data-bs-target="#carruselImperMobile" data-bs-slide="next">
                        <i class="bi bi-arrow-right-circle-fill text-black"></i> <!-- Icono del botón "next" -->
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>
    </div>

</section>

<!-- banner 2 -->

<!-- <section class="w-100 mt-5 mb-5">
    <div class="w-100">
        <img class="img-fluid w-100" src="public\img\Banners\banner2.webp" alt="">
    </div>
</section> -->