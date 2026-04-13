<?php
$idioma = isset($_GET['idioma']) ? $_GET['idioma'] : 1;
if ($idioma == 'ES') {
  $idioma = 1;
}
$eventos = $this->eventos;
?>
<!-- para pc -->
<div class="col-11 mx-auto">
  <section class="mb-5 d-none d-lg-block">
    <div class="d-flex mt-4 mb-4 d-flex justify-content-center position-relative">
      <div role="region" class="my-4 text-center fs-2 text-gris text-uppercase"><?php echo $idioma == 1 ? "Eventos destacados" : "Highlighted events"; ?></div>
      <div class="col-2 position-absolute top-50 end-0 translate-middle-y">
        <a class="btn boton-bg text-white " href="<?php echo constant('URL'); ?>eventos" onClick=""><?php echo $idioma == 1 ? "Más Eventos..." : "More Events..."; ?></a>
      </div>
    </div>
    <div>
      <div id="carruselEventosCompu" class="carousel slide carruEvent w-100">
        <div class="carousel-inner">
          <?php
          $cantidadEventos = count($eventos);
          $totalFilas = ceil($cantidadEventos / 3);
          $indexEvento = 0;
          for ($i = 0; $i < $totalFilas; $i++) :
          ?>
            <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?> justify-content-center">
              <div class="row">
                <?php
                for ($j = 0; $j < 3; $j++) :
                  $evento = $eventos[$indexEvento] ?? null;
                  if ($evento) :
                ?>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3 justify-content-center position-relative d-flex p-3 event-card">
                      <div class="card col-10 position-relative overflow-hidden rounded-0 border-0 d-flex flex-column">
                        <div class="ratio ratio-1x1 overflow-hidden">
                          <img id="imagen" src="<?php echo constant('URLApi') . 'public/img/' . $evento->imagen ?>" class="img-fluid" alt="<?php echo $evento->imagen; ?>">
                        </div>

                        <div class="rounded-bottom-2 font-event-cartas position-absolute bg-white fw-bold ps-1 pe-1 shadow fechainicio">
                          <span class="event-fecha " id="fechainicio"><?php echo $evento->fechainicio; ?></span> - 
                         
                          <span class="event-fecha" id="fechafin">
                            <?php echo $evento->fechafin; ?> <!-- Mostrar fecha de fin -->
                          </span>
                          <?php
                          //echo var_dump($evento);
                          ?> 
                        </div>

                        <div class="d-flex flex-column justify-content-between flex-grow-1 gap-2">
                          <h5 class="card-title text-uppercase line-clamp-3 fw-bold my-2 mb-0" id="nombre"><?php echo $evento->nombre; ?></h5>
                          <div class="d-none align-items-center gap-2 m-2">
                            <h5 class="text-uppercase fw-medium" id="subcategoria">Tipo de evento: <?php echo $evento->subcategoria; ?></h5>
                          </div>
                          <div class="d-flex align-items-center gap-2">
                            <span><i class="fa-regular fa-clock" style="color: #434041;"></i></span>
                            <span class="d-none event-fecha" id="fechaInicioModal"><?php echo $evento->fechainicio; ?></span>                        
                            <span class="d-none event-fecha" id="fechaFinModal"><?php echo $evento->fechafin . " ";?></span>

                            <span class="card-text horainicio" id="horainicio"><?php echo $evento->horainicio; ?>  </span> 
                            
                            <?php
                            //echo var_dump($evento->fechainicio,$evento->fechafin);
                            ?>
                          </div>
                          <div class="d-flex align-items-center gap-2">
                            <span class=""><i class="fa-solid fa-location-dot" style="color: #434041;"></i></span>
                            <p class="card-text line-clamp-3" id="direccion"><?php echo $evento->direccion . ' - ' . $evento->localidad; ?></p>
                            <span class="d-none" id="latitud"><?php echo $evento->latitud; ?></span>
                            <span class="d-none" id="longitud"><?php echo $evento->longitud; ?></span>
                          </div>
                          <div class="align-items-center d-none">
                            <p class=" card-text text-gray-800  mb-0" id="descripcion">
                              <?php echo $evento->descripcion; ?>
                            </p>
                          </div>
                          <div>
                            <button type="button" class="btn boton-bg text-white" data-bs-toggle="modal" data-bs-target="#eventoEventModal" onClick=""><?php echo $idioma == 1 ? "Conocé más aquí" : "Learn more"; ?></button>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php
                  endif;
                  $indexEvento++;
                endfor;
                ?>
              </div>
            </div>
          <?php endfor; ?>
        </div>
        <button class="carousel-control-prev custom-control-prev h-100" type="button" data-bs-target="#carruselEventosCompu" data-bs-slide="prev">
          <i class="bi bi-arrow-left-circle-fill text-black"></i> <!-- Icono del botón "Previous" -->
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next custom-control-next h-100" type="button" data-bs-target="#carruselEventosCompu" data-bs-slide="next">
          <i class="bi bi-arrow-right-circle-fill text-black"></i> <!-- Icono del botón "Next" -->
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </section>

  <!-- eventos movile -->
  <div class="container-carousel eventsMobile">
    <section class="mb-5 justify-content-center d-block d-lg-none">
      <div class=" row mt-4 mb-4 d-flex  justify-content-evenly">
        <div class="col-8 justify-content-center gap-3">
          <h3 class="my-4 text-center fs-2 text-gris text-uppercase"><?php echo $idioma == 1 ? "Eventos destacados" : "Highlighted events"; ?></h3>
        </div>
        <div class="col-4 justify-content-end">
          <a class="btn boton-bg text-white fw-bold mt-3 mb-3 " href="<?php echo constant('URL'); ?>eventos" onClick=""><?php echo $idioma == 1 ? "Más Eventos..." : "More Events..."; ?></a>
        </div>
      </div>
      <div id="carruselEventosMobile" class="carousel slide carruEvent">
        <div class="carousel-inner">
          <?php
          $totalEventos = count($eventos);
          $totalFilas = ceil($totalEventos / 1);
          $indexEvento = 0;
          for ($i = 0; $i < $totalFilas; $i++) :
          ?>
            <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>">
              <div class="d-flex justify-content-center">

                <?php if ($indexEvento < $totalEventos) : ?>
                  <div class="col-12 d-flex justify-content-center position-relative p-1 event-card w-100">
                    <div class="card col-10 position-relative overflow-hidden rounded-0 border-0 d-flex flex-column">
                      <div class="ratio ratio-1x1 overflow-hidden mb-2">
                        <img id="imagen" src="<?php echo constant('URLApi') . 'public/img/' . $eventos[$indexEvento]->imagen ?>" class="img-fluid" alt="<?php echo $eventos[$indexEvento]->imagen; ?>">
                      </div>
                      <div class="rounded-bottom-2 font-event-cartas position-absolute bg-white fw-bold ps-1 pe-1 fechainicio">
                        <span class="event-fecha " id="fechainicio"><?php echo $eventos[$indexEvento]->fechainicio; ?> </span> -
                        <span class="event-fecha " id="fechafin"><?php echo $eventos[$indexEvento]->fechafin; ?></span>
                      </div>


                      <div class="d-flex flex-column justify-content-between flex-grow-1 gap-2">
                        <h5 class="card-title text-uppercase line-clamp-3 fw-bold mb-0" id="nombre"><?php echo $eventos[$indexEvento]->nombre; ?></h5>
                        <div class="d-none align-items-center gap-2 m-2">
                          <h5 class="text-uppercase fw-medium" id="subcategoria">Tipo de evento: <?php echo $eventos[$indexEvento]->subcategoria; ?></h5>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                          <span><i class="fa-regular fa-clock" style="color: #434041;"></i></span>

                          <span class="d-none fechainicio" id="fechaInicioModal"><?php echo $eventos[$indexEvento]->fechainicio; ?></span>
                          <span class="d-none fechainicio" id="fechaFinModal"><?php echo $eventos[$indexEvento]->fechainicio; ?></span>

                          <span class="card-text horainicio" id="horainicio"><?php echo $eventos[$indexEvento]->horainicio; ?></span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                          <span class=""><i class="fa-solid fa-location-dot" style="color: #434041;"></i></span>
                          <p class="card-text" id="direccion"><?php echo $eventos[$indexEvento]->direccion . ' - ' . $eventos[$indexEvento]->localidad; ?></p>
                        </div>

                        <div class="align-items-center d-none">
                          <p class=" card-text text-gray-800  mb-0" id="descripcion">
                            <?php echo $eventos[$indexEvento]->descripcion; ?>
                          </p>
                        </div>
                        <button type="button" class="btn boton-bg text-white " data-bs-toggle="modal" data-bs-target="#eventoEventModal" onClick=""><?php echo $idioma == 1 ? "Conocé más aquí" : "Learn more"; ?></button>
                      </div>
                    </div>
                  </div>
                  <?php $indexEvento++; ?>
                <?php endif; ?>
              </div>
            </div>
          <?php endfor; ?>
        </div>
        <button class="carousel-control-prev custom-control-prev" type="button" data-bs-target="#carruselEventosMobile" data-bs-slide="prev">
          <i class="bi bi-arrow-left-circle-fill text-black"></i> <!-- Icono del botón "Previous" -->
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next custom-control-next" type="button" data-bs-target="#carruselEventosMobile" data-bs-slide="next">
          <i class="bi bi-arrow-right-circle-fill text-black"></i> <!-- Icono del botón "next" -->
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </section>
  </div>







  <!-- Modal -->
  <div class="modal modal-lg fade" id="eventoEventModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

      </div>
    </div>
  </div>
</div>