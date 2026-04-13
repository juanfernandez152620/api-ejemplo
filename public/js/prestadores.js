const contenedor = document.querySelector('#contenedor');
const prestadores = [ 
    {
        nombre: "Prestador 1",
        nombreCompleto: "Nombre y Apellido del prestador",
        direccion: "Dirección del prestador N° 1456",
        localidad: "Yerba Buena",
        actividades: ["Trekking", "safaris fotográficos", "senderismo", "cabalgatas"],
        tel: "(0381)45665484",
        mail: "correodelprestador@mail.com"
    },
    {
        nombre: "Prestador 2",
        nombreCompleto: "Nombre y Apellido del prestador",
        direccion: "Dirección del prestador N° 1457",
        localidad: "Yerba Buena",
        actividades: ["Trekking", "safaris fotográficos", "senderismo", "cabalgatas"],
        tel: "(0381)45665484",
        mail: "correodelprestador@mail.com"
    },
    {
        nombre: "Prestador 3",
        nombreCompleto: "Nombre y Apellido del prestador",
        direccion: "Dirección del prestador N° 1458",
        localidad: "Yerba Buena",
        actividades: ["Trekking", "senderismo", "baja dificultad"],
        tel: "(0381)45665484",
        mail: "correodelprestador@mail.com"
    },
    {
        nombre: "Prestador 4",
        nombreCompleto: "Nombre y Apellido del prestador",
        direccion: "Dirección del prestador N° 1459",
        localidad: "Yerba Buena",
        actividades: ["Trekking baja y moderada dificultad"],
        tel: "(0381)45665484",
        mail: "correodelprestador@mail.com"
    },
    {
        nombre: "Prestador 5",
        nombreCompleto: "Nombre y Apellido del prestador",
        direccion: "Dirección del prestador N° 1460",
        localidad: "Yerba Buena",
        actividades: ["Trekking", "senderismo"],
        tel: "(0381)45665484",
        mail: "correodelprestador@mail.com"
    },
    {
        nombre: "Prestador 6",
        nombreCompleto: "Nombre y Apellido del prestador",
        direccion: "Dirección del prestador N° 1461",
        localidad: "Yerba Buena",
        actividades: ["Trekking", "senderismo", "baja dificultad"],
        tel: "(0381)45665484",
        mail: "correodelprestador@mail.com"
    }
];

