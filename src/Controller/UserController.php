<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This is the admin controller, where only users with ROLE_ADMIN will
 * be able to access. This RBAC control is done in the security.yaml
 * configuration file.
 */
#[Route('/admin')]
class UserController extends AbstractController
{
    /**
     * By default, the admin page will render all the users from the database
     */
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * Handle the show user route by passing the user id as a parameter
     */
    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Handle the delete user route by passing the user id as a parameter
     */
    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
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

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}
