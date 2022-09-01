<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartementController extends AbstractController
{
    #[Route('/departement/{id}', name: 'app_departement', requirements: ['id' => '\d+'])]
    public function index(FestivalRepository $festivalRepository,DepartementRepository $departementRepository, $id): Response
    {
        $festivals = $festivalRepository->findByDepartement($id);
        $departement = $departementRepository->findByNum($id);
        return $this->render('departement/festivalsDepartement.html.twig', [
            'controller_name' => 'DepartementController',
            'festivals' => $festivals,
            'departement' => $departement
        ]);
    }

    #[Route('/departement/{idDep}/festival/{idFest}', name: 'app_departement_festival', requirements: ['idDep' => '\d+', 'idFest'=>'\d+'])]
    public function infosFestival(FestivalRepository $festivalRepository,DepartementRepository $departementRepository, $idDep, $idFest): Response
    {
        $festival = $festivalRepository->findById($idFest);
        $departement = $departementRepository->findByNum($idDep);
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');
        $dateFormat = strftime('%A %d %B %Y',strtotime($festival->getDate()->format('d-m-Y')));
        return $this->render('departement/infosFestivals.html.twig', [
            'controller_name' => 'DepartementController',
            'festival' => $festival,
            'departement' => $departement,
            'dateFormat'=>$dateFormat
        ]);
    }
}
