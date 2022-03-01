<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * This is the inbox controller. Where all the incoming messages wil be shown
 */
class InboxController extends AbstractController
{
    /**
     * This will handle the inbox route itself.
     */
    #[Route('/inbox', name: 'inbox')]
    public function index(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {
        $user = new User();
        $user = $this->getUser();

        // Get all users from the repository
        $allUsers = $userRepository->findAll();

        // Get all messages where the reciver is the current user
        $messages = $messageRepository
            ->findBy(
                ['receiver' => $user->getId()]
            );

        // Render the inbox html.twig with the messages object, users and the role,
        // in order to allow the user to access to the admin zone if it's an admin.
        return $this->render('inbox/index.html.twig', [
            'inbox_message' => $messages,
            'users' => $allUsers,
            'user_role' => $user->getRoles()[0]
        ]);
    }
}
