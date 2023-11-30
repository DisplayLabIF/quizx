<?php

namespace App\Controller\Api\ResponderQuiz;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quiz\LeadQuiz;
use App\Entity\Quiz\NotaQuiz;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\QuizesNivelamento;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use  App\Controller\Api\TraitApiController;
use App\Entity\Quiz\RespostaQuizQuestoes;
use App\Repository\LeadQuizRepository;
use App\Repository\NivelamentoRepository;
use App\Repository\QuizRepository;
use App\Repository\RespostaQuizRepository;
use App\Service\EnviarResultadoEmailService;
use App\Service\NotificacaoService;
use App\Service\RDStationService;


class SaveLeadController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Post("/quizes/{quiz_id}/save-lead", name="api_quiz_save-lead")
     * @return Response
     */
    public function postQuizSaveLead(Request $request, EnviarResultadoEmailService $serviceEnviarResultadosEmail, NivelamentoRepository $nivelamentoRepository, QuizRepository $quizRepository, RespostaQuizRepository $respostaQuizRepository, RDStationService $rDStationService, LeadQuizRepository $leadQuizRepository, NotificacaoService $notificacaoService)
    {
        $quizId = $request->get('quiz_id');
        $userId = $request->get('user_id');
        $nome = $request->get('nome');
        $email = $request->get('email');
        $telefone = preg_replace('/[^0-9]/', '', $request->get('telefone'));
        $empresa = $request->get('empresa');
        $cnpj = preg_replace('/[^0-9]/', '', $request->get('cnpj'));
        $cpf = preg_replace('/[^0-9]/', '', $request->get('cpf'));
        $cidade = $request->get('cidade');
        $type = $request->get('type');
        $communications =  $request->get('communications');
        $privacy_policy =  $request->get('privacy_policy');
        $terms_of_use =  $request->get('terms_of_use');
        $camposPersonalizados = $request->get('campos_personalizados');


        $em = $this->getDoctrine()->getManager();
        $response =  new Response('{"Erro": "O quiz informado não foi encontrado."}', Response::HTTP_NOT_FOUND);

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
            if ($nivelamento && !$respostaQuiz) {
                $quiz = $nivelamento->getQuizesNivelamento()->first()->getQuiz();
            } else if (!$quiz && $respostaQuiz) {
                $quiz = $respostaQuiz->getQuizAtual();
            }

            if ($quiz) {
                $leadQuiz = null;
                if ($email) {
                    $leadQuiz = $em->getRepository(LeadQuiz::class)->findOneBy(['email' => $email]);
                } else {
                    $leadQuiz = $em->getRepository(LeadQuiz::class)->find($userId);
                }

                if ($leadQuiz) {
                    $leadQuiz
                        ->setNome($nome)
                        ->setEmail($email)
                        ->setTelefone($telefone)
                        ->setEmpresa($empresa)
                        ->setCnpj($cnpj)
                        ->setCpf($cpf)
                        ->setCidade($cidade)
                        ->setCommunications($communications)
                        ->setPrivacyPolicy($privacy_policy)
                        ->setTermsOfUse($terms_of_use)
                        ->setCamposPersonalizados($camposPersonalizados);
                    if ($nivelamento) {
                        $nivelamentoLeadExists = $nivelamentoRepository->nivelamentoLeadExists($nivelamento->getId(),  $leadQuiz->getId());
                        if (!$nivelamentoLeadExists) {
                            $leadQuiz->addNivelamento($nivelamento);
                        }
                    } else {
                        $quizLeadExists = $quizRepository->quizLeadExists($quiz->getId(), $leadQuiz->getId());
                        if (!$quizLeadExists) {
                            $leadQuiz->addQuiz($quiz);
                        }
                    }
                } else {
                    $leadQuiz = new LeadQuiz();
                    $leadQuiz
                        ->setNome($nome)
                        ->setEmail($email)
                        ->setTelefone($telefone)
                        ->setEmpresa($empresa)
                        ->setCnpj($cnpj)
                        ->setCpf($cpf)
                        ->setCidade($cidade)
                        ->setCommunications($communications)
                        ->setPrivacyPolicy($privacy_policy)
                        ->setTermsOfUse($terms_of_use)
                        ->setCamposPersonalizados($camposPersonalizados);
                    if ($nivelamento) {
                        $leadQuiz->addNivelamento($nivelamento);
                    } else {
                        $leadQuiz->addQuiz($quiz);
                    }
                }

                // $notaAcertos = [
                //     'cf_teste_ingles_qtd_acertos' => 0,
                //     'cf_teste_ingles_qtd_questoes' => 0
                // ];

                $notaAcertos = null;

                if ($respostaQuiz) {
                    $respostaQuiz
                        ->setLeadQuizEntity($leadQuiz)
                        ->setAluno($leadQuiz->getId());
                    if ($type === 'NIVELAMENTO') {
                        if ($nivelamento->getConfiguracaoNivelamento()->getObterDadosRespondente() && $nivelamento->getConfiguracaoNivelamento()->getQuandoObterDados() === 'FIM') {
                            $atualQuizNivelamento =  $em->getRepository(QuizesNivelamento::class)->findOneBy([
                                'nivelamento' => $nivelamento->getId(),
                                'quiz' => $respostaQuiz->getQuizAtual()->getId()
                            ]);

                            $notaQuiz =  $em->getRepository(NotaQuiz::class)->findOneBy([
                                'respostaQuiz' => $respostaQuiz->getId(),
                                'quiz' => $atualQuizNivelamento->getQuiz()->getId()
                            ]);
                            if ($notaQuiz && ($notaQuiz->getNota() < $atualQuizNivelamento->getPontuacaoMinima())) {
                                $respostaQuiz->setFinalizou(true);
                                if (
                                    $nivelamento->getConfiguracaoNivelamento()->getQuandoObterDados() === 'FIM' &&
                                    $nivelamento->getConfiguracaoNivelamento()->getObterEmail() &&
                                    $nivelamento->getConfiguracaoNivelamento()->getMostrarNota() === 'ENVIAR_EMAIL'
                                ) {
                                    $serviceEnviarResultadosEmail->enviarEmailResultado($leadQuiz->getEmail(), $respostaQuiz->getQuizAtual()->getPersonalizacaoEmail(), $respostaQuiz);
                                }
                            }
                        }
                    } else if ($type === 'QUIZ') {
                        if ($quiz->getConfiguracaoQuiz()->getObterDadosRespondente() && $quiz->getConfiguracaoQuiz()->getQuandoObterDados() === 'FIM') {
                            $respostaQuiz->setFinalizou(true);
                            if (
                                $quiz->getConfiguracaoQuiz()->getQuandoObterDados() === 'FIM' &&
                                $quiz->getConfiguracaoQuiz()->getObterEmail() &&
                                $quiz->getConfiguracaoQuiz()->getMostrarNota() === 'ENVIAR_EMAIL'
                            ) {
                                $serviceEnviarResultadosEmail->enviarEmailResultado($leadQuiz->getEmail(), $quiz->getPersonalizacaoEmail(), $respostaQuiz);
                            }
                        }

                        $notaAcertos = [
                            'cf_teste_ingles_qtd_acertos' =>  strval(COUNT($em->getRepository(RespostaQuizQuestoes::class)->findBy([
                                'respostaQuiz' => $respostaQuiz->getId(), 'quiz' => $respostaQuiz->getQuizAtual()->getId(), 'correto' => 1
                            ]))),
                            'cf_teste_ingles_qtd_questoes' => strval($respostaQuiz->getQuizAtual()->getQuestoes()->count())
                        ];
                    }



                    $respostaQuiz->setLeadCapturado(true);
                    $em->persist($respostaQuiz);
                }

                $em->persist($leadQuiz);

                $notificacao = [];
                if ($email)
                    $notificacao = $notificacaoService->notificarLeadCadastrado($leadQuiz, $quiz, $leadQuizRepository);


                $em->flush();

                $this->enviarLeadRDStation($leadQuiz, $quiz, $rDStationService, $notaAcertos);

                $response =  new Response(
                    $this->serializer->serialize(
                        [
                            'lead_id' => $leadQuiz->getId(),
                            'finalizou' => $respostaQuiz ? $respostaQuiz->getFinalizou() : false,
                            'notificacao' => $notificacao
                        ],
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

    private function enviarLeadRDStation(LeadQuiz $lead, Quiz $quiz, RDStationService $rDStationService, $notaAcertos)
    {
        if (
            !$quiz->getUser()->getEscola() ||
            !$quiz->getUser()->getEscola()->getRdStationApiToken() ||
            !$lead->getEmail()
        ) return true;

        $communications = $lead->getCommunications();

        $conversion_identifier = $quiz->getUser()->getEscola() && $quiz->getUser()->getEscola()->getRdStationEventoConversao() ?
            $quiz->getUser()->getEscola()->getRdStationEventoConversao() : 'QUIZ_CLASS';
        $rdStationApiToken = $quiz->getUser()->getEscola()->getRdStationApiToken();

        $rDStationService->sendLeadResposta($lead, $communications, $conversion_identifier, $rdStationApiToken, $notaAcertos);
    }
}
