<?php

namespace RestBeer\Format;

use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Twig\TwigRenderer;

class Html implements MiddlewareInterface
{
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $header = $request->getHeader('accept');
        $accept = null;
        if (isset($header[0])) {
            $accept = $header[0];
        }
        if (!$accept || $accept != 'text/html') {
            return $next($request, $response);
        }

        $content = explode(',', $response->getBody());

        $twig = new TwigRenderer();
        $twig->addPath('views');
        $html = $twig->render('content.twig', ['content' => $content]);

        return $next($request, new HtmlResponse($html));
    }
}
