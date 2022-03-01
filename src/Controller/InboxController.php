<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InboxController extends AbstractController
{
    #[Route('/inbox', name: 'inbox')]
    public function index(Request $request, MessageRepository $messageRepository, UserRepository $userRepository): Response
    {
        $user = new User();
        $user = $this->getUser();
        $allUsers = $userRepository->findAll();

        $messages = $messageRepository
            ->findBy(
                ['receiver' => $user->getId()]
            );

        return $this->render('inbox/index.html.twig', [
            'inbox_message' => $messages,
            'users' => $allUsers,
            'user_role' => $user->getRoles()[0]
        ]);
    }
}
