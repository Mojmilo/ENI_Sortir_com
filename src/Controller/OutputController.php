<?php

namespace App\Controller;

use App\Entity\Output;
use App\Entity\Site;
use App\Enum\Status;
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
    public function index(Request $request, OutputRepository $outputRepository, EntityManagerInterface $entityManager): Response
    {
        $name = $request->query->get('name');
        $site = $request->query->get('site');
        $startDatetime = $request->query->get('startDatetime');
        $endDatetime = $request->query->get('endDatetime');

        $sites = $entityManager->getRepository(Site::class)->findAll();

        $outputs = $outputRepository->findGeneral();

        if ($name) {
            $outputs->andWhere('o.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($site) {
            $outputs->andWhere('o.site = :site')
                ->setParameter('site', $site);
        }

        if ($startDatetime) {
            $outputs->andWhere('o.startDatetime >= :startDatetime')
                ->setParameter('startDatetime', $startDatetime);
        }

        if ($endDatetime) {
            $outputs->andWhere('o.startDatetime <= :endDatetime')
                ->setParameter('endDatetime', $endDatetime);
        }

        return $this->render('output/index.html.twig', [
            'outputs' => $outputs->getQuery()->getResult(),
            'name' => $name,
            'site' => $site,
            'sites' => $sites,
            'startDatetime' => $startDatetime,
            'endDatetime' => $endDatetime,
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

            $output->setStatus(Status::CREATED);
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

    #[Route('/{id}/cancel', name: 'app_output_cancel', methods: ['GET', 'POST'])]
    public function cancel(Request $request, Output $output, EntityManagerInterface $entityManager): Response
    {
        if ($output->getStartDatetime() < new \DateTime()) {
            $this->addFlash('danger', 'Impossible de supprimer une sortie passée');
            return $this->redirectToRoute('app_output_index');
        } else {
            $output->setStatus(Status::CANCELLED);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_output_index');
    }

    #[Route('/{id}/archive', name: 'app_output_archive', methods: ['GET', 'POST'])]
    public function archive(Request $request, Output $output, EntityManagerInterface $entityManager): Response
    {
        $oneMonthAgo = (new \DateTime())->modify('-1 month');

        if ($output->getStartDatetime() >= $oneMonthAgo) {
            $this->addFlash('danger', 'Impossible de supprimer une sortie passée depuis plus d’un mois');
            return $this->redirectToRoute('app_output_index');
        } else {
            $output->setStatus(Status::PAST);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_output_index');
    }
}
