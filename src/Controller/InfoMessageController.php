<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Message;
use App\Repository\UserRepository;

class InfoMessageController extends AbstractController
{
	/**
	 * @Route("/info_message/{id}", methods="GET", name="info_message")
	 */
	public function infoMessage(Message $message, ManagerRegistry $doctrine, MessageRepository $messageRepository, UserRepository $userRepository): Response
	{
		// Error handling
		if (!$message) {
			throw $this->createNotFoundException(
				'No messsage found for id ' . $message->getId()
			);
		}

		$entityManager = $doctrine->getManager();

		// Get email of the user that sends the message
		$messageId = $message->getId();

		$fromUserID = $messageRepository->findBy([
			'id' => $message->getId()
		]);

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
