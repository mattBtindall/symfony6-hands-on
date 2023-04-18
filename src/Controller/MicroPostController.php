<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {
        // Add new entity
        // $microPostNew = new MicroPost();
        // $microPostNew->setTitle('It comes from controller');
        // $microPostNew->setText('Hi');
        // $microPostNew->setCreated(new DateTime());
        // $posts->save($microPostNew, true);

        // Update entity
        // $microPostUpdate = $posts->find(1);
        // $microPostUpdate->setTitle('Welcome to Findland');
        // $posts->save($microPostUpdate, true);

        // Delete entity
        // $microPostDelete = $posts->find(4);
        // $posts->remove($microPostDelete, true);

        return $this->render('micro_post/index.html.twig', [
            'controller_name' => 'MicroPostController',
        ]);
    }
}
