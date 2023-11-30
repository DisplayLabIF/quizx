<?php

namespace App\Controller\Api\ResponderQuiz;

use App\Entity\Quiz\NotaQuiz;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quiz\Questao;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\RespostaQuiz;
use App\Entity\Quiz\RespostaQuizQuestoes;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use  App\Controller\Api\TraitApiController;
use App\Entity\Quiz\LeadQuiz;
use App\Entity\User\Aluno;
use App\Repository\QuestaoRepository;
use App\Repository\QuizRepository;
use App\Repository\RespostaQuizRepository;
use App\Service\FinalizarQuizOuProximoQuizNivelamentoService;

class BuscaQuestaoController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Post("questoes/{quiz_id}/busca-questao", name="api_quiz_busca_questao")
     * @return Response
     */
    public function postQuizBuscaQuestao(Request $request, FinalizarQuizOuProximoQuizNivelamentoService $finalizarQuizOuProximoQuizNivelamentoService, NivelamentoRepository $nivelamentoRepository, QuizRepository $quizRepository, QuestaoRepository $questaoRepository, RespostaQuizRepository $respostaQuizRepository)
    {
        $quizId = $request->get('quiz_id');
        $userId = $request->get('user_id');
        $type = $request->get('type');
        $em = $this->getDoctrine()->getManager();
        $response =  new Response('{"Erro": "Quiz não encontrado."}', Response::HTTP_NOT_FOUND);

        try {
            $nivelamento = null;
            $quiz = null;

            if ($type === 'NIVELAMENTO') {
                $nivelamento = $nivelamentoRepository->findNivelamento($quizId);
            } else if ($type === 'QUIZ') {
                $quiz = $quizRepository->findQuiz($quizId);
            }

            if ($quiz || $nivelamento) {

                $data = [];
                $respostaQuiz = $this->getResposta($quiz, $nivelamento, $userId, $respostaQuizRepository);

                $questao = $questaoRepository->getQuestao($respostaQuiz->getQuizAtual()->getId(), $userId, false);

                if (count($questao) == 0) { //buscar questões puladas
                    $questao = $questaoRepository->getQuestao($respostaQuiz->getQuizAtual()->getId(), $userId, false, true);
                }

                if ($respostaQuiz->getTempoEsgotado() || count($questao) == 0 || $respostaQuiz->getFinalizou()) { //todas questões respondidas
                    //se for um nivelamento irá verificar se existe um proximo quiz e se a a resposta tem a nota minima
                    //no caso de um quiz ira finalizar o quiz
                    if ($type === 'QUIZ') {
                        $data = $finalizarQuizOuProximoQuizNivelamentoService->finalizarQuiz($respostaQuiz);
                        if ($quiz->getConfiguracaoQuiz()->getMostrarGabarito())
                            $data['respostas'] = $respostaQuiz->getRespostas();
                    } else if ($type === 'NIVELAMENTO') {
                        $data = $finalizarQuizOuProximoQuizNivelamentoService->finalizarOuAtualizarProximoQuizNivelamento($respostaQuiz, $nivelamento);
                    }
                } else {
                    $data = [
                        'questao' => $questao[0],
                        'opcoes' => $questao[0]->getOpcoes()
                    ];
                }

                $mostrarCorrecao = $type === 'NIVELAMENTO' ? $nivelamento->getConfiguracaoNivelamento()->getMostrarCorrecao()
                    : $respostaQuiz->getQuizAtual()->getConfiguracaoQuiz()->getMostrarCorrecao();

                if (!$respostaQuiz->getFinalizou() && array_key_exists("questao", $data)) {
                    $data['satus_progresso_quiz'] = $this->montarBarraProgressoQuiz($respostaQuiz, $data['questao']);
                }

                $data['finalizou'] = $respostaQuiz ? $respostaQuiz->getFinalizou() : false;
                $data['lead_capturado'] = $respostaQuiz ? $respostaQuiz->getLeadCapturado() : false;
                $data['tempo_esgotado'] = $respostaQuiz ? $respostaQuiz->getTempoEsgotado() : false;


                $response =  new Response(
                    $this->serializer->serialize(
                        $data,
                        $request->getRequestFormat()
                    ),
                    Response::HTTP_OK
                );
            }
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

    private function getResposta($quiz, $nivelamento, $userId, RespostaQuizRepository $respostaQuizRepository)
    {
        $em = $this->getDoctrine()->getManager();

        $respostaQuiz = $respostaQuizRepository->findResposta(
            $quiz ? $quiz->getId() : null,
            $nivelamento ? $nivelamento->getId() : null,
            $userId
        );

        if (!$respostaQuiz) {
            $respostaQuiz = new RespostaQuiz();

            if ($nivelamento) {
                $respostaQuiz
                    ->setQuizAtual($nivelamento->getQuizesNivelamento()->first()->getQuiz());
            } else {
                $respostaQuiz->setQuizAtual($quiz);
            }

            $respostaQuiz
                ->setNota(0)
                ->setApresentandoQuiz(false)
                ->setFinalizou(false)
                ->setAluno($userId);

            $lead = $em->getRepository(LeadQuiz::class)->find($userId);
            if ($lead) {
                $respostaQuiz->setLeadQuizEntity($lead);
            } else {
                $aluno = $em->getRepository(Aluno::class)->find($userId);
                if ($aluno)
                    $respostaQuiz->setAlunoEntity($aluno);
            }
        } else {
            $respostaQuiz->setApresentandoQuiz(false);
        }

        if (
            ($nivelamento && $nivelamento->getConfiguracaoNivelamento()->getObterDadosRespondente() && $nivelamento->getConfiguracaoNivelamento()->getQuandoObterDados() === 'INICIO')
            ||
            ($quiz && $quiz->getConfiguracaoQuiz()->getObterDadosRespondente() && $quiz->getConfiguracaoQuiz()->getQuandoObterDados() === 'INICIO')
        ) {
            if (!$respostaQuiz->getLeadCapturado()) {
                $lead = $em->getRepository(LeadQuiz::class)->find($respostaQuiz->getAluno());
                if ($lead) {
                    $respostaQuiz->setLeadCapturado(true);
                } else {
                    $aluno = $em->getRepository(Aluno::class)->find($respostaQuiz->getAluno());
                    $respostaQuiz->setLeadCapturado($aluno ? true : false);
                }
            }
        }
        $em->persist($respostaQuiz);
        $em->flush();

        return $respostaQuiz;
    }

    private function montarBarraProgressoQuiz($respostaQuiz, $dataQuestao)
    {
        $questoesBarraProgresso = [];
        $qtdRespondidas = 0;
        $selected = 0;
        $porcentagem = 100 / $respostaQuiz->getQuizAtual()->getQuestoes()->count();
        foreach ($respostaQuiz->getQuizAtual()->getQuestoes() as $key => $questao) {
            $questoesBarraProgresso[] = [
                'porcentagem' => $porcentagem,
                'numero_questao' => $questao->getNumeroQuestao(),
                'correcao' => '',
                'selected' => $dataQuestao->getId() === $questao->getId() ? true : false
            ];
            if ($questoesBarraProgresso[$key]['selected']) {
                $selected = $key;
            }
            foreach ($respostaQuiz->getRespostas() as  $resposta) {
                if (($questao->getId() === $resposta->getQuestao()->getId()) && !$resposta->getPulou()) {
                    $questoesBarraProgresso[$key]['correcao'] = $resposta->getCorreto();
                    $qtdRespondidas++;
                }
            }
        }

        return  [
            'questoes' => $questoesBarraProgresso,
            'status' => [
                'porcentagem' => ($porcentagem * $qtdRespondidas),
                'qtd_questoes' => ($selected + 1) . '/' . $respostaQuiz->getQuizAtual()->getQuestoes()->count()
            ]
        ];
    }
}
