<?php

namespace Src\Controllers\Auth;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Controllers\Controller;
use Src\Services\CartService;

class LoginController extends Controller
{
    public function __construct(PhpRenderer $renderer, protected CartService $cartService)
    {
        parent::__construct($renderer);
    }

    public function loginPage(RequestInterface $request, ResponseInterface $response, $args)
    {
        return $this->renderer->render($response, 'auth/login.php');
    }

    public function login(RequestInterface $request, ResponseInterface $response, array $args)
    {

        $phone = $request->getParsedBody()['phone'];
        $password = $request->getParsedBody()['password'];

        $user = ORM::forTable('users')->where('phone', $phone)->findOne();

        if (md5($password) !== $user['password']){
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        $_SESSION['user_id'] = $user['id'];

        $cartId = $this->cartService->getCartId();
        ORM::forTable('carts')->findOne($cartId)->set([
            'user_id' => $user['id'],
        ])->save();

        return $response->withHeader('Location', '/products')->withStatus(302);
    }

    public function logout(RequestInterface $request, ResponseInterface $response)
    {
        unset($_SESSION['user_id']);
        $cartId = $this->cartService->getCartId();
        setcookie('cart_id', $cartId, time() - 60 * 60 * 24 * 31, '/');
        return $response->withHeader('Location', $request->getHeaderLine('Referer'))->withStatus(302);
    }

}