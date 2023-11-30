<?php

namespace App\Controller\Aluno\Quiz;

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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\Api\TraitApiController;
use App\Repository\QuizRepository;
use App\Repository\RespostaQuizRepository;
use Symfony\Component\Routing\Annotation\Route;


class CorrigirQuestaoController extends AbstractController
{

    /**
     * 
     *
     *  @Route("/responder/{quiz_id}/questoes/{questao_id}/verificar-resposta", name="aluno_verificar_resposta_questao")
     * @return Response
     */
    public function questaoVerificarResposta(Request $request, CorrecaoQuestao $correcaoQuestao, QuizRepository $quizRepository, RespostaQuizRepository $respostaQuizRepository)
    {
        $quizId = $request->get('quiz_id');
        $userId = $this->getUser()->getId();
        $questaoId = $request->get('questao_id');
        $resposta = $request->get('resposta');
        $pulou = $request->get('pular');
        $type = 'QUIZ';


        $em = $this->getDoctrine()->getManager();
        try {
            
            $quiz = $quizRepository->findQuiz($quizId);
            
            $questao = $em->getRepository(Questao::class)->find($questaoId);
            $respostaQuiz = $respostaQuizRepository->findResposta(
                $quiz ? $quiz->getId() : null,
                null,
                $userId
            );

            if ($questao && $respostaQuiz) {

                $opcoes = $em->getRepository(Opcao::class)->findBy(['questao' => $questaoId, 'active' => true], ['numeroOpcao' => 'ASC']);
                $data['correto'] = false;
                $data['questao'] = $questao;
                $data['opceos'] = $questao->getOpcoes();
                $data['quiz_id'] = $quizId;
                $data['opcoes_respondidas'] = $request->get('opcao_respondida');
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

                            $data['correto'] = $correcaoQuestao->corrigirMultiplaEscolha($opcoes, $request->get('opcao_respondida'));
                            
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
                            $respostaQuizQuestao->setResposta(['MULTIPLA_ESCOLHA' => $request->get('opcao_respondida')]);

                            break;
                        case 'V_F':

                            $respostasCorretas = [];

                            foreach($questao->getOpcoes() as $opcao) {
                                $respostasCorretas[$opcao->getId()] = ($opcao->getRespostaCorreta()) ? 'V' : 'F';
                            }

                            $qtdRespostasCorretas = $correcaoQuestao->corrigirVF($request->request, $respostasCorretas);

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
                            $respostaQuizQuestao->setResposta(['V_F' => $opcao->getRespostaCorreta()]);

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

                return $this->render('aluno/quiz/questao-quiz.html.twig', [
                    'data' => $data
                ]);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->render('aluno/quiz/questao-quiz.html.twig', [
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }
        return $response;
    }
}
