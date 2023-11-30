<?php

namespace App\Controller\App;

use App\Entity\Curso\Curso;
use App\Form\Type\Curso\NovoCursoType;
use App\Repository\CursoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CursoController extends AbstractController
{
    /**
     * @Route("/app/curso", name="app_curso")
     */
    public function index(CursoRepository $cursoRepository)
    {
        $cursos = $cursoRepository->findBy(['user' => $this->getUser(), 'active' => 1], ['nome' => 'ASC']);
        $qtdCursos = sizeof($cursos);
        $qtdTurmas = [];
        $qtdAlunos = [];
        $qtdConteudos = [];
        foreach ($cursos as $key => $curso) {
            $qtdTurmas[] = sizeof($curso->getTurmas());
            $qtdConteudos[$key] = 0;
            $qtdAlunos[$key] = 0;
            foreach ($curso->getModulos() as $modulo) {
                $qtdConteudos[$key] += $modulo->getAulas()->count();
            }
            foreach ($curso->getTurmas() as $turma) {
                $qtdAlunos[$key] += $turma->getMatriculasFinalizadas()->count();
            }
        }
        //

        return $this->render('app/curso/index.html.twig', [
            'controller_name' => 'CursoController',
            'cursos' => $cursos,
            'quantidadeCursos' => $qtdCursos,
            'quantidadeTurmas' => $qtdTurmas,
            'quantidadeAlunos' => $qtdAlunos,
            'quantidadeConteudos' => $qtdConteudos
        ]);
    }

    /**
     * @Route("/app/curso/novo", name="app_curso_novo")
     */
    public function create(Request $request)
    {
        $curso = new Curso();

        $form = $this->createForm(NovoCursoType::class, $curso);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $curso = $form->getData();
            $curso->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($curso);
            $entityManager->flush();
            return $this->redirectToRoute('app_curso_gerenciar', ['curso_id' => $curso->getId()]);
        }

        return $this->render('app/curso/novo-curso.html.twig', [
            'form' => $form->createView(),
            'user_id' => $this->getUser()->getId(),
            'curso_id' => $curso->getId()
        ]);
    }

    /**
     * @Route("/app/edit-curso/{curso_id}", name="app_curso_edit")
     */
    public function edit(Request $request)
    {
        $curso_id = $request->get('curso_id');
        $entityManager = $this->getDoctrine()->getManager();

        $curso = $entityManager->getRepository(Curso::class)->find($curso_id);

        $form = $this->createForm(NovoCursoType::class, $curso);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $curso = $form->getData();

            $entityManager->persist($curso);
            $entityManager->flush();
            return $this->redirectToRoute('app_curso_gerenciar', ['curso_id' => $curso->getId()]);
        }

        return $this->render('app/curso/edit-curso.html.twig', [
            'form' => $form->createView(),
            'user_id' => $this->getUser()->getId(),
            'curso_id' => $curso->getId()
        ]);
    }
}
