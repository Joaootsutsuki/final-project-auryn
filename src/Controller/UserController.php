<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/api/usuarios', methods: ['GET'])]
    public function listar(EntityManagerInterface $em): JsonResponse
    {
        $usuarios = $em->getRepository(User::class)->findAll();
        $dados = array_map(fn($u) => [
            'id' => $u->getId(),
            'nome' => $u->getNome(),
            'telefone' =>$u->getTelefone(),
            'nascimento' => $u->getNascimento(),
            'email' => $u->getEmail(),
            'cpf' => $u->getCpf()
        ], $usuarios);

        return $this->json($dados);
    }

    #[Route('/api/usuario', methods: ['POST'])]
    public function criar(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $dados = json_decode($request->getContent(), true);

        if (!isset($dados['nome'], $dados['telefone'], $dados['nascimento'], $dados['email'], $dados['cpf'], $dados['senha'])) {
            return $this->json(['erro' => 'Todos os campos são obrigatórios'], 400);
        }

        $dataNascimento = \DateTime::createFromFormat('Y-m-d', $dados['nascimento']);

        $usuario = new User();
        $usuario->setNome($dados['nome']);
        $usuario->setTelefone(preg_replace('/[^0-9]/', '', $dados['telefone'])); // Deixe apenas números 49912345678
        $usuario->setEmail($dados['email']);
        $usuario->setCpf(preg_replace('/[^0-9]/', '', $dados['cpf'])); // Deixe apenas números 11122233344

        $usuario->setNascimento($dataNascimento); 
        
        // Hash da senha
        $usuario->setSenha(password_hash($dados['senha'], PASSWORD_DEFAULT));

        $em->persist($usuario);
        $em->flush();

        return $this->json(['mensagem' => 'Usuário criado com sucesso!'], 201);
    }


    #[Route('/api/usuario/{id}', methods: ['DELETE'])]
    public function deletar($id, EntityManagerInterface $em): JsonResponse
    {
        $usuario = $em->getRepository(User::class)->find($id);
        if (!$usuario) return $this->json(['erro' => 'Usuário não encontrado'], 404);

        $em->remove($usuario);
        $em->flush();

        return $this->json(['mensagem' => 'Usuário removido!']);
    }
}
