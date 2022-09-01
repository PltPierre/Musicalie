<?php

namespace App\Controller\Admin;

use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FestivalController extends AbstractController
{
    #[Route('/admin/festival', name: 'app_admin_festival')]
    public function index(FestivalRepository $festivalRepository): Response
    {
        $festivals = $festivalRepository->findAll();
        return $this->render('admin/festival/festivals.html.twig', [
            'controller_name' => 'FestivalController',
            'festivals' => $festivals
        ]);
    }

    #[Route('/admin/festival/supprimer/{id}', name: 'app_admin_festival_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(FestivalRepository $festivalRepository, EntityManagerInterface $entityManager, $id = null): Response
    {
        $memoire = $festivalRepository->find($id);
        $entityManager->remove($memoire);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            "Le festival à été supprimé !"
        );

        return $this->redirectToRoute('app_admin_festival');
    }

    #[Route('/admin/festival/ajouter', name: 'app_admin_festival_ajouter')]
    #[Route('/admin/festival/modifier/{id}', name: 'app_admin_festival_modifier', requirements: ['id' => '\d+'])]
    public function ajouter(FestivalRepository $festivalRepository, Request $request,FileUploader $fileUploader, EntityManagerInterface $entityManager, $id = null): Response
    {
        $message = "";
        if($request->attributes->get('_route') == "app_admin_festival_ajouter"){
            $festival = new Festival();
            $message = "Le festival à été ajouté !";
        } else{
            $festival = $festivalRepository->find($id);
            $message = "Le festival à été modifié !";
        }

        $form = $this->createForm(FestivalType::class, $festival);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('affiche')->getData();
            if ($photoFile) {
                $photoFileName = $fileUploader->upload($photoFile);
                $festival->setAffiche($photoFileName);
            }
            $festival = $form->getData();
            $festival->setPostedTime(new \DateTime());

            // ... perform some action, such as saving the task to the database
            $entityManager->persist($festival);
            $entityManager->flush();
            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('app_admin_festival');
        }

        return $this->render('admin/festival/festivalsForm.html.twig', [
            'controller_name' => 'MemoireController',
            'form' => $form->createView()
        ]);
    }
}
