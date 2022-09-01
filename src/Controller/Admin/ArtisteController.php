<?php

namespace App\Controller\Admin;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtisteController extends AbstractController
{
    #[Route('/admin/artiste', name: 'app_admin_artiste')]
    public function index(ArtisteRepository $artisteRepository): Response
    {
        $artistes = $artisteRepository->findAll();
        return $this->render('admin/artiste/artistes.html.twig', [
            'controller_name' => 'FestivalController',
            'artistes' => $artistes
        ]);
    }

    #[Route('/admin/artiste/supprimer/{id}', name: 'app_admin_artiste_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(ArtisteRepository $artisteRepository, EntityManagerInterface $entityManager, $id = null): Response
    {
        $artiste = $artisteRepository->find($id);
        $entityManager->remove($artiste);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            "L'artiste à été supprimé !"
        );

        return $this->redirectToRoute('app_admin_artiste');
    }

    #[Route('/admin/artiste/ajouter', name: 'app_admin_artiste_ajouter')]
    #[Route('/admin/artiste/modifier/{id}', name: 'app_admin_artiste_modifier', requirements: ['id' => '\d+'])]
    public function ajouter(ArtisteRepository $artisteRepository, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {
        $message = "";
        if($request->attributes->get('_route') == "app_admin_artiste_ajouter"){
            $artiste = new Artiste();
            $message = "L'artiste à été ajouté !";
        } else{
            $artiste = $artisteRepository->find($id);
            $message = "L'artiste à été modifié !";
        }

        $form = $this->createForm(ArtisteType::class, $artiste);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $artiste = $form->getData();

            // ... perform some action, such as saving the task to the database
            $entityManager->persist($artiste);
            $entityManager->flush();
            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('app_admin_artiste');
        }

        return $this->render('admin/artiste/artisteForm.html.twig', [
            'controller_name' => 'ArtisteController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/api/artiste', name: 'app_get_artiste')]
    public function getArtiste(ArtisteRepository $artisteRepository, Request $request): Response
    {
        return $this->json($artisteRepository->search($request->query->get('q')));
    }
}
