<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FestivalController extends AbstractController
{
    #[Route('/', name: 'app_festival')]
    public function index(FestivalRepository $festivalRepository, DepartementRepository $departementRepository): Response
    {
        $festivals = $festivalRepository->findLastThree();
        $departements = $departementRepository->findAll();
        return $this->render('festival/home.html.twig', [
            'controller_name' => 'FestivalController',
            'festivals'=>$festivals,
            'departements'=>$departements
        ]);
    }

    #[Route('/api/festival', name: 'app_get_festival')]
    public function getArtiste(FestivalRepository $festivalRepository, Request $request): Response
    {
        return $this->json($festivalRepository->search($request->query->get('q')));
    }
}
