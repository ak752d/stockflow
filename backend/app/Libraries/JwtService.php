<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use UnexpectedValueException;

class JwtService
{
    private string $secret;
    private int $ttl = 86400;

    public function __construct()
    {
        $this->secret = (string) env('JWT_SECRET');
    }

    public function encode(array $user): string
    {
        $now = time();

        $payload = [
            'iss'  => 'stockflow',
            'iat'  => $now,
            'exp'  => $now + $this->ttl,
            'sub'  => (int) $user['id'],
            'role' => $user['role'],
            'email'=> $user['email'],
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decode(string $token): object
    {
        if ($this->secret === '') {
            throw new UnexpectedValueException('JWT_SECRET is not set');
        }

        return JWT::decode($token, new Key($this->secret, 'HS256'));
    }
}
