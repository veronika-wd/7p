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

        $login = $request->getParsedBody()['login'];
        $password = $request->getParsedBody()['password'];

        $user = ORM::forTable('users')->where('login', $login)->findOne();



        if ($user['password'] == $password){
            $_SESSION['user_id'] = $user['id'];
            if($user['is_admin'] === 1){
                return $response->withHeader('Location', '/categories')->withStatus(302);
            }
            return $response->withHeader('Location', '/')->withStatus(302);
        }



        if ($user['password'] != $password){
            echo 'Пароль неверный';
            exit();
        }

        if (!$user){
            echo 'Такого пользователя не существует';
            exit();
        }

    }

    public function logout(RequestInterface $request, ResponseInterface $response)
    {
        unset($_SESSION['user_id']);
        return $response->withHeader('Location', '/')->withStatus(302);
    }

}