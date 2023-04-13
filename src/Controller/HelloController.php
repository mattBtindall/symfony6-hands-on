<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    private array $messages = [
        ['message' => 'Hello', 'created' => '2023/04/06'],
        ['message' => 'Hi', 'created' => '2023/03/12'],
        ['message' => 'Bye!', 'created' => '2021/05/12']
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
