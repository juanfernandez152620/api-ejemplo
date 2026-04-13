<?php
// //FUNCIONES DE COOKIES

// // Función para convertir hexadecimal a binario
// function hexToBin($hex) {
//     if (preg_match('/^[0-9a-fA-F]+$/', $hex)) {
//         return hex2bin($hex);
//     } else {
//         throw new Exception('Invalid hexadecimal input.');
//     }
// }

// // Función para desencriptar el texto
// function desencriptar($textoEncriptado, $clave) {
//     $partes = explode(':', $textoEncriptado);

//     if (count($partes) !== 2) {
//         throw new Exception('Invalid encrypted data format.');
//     }

//     $iv = hexToBin($partes[0]);
//     if (strlen($iv) !== 16) {
//         throw new Exception('Invalid IV length.');
//     }

//     $texto = base64_decode($partes[1]);
//     if ($texto === false) {
//         throw new Exception('Base64 decoding failed.');
//     }

//     $decrypted = openssl_decrypt($texto, 'aes-256-cbc', $clave, OPENSSL_RAW_DATA, $iv);
//     if ($decrypted === false) {
//         throw new Exception('Decryption failed.');
//     }

//     return $decrypted;
// }

// // Función para encriptar el texto
// function encriptar($string) {
//     $iv = openssl_random_pseudo_bytes(16); // Genera IV de 16 bytes (AES-256-CBC)
//     $encrypted = openssl_encrypt($string, 'AES-256-CBC', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv); // Encripta el texto
//     $ivHex = bin2hex($iv); // Convierte el IV en hexadecimal
//     $encryptedBase64 = base64_encode($encrypted); // Convierte el texto encriptado a base64
//     return "{$ivHex}:{$encryptedBase64}"; // Retorna el formato esperado 'iv:encryptedValue'
// }
// // Función para establecer cookies
// function setCookiePHP($name, $value, $days) {
//     $expiration = time() + ($days * 24 * 60 * 60);
//     setcookie($name, $value, $expiration, "/");
// }

// // Función para obtener cookies
// function getCookiePHP($name) {
//     return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
// }

// // Función para modificar el JSON
// function modificarJson(&$json, $atributo, $valor) {
//     echo 'ModificarCookies' . $valor;
//     $keys = explode('.', $atributo);
//     $target = &$json;

//     foreach ($keys as $i => $key) {
//         if ($i === count($keys) - 1) {
//             break;
//         }
    
//         if (!isset($target[$key])) {
//             error_log("El atributo '{$atributo}' no existe en el JSON.");
//             return;
//         }
    
//         $target = &$target[$key];
//     }

//     $finalKey = end($keys);

//     if ($finalKey === 'Permiso' && is_bool($valor)) {
//         $json['Permiso'] = $valor;
//     } elseif ($finalKey === 'id' && is_int($valor)) {
//         $json['id'] = $valor;
//     } elseif (isset($target[$finalKey]) && is_array($target[$finalKey])) {
//         $target[$finalKey][] = $valor;
//     } else {
//         error_log("El atributo '{$finalKey}' no es compatible con el valor proporcionado.");
//     }
// }



// // Función principal: agregarACookie
// function agregarACookie($datos) {
//     echo 'AgregarCookies';
//     foreach ($datos as $dato) {
//         echo $dato['atributo'] . ' ' . $dato['valor'] . '<br>';
//     }
//     echo '<br>';
//     try {
//         // Obtén la cookie encriptada
//         $encryptedCookie = getCookiePHP('__cookieSesion');
//         if ($encryptedCookie === null) {
//             $jsonData = []; // Si no existe, inicializa como un array vacío
//         } else {
//             // Desencripta la cookie
//             $jsonData = json_decode(desencriptar($encryptedCookie, ENCRYPTION_KEY), true);
//             if (!is_array($jsonData)) {
//                 $jsonData = []; // Si falla, inicializa como array vacío
//             }
//         }

//         // Iterar sobre los datos para modificar el JSON
//         foreach ($datos as $dato) {
//             if (isset($dato['atributo']) && isset($dato['valor'])) {
//                 $atributo = $dato['atributo'];
//                 $valor = $dato['valor'];

//                 // Procesar valor (puede ser un string con comas o un único valor)
//                 $valores = is_string($valor) && strpos($valor, ',') !== false
//                     ? array_map('trim', explode(',', $valor))
//                     : [$valor];

//                 // Modificar el JSON con cada valor
//                 foreach ($valores as $v) {
//                     modificarJson($jsonData, $atributo, $v);
//                 }
//             } else {
//                 error_log("El formato del dato no es válido: " . json_encode($dato));
//             }
//         }

//         // Encriptar el JSON actualizado
//         $encryptedValue = encriptar(json_encode($jsonData)); // Encriptar el JSON actualizado

