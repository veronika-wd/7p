<?php

namespace src\Middleware;

use ORM;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

class AdminMiddleware
{
    public function __construct(
        protected ResponseFactory $responseFactory
    )
    {
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $response = $this->responseFactory->createResponse();

        if (!isset($_SESSION['user_id'])) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        $user = ORM::forTable('users')->findOne($_SESSION['user_id']);

        if (!$user['is_admin']) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        return $handler->handle($request);
    }
}