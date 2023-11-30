<?php


namespace App\Controller\Aluno;

use App\Form\Type\Jogo\IdentificarAlunoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuizRepository;
use Symfony\Component\HttpFoundation\Session\Session;

class JogoController extends AbstractController
{

    /**
     * @Route("/{codigo}/lets-go",
     *     name="jogo_quiz_identificar_aluno")
     */
    public function index(Request $request, QuizRepository $quizRepository)
    {
        $codigo = $request->get('codigo');

        $quiz = $quizRepository->findQuiz($codigo);

        $form = $this->createForm(IdentificarAlunoType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('jogo_quiz_sala_espera', ['codigo' => $codigo, 'nome' => $form->get('nome')->getData()]);
        }


        return $this->render('jogo/identificar-aluno.html.twig', [
            'form' => $form->createView(),
            'quiz' => $quiz
        ]);
    }

    /**
     * @Route("/{codigo}/jogo/sala-espera",
     *     name="jogo_quiz_sala_espera")
     */
    public function salaEspera(Request $request, QuizRepository $quizRepository)
    {
        $codigo = $request->get('codigo');

        $quiz = $quizRepository->findQuiz($codigo);

        $session = new Session();
        $session->start();
        $sessionId = $session->getId();;

        return $this->render('jogo/index.html.twig', [
            'quiz' => $quiz,
            'nome' => $request->get('nome'),
            'tipo' => 'ALUNO',
            'sessionId' => $sessionId
        ]);
    }
}
