<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Vehicle;
use App\Form\VehicleType;
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
     * @Route("/show/favoris", name="show_favoris")
     * @return Response
     */
    public function showFavoris(): Response
    {
        $favoris = $this->entityManager->getRepository(Vehicle::class)->findAll();
        //dd($favoris);

        
        return $this->render('account/mes_favoris.html.twig', [
            'favoris' => $favoris,
       
        ]);
    }

}
