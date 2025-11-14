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
        $phone = $request->getParsedBody()['phone'];
        $password = $request->getParsedBody()['password'];

        $user = ORM::forTable('users')->create([
            'phone' => $phone,
            'password' => $password,
        ]);
        $user->save();

        $_SESSION['user_id'] = $user['id'];


        return $response->withHeader('Location', '/cart')->withStatus(302);
    }

}