<?php

namespace Alura\Cursos\Controller;

use Nyholm\Psr7\Response;
use Alura\Cursos\Entity\Curso;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Alura\Cursos\Helper\FlashMessageTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Exclusao implements RequestHandlerInterface
{
    use FlashMessageTrait;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = filter_var(
            $request->getQueryParams()['id'],
            FILTER_VALIDATE_INT
        );

        $resposta = new Response(302, ['Location' => '/listar-cursos']);
        if(is_null($id) || $id === false) {
            $this->defineMensagem("danger", "Curso inexistente");
            header('Location: /listar-cursos');
            return $resposta;
        }

        $curso = $this->entityManager->getReference(
            Curso::class,
            $id
        );

        $this->entityManager->remove($curso);
        $this->entityManager->flush();
        $_SESSION['tipo_mensagem'] = 'success';
        $_SESSION['mensagem'] = "Curso excluído com sucesso";

        header('Location: /listar-cursos');
    }
}