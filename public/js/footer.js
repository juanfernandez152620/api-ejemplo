// Funciones de encriptar y desencriptar
const key = CryptoJS.enc.Utf8.parse("EATT8521EATT8521EATT8521EATT8521");

function encriptar(string) {
    const iv = CryptoJS.lib.WordArray.random(16); // Generar un nuevo IV para cada encriptación
    const encrypted = CryptoJS.AES.encrypt(string, key, { iv: iv });
    return `${iv.toString(CryptoJS.enc.Hex)}:${encrypted.ciphertext.toString(CryptoJS.enc.Base64)}`;
}

// function desencriptar(encryptedString) {
//     const [ivHex, encryptedBase64] = encryptedString.split(':');
//     if (!ivHex || !encryptedBase64) {
//         throw new Error('Invalid encrypted string format');
//     }
//     const iv = CryptoJS.enc.Hex.parse(ivHex);
//     const encrypted = CryptoJS.enc.Base64.parse(encryptedBase64);
//     const decrypted = CryptoJS.AES.decrypt({ ciphertext: encrypted }, key, { iv: iv });
//     return decrypted.toString(CryptoJS.enc.Utf8);
// }

function desencriptar(encryptedString, key) {
    const [ivHex, encryptedBase64] = encryptedString.split(':'); // Divide la cadena en IV y texto encriptado
    if (!ivHex || !encryptedBase64) {
        throw new Error('Invalid encrypted string format');
    }
    const iv = CryptoJS.enc.Hex.parse(ivHex); // Convierte el IV de hexadecimal a formato de bytes
    const encrypted = CryptoJS.enc.Base64.parse(encryptedBase64); // Convierte el texto encriptado a base64
    const decrypted = CryptoJS.AES.decrypt({ ciphertext: encrypted }, key, { iv: iv }); // Desencripta el texto
    return decrypted.toString(CryptoJS.enc.Utf8); // Devuelve el texto desencriptado
}

// Funciones de cookies
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = `${name}=${value};${expires};path=/`;
}

function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function modificarJson(json, atributo, valor) {
  // Divide el atributo por puntos para soportar propiedades anidadas
  const keys = atributo.split('.');
  let target = json;

  // Navega hasta el último objeto antes del atributo final
  for (let i = 0; i < keys.length - 1; i++) {
    target = target[keys[i]];
    if (target === undefined) {
      console.error(`El atributo '${atributo}' no existe en el JSON.`);
      return;
    }
  }

  const finalKey = keys[keys.length - 1];

  // Si el atributo es 'Permiso' y el valor es booleano, actualiza directamente
  if (finalKey === 'Permiso' && typeof valor === 'boolean') {
    json.Permiso = valor;
    //console.log(`'Permiso' actualizado a:`, json.Permiso);
  }
  // Si el atributo es 'id' y el valor es un número entero, actualiza directamente
  else if (finalKey === 'id' && Number.isInteger(valor)) {
    json.id = valor;
    //console.log(`'id' actualizado a:`, json.id);
  }
  // Si el atributo es un array, agrega el valor al array
  else if (Array.isArray(target[finalKey])) {
    target[finalKey].push(valor);
    //console.log(`Valor añadido a ${atributo}:`, target[finalKey]);
  } else {
    console.error(`El atributo '${finalKey}' no es compatible con el valor proporcionado.`);
  }
}

function agregarACookie(atributo, valor) {
    try {
        // Obtén el valor encriptado de la cookie
        const encryptedCookie = getCookie('__cookieSesion');

        // Desencripta el valor de la cookie para obtener el JSON
        const jsonData = JSON.parse(desencriptar(encryptedCookie));

        // Si el valor es un string con comas, divídelo en múltiples valores
        const valores = typeof valor === 'string' && valor.includes(',')
            ? valor.split(',').map(item => item.trim())
            : [valor]; // Si es un solo valor, lo envuelve en un array

        // Añade cada valor de la lista usando modificarJson
        valores.forEach(v => modificarJson(jsonData, atributo, v));

        // Encripta el JSON actualizado
        const encryptedValue = encriptar(JSON.stringify(jsonData));

        // Guarda la cookie actualizada con el nuevo valor encriptado
        setCookie('__cookieSesion', encryptedValue, 60); // 60 días de duración

        console.log(`Datos añadidos a '${atributo}' y cookie actualizada exitosamente.`);
    } catch (e) {
        console.error('Error al agregar los datos y actualizar la cookie:', e);
    }
}

//----------------------------- COOKIES COOKIE

// Mostrar el banner si la cookie no está establecida
window.onload = function() {
    if (!getCookie('__cookieSesion')) {
        document.getElementById('cookieBanner').classList.remove('d-none');
    }

    // //MOSTRAR LA COOKIE
    // console.log('Hola ejemplo de cookie');
    // const nombreCookie = '__cookieSesion';
    // const valorCookieEncriptado = getCookie(nombreCookie);
    // const valorCookieDesencriptado = desencriptar(valorCookieEncriptado, key);
    // console.log('Valor desencriptado de la cookie:', valorCookieDesencriptado);
};

