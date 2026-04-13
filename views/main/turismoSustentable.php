<?php
$detect = new Mobile_Detect();
$isMobile = $detect->isMobile();
?>

<?php
if ($isMobile) {
?>
<!-- Mobile -->
<div class="mb-5" onclick="window.location.href='<?php echo constant('URL'); ?>articulos/articulo/473'">
    <img class="img-fluid" src="<?php echo constant('URL') ?>public/img/Banners/turismoSustentable/BannerwebTurSusten-Base-Mobile.jpg" alt="ElBanner-Promocional-De-La-Campaña-Turismo-Sustentable"  >
    <div>
        <h3 class="fw-medium p-2">Generar conciencia ambiental por un futuro más sustentable</h3>
        <button aria-label="<?php echo $item['titulo']; ?> <?php echo $item['texto']; ?> Conocé más aquí" class="plan-articulo-boton rounded-3 p-2"><?php echo $idioma == 1 ? "Conocé más aquí" : "Learn more"; ?></button>
    </div>
</div>




<?php
} else {
?>
<!-- DeskTop -->
<div class="d-flex overflow-hidden" onclick="window.location.href='<?php echo constant('URL'); ?>articulos/articulo/473'">
    <div class="img col position-relative">
        <img class="img-fluid z-100 position-relative" src="<?php echo constant('URL') ?>public/img/Banners/turismoSustentable/BannerwebTurSusten-Base.png" alt="">
        <div class="col position-absolute top-0 start-0" style="width: 50%; height: 100%;" id="TUSUSimg1">
            <img class="img-fluid h-100 w-100 z-1" src="<?php echo constant('URL') ?>public/img/Banners/turismoSustentable/FotosBannerWeb(1).jpeg" alt="" style="object-fit: cover;">
        </div>
        <div class="col position-absolute top-0 start-0" style="width: 50%; height: 100%;" id="TUSUSimg2">
            <img class="img-fluid h-100 w-100 z-1" src="<?php echo constant('URL') ?>public/img/Banners/turismoSustentable/FotosBannerWeb(2).jpeg" alt="" style="object-fit: cover;">
        </div>
        <div class="col position-absolute top-0 start-0" style="width: 50%; height: 100%;" id="TUSUSimg3">
            <img class="img-fluid h-100 w-100 z-1" src="<?php echo constant('URL') ?>public/img/Banners/turismoSustentable/FotosBannerWeb(3).jpeg" alt="" style="object-fit: cover;">
        </div>
    </div>
</div>
<?php
}
?>

<style>
    /* Posicionamiento inicial para las imágenes */
    #TUSUSimg2, #TUSUSimg3 {
        transition: transform 1s ease-in-out;
        animation: slideUpDown 12s infinite;
    }

    /* Animación para las tres imágenes */
    #TUSUSimg3 {
        animation-delay: 1s;
        transform: translateY(100%);
    }

    #TUSUSimg2 {
        animation-delay: 4s;
        transform: translateY(100%);
    }

    /* #img1 {
        animation-delay: 6s;
        transform: translateY(100%);
    } */

    /* Definición de la animación */
    @keyframes slideUpDown {
        0% { transform: translateY(100%); }
        20% { transform: translateY(0%); }  /* Sube la imagen */
        40% { transform: translateY(0%); }  /* Mantiene la imagen arriba */
        60% { transform: translateY(100%); }      /* Baja la imagen */
        100% { transform: translateY(100%); }     /* Espera antes de repetir */
    }
</style>