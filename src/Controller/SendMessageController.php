<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Message;
use App\Repository\UserRepository;

class SendMessageController extends AbstractController
{
    #[Route('/send/message/', name: 'send_message')]
    public function index(ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {
        $user = new User();
        $user = $this->getUser();

        $entityManager = $doctrine->getManager();
        $message = new Message();
        $date = new \DateTime('@' . strtotime('now'));

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $userReceiver = $userRepository
                ->findBy(
                    ['email' => $_POST['username']]
                );

            if ($userReceiver == null) {
                array_push($errors, "Introduce a valid username.");
            } else {
                if ($user->getId() == $userReceiver[0]->getId()) {
                    array_push($errors, "You can't send a message to yourself.");
                }
            }

            if ($_POST['message'] == "" || $_POST['message'] == null) {
                array_push($errors, "A message is required.");
            }

            if (count($errors) > 0) {
                return $this->render('send_message/index.html.twig', [
                    'errors' => $errors
                ]);
            }

            $message->setReceiver($userReceiver[0]->getId());
            $message->setMessage($_POST['message']);

            $message->setSender($user->getId());
            $message->setDate($date);
            $message->setIsRead(false);

            // Save the new message in database
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('outbox');
        }

        return $this->render('send_message/index.html.twig', [
            'errors' => $errors
        ]);
    }
}
