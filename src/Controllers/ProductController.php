<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProductController extends Controller
{
    public function index(RequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->renderer->render($response, 'index.php', [
            'products' => ORM::forTable('products')->findMany(),
        ]);
    }
}