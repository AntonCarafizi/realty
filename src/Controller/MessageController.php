<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Service\ResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/item/{item_id}", name="message_index", methods={"GET"})
     */
    public function index(MessageRepository $messageRepository, $item_id): Response
    {

        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findBy(['item' => $item_id])
        ]);
    }

    /**
     * @Route("/item/{item_id}/new", name="message_new", methods={"GET","POST"})
     */
    public function new(Request $request, AuthenticationUtils $authenticationUtils, ResponseService $responseService, $item_id): Response
    {
        $message = new Message();
        $user = $this->getUser();
        $item = $this->getDoctrine()->getRepository(Item::class)->find($item_id);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreated(new \DateTime("now"));
            $user->addMessage($message);
            $item->addMessage($message);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            $status = $responseService->getStatus($request);

            return $this->redirectToRoute('message_show', [
                'id' => $message->getId(),
                'action' => 'create',
                'status' => $status
            ]);
        }

        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="message_show", methods={"GET"})
     */
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="message_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Message $message, ResponseService $responseService): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $status = $responseService->getStatus($request);

            return $this->redirectToRoute('message_show', [
                'id' => $message->getId(),
                'action' => 'update',
                'status' => $status
            ]);
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="message_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('message_index');
    }
}
