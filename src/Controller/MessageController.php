<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/form_message", name="form_message", methods={"GET|POST"})
     * @param Request $request
     * @return Response
     */
    public function addMessage( Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                $message = $form->getData();


                $message->setCreatedAt(new DateTime());




                $entityManager->persist($message);
                $entityManager->flush();

                $this->addFlash('success', "Vous avez commentez l'article !");

        }

        return $this->render('dashboard/form_message.html.twig', [
            'form' => $form->createView()
        ]);
    } // end function

} // end class