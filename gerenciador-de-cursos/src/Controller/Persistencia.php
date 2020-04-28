<?php 

namespace Alura\Cursos\Controller;

use Nyholm\Psr7\Response;
use Alura\Cursos\Entity\Curso;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Alura\Cursos\Helper\FlashMessageTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Persistencia implements RequestHandlerInterface
{
    use FlashMessageTrait;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $descricao = filter_var(
            $request->getParsedBody()['descricao'],
            FILTER_SANITIZE_STRING
        );

        $curso = new Curso();
        $curso->setDescricao($descricao);

        $id = filter_var(
            $request->getQueryParams()['id'],
            FILTER_VALIDATE_INT
        );

        $tipo = "success";

        if(!is_null($id) && $id !== false) {
            $curso->setId($id);
            $this->entityManager->merge($curso);
            $this->defineMensagem($tipo, "Curso atualizado com sucesso");
        } else {
            $this->defineMensagem($tipo, "Curso inserido com sucesso");
            $this->entityManager->persist($curso);
        }

        $this->entityManager->flush();

        return new Response(302, ['Location' => '/listar-cursos']);
    }
}