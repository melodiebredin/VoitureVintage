<?php

namespace App\Controller;

use DateTime;
use App\Entity\Vehicle;
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
     * @Route("/add_message/?vehicule_id={id}", name="form_message", methods={"GET|POST"})
     * @param Request $request
     * @return Response
     */
    public function addMessage(Vehicle $vehicule, Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                $message = $form->getData();

                // $message->setVehicule($this->getVehicle());
                $message->setAuthor($this->getUser());
                $message->setCreatedAt(new DateTime());

                $message->setVehicule($vehicule);


                $entityManager->persist($message);
                $entityManager->flush();

                $this->addFlash('success', "Vous avez envoyez un message !");
                return $this->redirectToRoute('single_vehicle', 
                [
                 'id' => $vehicule->getId()
        ]);


        }

        return $this->render('dashboard/form_message.html.twig', [
            'form' => $form->createView()
        ]);
    } // end function

} // end class