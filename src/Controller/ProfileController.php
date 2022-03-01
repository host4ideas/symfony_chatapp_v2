<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * This controller handles the request to show the profile and edit the profile.
 */
class ProfileController extends AbstractController
{
    /**
     * Handle the show user route by passing the user id as a parameter
     */
    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function show(UserRepository $userRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Get all the messages from the repository where the sender is the current user
        $currentUser = $userRepository->findBy(
            ['id' => $user->getId()]
        );

        return $this->render('profile/index.html.twig', [
            'user' => $currentUser[0],
        ]);
    }

    /**
     * Handle the delete user route by passing the user id as a parameter
     */
    #[Route('/user_delete_self', name: 'user_delete_self', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        /**
         * CSRF - or Cross-site request forgery - is a method by which a malicious user attempts to 
         * make your legitimate users unknowingly submit data that they don't intend to submit.
         * 
         * CSRF protection works by adding a hidden field to your form that contains a value that only you and your user know. 
         * This ensures that the user - not some other entity - is submitting the given data.
         */
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
    }
}
