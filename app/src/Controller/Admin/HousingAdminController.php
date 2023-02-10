<?php

namespace App\Controller\Admin;

use App\Entity\Housing;
use App\Form\HousingType;
use App\Repository\HousingRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        return $this->render('admin/housing/index.html.twig', [
            'housings' => $housingRepository->findAllAssocietedAdvertiser($this->getUser())
        ]);
    }

    #[Route('/logements/creation', name: 'admin.housing.new', methods: ['GET', 'POST'])]
    public function new(Request $request, HousingRepository $housingRepository): Response
    {
        $housing = new Housing();

        $form = $this->createForm(HousingType::class, $housing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $housing->setUser($this->getUser());
            $housingRepository->save($housing, true);

            return $this->redirectToRoute('admin.housing.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/housing/new.html.twig', [
            'housing' => $housing,
            'form' => $form,
        ]);
    }

    #[Route('/logements/{id}/edit', name: 'admin.housing.edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $housing = $manager->getRepository(Housing::class)->find($id);
        $form = $this->createForm(HousingType::class, $housing);
        $form->handleRequest($request);

        $originalImages = $housing->getImages();

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalImages as $image) {
                if (!$housing->getImages()->contains($image)) {
                    $image->setHousing(null);
                    $manager->persist($image);
                    $manager->remove($image);
                }
            }

            $manager->persist($housing);
            $manager->flush();

            return $this->redirectToRoute('admin.housing.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/housing/edit.html.twig', [
            'housing' => $housing,
            'form' => $form,
        ]);
    }

    #[Route('/logements/{id}', name: 'admin.housing.delete', methods: ['POST'])]
    public function delete(Housing $housing, Request $request, HousingRepository $housingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$housing->getId(), $request->request->get('_token'))) {
            $housingRepository->remove($housing, true);
            $this->addFlash('success', 'Suppression du logement avec succès');
        }

        return $this->redirectToRoute('admin.housing.index', [], Response::HTTP_SEE_OTHER);
    }
}
