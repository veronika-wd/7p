<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController extends Controller
{
    public function index(RequestInterface $request, ResponseInterface $response, array $args)
    {
        if (isset($_SESSION['user_id'])) {
            $user = ORM::forTable('users')->findOne($_SESSION['user_id']);
        }

        if (!isset($args['slug'])) {
            $goods = ORM::forTable('goods')
                ->select('goods.*')
                ->select('categories.name', 'category_name')
                ->leftOuterJoin('categories', 'goods.category_id = categories.id')
                ->findArray();
            $categories = ORM::forTable('categories')
                ->select('categories.*')
                ->whereNull('parent_category')
                ->findArray();

            return $this->renderer->render($response, 'index.php', [
                'goods' => $goods,
                'categories' => $categories,
                'user' => $user ?? '',
            ]);
        } else {
            $category = ORM::forTable('categories')->where('slug', $args['slug'])->findOne();
            $goods = ORM::forTable('goods')
                ->select('goods.*')
                ->select('categories.name', 'category_name')
                ->leftOuterJoin('categories', ['goods.category_id', '=', 'categories.id'])
                ->where('category_id', $category['id'])
                ->findArray();
            $categories = ORM::for_table('categories')
                ->select('categories.*')
                ->whereNull('parent_category')
                ->find_array();
            $childCategories = ORM::for_table('categories')
                ->select('categories.*')
                ->select('p.name', 'parent_name')
                ->left_outer_join('categories', ['categories.parent_category', '=', 'p.id'], 'p')
                ->where('parent_category', $category['id'])
                ->find_array();

            return $this->renderer->render($response, 'index.php', [
                'goods' => $goods,
                'categories' => $categories,
                'childCategories' => $childCategories,
                'user' => $user ?? '',
            ]);
        }
    }

    public function show(RequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->renderer->render($response, 'show.php', [
            'good' => ORM::forTable('goods')
                ->select('goods.*')
                ->select('categories.name', 'category_name')
                ->leftOuterJoin('categories', ['goods.category_id', '=', 'categories.id'])
                ->where('slug', $args['slug'])
                ->findOne(),
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