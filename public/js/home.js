const myModalEvent = new bootstrap.Modal(document.getElementById('eventoEventModal'))
const urlLocalHome = URLHome;
const urlServerHome = "https://www.tucumanturismo.gob.ar/";
// var idioma = getParams.get('idioma') || 1;
// console.log(idioma);
/*const urlServerHome = "https:127.0.0.1/";*/
var bannerGastronomia = [
  {
    img: URLHome + "public/img/Banners/locro.jpg",
    url: urlLocalHome + "subsecciones/lista/37",
    title: "Deleitate con gastronomía única",
  },
  {
    img: URLHome + "public/img/Banners/empanadas.jpg",
    url: urlLocalHome + "subsecciones/lista/37",
    title: "Disfrutá de la Empanada más rica del país",
  },
  {
    img: URLHome + "public/img/Banners/milanesa.jpg",
    url: urlLocalHome + "subsecciones/lista/37",
    title: "No te pierdás la famosa Milanesa Tucumana",
  },
];

// Ahora la variable idioma contiene el valor del parámetro idioma de la URL

var bannerGastronomiaEN = [
  {
    img: URLHome + "public/img/Banners/locro.jpg",
    url: urlLocalHome + "subsecciones/lista/79?idioma=EN",
    title: "Indulge in unique gastronomy",
  },
  {
    img: URLHome + "public/img/Banners/empanadas.jpg",
    url: urlLocalHome + "subsecciones/lista/79?idioma=EN",
    title: "Enjoy the country's most delicious Empanada",
  },
  {
    img: URLHome + "public/img/Banners/milanesa.jpg",
    url: urlLocalHome + "subsecciones/lista/79?idioma=EN",
    title: "Don't miss the famous Milanesa Tucumana",
  },
];


var bannerRutas = [
  {
    img: URLHome + "public/img/Banners/fe.jpg",
    title: "Un viaje de Fe, un camino de encuentro",
    icono: "fe.svg",
    url: urlLocalHome + "articulos/articulo/90",
  },
  {
    img: URLHome + "public/img/Banners/vino.jpg",
    title: "El sabor de Tucumán en cada copa",
    icono: "vino.svg",
    url: urlLocalHome + "articulos/articulo/89",
  },
  {
    img: URLHome + "public/img/Banners/artesano.jpg",
    title: "Viví una experiencia artesanal",
    icono: "artesano.svg",
    url: urlLocalHome + "articulos/articulo/88",
  },
];

var bannerRutasEN = [
  {
    img: URLHome + "public/img/Banners/fe.jpg",
    title: "A journey of faith, a path of encounter",
    icono: "fe.svg",
    url: urlLocalHome + "articulos/articulo/90",
  },
  {
    img: URLHome + "public/img/Banners/vino.jpg",
    title: "The taste of Tucumán in every glass",
    icono: "vino.svg",
    url: urlLocalHome + "articulos/articulo/89",
  },
  {
    img: URLHome + "public/img/Banners/artesano.jpg",
    title: "Experience craftsmanship firsthand",
    icono: "artesano.svg",
    url: urlLocalHome + "articulos/articulo/88",
  },
];

$(document).ready(function () {
  
  // Obtener la URL actual
  var url = new URL(window.location.href);
  
  // Obtener el valor del parámetro idioma
  var idioma = url.searchParams.get('idioma');

  // Condición para asignar valor a isioma
  if (idioma === null || idioma === 'ES') {
    idioma = 1;
  } else {
    idioma = 2;
  }

  if (idioma == 2) {
    bannerGastronomia = bannerGastronomiaEN; // Usamos los banners en inglés
    bannerRutas = bannerRutasEN; // Usamos los banners en inglés
  }
});

// if (idioma != '1') {
//   bannerGastronomia = bannerGastronomiaEN; // Usamos los banners en inglés
// }

let currentIndexGast = 0;
let currentIndexRutas = 0;

function updateBannerGastronomia() {
  const currentBanner = bannerGastronomia[currentIndexGast];
  $('#bannerGatronomia').css('background-image', `url(${currentBanner.img})`);
  $('#bannerGastTitle').text(currentBanner.title);
  $('#bannerGastButton').off('click').on('click', function () {
    window.location.href = currentBanner.url;
  });

  currentIndexGast = (currentIndexGast + 1) % bannerGastronomia.length;
}
function updateBannerRutas() {
  const currentBanner = bannerRutas[currentIndexRutas];
  $('#iconoRuta').attr("src", `public/icons/svg/rutas/${currentBanner.icono}`);
  $('#img-rutas').attr("src", currentBanner.img);
  $('#bannerRutaTitle').text(currentBanner.title);
  $('#bannerRutas').off('click').on('click', function () {
    window.location.href = currentBanner.url;
  });

  currentIndexRutas = (currentIndexRutas + 1) % bannerRutas.length;
}

