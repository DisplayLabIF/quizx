<?php


namespace App\Controller\App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuizRepository;

class JogoController extends AbstractController
{

    /**
     * @Route("/app/{codigo}/jogo/sala-espera",
     *     name="app_iniciar_jogo_quiz")
     */
    public function index(Request $request, QuizRepository $quizRepository)
    {
        $codigo = $request->get('codigo');
        $em = $this->getDoctrine()->getManager();
        $quiz = $quizRepository->findQuiz($codigo);

        $quiz->setStartGame(true);
        $em->persist($quiz);
        $em->flush();

        return $this->render('jogo/index.html.twig', [
            'quiz' => $quiz,
            'tipo' => $this->getUser()->getTipo(),
            'nome' => $this->getUser()->getNome()
        ]);
    }

    /**
     * @Route("/app/{codigo}/jogo/encerrar",
     *     name="app_encerrar_jogo_quiz")
     */
    public function encerrarJogo(Request $request, QuizRepository $quizRepository)
    {
        $codigo = $request->get('codigo');
        $em = $this->getDoctrine()->getManager();
        $quiz = $quizRepository->findQuiz($codigo);

        $quiz->setStartGame(false);
        $em->persist($quiz);
        $em->flush();

        return $this->redirectToRoute('app_quiz_index');
    }

    /**
     * @Route("/app/{codigo}/jogo",
     *     name="app_jogo_quiz")
     */
    public function jogo(Request $request, QuizRepository $quizRepository)
    {
        $codigo = $request->get('codigo');
        // $em = $this->getDoctrine()->getManager();
        $quiz = $quizRepository->findQuiz($codigo);

        // $quiz->setStartGame(false);
        // $em->persist($quiz);
        // $em->flush();

        $pontos = 0;

        foreach ($quiz->getQuestoes() as $questao) {
            $pontos += $questao->getValor();
        }

        return $this->render('jogo/jogo.html.twig', [
            'quiz' => $quiz,
            'tipo' => $this->getUser()->getTipo(),
            'nome' => $this->getUser()->getNome(),
            'valorTotal' => $pontos
        ]);
    }

    /**
     * @Route("/app/{codigo}/jogo/finalizar",
     *     name="app_finalizar_jogo_quiz")
     */
    public function endGame(Request $request, QuizRepository $quizRepository)
    {
        $codigo = $request->get('codigo');
        $em = $this->getDoctrine()->getManager();
        $quiz = $quizRepository->findQuiz($codigo);

        $quiz->setStartGame(false);
        $em->persist($quiz);
        $em->flush();

        return $this->render('jogo/end-game.html.twig', [
            'quiz' => $quiz,
            'tipo' => $this->getUser()->getTipo(),
            'nome' => $this->getUser()->getNome(),
        ]);
    }
}
