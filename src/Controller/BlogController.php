<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BlogController extends AbstractController
{
        /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)

    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/admin/blog", name="create_blog")
     */
    public function createBlog(Request $request, SluggerInterface $slugger): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle = $form->getData();

            // $vehicle->setUser($this->getUser());
            $blog->setCreatedAt(new \DateTime());


            $file = $form->get('picture')->getData();

            
            if ($file) {



                $extension = '.' . $file->guessExtension();



                $safeFilename = $slugger->slug($vehicle->getTitle());




                $newFilename = $safeFilename . '_' . uniqid() . $extension;



                try {


                    $file->move($this->getParameter('uploads_dir'), $newFilename);



                    $blog->setPicture($newFilename);
                } catch (FileException $exception) {
                }
            }
            
            $this->entityManager->persist($blog);
            $this->entityManager->flush();
        }



        return $this->render('blog/blog.html.twig', [
            'form' => $form->createView(),
        ]);
    }

       /**
     * @Route("/blog", name="show_blog")
     * @return Response
     */
    public function showBlog(): Response
    {
        $blog = $this->entityManager->getRepository(Blog::class)->findAll();


        
        return $this->render('blog/viewBlog.html.twig', [
            'blogs' => $blog,
       
        ]);
    }
}
