<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController extends Controller
{


    public function show(RequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->renderer->render($response, 'show.php', [

        ]);
    }

    public function catalog(RequestInterface $request, ResponseInterface $response, $args)
    {
        return $this->renderer->render($response, 'catalog.php', [
            'parentCategories' => \ORM::for_table('categories')
                ->select('categories.*')
                ->whereNull('parent_category')
                ->find_array(),
            'categories' => \ORM::for_table('categories')->find_array(),
        ]);
    }
}