<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/profil')]
class UserController extends AbstractController
{
    public function __construct(private Security $security,){

    }

    // Display the profil page for the logged-in user
    #[Route('', name: 'app_user_show', methods: ['GET'])]
    public function show(): Response
    {
        // Get the logged-in user
        $user = $this->getUser();

        if(!$user) {
            throw $this->createNotFoundException('User not found');
        }
        
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    // Display the profil page for a specific user
    #[Route('/{id}', name: 'app_user_id_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function profil(EntityManagerInterface $entityManager, int $id): Response
    {
        // Get the user by id
        $user = $entityManager->getRepository(User::class)->findOneBy(array('id' => $id));

        if(!$user) {
            throw $this->createNotFoundException('User not found for id '.$id);
        }
        
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    // Display a users's page to modify data
    #[Route('/update/{id}', name:'app_user_id_update', requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the user by id
        $user = $entityManager->getRepository(User::class)->findOneBy(array('id' => $id));

        if(!$user) {
            throw $this->createNotFoundException('User not found');
        }
        
        // Create the form with the user's data
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        
        if($userForm->isSubmitted() && $userForm->isValid()) {

            // Save into database
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("success", "User updated successfully !");

            return $this->redirectToRoute('app_user_id_show', ['id'=>$user->getId()]);
        }
        
        return $this->render('user/update.html.twig', [
            'userForm' => $userForm,
        ]);
    }

    // todo: suppression d'un compte
    #[Route('/delete/{id}', name:'app_user_id_delete', requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function delete(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        return $this->render('user/index.html.twig');
    }
}
