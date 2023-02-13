<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Housing;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
  public function __construct(
    private BookingRepository $bookingRepository
  )
  {
  }

  #[Route('reservations', name: 'booking.index', methods: ['GET'])]
  public function index(): Response
  {
    $bookings = $this->bookingRepository->findBy([
      'user' => $this->getUser()
    ]);

    return $this->render('pages/booking/index.html.twig', [
      'bookings' => $bookings
    ]);
  }

  #[Route('/logements/{id}/reservation', name: 'booking.book', methods: ['POST'])]
  public function book(Housing $housing, Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_USER');

    if ($housing->getBookings()->isEmpty()) {
      $booking = new Booking();

      $booking
        ->setStartDate(new \DateTime($request->request->get('start_date')))
        ->setEndDate(new \DateTime($request->request->get('end_date')))
        ->setUser($this->getUser())
        ->setHousing($housing);

      $this->bookingRepository->save($booking, true);

      $this->addFlash('success', 'Réservation du logement prise en compte!');
    } else {
      $this->addFlash('error', 'Le logement est déjà réserver!');
    }

    return $this->redirectToRoute('housing.show', [
      'slug' => $housing->getSlug(),
      'id' => $housing->getId()
    ]);
  }
}