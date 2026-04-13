const contenedor = document.querySelector('#contenedorEventos');
const eventos = [
    {
        direcImg: "actividad1.webp",
        fecha: "7 MAY",
        titulo: "1° Encuentro la Federación Argentina de Ingeniería Especializada",
        horario: "16:00",
        direccion: "Consejo Profesional de la Ingeniería de Tucumán - Virgen de la Merced 179, San Miguel de Tucumán."
    },
    {
        direcImg: "public/img/eventos/actividad2.webp",
        fecha: "12 JUN",
        titulo: "Curso Superior de Medicina Interna: Avances en temas de Medicina Interna",
        horario: "16:00",
        direccion: "Colegio Médico de Tucumán - Las Piedras 496 San Miguel de Tucumán"
    },
    {
        direcImg: "public/img/eventos/actividad3.webp",
        fecha: "15 JUN",
        titulo: "Exposición de Fotografías",
        horario: "20:00",
        direccion: "Salón Comunal - Amaicha del Valle"
    },
    {
        direcImg: "public/img/eventos/actividad4.webp",
        fecha: "20 JUN",
        titulo: "CITY TOUR - Tafí Viejo",
        horario: "9:00",
        direccion: "CITY TOUR - Tafí Viejo"
    },
    {
        direcImg: "public/img/eventos/actividad5.webp",
        fecha: "4 MAY",
        titulo: "Día Nacional de la Zamba",
        horario: "16:00",
        direccion: "Virgen de la Merced 179 (6to piso), San Miguel de Tucumán"
    }
];

for (let i = 0; i < eventos.length; i++) {
    const {direcImg, fecha, titulo, horario, direccion} = eventos[i];
    
    contenedor.innerHTML += `<div class="card position-relative shadow-sm rounded-3 col-12 col-md-5 col-lg-4 mb-3 mx-0 mx-lg-3">
        <img src="public/img/eventos/${direcImg}" class="card-img-top" alt="Imagen del evento">
        <div class="rounded-bottom-2 font-event-cartas position-absolute bg-white text-muted ps-1 pe-1">
            ${fecha}
        </div>
        <div class="card-body">
            <h5 class="card-title text-uppercase">${titulo}</h5>
            <p class="card-text"><i class="bi bi-clock"></i>${horario}</p>
            <p class="card-text"><i class="bi bi-geo-alt"></i>${direccion}</p>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Conocé más aquí</button>
        </div>
    </div>`;
}