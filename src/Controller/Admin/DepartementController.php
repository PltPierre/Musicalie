<?php

namespace App\Controller\Admin;

use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartementController extends AbstractController
{
    #[Route('/admin/departement', name: 'app_admin_departement')]
    public function index(DepartementRepository $departementRepository): Response
    {
        $departements = $departementRepository->findAll();
        return $this->render('admin/departement/departement.html.twig', [
            'controller_name' => 'DepartementController',
            'departements' => $departements
        ]);
    }

    #[Route('/admin/departement/supprimer/{id}', name: 'app_admin_departement_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(DepartementRepository $departementRepository, EntityManagerInterface $entityManager, $id = null): Response
    {
        $departement = $departementRepository->find($id);
        $entityManager->remove($departement);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            "Le département à été supprimé !"
        );

        return $this->redirectToRoute('app_admin_departement');
    }

    #[Route('/admin/departement/ajouter', name: 'app_admin_departement_ajouter')]
    #[Route('/admin/departement/modifier/{id}', name: 'app_admin_departement_modifier', requirements: ['id' => '\d+'])]
    public function ajouter(DepartementRepository $departementRepository, Request $request, EntityManagerInterface $entityManager, $id = null): Response
    {
        $message = "";
        if($request->attributes->get('_route') == "app_admin_departement_ajouter"){
            $departement = new Departement();
            $message = "Le département à été ajouté !";
        } else{
            $departement = $departementRepository->find($id);
            $message = "Le département à été modifié !";
        }

        $form = $this->createForm(DepartementType::class, $departement);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $departement = $form->getData();

            // ... perform some action, such as saving the task to the database
            $entityManager->persist($departement);
            $entityManager->flush();
            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('app_admin_departement');
        }

        return $this->render('admin/departement/departementForm.html.twig', [
            'controller_name' => 'DepartementController',
            'form' => $form->createView()
        ]);
    }
}
