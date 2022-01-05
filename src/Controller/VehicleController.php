<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        $this->addFlash('success', 'votre annonce a été supprimée !');

        return $this->redirectToRoute('account');
    }


   /**
     * @Route("/show/vehicle", name="show_article")
     * @return Response
     */
    public function showArticle(): Response
    {
        $vehicle = $this->entityManager->getRepository(Vehicle::class)->findBy(['user'=>$this->getUser()]);
        // dd($vehicle);

        
        return $this->render('account/mes_annonces.html.twig', [
            'vehicles' => $vehicle,
       
        ]);
    }


}
