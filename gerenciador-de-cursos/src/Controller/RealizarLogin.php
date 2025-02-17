<?php

namespace Alura\Cursos\Controller;

use Nyholm\Psr7\Response;
use Alura\Cursos\Entity\Usuario;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Infra\EntityManagerCreator;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RealizarLogin implements RequestHandlerInterface
{
    use FlashMessageTrait;

    private $repositorioDeUsuarios;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $entityManager = (new EntityManagerCreator())->getEntityManager();
        $this->repositorioDeUsuarios = $entityManager
            ->getRepository(Usuario::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = filter_var(
            $request->getParsedBody()['email'],
            FILTER_VALIDATE_EMAIL
        );

        $redirecionamentoLogin = new Response(302, ['Location' => '/login']);
        if(is_null($email) || $email === false) {
            $this->defineMensagem("danger", "E-mail digitado não é um e-mail válido");
            return $redirecionamentoLogin;
        }

        $senha = filter_input(
            INPUT_POST, 
            'senha',
            FILTER_SANITIZE_STRING
        );

        $usuario = $this->repositorioDeUsuarios->findOneBy(['email' => $email]);
        
        if(is_null($usuario) || !$usuario->senhaEstaCorreta($senha)) {
            $this->defineMensagem("danger", "E-mail ou senha inválidos");

            return $redirecionamentoLogin;
        }

        $_SESSION['logado'] = true;

        return new Response(302, ['Location' => '/listar-cursos']);
    }
}