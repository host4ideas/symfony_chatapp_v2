<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Message;
use App\Repository\UserRepository;

/**
 * This is the controller which handles the request to show each message content.
 * When a message is clicked, the user is redirected to: /info_message/{id},
 * being id the mesages's id.
 */
class InfoMessageController extends AbstractController
{
	#[Route('/info_message/{id}', name: 'info_message', methods: ['GET'])]
	public function infoMessage(Message $message, ManagerRegistry $doctrine, MessageRepository $messageRepository, UserRepository $userRepository): Response
	{
		// Error handling
		if (!$message) {
			throw $this->createNotFoundException(
				'No messsage found for id ' . $message->getId()
			);
		}

		$entityManager = $doctrine->getManager();

		// Get messages's id
		$messageId = $message->getId();

		// Get the sender id by the sender of the message in the messages repository
		$fromUserID = $messageRepository->findBy([
			'id' => $message->getId()
		]);

		// Get the user that sends the message by the sender's id
		$fromUser = $userRepository->findBy([
			'id' => $fromUserID[0]->getSender()
		]);

		// Update isRead in database
		$messageToUpdate = $entityManager->getRepository(Message::class)->find($messageId);

		$messageToUpdate->setIsRead(true);
		$entityManager->flush();

		return $this->render('messages/info_message.html.twig', [
			'message' => $message,
			'user' => $fromUser
		]);
	}
}
