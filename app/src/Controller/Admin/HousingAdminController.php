<?php

namespace App\Controller\Admin;

use App\Entity\Housing;
use App\Form\HousingType;
use App\Repository\HousingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/annonceur')]
#[IsGranted('ROLE_ADVERTISER')]
class HousingAdminController extends AbstractController
{
    #[Route('/logements', name: 'admin.housing.index', methods: ['GET'])]
    public function index(HousingRepository $housingRepository): Response
    {
        return $this->render('admin/housings/index.html.twig', [
            'housings' => $housingRepository->findAll(),
        ]);
    }

    #[Route('/logements/creation', name: 'admin.housing.new', methods: ['GET', 'POST'])]
    public function new(Request $request, HousingRepository $housingRepository): Response
    {
        $housing = new Housing();
        $form = $this->createForm(HousingType::class, $housing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $housingRepository->save($housing, true);

            return $this->redirectToRoute('admin.housing.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/housings/new.html.twig', [
            'housing' => $housing,
            'form' => $form,
        ]);
    }

    #[Route('/logements/{id}/edit', name: 'admin.housing.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Housing $housing, HousingRepository $housingRepository): Response
    {
        $form = $this->createForm(HousingType::class, $housing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $housingRepository->save($housing, true);

            return $this->redirectToRoute('admin.housing.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/housings/edit.html.twig', [
            'housing' => $housing,
            'form' => $form,
        ]);
    }

    #[Route('/logements/{id}', name: 'admin.housing.delete', methods: ['POST'])]
    public function delete(Request $request, Housing $housing, HousingRepository $housingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$housing->getId(), $request->request->get('_token'))) {
            $housingRepository->remove($housing, true);
        }

        return $this->redirectToRoute('admin.housing.index', [], Response::HTTP_SEE_OTHER);
    }
}
