<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{
    private array $messages = [
        ['message' => 'Hello', 'created' => '2023/04/06'],
        ['message' => 'Hi', 'created' => '2023/03/12'],
        ['message' => 'Bye!', 'created' => '2021/05/12']
    ];


    #[Route('/', name: 'app_index')]
    public function index(MicroPostRepository $posts, CommentRepository $comments): Response
    {
        // this causes issues, cascade isn't set to persist by default
        // so when you create a post and then create a comment
        // you need to persist both the post and the comment
        // but if you set cascade to persist on the post entity
        // you don't have to persist the comment, but in most cases you will
        // be creating comments on there own as posts will have already been created
        // $post = new MicroPost();
        // $post->setTitle('Hello');
        // $post->setText('Hello');
        // $post->setCreated(new DateTime());

        $post = $posts->find(1);
        // removing a comment
        // this can only be done through the posts repo as the comment entity 'post_id' is not nullable
        // so it has to be done from the post side
        // $comment = $post->getComments()[0];
        // $post->removeComment($comment);
        // $posts->save($post, true);

        $comment = new Comment();
        $comment->setText('Hello');
        $comment->setPost($post);
        // $post->addComment($comment);
        // $posts->save($post, true);
        $comments->save($comment, true);

        return $this->render(
            '/hello/index.html.twig',
            [
                'messages' => $this->messages,
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
