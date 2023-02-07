<?php

namespace App\Controller;

use App\Entity\Housing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HousingController extends AbstractController
{
  #[Route("/logements/{slug}-{id}", name:"housing.show", requirements: [
    'slug' => '[\w\-]+',
    'id' => '\d+'
  ])]
  public function show(Housing $housing): Response
  {
    return $this->render('pages/housings/show.html.twig', [
      'housing' => $housing
    ]);
  }
}