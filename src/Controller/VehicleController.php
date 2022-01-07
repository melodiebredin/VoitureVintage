<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Message;
use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Form\EditVehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class VehicleController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)

    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/vehicle", name="vehicle")
     */
    public function vehicle(Request $request, SluggerInterface $slugger): Response
    {
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle = $form->getData();

            $vehicle->setUser($this->getUser());
            $vehicle->setCreatedAt(new \DateTime());


            $file = $form->get('picture')->getData();



            if ($file) {



                $extension = '.' . $file->guessExtension();



                $safeFilename = $slugger->slug($vehicle->getBrand());




                $newFilename = $safeFilename . '_' . uniqid() . $extension;



                try {


                    $file->move($this->getParameter('uploads_dir'), $newFilename);



                    $vehicle->setPicture($newFilename);
                } catch (FileException $exception) {
                }
            }

            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();
            $this->addFlash('success', 'votre annonce a été prise en compte !');

            return $this->redirectToRoute('home');
        }




        return $this->render('vehicle/vehicle.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/supprimer/vehicle/{id}", name="delete_vehicle")
     * @param vehicle $vehicle
     * @return Response
     */
    public function deleteVehicle(Vehicle $vehicle): Response
    {
        $this->entityManager->remove($vehicle);
        $this->entityManager->flush();

        $this->addFlash('success', 'votre vehicle a été supprimée !');

        return $this->redirectToRoute('account');

            return $this->render('vehicle/vehicle.html.twig');
        }




   /**
     * @Route("/show/vehicle", name="show_vehicle")
     * @return Response
     */
    public function showVehicle(): Response
    {
        $vehicle = $this->entityManager->getRepository(Vehicle::class)->findBy(['user'=>$this->getUser()]);
        // dd($vehicle);

        
        return $this->render('account/mes_annonces.html.twig', [
            'vehicles' => $vehicle,
       
        ]);
    }


       /**
     * @Route("/show/message", name="show_message")
     * @return Response
     */
    public function showMessage(): Response
    {
        $message = $this->entityManager->getRepository(Message::class)->findAll();
    

        
        return $this->render('account/mes_messages.html.twig', [
            'messages' => $message,
        ]);
    }

    /**
     * @Route("/favoris/ajout/{id}", name="ajout_favoris")
     */
    public function ajoutFavoris(Vehicle $vehicle)
    {
        
        $vehicle->addFavori($this->getUser());

        $vehicle = $this->entityManager->persist($vehicle);
        $vehicle = $this->entityManager->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/favoris/retrait/{id}", name="retrait_favoris")
     */
    public function retraitFavoris(Vehicle $vehicle)
    {
        
        $vehicle->removeFavori($this->getUser());

        $vehicle = $this->entityManager->persist($vehicle);
        $vehicle = $this->entityManager->flush();
        return $this->redirectToRoute('home');
    }

/**
     * @Route("/favoris", name="mes_favoris")
     * @return Response
     */
    public function mesFavoris(): Response
    {
        $vehicle = $this->entityManager->getRepository(Vehicle::class)->findAll();


        
        return $this->render('account/mes_favoris.html.twig', [
            'favoris' => $vehicle,
       
        ]);
    }


    /**
     * @Route("/supprimer/message/{id}", name="delete_message")
     * @param message $message
     * @return Response
     */
    public function deleteMessage(Message $message): Response
    {
        $this->entityManager->remove($message);
        $this->entityManager->flush();

        $this->addFlash('success', 'votre message a été supprimée !');

        // return $this->redirectToRoute('account');

        return $this->redirectToRoute('account');
        }



/**
     * @Route("/modifier/vehicle/{id}", name="edit_vehicle")
     * @param Vehicle $vehicle
     * @param Request $request
     * @return Response
     */
    public function editVehicle(Vehicle $vehicle, Request $request): Response
    {
        # Supprimer le edit form et utiliser Type (configurer les options) : pas besoin de dupliquer un form
        $form = $this->createForm(EditVehicleType::class, $vehicle)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Créer une nouvelle propriété dans l'entité : setUpdatedAt()

            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();
        }

        return $this->render('single_vehicle/edit_vehicle.html.twig', [
            'form' => $form->createView()
        ]);
    }




}
