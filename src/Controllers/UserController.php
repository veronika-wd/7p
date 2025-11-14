<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController extends Controller
{
    public function index(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $user = ORM::forTable('users')->findOne($_SESSION['user_id']);

        $user = ORM::forTable('orders')->where('login', $user['login'])->findMany();

        return $this->renderer->render($response, 'profile.php', [
            'applications' => $oreder,
        ]);
    }
}