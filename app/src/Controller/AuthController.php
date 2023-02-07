<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
  public function __construct(
    private UserRepository $userRepository
  )
  {
  }

  #[Route('/connexion', name: 'login')]
  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    return $this->render('auth/login.html.twig', [
      'last_username' => $authenticationUtils->getLastUsername(),
      'error' => $authenticationUtils->getLastAuthenticationError()
    ]);
  }

  #[Route('/inscription', name: 'register')]
  public function register(Request $request, UserPasswordHasherInterface $hasher, Security $security): Response
  {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $password = $hasher->hashPassword($user, $user->getPassword());
      $user->setPassword($password);

      $this->userRepository->save($user, true);

      $security->login($user);

      return $this->redirectToRoute('home');
    }

    return $this->render('auth/register.html.twig', [
      'form' => $form
    ]);
  }
}