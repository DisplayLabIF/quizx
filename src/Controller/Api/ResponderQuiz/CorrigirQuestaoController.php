<?php

namespace App\Controller\Api\ResponderQuiz;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quiz\Opcao;
use App\Entity\Quiz\Questao;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\RespostaQuiz;
use App\Entity\Quiz\RespostaQuizQuestoes;
use App\Entity\User;
use App\Service\CorrecaoQuestao;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use  App\Controller\Api\TraitApiController;
use App\Repository\NivelamentoRepository;
use App\Repository\QuizRepository;
use App\Repository\RespostaQuizRepository;

class CorrigirQuestaoController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     *  @Rest\Post("/questoes/{questao_id}/verificar-resposta", name="api_verificar_resposta_questao")
     * @return Response
     */
    public function questaoVerificarResposta(Request $request, CorrecaoQuestao $correcaoQuestao, QuizRepository $quizRepository, NivelamentoRepository $nivelamentoRepository, RespostaQuizRepository $respostaQuizRepository)
    {
        $quizId = $request->get('quiz_id');
        $userId = $request->get('user_id');
        $questaoId = $request->get('questao_id');
        $resposta = $request->get('resposta');
        $pulou = $request->get('pular');
        $type = $request->get('type');

        $em = $this->getDoctrine()->getManager();
        try {
            $response =  new Response('{"Erro": "A questão informada não foi encontrada."}', Response::HTTP_NOT_FOUND);

            $nivelamento = null;
            $quiz = null;

            if ($type === 'NIVELAMENTO') {
                $nivelamento = $nivelamentoRepository->findNivelamento($quizId);
            } else if ($type === 'QUIZ') {
                $quiz = $quizRepository->findQuiz($quizId);
            }

            $questao = $em->getRepository(Questao::class)->find($questaoId);
            $respostaQuiz = $respostaQuizRepository->findResposta(
                $quiz ? $quiz->getId() : null,
                $nivelamento ? $nivelamento->getId() : null,
                $userId
            );

            if ($questao && $respostaQuiz) {

                $opcoes = $em->getRepository(Opcao::class)->findBy(['questao' => $questaoId, 'active' => true], ['numeroOpcao' => 'ASC']);
                $data['correto'] = false;
                $qtdRespostasCorretas = 0;
                $qtdOpcoes = count($opcoes);

                $respostaQuizQuestao = $em->getRepository(RespostaQuizQuestoes::class)
                    ->findOneBy(['respostaQuiz' => $respostaQuiz->getId(), 'questao' => $questaoId]);

                if (!$respostaQuizQuestao) {
                    $respostaQuizQuestao = new RespostaQuizQuestoes();
                    $respostaQuizQuestao
                        ->setRespostaQuiz($respostaQuiz)
                        ->setQuiz($respostaQuiz->getQuizAtual())
                        ->setQuestao($questao)
                        ->setQtdTentativas(1);
                } else {
                    $tentativas = $respostaQuizQuestao->getQtdTentativas();
                    $respostaQuizQuestao->setQtdTentativas($tentativas + 1);
                }

                if (!$pulou) {
                    switch ($questao->getTipo()) {
                        case 'MULTIPLA_ESCOLHA':

                            $data['correto'] = $correcaoQuestao->corrigirMultiplaEscolha($opcoes, $resposta['MULTIPLA_ESCOLHA']);

                            if ($data['correto'] === true) {
                                $respostaQuiz->setNota($questao->getValor() + $respostaQuiz->getNota());
                                $data['nota'] = $questao->getValor();
                                $em->persist($respostaQuiz);

                                $respostaQuizQuestao
                                    ->setCorreto(true)
                                    ->setNota($data['nota']);
                                $data['correto'] = true;
                            } else if ($data['correto'] === false || $data['correto'] == 0) {
                                $data['correto'] = false;
                                $respostaQuizQuestao->setCorreto(false);
                            } else {
                                $qtdOpcoesCorretas = 0;
                                $qtdRespostasCorretas = $data['correto'];

                                foreach ($opcoes as $opcao) {
                                    if ($opcao->getRespostaCorreta())
                                        $qtdOpcoesCorretas++;
                                }

                                if ($qtdOpcoesCorretas === $qtdRespostasCorretas) {
                                    $respostaQuiz->setNota($questao->getValor() + $respostaQuiz->getNota());
                                    $data['nota'] = $questao->getValor();
                                    $em->persist($respostaQuiz);

                                    $respostaQuizQuestao
                                        ->setCorreto(true)
                                        ->setNota($data['nota']);
                                    $data['correto'] = true;
                                } else {
                                    $notaTotal = $questao->getValor();
                                    $nota = ($notaTotal / $qtdOpcoesCorretas) * $qtdRespostasCorretas;
                                    $respostaQuiz->setNota($nota + $respostaQuiz->getNota());
                                    $data['nota'] = $nota;
                                    $em->persist($respostaQuiz);

                                    $respostaQuizQuestao
                                        ->setCorreto(false)
                                        ->setNota($data['nota']);

                                    $data['correto'] = false;
                                    $data['mensagem'] = "Você acertou parcialmente a questão!";
                                }
                            }
                            $respostaQuizQuestao->setResposta(['MULTIPLA_ESCOLHA' => $resposta['MULTIPLA_ESCOLHA']]);

                            break;
                        case 'V_F':

                            $qtdRespostasCorretas = $correcaoQuestao->corrigirVF($resposta['V_F']);

                            if ($qtdOpcoes == $qtdRespostasCorretas) {
                                $respostaQuiz->setNota($questao->getValor() + $respostaQuiz->getNota());
                                $data['nota'] = $questao->getValor();
                                $em->persist($respostaQuiz);

                                $respostaQuizQuestao
                                    ->setCorreto(true)
                                    ->setNota($data['nota']);
                                $data['correto'] = true;
                            } else if ($qtdOpcoes > $qtdRespostasCorretas and $qtdRespostasCorretas > 0) {
                                $notaTotal = $questao->getValor();
                                $nota = ($notaTotal / $qtdOpcoes) * $qtdRespostasCorretas;
                                $respostaQuiz->setNota($nota + $respostaQuiz->getNota());
                                $data['nota'] = $nota;
                                $em->persist($respostaQuiz);

                                $respostaQuizQuestao
                                    ->setCorreto(false)
                                    ->setNota($data['nota']);

                                $data['correto'] = false;
                                $data['mensagem'] = "Você acertou {$qtdRespostasCorretas} de {$qtdOpcoes}!";
                            } else {
                                $respostaQuizQuestao->setCorreto(false);
                            }
                            $respostaQuizQuestao->setResposta(['V_F' => $resposta['V_F']]);

                            break;

                        case 'ABERTA':

                            $data['correto'] = $correcaoQuestao->corrigirAberta($opcoes, $resposta['ABERTA']);

                            if ($data['correto']) {
                                $respostaQuiz->setNota($questao->getValor() + $respostaQuiz->getNota());
                                $data['nota'] = $questao->getValor();
                                $em->persist($respostaQuiz);
                                $respostaQuizQuestao->setNota($data['nota']);
                            }
                            $respostaQuizQuestao
                                ->setCorreto($data['correto'])
                                ->setResposta(['ABERTA' => $resposta['ABERTA']]);

                            break;

                        case 'ORDENAR':

                            $data['correto'] = $correcaoQuestao->corrigirOrdenar($opcoes, $resposta['ORDENAR']);

                            if ($data['correto']) {
                                $respostaQuiz->setNota($questao->getValor() + $respostaQuiz->getNota());
                                $data['nota'] = $questao->getValor();
                                $em->persist($respostaQuiz);
                                $respostaQuizQuestao->setNota($data['nota']);
                            }
                            $respostaQuizQuestao
                                ->setCorreto($data['correto'])
                                ->setResposta(['ORDENAR' => $resposta['ORDENAR']]);

                            break;

                        case 'RESPOSTA_VOZ':

                            $data['correto'] = $resposta['RESPOSTA_VOZ']['correcao'];

                            if ($data['correto']) {
                                $respostaQuiz->setNota($questao->getValor() + $respostaQuiz->getNota());
                                $data['nota'] = $questao->getValor();
                                $em->persist($respostaQuiz);
                                $respostaQuizQuestao->setNota($data['nota']);
                            }
                            $respostaQuizQuestao
                                ->setCorreto($data['correto'])
                                ->setResposta(['RESPOSTA_VOZ' => $resposta['RESPOSTA_VOZ']['resposta_falada']]);

                            break;
                    }
                    if (array_key_exists('nota', $data))
                        $data['nota'] = round($data['nota'], 2);
                } else {
                    $respostaQuizQuestao->setResposta([]);
                }

                $respostaQuizQuestao->setPulou($pulou);

                $em->persist($respostaQuizQuestao);
                $em->flush();

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
}