for (let i = 0; i < prestadores.length; i++) { 
    
    const {nombre, nombreCompleto, direccion, localidad, actividades, tel, mail} = prestadores[i];
    const actividadesHTML = actividades.map(actividad => `<li class="d-inline-block ms-1">${actividad}</li>`).join('');

    contenedor.innerHTML += `<div class="shadow-sm rounded-3 col-12 col-md-5 col-lg-4 mb-3 mx-0 mx-lg-3 max-width-card-pres">
        <div class="titulo-bg rounded-top-3">
            <p class="text-white fw-bold p-3 m-0 fs-4">
                ${nombre}
            </p>
        </div>
        <div class="p-3">
            <p class="fw-semibold">${nombreCompleto}</p>
            <p class="">${direccion}</p>
            <p class="">${localidad}</p>
            <p class="text-uppercase mt-3">Actividades habilitadas</p>
            <ul class="list-unstyled p-0">
                ${actividadesHTML}
            </ul>

            <p class="mt-3">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 32 32" class="inline-block text-lg mr-2 text-gray-600" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 19 0 L 19 2 C 25.065 2 30 6.935 30 13 L 32 13 C 32 5.832 26.168 0 19 0 z M 8.6503906 3.0058594 C 8.1255067 3.0058594 7.6010996 3.1771036 7.1738281 3.5214844 L 7.125 3.5605469 L 3.9804688 6.8046875 L 4.046875 6.7421875 C 3.0062406 7.6384134 2.7194504 9.0541178 3.1660156 10.244141 C 4.0086833 12.674074 6.1568506 17.372953 10.394531 21.605469 C 14.641274 25.857394 19.389499 27.91343 21.738281 28.830078 L 21.761719 28.837891 L 21.785156 28.845703 C 22.999558 29.252758 24.311449 28.962441 25.251953 28.158203 L 25.28125 28.132812 L 28.40625 25.007812 C 29.235762 24.178301 29.235762 22.724043 28.40625 21.894531 L 24.308594 17.792969 C 23.476725 16.9611 22.023275 16.9611 21.191406 17.792969 L 19.207031 19.777344 C 18.496637 19.438281 16.74879 18.558644 15.087891 16.974609 C 13.448585 15.411343 12.61169 13.604382 12.308594 12.90625 L 14.308594 10.90625 C 15.186189 10.028655 15.236115 8.5219515 14.224609 7.7167969 L 14.316406 7.8007812 L 10.173828 3.5605469 L 10.126953 3.5214844 C 9.6996708 3.1771284 9.1752746 3.0058594 8.6503906 3.0058594 z M 19 4 L 19 6 C 22.859 6 26 9.14 26 13 L 28 13 C 28 8.038 23.963 4 19 4 z M 8.6523438 5 C 8.7236064 4.9998777 8.7939074 5.026734 8.8632812 5.0800781 L 12.929688 9.2441406 L 12.978516 9.2832031 C 12.967016 9.2740531 13.016941 9.3697821 12.894531 9.4921875 L 9.9375 12.449219 L 10.166016 13.052734 C 10.166016 13.052734 11.294663 16.121404 13.707031 18.421875 C 16.079259 20.684317 19.003906 21.919922 19.003906 21.919922 L 19.626953 22.185547 L 22.605469 19.207031 C 22.7736 19.0389 22.7264 19.0389 22.894531 19.207031 L 26.992188 23.308594 C 27.162675 23.479082 27.162676 23.423262 26.992188 23.59375 L 23.945312 26.640625 C 23.481361 27.034971 23.00205 27.13833 22.425781 26.947266 C 20.163651 26.062686 15.739269 24.126878 11.808594 20.191406 C 7.8506547 16.238292 5.7997308 11.746595 5.046875 9.5703125 L 5.0429688 9.5585938 L 5.0371094 9.5449219 C 4.8856915 9.1444833 4.9963719 8.5637134 5.3515625 8.2578125 L 5.3847656 8.2285156 L 8.4394531 5.0800781 C 8.508857 5.0272234 8.5810811 5.0001223 8.6523438 5 z M 19 8 L 19 10 C 20.654 10 22 11.346 22 13 L 24 13 C 24 10.243 21.757 8 19 8 z"></path>
                </svg>

                ${tel}
            </p>

            <p>
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 32 32" class="inline-block text-lg text-gray-600 mr-2" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 3 8 L 3 26 L 29 26 L 29 8 Z M 7.3125 10 L 24.6875 10 L 16 15.78125 Z M 5 10.875 L 15.4375 17.84375 L 16 18.1875 L 16.5625 17.84375 L 27 10.875 L 27 24 L 5 24 Z">
                        </path>
                </svg>

                ${mail}
            </p>

            <p class="text-uppercase mt-3">Encontranos en</p>

            <p>
                <svg stroke="currentColor" fill="#65a30d" stroke-width="0" viewBox="0 0 256 256" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24ZM101.63,168h52.74C149,186.34,140,202.87,128,215.89,116,202.87,107,186.34,101.63,168ZM98,152a145.72,145.72,0,0,1,0-48h60a145.72,145.72,0,0,1,0,48ZM40,128a87.61,87.61,0,0,1,3.33-24H81.79a161.79,161.79,0,0,0,0,48H43.33A87.61,87.61,0,0,1,40,128ZM154.37,88H101.63C107,69.66,116,53.13,128,40.11,140,53.13,149,69.66,154.37,88Zm19.84,16h38.46a88.15,88.15,0,0,1,0,48H174.21a161.79,161.79,0,0,0,0-48Zm32.16-16H170.94a142.39,142.39,0,0,0-20.26-45A88.37,88.37,0,0,1,206.37,88ZM105.32,43A142.39,142.39,0,0,0,85.06,88H49.63A88.37,88.37,0,0,1,105.32,43ZM49.63,168H85.06a142.39,142.39,0,0,0,20.26,45A88.37,88.37,0,0,1,49.63,168Zm101.05,45a142.39,142.39,0,0,0,20.26-45h35.43A88.37,88.37,0,0,1,150.68,213Z"></path>
                </svg>

                <svg stroke="currentColor" fill="#65a30d" stroke-width="0" viewBox="0 0 32 32" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 16 4 C 9.3844276 4 4 9.3844276 4 16 C 4 22.615572 9.3844276 28 16 28 C 22.615572 28 28 22.615572 28 16 C 28 9.3844276 22.615572 4 16 4 z M 16 6 C 21.534692 6 26 10.465308 26 16 C 26 21.027386 22.311682 25.161277 17.488281 25.878906 L 17.488281 18.916016 L 20.335938 18.916016 L 20.783203 16.023438 L 17.488281 16.023438 L 17.488281 14.443359 C 17.488281 13.242359 17.882859 12.175781 19.005859 12.175781 L 20.810547 12.175781 L 20.810547 9.6523438 C 20.493547 9.6093438 19.822688 9.515625 18.554688 9.515625 C 15.906688 9.515625 14.355469 10.913609 14.355469 14.099609 L 14.355469 16.023438 L 11.632812 16.023438 L 11.632812 18.916016 L 14.355469 18.916016 L 14.355469 25.853516 C 9.6088556 25.070647 6 20.973047 6 16 C 6 10.465308 10.465308 6 16 6 z"></path>
                </svg>

                <svg stroke="currentColor" fill="#65a30d" stroke-width="0" viewBox="0 0 32 32" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 11.46875 5 C 7.917969 5 5 7.914063 5 11.46875 L 5 20.53125 C 5 24.082031 7.914063 27 11.46875 27 L 20.53125 27 C 24.082031 27 27 24.085938 27 20.53125 L 27 11.46875 C 27 7.917969 24.085938 5 20.53125 5 Z M 11.46875 7 L 20.53125 7 C 23.003906 7 25 8.996094 25 11.46875 L 25 20.53125 C 25 23.003906 23.003906 25 20.53125 25 L 11.46875 25 C 8.996094 25 7 23.003906 7 20.53125 L 7 11.46875 C 7 8.996094 8.996094 7 11.46875 7 Z M 21.90625 9.1875 C 21.402344 9.1875 21 9.589844 21 10.09375 C 21 10.597656 21.402344 11 21.90625 11 C 22.410156 11 22.8125 10.597656 22.8125 10.09375 C 22.8125 9.589844 22.410156 9.1875 21.90625 9.1875 Z M 16 10 C 12.699219 10 10 12.699219 10 16 C 10 19.300781 12.699219 22 16 22 C 19.300781 22 22 19.300781 22 16 C 22 12.699219 19.300781 10 16 10 Z M 16 12 C 18.222656 12 20 13.777344 20 16 C 20 18.222656 18.222656 20 16 20 C 13.777344 20 12 18.222656 12 16 C 12 13.777344 13.777344 12 16 12 Z"></path>
                </svg>
            </p>

            <p class="text-uppercase mt-3">Descargas</p>

            <p>
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="inline-block text-lg mr-2" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M64 464H96v48H64c-35.3 0-64-28.7-64-64V64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V288H336V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16zM176 352h32c30.9 0 56 25.1 56 56s-25.1 56-56 56H192v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V448 368c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24H192v48h16zm96-80h32c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H304c-8.8 0-16-7.2-16-16V368c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H320v96h16zm80-112c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16s-7.2 16-16 16H448v32h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H448v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V432 368z"></path>
                </svg>

                Credencial de habilitación
            </p>
        </div>
    </div>`;

}