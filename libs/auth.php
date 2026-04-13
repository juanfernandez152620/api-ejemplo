<?php

require_once('config/jwt/JWTExceptionWithPayloadInterface.php');
require_once('config/jwt/CachedKeySet.php');
require_once('config/jwt/ExpiredException.php');
require_once('config/jwt/SignatureInvalidException.php');
require_once('config/jwt/BeforeValidException.php');
require_once('config/jwt/JWK.php');
require_once('config/jwt/Key.php');
require_once('config/jwt/JWT.php');

use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;

class JwtHandler
{
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function validateJwtToken($jwtToken)
    {
        try {
            $payload = JWT::decode($jwtToken, new Key($this->secretKey, 'HS256'));
            return array('status' => 'success', "result" => $payload, 'message' => 'El token es valido');
        } catch (ExpiredException $e) {
            return array('status' => 'error', 'message' => 'El token esta expirado.');
        } catch (SignatureInvalidException $e) {
            return array('status' => 'error', 'message' => 'Firma de token no válida.');
        } catch (BeforeValidException $e) {
            return array('status' => 'error', 'message' => 'El token no es valido todavia.');
        } catch (Exception $e) {
            return array('status' => 'error', 'message' =>  var_dump($_COOKIE['token']), 'took' => "dfsdfsdf");
        }
    }

    public function generateJwtToken($userId)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + (60 * 60); // valid for 1 hour

        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'cualquiera' => "Pruebaaaa123135",
            'sub' => $userId
        );

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }
}
