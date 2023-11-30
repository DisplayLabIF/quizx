<?php


namespace App\Controller\Aluno;

use App\Entity\Curso\Curso;
use App\Entity\Curso\Matricula;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CursoController extends AbstractController
{

    /**
     * @Route("/aluno/cursos",
     *     name="aluno_cursos")
     */
    public function index(Request $request)
    {
        return $this->render('aluno/curso/index.html.twig', [
            'matriculas' => $this->getUser()->getMatriculas()
        ]);
    }

    /**
     * @Route("/aluno/cursos/{matricula_id}",
     *     name="aluno_curso_show")
     */
    public function actionShow(Request $request)
    {
        $matriculaId = $request->get('matricula_id');

        $em = $this->getDoctrine()->getManager();

        $matricula = $em->getRepository(Matricula::class)->find($matriculaId);

        return $this->render('aluno/curso/show.html.twig', [
            'curso' => $matricula->getTurma()->getCurso(),
            'matricula' => $matricula
        ]);
    }
}