// Ejemplo de uso:
//fetch(URLHome + 'data/cookie.json')
//  .then(response => response.json())
//  .then(data => {
//    // Cambia 'Permiso' a true
//    modificarJson(data, 'Permiso', true);
//    // Cambia 'id' a 123
//    modificarJson(data, 'id', 123);
//    
//    // Añade una nueva ubicación
//    const ubicacionObjeto = { "Lat": "", "Long": "" };
//    modificarJson(data, 'Datos.Informacion.Ubicacion', ubicacionObjeto);
//
//    console.log(data); // Muestra el JSON actualizado
//  })
//  .catch(error => console.error('Error al cargar el JSON:', error));

// Manejar clic en el botón Aceptar
document.getElementById('aceptarCookies').addEventListener('click', async function() {
    try {
        // Llama al JSON desde el servidor
        const response = await fetch(URLHome + 'data/cookie.json');
        const jsonData = await response.json();

        // Modifica el valor de 'Permiso' a true
        jsonData.Permiso = true;

        // Encripta el JSON actualizado
        const encryptedValue = encriptar(JSON.stringify(jsonData));
        
        // Establece la cookie con el JSON encriptado
        setCookie('__cookieSesion', encryptedValue, 60); // Establece la cookie por 60 días

        // Oculta el banner de cookies y recarga la página
        document.getElementById('cookieBanner').classList.add('d-none');
        location.reload();
    } catch (e) {
        console.error('Error handling accept cookies:', e);
    }
});

// document.getElementById('aceptarCookies').addEventListener('click', function() {
//     try {
//         const encryptedValue = encriptar('1');
//         setCookie('__cookieSesion', encryptedValue, 60); // Establece la cookie por 60 días
//         document.getElementById('cookieBanner').classList.add('d-none');
//         location.reload(); // Recargar la página
//     } catch (e) {
//         console.error('Error handling accept cookies:', e);
//     }
// });

// Manejar clic en el botón Rechazar
document.getElementById('rechazarCookies').addEventListener('click', async function() {
    try {
        // Llama al JSON desde el servidor
        const response = await fetch(URLHome + 'data/cookie.json');
        const jsonData = await response.json();

        // Modifica el valor de 'Permiso' a false
        jsonData.Permiso = false;

        // Encripta el JSON actualizado
        const encryptedValue = encriptar(JSON.stringify(jsonData));

        // Establece la cookie con el JSON encriptado
        setCookie('__cookieSesion', encryptedValue, 1); // Establece la cookie por 1 día

        // Oculta el banner de cookies y recarga la página
        document.getElementById('cookieBanner').classList.add('d-none');
        location.reload();
    } catch (e) {
        console.error('Error handling reject cookies:', e);
    }
});

// document.getElementById('rechazarCookies').addEventListener('click', function() {
//     try {
//         const encryptedValue = encriptar('0');
//         setCookie('__cookieSesion', encryptedValue, 60); // Establece la cookie por 60 días
//         document.getElementById('cookieBanner').classList.add('d-none');
//         location.reload(); // Recargar la página
//     } catch (e) {
//         console.error('Error handling reject cookies:', e);
//     }
// });

//----------------------------- COOKIES COOKIE PRIVACIDAD

// Manejar clic en el botón Rechazar
document.getElementById('rechazarCookies2').addEventListener('click', async function() {
    try {
        // Llama al JSON desde el servidor
        const response = await fetch(URLHome + 'data/cookie.json');
        const jsonData = await response.json();

        // Modifica el valor de 'Permiso' a false
        jsonData.Permiso = false;

        // Encripta el JSON actualizado
        const encryptedValue = encriptar(JSON.stringify(jsonData));

        // Establece la cookie con el JSON encriptado
        setCookie('__cookieSesion', encryptedValue, 1); // Establece la cookie por 1 días

        // Oculta el banner de cookies y recarga la página
        document.getElementById('cookieBanner').classList.add('d-none');
        location.reload();
    } catch (e) {
        console.error('Error handling reject cookies:', e);
    }
});


// Manejar clic en el botón Aceptar
document.getElementById('aceptarCookies2').addEventListener('click', async function() {
    try {
        // Llama al JSON desde el servidor
        const response = await fetch(URLHome + 'data/cookie.json');
        const jsonData = await response.json();

        // Modifica el valor de 'Permiso' a true
        jsonData.Permiso = true;

        // Encripta el JSON actualizado
        const encryptedValue = encriptar(JSON.stringify(jsonData));
        
        // Establece la cookie con el JSON encriptado
        setCookie('__cookieSesion', encryptedValue, 60); // Establece la cookie por 60 días

        // Oculta el banner de cookies y recarga la página
        document.getElementById('cookieBanner').classList.add('d-none');
        location.reload();
    } catch (e) {
        console.error('Error handling accept cookies:', e);
    }
});


//Si o si tienen que estar ambos solo que uno tiene que estar invisible para que el javascript funcione wtf
