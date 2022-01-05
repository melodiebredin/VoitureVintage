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

        if ($form->isSubmitted() && $form->isValid()){
             $vehicle = $form->getData();

             $vehicle->setUser($this->getUser());
             $vehicle->setCreatedAt(new \DateTime());

 # Coder ici la logique pour uploader la photo


            // On rècupère le fichier  du formulaire grâce à getData().Cela nous retourne un objet de type UploadedFile.

            $file = $form->get('picture')->getData();



            //dd($file);
            //Condition qui vérifie si un fichier est présent dans le formulaire.
            if ($file) {


                //Générer une contrainte d'Upload. On déclare un array avec deux valeurs de type string qui sont les MimeType autorisés.
                //Vous retrouvez tous les Mimetype existant sur internet (Mozilla developper)
                //$allowedMimeType = ['image/jpeg', 'image/png'];


                //La fonction native in_array() permet de vérifier deux valeurs(2 arguments attendus )
                //if (in_array($file->getMimeType(), $allowedMimeType)) {


                #Nous allons construire le nouveau nom du fichier 
                //On stocke dans une variable $originalFilename le nom du fichier.
                //On utilise encore une fonction native pathinfo()

                // //$originalFilename = pathinfo(
                //     $file->getClientOriginalName(),

                //     PATHINFO_FILENAME
                // );


                #récupération de l'extension pour pouvoir reconstruire le nom quelques lignes après.
                //On utilise la concaténation pour ajouter un point '.' 
                $extension = '.' . $file->guessExtension();

                #Assainissement du nom grâce au slugger fourni par Symfony pour la construction du nouveau nom

                $safeFilename = $slugger->slug($vehicle->getBrand());
                //$safeFilename = $slugger->slug($originalFilename);


                #construction du nouveau nom
                //uniqid() est une fonction native qui permet de générer un ID unique.
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                //dd($newFilename);


                //On utilise un try {} catch {} lorsqu'on appelle une méthode qui lance une erreur.

                try {

                    /*On appelle la méthode move() de UploadedFile pour pouvoir déplacer le fichier dans son dossier de destination.
                        Le dossier de destination a été paramétré dans service.yaml
                        
                        /!\ATTENTION :

                        La méthode move () lance une erreur de type FileException.
                        On attrape cette erreur dans le catch(FileException $exception)
                        */

                    $file->move($this->getParameter('uploads_dir'), $newFilename);


                    // On set la nouvelle valeur (nom du fichier) de la propriété picture de notre objet Article.
                    $vehicle->setPicture($newFilename);
                } catch (FileException $exception) {
                    // code à éxécuter si une erreur est attrapée.



                }
                // }
                // // Si ce n'est pas le bon type de fichier uploadé, alors on affiche un message et on redirige.

                // else {

                //     $this->addFlash('warning', 'Les types de fichier autorisés sont : .jpeg / .png');
                //     return $this->redirectToRoute('create_article');
                // }
            }

            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();
        }
        


            return $this->render('vehicle/vehicle.html.twig',[
                'form'=> $form->createView(),
            ]);
        }
    /**
     * @Route("/vehicle/view", name="vehicle_view")
     */
    public function viewVehicles(Request $request): Response
    {

        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class,$vehicle);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        $vehicle = $form->getData();
        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();
    
    }
        return $this->render(
            'vehicle/vehicle.html.twig',
            [
                'form' => $form->createView(),
                'vehicle' => $vehicle,
            ]
        );
    }
}
