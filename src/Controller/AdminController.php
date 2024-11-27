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
                //$site = new Site();
                $site = $entityManager->getRepository(Site::class)->find($data[0]);
                $first_name = $data[1];
                $last_name = $data[2];
                $phone = $data[3];
                $email = $data[4];
                $roles = json_decode($data[5], true);
                $password = $data[6];
                $active = $data[7];
                $pseudo = $data[8];

                   // dd($data[0]);
                   // dd($data[1]);
                   // dd($data[2]);
                   // dd($data[3]);
                   // dd($data[4]);
                   // dd($data[5]);
                   // dd($data[6]);
                   // dd($data[7]);
                   // dd($data[8]);

                    //TODO foreach + verif champs
                // Créer un nouvel utilisateur
                $user = new User();
                $user->setSite($site);
                $user->setFirstName($first_name);
                $user->setLastname($last_name);
                $user->setPhone($phone);
                $user->setEmail($email);
                $user->setRoles($roles);
                $user->setPassword($password);
                $user->setActive($active);
                $user->setPseudo($pseudo);


                // Sauvegarder l'utilisateur dans la base de données
                $entityManager->persist($user);
            }

            fclose($handle);

            // Sauvegarder tous les utilisateurs dans la base de données
            $entityManager->flush();
        }
    }
}