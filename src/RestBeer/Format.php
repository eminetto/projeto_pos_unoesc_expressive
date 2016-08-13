<?php

namespace RestBeer;

use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\HtmlResponse;

class Format implements MiddlewareInterface
{
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $content = explode(',', $response->getBody());
        $header = $request->getHeader('accept');
        $accept = null;
        if (isset($header[0])) {
            $accept = $header[0];
        }
        switch ($accept) {
            case 'application/json':
                return new JsonResponse($content, $response->getStatusCode());
                break;
            default:
                $twig = new TwigRenderer();
                $twig->addPath('views');
                $html = $twig->render('content.twig', ['content' => $content]);
                return new HtmlResponse($html);
                break;
        }
    }
}