$(document).ready(function () {
  // Initialize the banner with the first item
  updateBannerGastronomia();
  updateBannerRutas();

  // Update the banner every 3 seconds
  setInterval(updateBannerGastronomia, 3000);
  setInterval(updateBannerRutas, 3000);
});

//------
console.log(myModalEvent);
// Cuando se hace clic en una tarjeta de alojamiento
$('.event-card').on('click', function () {
  const eventos = obtenerDatosEventos($(this)); // Función para obtener datos del eventos
  mostrarModalEventHome(eventos);
});
// Cuando se hace clic en el botón de cerrar del modal
$('#eventoEventModal').on('click', function () {
  cerrarModal();
});
function obtenerDatosEventos(tarjeta) {
  const evento = {
    nombre: tarjeta.find('#nombre').text().trim(),
    subcategoria: tarjeta.find('#subcategoria').text().trim(),
    fechaInicio: tarjeta.find('#fechaInicioModal').text().trim(),
    fechaFin: tarjeta.find('#fechaFinModal').text().trim(),
    horaInicio: tarjeta.find('#horainicio').text().trim(),
    direccion: tarjeta.find('#direccion').text().trim(),
    localidad: tarjeta.find('#localidad').text().trim(),
    latitud: tarjeta.find('#latitud').text().trim(),
    longitud: tarjeta.find('#longitud').text().trim(),
    descripcion: tarjeta.find('#descripcion').text().trim(),
    imagen: tarjeta.find('#imagen').attr("src"),

  };
  console.log(evento);
  return evento;
}
function mostrarModalEventHome(evento) {
  $('#eventoEventModal .modal-content').html(`
<div class="modal-content p-2">
        <div class="modal-header justify-content-end border-0 p-2">
          <button type="button" class="btn-close close fw-bold p-2" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body border-0 align-items-center">
          <h4 class="modal-title mb-3" id="nombre"> ${evento.nombre} </h4>
            <div class="row">
                <div class="col-lg-6 col-12 p-0 px-3">
                    <div>

                        <div class="d-flex align-items-center gap-2 m-2">
                            <h6 class="text-uppercase fw-medium" id="subcategoria">${evento.subcategoria}</h6>
                        </div>

                        <div class="d-flex align-items-center gap-2 m-2">
                            <span><i class="fa-regular fa-clock" style="color: #434041;"></i></span>
                            <div class="d-flex align-items-center">
                              <span class="fw-bold" id="fechaInicioModal">${evento.fechaInicio} -   
                                ${evento.fechaFin}  ${evento.horaInicio}</span>     
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 m-2">
                            <span class=""><i class="fa-solid fa-location-dot" style="color: #434041;"></i></span>
                            <p class="card-text text-gray-800 fw-bold mb-0" id="direccion">
                            ${evento.direccion} - ${evento.localidad}
                            </p>
                        </div>

                        <div class="align-items-center m-2">
                            <p class="card-text text-gray-800  mb-0" id="descripcion">
                            ${DataConverter.htmlParse(evento.descripcion)}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12 p-0">
                   <img id="modal-imagen" src="${evento.imagen}" class="img-fluid" alt="...">
                </div>      
            </div> 
            <div class="col m-2">
                <h5>Mapa</h5>
                <div class="" style="height: 200px;">
                  <iframe class="w-100"
                  src="//maps.google.com/maps?q=${evento.latitud},${evento.longitud}&z=15&output=embed"></iframe>
                </div>
          </div>   
        </div>   
        <div class="modal-footer justify-content-start align-items-center border-0 pt-0">
           <button type="button" class="btn boton-bg text-white " data-bs-dismiss="modal">Cerrar</button>
        </div>      
</div>
  `);
  // Muestra el modal
  myModalEvent.show();
}
function cerrarModal() {
  // Oculta el modal
  myModalEvent.hide();
}
////////////filtros- eventos/////////////////////////
document.addEventListener("DOMContentLoaded", function () {
  // Obtener todos los botones de filtro
  var filterButtons = document.querySelectorAll('.btn-filter');

  // Agregar evento de clic a cada botón
  filterButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      var filterValue = this.getAttribute('data-filter'); // Obtener el valor de filtro del atributo 'data-filter'
      var currentUrl = new URL(window.location.href); // Obtener la URL actual
      var searchParams = new URLSearchParams(currentUrl.search);
      // Eliminar el parámetro 'dia' si ya existe
      if (searchParams.has('dia')) {
        searchParams.delete('dia');
      }
      // Agregar el nuevo valor de filtro
      searchParams.append('dia', filterValue);
      // Actualizar la URL con los nuevos parámetros
      currentUrl.search = searchParams.toString();
      // Redireccionar a la nueva URL
      window.location.href = currentUrl.toString();
    });
  });



});

//------------------------------------------

// Cargador de video
$('#preload-image').show();
$(window).on('load', function () {
  $('#preload-image').hide();
  $('#video').show();
  $('#video').addClass("d-flex");
});