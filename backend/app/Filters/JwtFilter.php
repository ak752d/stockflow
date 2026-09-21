<?php

namespace App\Filters;

use App\Libraries\JwtService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class JwtFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');

        if (! str_starts_with($header, 'Bearer ')) {
            return service('response')->setStatusCode(401)->setJSON([
                'message' => 'Missing or invalid Authorization header.',
            ]);
        }

        try {
            $payload = (new JwtService())->decode(substr($header, 7));
        } catch (Throwable $e) {
            return service('response')->setStatusCode(401)->setJSON([
                'message' => 'Invalid or expired token.',
            ]);
        }

        if ($arguments !== null && $arguments !== [] && ! in_array($payload->role, $arguments, true)) {
            return service('response')->setStatusCode(403)->setJSON([
                'message' => 'Forbidden.',
            ]);
        }

        $request->user = $payload;

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
