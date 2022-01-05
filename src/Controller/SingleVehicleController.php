<?php

namespace App\Controller;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SingleVehicleController extends AbstractController
{
    public function __construct(EntityManagerInterface $EntityManager) {
        $this->entityManager = $EntityManager;
    }
    /**
     * @Route("/single/vehicle/{id}", name="single_vehicle")
     */
    public function viewVehicle($id): Response
    {
    
        $singleVehicle = $this->entityManager->getRepository(Vehicle::class)->findBy(['id' => $id]);
        return $this->render('single_vehicle/viewVehicle.html.twig', [
            'singleVehicle' => $singleVehicle,
        ]);
    }

}
