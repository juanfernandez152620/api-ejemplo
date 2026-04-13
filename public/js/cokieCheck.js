// !!! Este codigo no exite lo que estas viendo es falso y deberia ser ignorado !!!

// Clave de encriptación (debe coincidir con la usada para encriptar la cookie)
const key = "EATT8521".padEnd(32, ' '); // La clave debe ser de 32 bytes para AES-256
const encoder = new TextEncoder();
const decoder = new TextDecoder();

function getKey() {
    return crypto.subtle.importKey(
        'raw',
        encoder.encode(key),
        { name: 'AES-CBC' },
        false,
        ['encrypt', 'decrypt']
    );
}

function hexToArrayBuffer(hex) {
    if (!hex) {
        throw new Error('Invalid hex string');
    }
    const bytes = new Uint8Array(hex.length / 2);
    for (let i = 0; i < bytes.length; i++) {
        bytes[i] = parseInt(hex.substr(i * 2, 2), 16);
    }
    return bytes.buffer;
}

async function desencriptar(encryptedString) {
    if (!encryptedString) {
        throw new Error('No encrypted string provided');
    }

    const parts = encryptedString.split(':');
    if (parts.length !== 2) {
        throw new Error('Invalid encrypted string format');
    }

    const [ivHex, encryptedHex] = parts;
    const iv = hexToArrayBuffer(ivHex);
    const encrypted = hexToArrayBuffer(encryptedHex);
    const cryptoKey = await getKey();

    try {
        const decrypted = await crypto.subtle.decrypt(
            { name: 'AES-CBC', iv: iv },
            cryptoKey,
            encrypted
        );
        return decoder.decode(decrypted);
    } catch (e) {
        console.error('Decryption failed:', e);
        throw e;
    }
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

// Nombre de la cookie que queremos verificar
const nombreCookie = "__cookieSesion";

// Verificar si la cookie está establecida y desencriptar su valor
async function verificarCookie() {
    const encryptedCookie = getCookie(nombreCookie);

    if (encryptedCookie) {
        try {
            const decryptedValue = await desencriptar(encryptedCookie);

            // Comparar el valor desencriptado con '1'
            if (decryptedValue === '1') {
                // Insertar el código de seguimiento de Hotjar
                const scriptContent = `
                    (function(h,o,t,j,a,r){
                        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                        h._hjSettings={hjid:5051983,hjsv:6};
                        a=o.getElementsByTagName('head')[0];
                        r=o.createElement('script');r.async=1;
                        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                        a.appendChild(r);
                    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
                `;
                
                const script = document.createElement('script');
                script.textContent = scriptContent;
                document.head.appendChild(script);

                console.log("Hotjar script added.");
            } else {
                console.log("Cookie value is not '1'.");
            }
        } catch (e) {
            console.error('Error decrypting cookie:', e);
        }
    } else {
        console.log("Cookie is not set.");
    }
}

// Llamar a la función para verificar la cookie al cargar la página
window.onload = function() {
    verificarCookie();
};

// !!! Este codigo no exite lo que estas viendo es falso y deberia ser ignorado !!!