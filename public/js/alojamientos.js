// Verifica si la URL actual termina con "/alojamientos"
//if (window.location.pathname.endsWith("/alojamientos")) {
//    // Añadir un tema al array "Temas" en la cookie solo si estamos en la página "/alojamientos"
//    console.log("Estoy en la pagina de alojamientos");
//    agregarACookie("Datos.Temas", "Alojamientos, Hoteles");
//}

const myModalRest = new bootstrap.Modal(document.getElementById('alojamientoModal'));
const urlLocalAloj = URLHome;
const urlEstrellas = URLHome + 'public/icons/alojamiento/star.svg';
const urlImagenPorDefecto = './public/img/alojamiento/Hotel.svg';

$('.card-aloj').on('click', function () {
    const alojamiento = obtenerDatosAlojamiento($(this));
    mostrarModalAloj(alojamiento);
});

$('#cerrarModal').on('click', function () {
    cerrarModal();
});
function obtenerDatosAlojamiento(tarjeta) {
    const alojamiento = {
        nombre: tarjeta.find('#nombre').text().trim(),
        categoria: tarjeta.find('#categoria').text().trim(),
        imagen: tarjeta.find('#imagen').html(),
        estrellas: tarjeta.find('.star').length,
        direccion: tarjeta.find('#direccion').text().trim(),
        telefono: tarjeta.find('#telefono').text().trim(),
        web: tarjeta.find('#web').text().trim(),
        longitude: parseFloat(tarjeta.find('#longitude').text().trim()),
        latitude: parseFloat(tarjeta.find('#latitude').text().trim()),
        servicios: tarjeta.find('#servicios').html(),
    };
    return alojamiento;
}


function mostrarModalAloj(alojamiento) {
    const imagen = alojamiento.imagen;
    console.log(imagen)

    $('#alojamientoModal .modal-content').html(`
        <div class="modal-content">
            <div class="modal-header align-items-start">
                <div class="d-flex align-items-md-center flex-column flex-md-row">
                    <h4 class="modal-title col-lg-8 fs-3 fw-bold order-2 order-md-1">${alojamiento.nombre}</h4>
                    <div id="modal-estrellas" class="d-flex col-lg-4 align-items-center ms-md-3 order-1 order-md-2">
                        ${Array.from({ length: alojamiento.estrellas }).map(() => {
                            return `<img class='img-fluid col-1 star' src='${urlEstrellas}' alt=''>`;
                        }).join('')}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col flex-lg-row mb-3">
                    <div class="col-12 col-lg-7 rounded mx-auto mb-4 mb-lg-0 flex-column px-md-3 px-0">
                        <div class="row-md-8 justify-content-center align-items-center mb-4">
                            <div class="d-flex justify-content-center align-items-center w-100" style="height: 235px;">
                                ${imagen}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap rounded mx-auto mb-4 mb-lg-0 px-md-3 px-0">
                        <div class="col-md-6 bg-color py-4 px-3">
                            <h5 class="fw-bold">Datos del alojamiento</h5>
                            <h6 class="main-text mb-3 align-items-center d-flex">
                                <i class="fa-solid fa-house me-2" style="color: #374151;"></i>
                                <span>${alojamiento.direccion}</span>
                            </h6>
                            <h6 class="main-text mb-3 align-items-center d-flex">
                                <i class="fa-solid fa-phone me-2" style="color: #374151;"></i>
                                <span>${alojamiento.telefono}</span>
                            </h6>
                            <h6 class="main-text mb-0 align-items-center d-flex text-break">
                                <i class="fa-solid fa-earth-americas me-2" style="color: #374151;"></i>
                                <span>${alojamiento.web}</span>
                            </h6>
                        </div>
                        <div class="col-md-6 px-md-4 px-2 py-3 d-flex flex-column">
                            <h5 class="mb-1 fw-bold">Servicios:</h5>
                            <div id="modal-servicios" class="main-text d-flex flex-column fs-4">
                                <ul class="main-text text-servicios fw-bold">${alojamiento.servicios}</ul>
                            </div>
                        </div>
                    </div>                        
                </div>
                <div class="col">
                    <h3 class="mb-3 text-aloj text-center">Mapa</h3>
                    <div class="rounded overflow-hidden border" style="height: 25vh;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3560.2842748526396!2d${alojamiento.longitude}!3d${alojamiento.latitude}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMjbCsDQ5JzUxLjMiUyA2NcKwMTInMDYuOSJX!5e0!3m2!1ses-419!2sar!4v1717594569358!5m2!1ses-419!2sar" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-color-4 text-white rounded border-0" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    `);

    myModalRest.show();
}
function cerrarModal() {
    // Oculta el modal
    myModalRest.hide();
}