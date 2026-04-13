//formateo de la fecha de los eventos 
document.addEventListener("DOMContentLoaded", function () {
    const months = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"];
    
    // Función para formatear la fecha en formato "día mes abreviado"
    function formatDate(dateString) {
        const dateParts = dateString.split('/'); // Separar por "/"
        const day = dateParts[0];  // Obtener el día
        const month = dateParts[1];  // Obtener el mes
        const monthName = months[parseInt(month) - 1];  // Obtener el nombre del mes abreviado
        return `${day} ${monthName}`;  // Retornar día y mes abreviado
    }

    // Seleccionar los spans que contienen la fecha de inicio y la fecha de fin
    const fechaInicioDivs = document.querySelectorAll("#fechainicio");  // Usamos ID para la fecha de inicio
    const fechaFinDivs = document.querySelectorAll("#fechafin");  // Usamos ID para la fecha de fin

    // Formatear las fechas de inicio
    fechaInicioDivs.forEach(span => {
        const fechaOriginal = span.textContent.trim();  // Obtener texto original
        const fechaFormateada = formatDate(fechaOriginal);  // Formatear la fecha
        span.textContent = fechaFormateada;  // Actualizar el texto con la fecha formateada
    });

    // Formatear las fechas de fin
    fechaFinDivs.forEach(span => {
        const fechaOriginal = span.textContent.trim();  // Obtener texto original
        const fechaFormateada = formatDate(fechaOriginal);  // Formatear la fecha
        span.textContent = fechaFormateada;  // Actualizar el texto con la fecha formateada
    });
});
function formatearHora(fecha) {
    const hora = fecha.getHours();
    const minutos = fecha.getMinutes();
    const minutosFormateados = (minutos < 10 ? '0' : '') + minutos;
    return hora + ':' + minutosFormateados +' hs.';
}

// Obtener todos los elementos  que necesitan ser formateados
const horaParrafos = document.querySelectorAll(".horainicio");

// Utilizar el método forEach para iterar sobre cada elemento
horaParrafos.forEach(span => {
    // Obtener el texto del párrafo y limpiar los espacios en blanco
    const horaOriginal = span.textContent.trim();
    
    // Crear un objeto de fecha a partir de la cadena de hora original
    const partesHora = horaOriginal.split(':');
    const fecha = new Date();
    fecha.setHours(parseInt(partesHora[0]));
    fecha.setMinutes(parseInt(partesHora[1]));
    
    // Formatear la hora
    const horaFormateada = formatearHora(fecha);
    
    // Actualizar el texto del párrafo con la hora formateada
    span.textContent = horaFormateada;
});

function buscarEventosPorFecha() {
    // Obtener los valores de las fechas desde y hasta
    var fechaIni = document.getElementById('fecha-ini').value;
    var fechaFin = document.getElementById('fecha-fin').value;

    // Convertir las fechas en objetos Date para comparación
    var fechaInicio = new Date(fechaIni);
    var fechaFinal = new Date(fechaFin);

    // Validar que las fechas sean válidas y la fecha inicial no sea mayor que la fecha final
    if (fechaInicio && fechaFinal && fechaInicio <= fechaFinal) {
        // Filtrar los eventos visibles en la página
        var eventos = document.querySelectorAll('.event-card');

        eventos.forEach(function(evento) {
            var fechaEvento = new Date(evento.querySelector('.fechainicio').textContent.trim());

            // Mostrar o esconder el evento según esté dentro del rango de fechas seleccionado
            if (fechaEvento >= fechaInicio && fechaEvento <= fechaFinal) {
                evento.style.display = 'block';  // Mostrar el evento
            } else {
                evento.style.display = 'none';   // Ocultar el evento
            }
        });
    } else {
        // Si las fechas no son válidas, mostrar un mensaje de error o manejarlo según tu aplicación
        alert('Por favor selecciona un rango de fechas válido.');
    }
}

document.getElementById('filtro-evento').addEventListener('submit', function(event) {
        /* event.preventDefault();
        buscarEventosPorFecha(); */
 });

