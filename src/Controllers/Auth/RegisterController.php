<?php

namespace Src\Controllers\Auth;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\Controllers\Controller;

class RegisterController extends Controller
{

    public function registerPage(RequestInterface $request, ResponseInterface $response, $args)
    {
        return $this->renderer->render($response, 'auth/register.php');
    }

    public function register(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $login = $request->getParsedBody()['login'];
        $password = $request->getParsedBody()['password'];

        ORM::forTable('users')->create([
            'login' => $login,
            'password' => $password,
        ])->save();

        $_SESSION['user_id'] = ORM::forTable('users')->max('id');


        return $response->withHeader('Location', '/')->withStatus(302);
    }

}