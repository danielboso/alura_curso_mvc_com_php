<?php

namespace Alura\Cursos\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Alura\Cursos\Helper\RenderizadorDeHtmlTrait;
use Psr\Http\Server\RequestHandlerInterface;

class FormularioInsercao implements RequestHandlerInterface
{
    use RenderizadorDeHtmlTrait;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $html = $this->renderizaHtml('cursos/formulario.php', [
            'titulo' => 'Novo curso',
        ]);

        return new Response(200, [], $html);
    }
}