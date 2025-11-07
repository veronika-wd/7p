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

        $applications = ORM::forTable('applications')->where('user_phone', $user['login'])->findMany();

        return $this->renderer->render($response, 'profile.php', [
            'applications' => $applications,
        ]);
    }
}