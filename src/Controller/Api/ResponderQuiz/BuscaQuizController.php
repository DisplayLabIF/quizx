<?php

namespace App\Controller\Api\ResponderQuiz;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\RespostaQuiz;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Session\Session;
use  App\Controller\Api\TraitApiController;
use App\Entity\Quiz\ConfiguracaoNivelamento;
use App\Entity\Quiz\ConfiguracaoQuiz;
use App\Repository\NivelamentoRepository;
use App\Repository\QuizRepository;
use App\Repository\RespostaQuizRepository;
use App\Service\FinalizarQuizOuProximoQuizNivelamentoService;

class BuscaQuizController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Post("quizes/{quiz_id}/configuracao", name="api_quiz_configuracao")
     * @return Response
     */
    public function postQuizConfiguracao(Request $request, NivelamentoRepository $nivelamentoRepository, QuizRepository $quizRepository, RespostaQuizRepository $respostaQuizRepository)
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
                $sessionId = null;

                if (!$userId || $userId === 'null') {
                    $session = new Session();
                    $session->start();
                    $sessionId = $session->getId();

                    $userId = $sessionId;
                }

                $renderComponent = 'APRESENTA_QUIZ';

                $respostaQuiz =  $respostaQuizRepository->findResposta(
                    $quiz ? $quiz->getId() : null,
                    $nivelamento ? $nivelamento->getId() : null,
                    $userId
                );


                if ($respostaQuiz) {
                    $quiz = $respostaQuiz->getQuizAtual();
                    if ($type === 'QUIZ' || ($type === 'NIVELAMENTO' && !$respostaQuiz->getApresentandoQuiz())) {
                        $renderComponent = 'QUESTAO';
                    }
                } else if ($nivelamento) {
                    $quiz = $nivelamento->getQuizesNivelamento()->first()->getQuiz();
                }
                $materiais = $quiz->getConfiguracaoQuiz() ? $quiz->getConfiguracaoQuiz()->getMateriais() : null;


                $configuracao = $type === 'NIVELAMENTO' ? $nivelamento->getConfiguracaoNivelamento() : $quiz->getConfiguracaoQuiz();

                if ($type === 'QUIZ' && !$configuracao) {
                    $configuracao =  $this->setDefaultConfigQuiz($quiz);
                }

                $configuracao->setCamposPersonalizados($configuracao->getCamposPersonalizados());

                $data = [
                    'nome' => $quiz->getNome(),
                    'assuntos' => $quiz->getAssuntos(),
                    'nivel' => $quiz->getNivel(),
                    'image' => $quiz->getImage(),
                    'render_component' => $renderComponent,
                    'qtd_questoes' => $quiz->getQuestoes()->count(),
                    'configuracao' => $configuracao,
                    'personalizacao' => $quiz->getPersonalizacaoQuiz(),
                    'configuracao_marketing' => $quiz->getConfiguracaoMarketingQuiz(),
                    'materiais' => $materiais,
                    'session_id' => $sessionId,
                    'finalizou' => $respostaQuiz ? $respostaQuiz->getFinalizou() : false,
                    'lead_capturado' => $respostaQuiz ? $respostaQuiz->getLeadCapturado() : false,
                    'start_game' => $quiz->getStartGame() ? $quiz->getStartGame() : false
                ];
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

    /**
     *
     * @Rest\Post("quizes/{quiz_id}/sair-resposta", name="api_quiz_sair_resposta")
     * @return Response
     */
    public function postQuizSairResposta(Request $request, NivelamentoRepository $nivelamentoRepository, QuizRepository $quizRepository, RespostaQuizRepository $respostaQuizRepository)
    {
        $quizId = $request->get('quiz_id');
        $userId = $request->get('user_id');
        $type = $request->get('type');

        $em = $this->getDoctrine()->getManager();
        $response =  new Response('{"Erro": "Resposta do quiz não encontrado."}', Response::HTTP_NOT_FOUND);

        try {
            $nivelamento = null;
            $quiz = null;

            if ($type === 'NIVELAMENTO') {
                $nivelamento = $nivelamentoRepository->findNivelamento($quizId);
            } else if ($type === 'QUIZ') {
                $quiz = $quizRepository->findQuiz($quizId);
            }

            $respostaQuiz = $respostaQuizRepository->findResposta(
                $quiz ? $quiz->getId() : null,
                $nivelamento ? $nivelamento->getId() : null,
                $userId
            );

            if ($respostaQuiz) {
                $respostaQuiz->setFinalizou(true);
                $em->persist($respostaQuiz);
                $em->flush();
                $data = [
                    'render_component' => 'CARREGAR_QUIZ'
                ];

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

    /**
     *
     * @Rest\Post("quizes/{quiz_id}/tempo-esgotado", name="api_quiz_tempo-esgotado")
     * @return Response
     */
    public function postQuizTempoEsgotado(Request $request, NivelamentoRepository $nivelamentoRepository, QuizRepository $quizRepository, RespostaQuizRepository $respostaQuizRepository, FinalizarQuizOuProximoQuizNivelamentoService $finalizarQuizOuProximoQuizNivelamentoService)
    {
        $quizId = $request->get('quiz_id');
        $userId = $request->get('user_id');
        $type = $request->get('type');

        $em = $this->getDoctrine()->getManager();
        $response =  new Response('{"Erro": "Resposta do quiz não encontrado."}', Response::HTTP_NOT_FOUND);

        try {
            $nivelamento = null;
            $quiz = null;

            if ($type === 'NIVELAMENTO') {
                $nivelamento = $nivelamentoRepository->findNivelamento($quizId);
            } else if ($type === 'QUIZ') {
                $quiz = $quizRepository->findQuiz($quizId);
            }

            $respostaQuiz = $respostaQuizRepository->findResposta(
                $quiz ? $quiz->getId() : null,
                $nivelamento ? $nivelamento->getId() : null,
                $userId
            );

            if ($respostaQuiz) {
                $respostaQuiz->setTempoEsgotado(true);

                $em->persist($respostaQuiz);
                $em->flush();

                $response =  new Response(
                    $this->serializer->serialize(
                        'tempo esgotado!',
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

    private function setDefaultConfigQuiz(Quiz $quiz)
    {
        $em = $this->getDoctrine()->getManager();

        $configuracaoQuiz = new ConfiguracaoQuiz();
        $configuracaoQuiz
            ->setObterDadosRespondente(true)
            ->setObterNome(true)
            ->setObterEmail(true)
            ->setObterTelefone(false)
            ->setObterEmpresa(false)
            ->setObterCnpj(false)
            ->setObterCpf(false)
            ->setObterCidade(false)
            ->setQuandoObterDados('INICIO')
            ->setMostrarNota('MOSTRAR')
            ->setDefinirTempoResposta(false)
            ->setPodePularQuestao(true)
            ->setMostrarCorrecao(true)
            ->setMostrarGabarito(true)
            ->setConfigurarLandPage(false)
            ->setRedirecionarUsuario(false)
            ->setDefinirNotaMinima(false)
            ->setAdicionarMateriais(false)
            ->setOrdemCampos([
                ["campo" => "Nome"],
                ["campo" => "E-mail"],
                ["campo" => "Telefone"],
                ["campo" => "Empresa"],
                ["campo" => "CNPJ"],
                ["campo" => "CPF"],
                ["campo" => "Cidade"]
            ]);

        $quiz->setConfiguracaoQuiz($configuracaoQuiz);

        $em->persist($quiz);
        $em->flush();
        return $configuracaoQuiz;
    }
}
