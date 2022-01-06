<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\EditUserType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DashboardController extends AbstractController
{

public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher )
{
    $this->entityManager = $entityManager;
    $this->passwordHasher = $passwordHasher;
}

    /**
     * @Route("admin/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {

        $users = $this->entityManager->getRepository(User::class)->findAll();
        $vehicles = $this->entityManager->getRepository(Vehicle::class)->findAll();
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        $blogs = $this->entityManager->getRepository(Blog::class)->findAll();

        return $this->render('dashboard/dashboard.html.twig', [
            'users' => $users,
            'vehicles' => $vehicles,
            'categories' => $categories,
            'blogs' => $blogs,
        ]);
    }

    /**
     * @Route("admin/add/user", name="add_user")
     */
    public function addUser(Request $request): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }

        return $this->render('dashboard/addUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("admin/edit/user/{id}", name="edit_user")
     */
    public function editUser($id, Request $request): Response
    {

        $users = $this->entityManager->getRepository(User::class)->find($id);

        $form = $this->createForm(EditUserType::class, $users);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users->setPassword($this->passwordHasher->hashPassword($users, $users->getPassword()));
            $this->entityManager->persist($users);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }

        return $this->render('dashboard/editUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

/**
     * @Route("/admin/delete/user/{id}", name="delete_user")
     */
    public function deleteUser(User $users, Request $request): Response
    {

        $this->entityManager->remove($users);
        $this->entityManager->flush();
        $this->addFlash('success', 'Membre supprimé !');

        return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
    }

/**
     * @Route("/admin/delete/vehicle/{id}", name="delete_vehicle")
     */
    public function deleteVehicle(vehicle $vehicles, Request $request): Response
    {

        $this->entityManager->remove($vehicles);
        $this->entityManager->flush();
        $this->addFlash('success', 'Annonce supprimée !');





        return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
    }


/**
     * @Route("/admin/edit/category/{id}", name="edit_category")
     */
    public function editCategory($id, Request $request): Response
    {

        $categories = $this->entityManager->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($categories);
            $this->entityManager->flush();
            return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
        }




        return $this->render('dashboard/editCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/category/{id}", name="delete_category")
     */
    public function deleteCategory(Category $categories, Request $request): Response
    {

        $this->entityManager->remove($categories);
        $this->entityManager->flush();
        $this->addFlash('success', 'La catégorie a bien été supprimée !');





        return $this->redirect($request->get('redirect') ?? '/admin/dashboard');
    }


}
