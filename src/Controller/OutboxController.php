<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutboxController extends AbstractController
{
    /**
     * This will handle the outbox route itself
     */
    #[Route('/outbox', name: 'outbox')]
    public function index(MessageRepository $messageRepository, UserRepository $userRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $allUsers = $userRepository->findAll();

        // Get all the messages from the repository where the sender is the current user
        $messages = $messageRepository->findBy(
            ['sender' => $user->getId()]
        );

        // Render the inbox html.twig with the messages object, users and the role,
        // in order to allow the user to access to the admin zone if it's an admin.
        return $this->render('outbox/index.html.twig', [
            'outbox_message' => $messages,
            'users' => $allUsers,
            'user_role' => $user->getRoles()[0]
        ]);
    }
}
