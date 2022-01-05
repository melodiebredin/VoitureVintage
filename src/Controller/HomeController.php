<?php

namespace App\Controller;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager) {

        $this->entityManager = $entityManager;
        
    }
    /**
     * @Route("/", name="home")
     * 
     */
    public function home(): Response
    {
        $vehicles = $this->entityManager->getRepository(Vehicle::class)->findAll();



        return $this->render('home/home.html.twig',[
            'vehicles' => $vehicles,

        ]);

    }
}
