$(document).ready(function () {
    const parallaxImage = document.getElementById("parallaxImage");
    const parallaxContainer = document.querySelector(".parallax-container");

    // Adjust container height based on image height after the image loads
    parallaxImage.onload = function () {
        console.log(parallaxImage.clientHeight);
        parallaxContainer.style.height = parallaxImage.clientHeight + "px";
    };

    // Add scroll event listener for parallax effect
    window.addEventListener('scroll', function () {
        const scrollTop = window.pageYOffset;
        parallaxImage.style.transform = `translateY(${scrollTop * 0.5}px)`;
    });
});

const DataConverter = {};

DataConverter.htmlParse = (str) => {
    var rp = String(str);
    rp = rp.replace(/<[^>]*>?/g, '');
    //
    rp = rp.replace(/&aacute;/g, 'á');
    rp = rp.replace(/&eacute;/g, 'é');
    rp = rp.replace(/&iacute;/g, 'í');
    rp = rp.replace(/&oacute;/g, 'ó');
    rp = rp.replace(/&uacute;/g, 'ú');
    rp = rp.replace(/&ntilde;/g, 'ñ');
    rp = rp.replace(/&uuml;/g, 'ü');
    //
    rp = rp.replace(/&Aacute;/g, 'Á');
    rp = rp.replace(/&Eacute;/g, 'É');
    rp = rp.replace(/&Iacute;/g, 'Í');
    rp = rp.replace(/&Oacute;/g, 'Ó');
    rp = rp.replace(/&Uacute;/g, 'Ú');
    rp = rp.replace(/&Ñtilde;/g, 'Ñ');
    rp = rp.replace(/&Üuml;/g, 'Ü');
    //
    rp = rp.replace(/&nbsp;/g, '');
    return rp;
}


DataConverter.dateStringParse = (fecha) => {
    const meses = [
        "enero", "febrero", "marzo", "abril", "mayo", "junio",
        "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"
    ];

    const [dia, mes, año] = fecha.split('/').map(Number);

    const fechaUTC = new Date(Date.UTC(año, mes - 1, dia));

    const fechaFormateada = `${dia} de ${meses[fechaUTC.getUTCMonth()]}`;

    return fechaFormateada;
}

// $('#loader-container').show();
// $('#loader-container').addClass("d-flex");
// $(window).on('load', function () {
//     $('#loader-container').hide();
//     $('#loader-container').removeClass("d-flex");
// });
// $(".seccion-button").click(function () {
//     $('#loader-container').show();
//     $('#loader-container').addClass("d-flex");
// });

$(document).ready(function () {
    // Muestra el loader cuando la página se está cargando
    // Oculta el loader después de que la página se haya cargado completamente
});

//formateo de la fecha de los eventos 
document.addEventListener("DOMContentLoaded", function () {
    const months = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"];

    function formatDate(dateString) {
        const dateParts = dateString.split('/');
        const day = dateParts[0];
        const month = dateParts[1];
        const year = dateParts[2];
        const monthName = months[parseInt(month) - 1];
        return `${day} ${monthName}`;
    }
    // Seleccionar el div que contiene la fecha
    const fechaDivs = document.querySelectorAll(".fecha-div");

    fechaDivs.forEach(div => {
        // Obtener el valor de la fecha del div
        const fechaOriginal = div.textContent.trim();
        // Formatear la fecha
        const fechaFormateada = formatDate(fechaOriginal);
        // Actualizar el contenido del div con la fecha formateada
        div.textContent = fechaFormateada;
    });
});
