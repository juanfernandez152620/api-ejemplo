<?php

$detect = new Mobile_Detect();
$isMobile = $detect->isMobile();


?>
<?php
if ($isMobile) {

?>

    <div class="d-flex justify-content-center align-items-center w-100" onclick="window.location.href='<?php echo constant('URL'); ?>articulos/articulo/477'" style="cursor: pointer; ">

        <video autoplay muted loop id="video" class="margen-video w-100" style="z-index: -1; cursor: pointer;">
            <source src="<?php echo constant('URL') . 'public/video/BANNER SEMANA SANTA WEB500k.mp4' ?>" type="video/mp4">

        </video>
    </div>
<?php
} else {
?>
    <div class="d-flex justify-content-center align-items-center w-100" onclick="window.location.href='<?php echo constant('URL'); ?>articulos/articulo/477'" style="cursor: pointer; ">

        <video autoplay muted loop id="video" class="margen-video w-100" style="z-index: -1; cursor: pointer;  ">
            <source src="<?php echo constant('URL') . 'public/video/BANNER TUCUMAN SEMANA SANTA150.mp4'?>" type="video/mp4">


        </video>
    </div>

<?php
}
?>