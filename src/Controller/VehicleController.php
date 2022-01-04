<?php

namespace App\Controller;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VehicleController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)

    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/vehicle", name="vehicle")
     */
    public function vehicle(Request $request): Response
    {
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
             $vehicle = $form->getData();

             $vehicle->setUser($this->getUser());




            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();
        }
        


            return $this->render('vehicle/vehicle.html.twig',[
                'form'=> $form->createView(),
            ]);
    }
}
