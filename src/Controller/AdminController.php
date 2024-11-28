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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use App\Service\UserImportService;


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

    #[Route('/import-csv', name: 'app_user_new_csv', methods: ['GET', 'POST'])]
    public function import(Request $request, UserImportService $userImportService): Response
    {
        $form = $this->createForm(ImportUsersType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('csvFile')->getData();

            if ($file) {
                $filePath = $file->getRealPath();

                try {
                    // Import des utilisateurs
                    $userImportService->importUsersFromCSV($filePath);

                    $this->addFlash('success', 'Utilisateurs importés avec succès!');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
                }
            } else {
                $this->addFlash('error', 'Veuillez sélectionner un fichier CSV.');
            }

            return $this->redirectToRoute('app_user_new_csv');
        }

        return $this->render('user/new_csv.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}