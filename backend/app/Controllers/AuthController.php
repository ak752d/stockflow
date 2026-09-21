<?php

namespace App\Controllers;

use App\Libraries\JwtService;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function login(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? [];
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($email === '' || $password === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Email and password are required.',
            ]);
        }

        $user = (new UserModel())->where('email', $email)->first();

        if ($user === null || ! password_verify($password, $user['password'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'message' => 'Invalid credentials.',
            ]);
        }

        unset($user['password']);

        return $this->response->setJSON([
            'token' => (new JwtService())->encode($user),
            'user'  => $user,
        ]);
    }
}
