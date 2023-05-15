<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\ProfileImageType;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;
use Symfony\Component\String\Slugger\SluggerInterface;

class SettingsProfileController extends AbstractController
{
    #[Route('/settings/profile', name: 'app_settings_profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profile(
        Request $request,
        UserRepository $users
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userProfile = $user->getUserProfile() ?? new UserProfile;

        $form = $this->createForm(
            UserProfileType::class, $userProfile
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfile = $form->getData();
            $user->setUserProfile($userProfile);
            $users->save($user, true);
            $this->addFlash(
                'success',
                'Your user profile was updated'
            );
            // Redirect
            return $this->redirectToRoute('app_settings_profile');
        }

        return $this->render(
            'settings_profile/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/settings/profile-image', name: 'app_settings_profile_image')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profileImage(
        Request $request,
        SluggerInterface $slugger,
        UserRepository $users
    ): Response
    {
        $form = $this->createForm(ProfileImageType::class);
        $templateName = 'settings_profile/profile_image.html.twig';
        $formView = $form->createView();
        /** @var User $user */
        $user = $this->getUser();
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render($templateName, ['form' => $formView]);
        }

        $profileImageFile = $form->get('profileImage')->getData();

        if (!$profileImageFile) {
            return $this->render($templateName, ['form' => $formView]);
        }

        $originalFileName = pathinfo(
            $profileImageFile->getClientOriginalName(),
            PATHINFO_FILENAME
        );
        $safeFileName = $slugger->slug($originalFileName);
        $newFileName = $safeFileName . '-' . uniqid() . '.' . $profileImageFile->guessExtension();

        try {
            $profileImageFile->move(
                $this->getParameter('profile_directory'),
                $newFileName
            );
        } catch (FileException $e) {
            $this->addFlash('warning', 'Failed image uplodaed');
            return $this->render($templateName, ['form' => $formView]);
        }

        $profile = $user->getUserProfile() ?? new UserProfile();
        if (!$user->getUserProfile()) {
            // must set name as it is not nullable
            $profile->setName('N/a');
            $user->setUserProfile($profile);
        }
        $profile->setImage($newFileName);
        $users->save($user, true);
        $this->addFlash('success', 'Your profile image has been uploaded');

        return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
    }
}
