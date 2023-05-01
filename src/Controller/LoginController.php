<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * Remember by using the built in form_login we don't need to do anything
     * here apart from handle the rendering of the form. However, it is nice
     * to be able to inject the last entered username if there are errors
     * Also, we build the form using standad HTML elements and without relying on
     * the form builders. This is because symfony handles the request for us, so
     * within this controller we don't have any logic to handle the form submission.
     * Therefore, building a basic form with html elements is the easiest method.
     */
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $utils): Response
    {
        $lastUsername = $utils->getLastUsername();
        $error = $utils->getLastAuthenticationError();

        return $this->render('login/index.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error
        ]);
    }
}
