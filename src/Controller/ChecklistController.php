<?php

namespace App\Controller;

use App\Entity\Checklist;
use App\Form\ChecklistType;
use App\Repository\ChecklistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/checklist')]
class ChecklistController extends AbstractController
{
    #[Route('/', name: 'app_checklist_index', methods: ['GET'])]
    public function index(ChecklistRepository $checklistRepository): Response
    {
        return $this->render('checklist/index.html.twig', [
            'checklists' => $checklistRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_checklist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if($user) {
            $checklist = new Checklist();
            $form = $this->createForm(ChecklistType::class, $checklist);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $checklist->setUser($user);
                $entityManager->persist($checklist);
                $entityManager->flush();

                return $this->redirectToRoute('app_checklist_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('checklist/new.html.twig', [
                'checklist' => $checklist,
                'form' => $form,
            ]);
        }
    }

    #[Route('/{id}', name: 'app_checklist_show', methods: ['GET'])]
    public function show(Checklist $checklist): Response
    {
        return $this->render('checklist/show.html.twig', [
            'checklist' => $checklist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_checklist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Checklist $checklist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChecklistType::class, $checklist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_checklist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('checklist/edit.html.twig', [
            'checklist' => $checklist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_checklist_delete', methods: ['POST'])]
    public function delete(Request $request, Checklist $checklist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$checklist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($checklist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_checklist_index', [], Response::HTTP_SEE_OTHER);
    }
}
