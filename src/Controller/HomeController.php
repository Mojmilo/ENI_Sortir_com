<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Site;
use App\Form\CityType;
use App\Repository\CityRepository;
use App\Repository\OutputRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class HomeController extends AbstractController
{
    #[Route(name: 'app_home_index', methods: ['GET'])]
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
        return $this->render('home/index.html.twig', [
            'outputs' => $outputs->getQuery()->getResult(),
            'name' => $name,
            'site' => $site,
            'sites' => $sites,
            'startDatetime' => $startDatetime,
            'endDatetime' => $endDatetime,
        ]);
    }
}
