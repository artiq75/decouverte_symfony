<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Housing;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\HousingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
  public function __construct(
    private CommentRepository $repository
  )
  {
  }

  #[Route('/logements/{id}/commentaires', name: 'comment.new', methods: ['GET', 'POST'])]
  public function new (Housing $housing, Request $request): Response
  {
    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment, [
      'action' => $this->generateUrl('comment.new', [
        'id' => $housing->getId()
      ])
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->denyAccessUnlessGranted('ROLE_USER');

      $comment
        ->setUser($this->getUser())
        ->setHousing($housing);

      $this->repository->save($comment, true);
      $this->addFlash('success', 'Ajout du commentaire avec succès');

      return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    return $this->render('includes/_comment_form.html.twig', [
      'form' => $form
    ]);
  }

  #[Route('/commentaires/{id}', name: 'comment.delete', methods: ['POST'])]
  public function delete(Comment $comment, Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_USER');

    if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
      $this->repository->remove($comment, true);
      $this->addFlash('success', 'Suppression du commentaire avec succès');
    }

    return $this->redirect($request->server->get('HTTP_REFERER'));
  }
}