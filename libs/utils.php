<?php
function getEnvVariable($variableName)
{
    $filePath = __DIR__ . '/.env.local';
    var_dump($filePath);
    if (!file_exists($filePath)) {
        return null; // Retorna null si el archivo no existe
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar líneas que comiencen con #
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Dividir la línea en clave y valor
        [$key, $value] = explode('=', $line, 2);

        // Limpiar espacios y comillas
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"");
        // Retornar el valor si coincide con el nombre de la variable
        if ($key === $variableName) {
            return $value;
        }
    }

    return null; // Retorna null si la variable no se encuentra
}
