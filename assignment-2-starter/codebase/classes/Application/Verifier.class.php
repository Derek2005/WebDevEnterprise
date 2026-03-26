<?php
namespace Application;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Verifier
{
    public $userId;
    public $role;

    public function decode($jwt) 
    {   
        if (!empty($jwt)) {
            $jwt = trim($jwt);

            if (substr($jwt, 0, 7) === 'Bearer ') {
                $jwt = substr($jwt, 7);
            }

            try {
                $token = JWT::decode($jwt, new Key("a8f4d9c2e7b1f6m3q9z5x2k8p4r7t1y", 'HS256'));
                $this->userId = $token->userId;
                $this->role = $token->role;
            } catch (\Throwable $e) {
                throw $e;
            }
        }
    }
}