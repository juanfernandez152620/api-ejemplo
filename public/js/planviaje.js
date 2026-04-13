var articulo = document.getElementById("art-Alojamientos-tab");
articulo.classList.add("active-plan");

function mostrarPlanViaje(siguiente, idBoton) {
  var activeChild = document.querySelector('.active-plan');
  var nextChild = document.getElementById(siguiente);
  var container = document.getElementById('contenedor-plan');

  // Crear el diccionario de IDs a números
  var idToNumber = {
    "art-Alojamientos-tab": 1,
    "art-Transport-tab": 2,
    "art-Alquiler-Autos-tab": 3,
    "art-Prestadores-activos-tab": 4,
    "art-Agencias-tab": 5,
    "art-Guias-Turismo-tab": 6,
    "art-Itinerarios-tab": 7,
    "art-Mapas-folletos-tab": 8
  };

  // Función para obtener el número correspondiente a una ID de div
  function getNumberFromId(id) {
    return idToNumber[id];
  }

  if (getNumberFromId(activeChild.id) < getNumberFromId(nextChild.id)) {

    activeChild.classList.add('prev-active-plan');
    activeChild.classList.remove('active-plan');

    nextChild.classList.add('active-plan');

    container.classList.add('animation-left-plan');

    setTimeout(function () {
      container.classList.remove('animation-left-plan');
      activeChild.classList.remove('prev-active-plan');
    }, 300);

  } else if (getNumberFromId(activeChild.id) > getNumberFromId(nextChild.id)) {

    activeChild.classList.add('prev-active-plan');
    activeChild.classList.remove('active-plan');

    nextChild.classList.add('active-plan');

    container.classList.add('animation-right-plan');

    setTimeout(function () {
      container.classList.remove('animation-right-plan');
      activeChild.classList.remove('prev-active-plan');
    }, 300);

  } else {
    console.log('No se puede realizar la animación');
  }
  
  $(`#${idBoton}`).attr("aria-label", "Avanze hacia el final de las secciones para encontrar el contenido");

};

$(document).ready(function () {
  // Scroll smoothly to the corresponding tab-pane
  $('.planviaje-btn').on('click', function () {
    console.log("hago clickkkkk")
    $('html, body').animate({
      scrollTop: $("#contenedor-plan").offset().top - 130
    }, 100);
  });
});