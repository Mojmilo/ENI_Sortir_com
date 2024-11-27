<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User;
use App\Form\ImportUsersType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

   
    /*public function new(): Response
    {

        return $this->render('user/new_csv.html.twig');
    }*/
    #[Route('/import-csv', name: 'app_user_new_csv', methods: ['GET', 'POST'])]
    public function import(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImportUsersType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier
            $file = $form->get('csvFile')->getData();
            if ($file) {
                try {
                    // Sauvegarder le fichier temporairement
                    $filename = uniqid() . '.' . $file->guessExtension();
                    $file->move($this->getParameter('csv_directory'), $filename);
        
                    // Lire le fichier CSV et importer les utilisateurs
                    $filePath = $this->getParameter('csv_directory') . '/' . $filename;
                    $this->importUsersFromCSV($filePath, $entityManager);
        
                    // Ajouter un message de succès
                    $this->addFlash('success', 'Utilisateurs importés avec succès!');
                } catch (FileException $e) {
                    // Gérer les erreurs d'upload
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'importation du fichier.');
                }
            }
        
            // Redirection après traitement pour éviter les resoumissions
            return $this->redirectToRoute('app_user_new_csv');
        }
        // dd($request);
        
        
        return $this->render('user/new_csv.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function importUsersFromCSV(string $filePath, EntityManagerInterface $entityManager): void
    {
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Lire les en-têtes du CSV
            $headers = fgetcsv($handle);

            while (($data = fgetcsv($handle)) !== false) {
                // Associer les valeurs aux en-têtes
                $row = array_combine($headers, $data);

                // Récupérer les données avec les noms des colonnes
                $siteId = $row['site_id'] ?? null;
                $firstName = $row['first_name'] ?? null;
                $lastName = $row['last_name'] ?? null;
                $phone = $row['phone'] ?? null;
                $email = $row['email'] ?? null;
                $roles = isset($row['role']) ? json_decode($row['role'], true) : [];
                $password = $row['password'] ?? null;
                $active = isset($row['active']) ? (bool)$row['active'] : false;
                $pseudo = $row['pseudo'] ?? null;

                // Vérification des données avant de créer un utilisateur
                if (!$this->validateUserData($siteId, $firstName, $lastName, $phone, $email, $roles, $password, $pseudo)) {
                    continue; // Passer à la ligne suivante si les données sont invalides
                }

                // Vérifier si le site existe
                $site = $entityManager->getRepository(Site::class)->find($siteId);
                if (!$site) {
                    continue; // Si le site n'existe pas, on passe à la ligne suivante
                }

                // Créer un nouvel utilisateur
                $user = new User();
                $user->setSite($site)
                    ->setFirstName($firstName)
                    ->setLastName($lastName)
                    ->setPhone($phone)
                    ->setEmail($email)
                    ->setRoles($roles)
                    ->setPassword($password)
                    ->setActive($active)
                    ->setPseudo($pseudo);

                // Sauvegarder l'utilisateur dans la base de données
                $entityManager->persist($user);
            }

            fclose($handle);

            // Sauvegarder tous les utilisateurs dans la base de données
            $entityManager->flush();
        }
    }


    private function validateUserData($siteId, $firstName, $lastName, $phone, $email, $roles, $password, $pseudo): bool
    {
        // Vérification que les champs nécessaires ne sont pas vides
        if (empty($siteId) || empty($firstName) || empty($lastName) || empty($phone) || empty($email) || empty($roles) || empty($password) || empty($pseudo)) {
            // Si un champ est vide, retourner false
            return false;
        }

        // Autres vérifications peuvent être ajoutées ici (par exemple, format de l'email, longueur du mot de passe, etc.)
        return true;
    }

}