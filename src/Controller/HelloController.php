<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    private array $messages = [
        "Hello", "Hi", "Bye!"
    ];

    #[Route('/{limit<\d+>?3}', name: 'app_index')]
    public function index(int $limit): Response
    {
        return $this->render(
            '/hello/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => $limit
            ]

        );
    }

    #[Route('/show/{id<\d+>}', name: 'app_show')]
    public function show(int $id): Response
    {
        return $this->render(
            'hello/show.html.twig',
            ['message' => $this->messages[$id]]
        );
    }
}
