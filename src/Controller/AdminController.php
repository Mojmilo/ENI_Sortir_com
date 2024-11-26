<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ImportUsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_index', methods: ['GET'])]
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
    #[Route('/newCSV', name: 'app_user_new_csv', methods: ['GET', 'POST'])]
    public function import(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImportUsersType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier
            $file = $form->get('csvFile')->getData();

            if ($file) {
                // Gérer le fichier téléchargé
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
        }

        return $this->render('user/new_csv.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function importUsersFromCSV(string $filePath, EntityManagerInterface $entityManager): void
    {
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Ignorer la première ligne si elle contient des en-têtes
            fgetcsv($handle);

            while (($data = fgetcsv($handle)) !== false) {
                // Récupérer les données du CSV (ex: nom, prénom, email, etc.)
                $name = $data[0];
                $surname = $data[1];
                $email = $data[2];

                // Créer un nouvel utilisateur
                $user = new User();
                $user->setName($name);
                $user->setSurname($surname);
                $user->setEmail($email);

                // Sauvegarder l'utilisateur dans la base de données
                $entityManager->persist($user);
            }

            fclose($handle);

            // Sauvegarder tous les utilisateurs dans la base de données
            $entityManager->flush();
        }
    }
}
