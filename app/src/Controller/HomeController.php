<?php

namespace App\Controller;

use App\Repository\HousingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
  #[Route('/', name: 'home')]
  public function index(HousingRepository $repo): Response
  {
    $housings = $repo->findAllPublished();

    return $this->render('pages/home.html.twig', [
      'housings' => $housings
    ]);
  }
}