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
            return $this->redirectToRoute('app_login');
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
            throw $this->createAccessDeniedException('Not authorized');
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
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);

        // Get the logged-in user
        $currentUser = $this->getUser();

        if(!$currentUser) {
            return $this->redirectToRoute('app_login');
        }
        
        // If the logged-in user is not the same as the target user AND is not an admin, deny access
        if(!$user || ($currentUser->getId() != $user->getId() && !in_array('ROLE_ADMIN', $currentUser->getRoles(), true))) {
            throw $this->createAccessDeniedException('Not authorized');
        }

        
        // Check if the current user is an admin to allow editing of the city
        $isAdmin = in_array('ROLE_ADMIN', $currentUser->getRoles(), true);
        
        // Create the form with the user's data, passing the is_admin flag to the form
        $userForm = $this->createForm(UserType::class, $user, ['is_admin'=>$isAdmin]);
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
            'user' => $user
        ]);
    }

    

    // 
    #[Route('/delete/{id}', name:'app_user_id_delete', requirements:['id'=>'\d+'], methods: ['POST'])]
    public function delete(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the user to delete
        $user = $entityManager->getRepository(User::class)->find($id);

        // Retrieve the logged-in user
        $currentUser = $this->getUser();

        if(!$currentUser) {
            return $this->redirectToRoute('app_login');
        }

        // If the logged-in user is not the same as the target user AND is not an admin, deny access
        if (!$user || ($currentUser->getId() != $user->getId() && !in_array('ROLE_ADMIN', $currentUser->getRoles(), true))) {
            throw $this->createAccessDeniedException('Not authorized');
        }

        // Vérifie le jeton CSRF pour éviter les attaques
        if (!$this->isCsrfTokenValid('delete_user_' . $user->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_user_id_show', ['id' => $user->getId()]);
        }

        // If the logged-in user deletes his own account
        if ($currentUser->getId() === $user->getId()) {
            // Delete the CSRF token for the user
            $this->container->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();
        }
        
        // Rediriger selon les droits de l'utilisateur
        if (in_array('ROLE_ADMIN', $currentUser->getRoles(), true)) {
            // Delete the user
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');

            return $this->redirectToRoute('app_output_index'); // Redirige l'admin vers la liste des utilisateurs
        } else {
            // Delete the user
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
            return $this->redirectToRoute('app_logout'); // Déconnecte l'utilisateur après avoir supprimé son compte

        }

    }

}
