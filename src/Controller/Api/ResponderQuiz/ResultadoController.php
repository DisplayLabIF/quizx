<?php

namespace App\Controller\Api\ResponderQuiz;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Api\TraitApiController;
use App\Entity\Quiz\RespostaQuiz;
use App\Entity\Quiz\RespostaQuizQuestoes;
use App\Repository\NivelamentoRepository;
use App\Repository\QuizRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

class ResultadoController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Post("/resultado", name="api_post_resultado")
     * @return Response
     */
    public function postResultadoAction(Request $request, QuizRepository $quizRepository, NivelamentoRepository $nivelamentoRepository)
    {

        try {
            $quizId = $request->get('quiz_id');
            $userId = $request->get('user_id');

            $em = $this->getDoctrine()->getManager();

            $quiz = null;
            $nivelamento = null;
            $response = null;

            $quiz = $quizRepository->findQuiz($quizId);

            if (!$quiz)
                $nivelamento = $nivelamentoRepository->findNivelamento($quizId);

            if ($quiz === null && $nivelamento === null) {
                $response =  new Response('{"Erro": "Quiz ou nivelamento não encontrado."}' . $quizId, Response::HTTP_NOT_FOUND);
                return $response;
            }

            if ($nivelamento) {
                $respostaQuiz = $em->getRepository(RespostaQuiz::class)
                    ->findBy(['nivelamento' => $nivelamento->getId(), 'aluno' => $userId, 'finalizou' => 1]);
            } else {
                $respostaQuiz = $em->getRepository(RespostaQuiz::class)
                    ->findBy(['quizAtual' => $quiz->getId(), 'aluno' => $userId, 'finalizou' => 1]);
            }

            if ($respostaQuiz == null) {
                $response =  new Response('{"Erro": "Reposta não encontrada."}', Response::HTTP_NOT_FOUND);
                return $response;
            }

            $notaObtida = 0;
            $notaPossivel = 0;
            $qtdQuestoes = 0;

            $nivelAtingido = null;

            if ($nivelamento) {

                $respostaQuiz = $em->getRepository(RespostaQuiz::class)
                    ->findBy(['nivelamento' => $nivelamento->getId(), 'aluno' => $userId]);


                if (isset($respostaQuiz[count($respostaQuiz) - 1]) and $respostaQuiz[count($respostaQuiz) - 1]->getFinalizou() == 1) {

                    $nivelAtingido = $respostaQuiz[count($respostaQuiz) - 1]->getQuizAtual()->getNome();

                    foreach ($respostaQuiz[count($respostaQuiz) - 1]->getNotas() as $nota) {
                        $notaObtida += $nota->getNota();
                    }
                }

                if ($nivelAtingido) {
                    foreach ($nivelamento->getQuizesNivelamento() as $quizNivelamento) {
                        foreach ($quizNivelamento->getQuiz()->getQuestoes() as $questao) {
                            $notaPossivel += $questao->getValor();
                            $qtdQuestoes++;
                        }
                    }
                }
            } else {
                $notaObtida = (float)$respostaQuiz[count($respostaQuiz) - 1]->getNota();
                foreach ($quiz->getQuestoes() as $questao) {
                    $notaPossivel += $questao->getValor();
                    $qtdQuestoes++;
                }
            }

            $respostaQuizQuestoes = $em->getRepository(RespostaQuizQuestoes::class)
                ->findBy([
                    'respostaQuiz' => (isset($respostaQuiz[count($respostaQuiz) - 1])) ? $respostaQuiz[count($respostaQuiz) - 1]->getId() : null
                ], ['quiz' => 'ASC']);

            $details = [];
            $qtdAcertos = 0;

            foreach ($respostaQuizQuestoes as $respostaQuizQuestao) {
                if ($respostaQuizQuestao->getCorreto()) {
                    $qtdAcertos += 1;
                }
                $details[] = [
                    "quiz_questao" => $respostaQuizQuestao->getQuiz()->getNome() . ' - questão ' . $respostaQuizQuestao->getQuestao()->getNumeroQuestao(),
                    "correto" => $respostaQuizQuestao->getCorreto()
                ];
            }

            $data = [
                'nota_obtida' => (float)$notaObtida,
                'nota_possivel' => $notaPossivel,
                'qtd_questoes' => $qtdQuestoes,
                'nivel_atingido' => $nivelAtingido,
                'quiz_atual' => $respostaQuiz[count($respostaQuiz) - 1]->getQuizAtual()->getNome(),
                'qtd_acertos' => $qtdAcertos,
                'details' => $details
            ];

            $response =  new Response(
                $this->serializer->serialize(
                    $data,
                    $request->getRequestFormat()
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $response = new Response(
                $this->serializer->serialize(
                    $e->getMessage(),
                    $request->getRequestFormat()
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $response;
    }
}
