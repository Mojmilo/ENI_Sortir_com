<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\Output;
use App\Entity\Status;
use App\Form\OutputType;
use App\Repository\OutputRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/output')]
final class OutputController extends AbstractController
{
    #[Route(name: 'app_output_index', methods: ['GET'])]
    public function index(OutputRepository $outputRepository): Response
    {
        return $this->render('output/index.html.twig', [
            'outputs' => $outputRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_output_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $output = new Output();
        $site = $this->getUser()->getSite();
        $output->setSite($site);
        $form = $this->createForm(OutputType::class, $output);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $output->getLocation();
            if ($location) {
                $entityManager->persist($location);
            }

            $isPublished = $request->request->get('action') === 'save_and_publish'; // TODO : A faire

            $output->setStatus($entityManager->getReference(Status::class, 1)); // TODO : Utiliser une enum
            $output->setOrganisator($this->getUser());
            $entityManager->persist($output);
            $entityManager->flush();

            return $this->redirectToRoute('app_output_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('output/new.html.twig', [
            'output' => $output,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_output_show', methods: ['GET'])]
    public function show(Output $output): Response
    {
        return $this->render('output/show.html.twig', [
            'output' => $output,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_output_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Output $output, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OutputType::class, $output);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_output_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('output/edit.html.twig', [
            'output' => $output,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_output_delete', methods: ['POST'])]
    public function delete(Request $request, Output $output, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$output->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($output);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_output_index', [], Response::HTTP_SEE_OTHER);
    }
}
