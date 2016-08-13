<?php

namespace RestBeer\Format;

use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\JsonResponse;

class Json implements MiddlewareInterface
{
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $header = $request->getHeader('accept');
        $accept = null;
        if (isset($header[0])) {
            $accept = $header[0];
        }
        if (!$accept || $accept != 'application/json') {
            return $next($request, $response);
        }
        $content = explode(',', $response->getBody());

        return new JsonResponse($content, $response->getStatusCode());
    }
}
