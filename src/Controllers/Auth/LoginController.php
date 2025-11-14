<?php

namespace Src\Controllers\Auth;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\Controllers\Controller;

class LoginController extends Controller
{
    public function loginPage(RequestInterface $request, ResponseInterface $response, $args)
    {
        return $this->renderer->render($response, 'auth/login.php');
    }

    public function login(RequestInterface $request, ResponseInterface $response, array $args)
    {

        $phone = $request->getParsedBody()['phone'];
        $password = $request->getParsedBody()['password'];

        $user = ORM::forTable('users')->where('phone', $phone)->findOne();

        $_SESSION['user_id'] = $user['id'];

        return $response->withHeader('Location', '/cart')->withStatus(302);
    }

    public function logout(RequestInterface $request, ResponseInterface $response)
    {
        unset($_SESSION['user_id']);
        return $response->withHeader('Location', '/products')->withStatus(302);
    }

}