//         // Guardar la cookie actualizada
//         setCookiePHP('__cookieSesion', $encryptedValue, 60); // La cookie se guarda por 60 días

//         echo "Datos añadidos y cookie actualizada exitosamente.";
//     } catch (Exception $e) {
//         error_log('Error al agregar los datos y actualizar la cookie: ' . $e->getMessage());
//         echo "Ocurrió un error: " . $e->getMessage();
//     }
// }




// // Función para verificar el permiso de la cookie
// function verificarPermisoCookie() {

//     // Nombre de la cookie a verificar
//     $nombreCookie = '__cookieSesion';

//     // Verificar si la cookie existe
//     if (isset($_COOKIE[$nombreCookie])) {
//         // Obtener la clave de encriptación definida
//         $clave = constant('ENCRYPTION_KEY'); 

//         // Valor encriptado de la cookie
//         $valorCookieEncriptado = $_COOKIE[$nombreCookie];

//         try {
//             // Desencriptar el valor de la cookie
//             $valorCookieDesencriptado = desencriptar($valorCookieEncriptado, $clave);

//             // Decodificar el JSON desencriptado
//             $jsonData = json_decode($valorCookieDesencriptado, true);

//             // Verificar si el atributo 'Permiso' existe y es true
//             if (isset($jsonData['Permiso']) && $jsonData['Permiso'] === true) {
//                 //echo "El permiso de la cookie es verdadero.";
//                 return true;
//             }
//         } catch (Exception $e) {
//             // Registrar cualquier error que ocurra
//             //echo "Error: " . $e->getMessage();
//             error_log("Error al verificar el permiso de la cookie: " . $e->getMessage());
//         }
//     }

//     // Si no existe la cookie o no se cumple la condición, retorna false
//     return false;
// }

// function generarHashUnico() {
//     // Obtiene la fecha y hora con milisegundos
//     $microTime = microtime(true);
//     $milisegundos = sprintf("%03d", ($microTime - floor($microTime)) * 1000);
//     $fechaHora = date("YmdHis") . $milisegundos;

//     // Genera un número aleatorio
//     $numeroAleatorio = random_int(100000, 999999);

//     // Combina los valores
//     $entrada = $fechaHora . $numeroAleatorio;

//     // Genera el hash usando SHA-256
//     $hash = hash("sha256", $entrada);

//     return $hash;
// }

// // Uso de la función
// $hashUnico = generarHashUnico();
// echo "Hash generado: " . $hashUnico;

// //FIN DE FUNCIONES PARA COOKIES

// //------------ Pruebas de COOKIES

// if (verificarPermisoCookie()) {
//     echo "El permiso de la cookie es: Verdadero";
// } else {
//     echo "El permiso de la cookie es: Falso";
// }

// //------------ Agregar datos de COOKIES
// if (verificarPermisoCookie()) {
//     // Establecer la zona horaria a GMT-3
//     date_default_timezone_set('America/Argentina/Buenos_Aires'); // GMT-3
//     // Obtener la URL completa del navegador
//     $URL = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//     // Obtener la fecha y hora actual en formato 'Y-m-d H:i:s'
//     $fechayHora = date('Y-m-d H:i:s');  // Este valor será en GMT-3
//     echo " <br> La URL del navegador es: " . $_SERVER['REQUEST_URI'];
//     $ellink = constant('URL') . $_SERVER['REQUEST_URI'];

//     // Guardar la URL y la fecha y hora en la cookie
//     agregarACookie([
//         ['atributo' => "Datos.URLVisitados", 'valor' => $ellink],
//         ['atributo' => "Datos.Informacion.Uso.FechasYHoras", 'valor' => $fechayHora]
//     ]);

// }

// //Pruebas de COOKIES

// // Verificar si la cookie existe
// $nombreCookie = '__cookieSesion';
// if (isset($_COOKIE[$nombreCookie])) {
//     // Obtener la clave de encriptación definida
//     $clave = constant('ENCRYPTION_KEY');
//     // Valor encriptado de la cookie
//     $valorCookieEncriptado = $_COOKIE[$nombreCookie];
//     try {
//         // Desencriptar el valor de la cookie
//         $valorCookieDesencriptado = desencriptar($valorCookieEncriptado, $clave);
//         // Decodificar el JSON desencriptado
//         $jsonData = json_decode($valorCookieDesencriptado, true);
//         // Mostrar el valor desencriptado (si existe)
//         echo 'Valor desencriptado de la cookie: ';
//         echo '<pre>';
//         print_r($jsonData); // Mostrar el contenido de la cookie como un array (o lo que se haya desencriptado)
//         echo '</pre>';
//     } catch (Exception $e) {
//         // Si ocurre un error durante la desencriptación, mostrar un mensaje
//         echo 'Error al desencriptar la cookie: ' . $e->getMessage();
//     }
// } else {
//     echo 'La cookie no existe o ha expirado.';
// }

?>