document.addEventListener('DOMContentLoaded', function () {
    var thumbnails = document.querySelectorAll('.thumbnail');
    var carouselSuperior = document.getElementById('carouselExample');

    // Al hacer clic en una imagen del carrusel inferior
    thumbnails.forEach(function (thumbnail, index) {
        thumbnail.addEventListener('click', function () {
            // Cambiar la imagen activa en el carrusel superior
            var bsCarousel = bootstrap.Carousel.getInstance(carouselSuperior); // Obtener la instancia de Bootstrap
            bsCarousel.to(index); // Cambiar a la imagen correspondiente en el carrusel superior

            // Cambiar el estilo de la miniatura activa
            thumbnails.forEach(function (thumb) {
                thumb.classList.remove('active');
            });
            thumbnail.classList.add('active');
        });
    });

    // Sincronizar la miniatura activa con la imagen actual del carrusel superior
    carouselSuperior.addEventListener('slid.bs.carousel', function (e) {
        var activeIndex = e.to;
        thumbnails.forEach(function (thumb) {
            thumb.classList.remove('active');
        });
        thumbnails[activeIndex].classList.add('active');
    });
});
var URLHome = "https://www.tucumanturismo.gob.ar/";

// Variables de control del audio
const player = document.getElementById('player');
const progressBar = document.getElementById('progressBar');
const playPauseBtn = document.getElementById('playPause');
const currentTimeEl = document.getElementById('currentTime');
const durationEl = document.getElementById('duracion'); // Cambiado
const forwardBtn = document.getElementById('forward');
const rewindBtn = document.getElementById('rewind');
const volumeIcon = document.getElementById('volume');
const volumeBar = document.getElementById('volumeBar');
const volumeControl = document.getElementById('volumeControl');

const UrlPlay = URLHome + 'public/img/audioguias/play-solid.svg';
const UrlPause = URLHome + 'public/img/audioguias/pause-solid.svg';

// Play/Pause toggle
playPauseBtn.addEventListener('click', () => {
    if (player.paused) {
        player.play();
        playPauseBtn.innerHTML = `<img id="playIcon" src="${UrlPause}" alt="Pause" width="40" />`;
    } else {
        player.pause();
        playPauseBtn.innerHTML = `<img id="playIcon" src="${UrlPlay}" alt="Play" width="40" />`;
    }
});

// Actualizar barra de progreso y tiempo actual
player.addEventListener('timeupdate', () => {
    if (player.duration) {
        const progressPercent = (player.currentTime / player.duration) * 100;
        progressBar.value = progressPercent;

        progressBar.classList.add('progressing');
        currentTimeEl.textContent = formatTime(player.currentTime);
    }
});

// Permitir saltar a través de la barra de progreso
progressBar.addEventListener('input', () => {
    if (player.duration) {
        const newTime = (progressBar.value / 100) * player.duration;
        player.currentTime = newTime;
    }
});

// Avanzar/Rebobinar
forwardBtn.addEventListener('click', () => {
    player.currentTime = Math.min(player.currentTime + 10, player.duration);
});

rewindBtn.addEventListener('click', () => {
    player.currentTime = Math.max(player.currentTime - 10, 0);
});

// Formatear tiempo en minutos y segundos
function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
}

// Cargar la duración del audio al cargar los metadatos
player.addEventListener('loadedmetadata', () => {
    durationEl.textContent = formatTime(player.duration);
});


// Actualizar barra de progreso en clic o arrastre
progressBar.addEventListener('change', () => {
    if (player.duration) {
        const seekTime = (progressBar.value / 100) * player.duration;
        player.currentTime = seekTime;
    }
});

// Reiniciar barra y tiempo cuando el audio termina
player.addEventListener('ended', () => {
    progressBar.classList.remove('progressing');
    progressBar.value = 0;
    currentTimeEl.textContent = '00:00';
});

// Mostrar/ocultar control de volumen al hacer clic en el ícono de volumen
volumeIcon.addEventListener('click', () => {
    volumeControl.style.display = volumeControl.style.display === 'none' ? 'flex' : 'none';
});

// Controlar el volumen del audio
volumeBar.addEventListener('input', () => {
    player.volume = volumeBar.value;
});
