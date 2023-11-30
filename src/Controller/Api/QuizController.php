<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quiz\ArquivosQuestao;
use App\Entity\Quiz\Opcao;
use App\Entity\Quiz\Questao;
use App\Entity\Quiz\Quiz;
use App\Entity\User;
use App\Repository\QuestaoRepository;
use App\Repository\QuizRepository;
use App\Service\GenerateCodigoQuizOuNivelamento;
use App\Service\RDStationService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/quizes")
 */
class QuizController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Get("/{quiz_id}", name="api_get_quiz")
     * @return Response
     */
    public function getQuiz(Request $request)
    {
        $quizId = $request->get('quiz_id');

        $em = $this->getDoctrine()->getManager();

        $quiz = null;

        if ($quizId === 'session') {
            $sessionId = $request->headers->get('X-Session-Id');
            $quiz = $em->getRepository(Quiz::class)->findOneBy(['session' => $sessionId, 'active' => 1]);
        } else {
            $quiz = $em->getRepository(Quiz::class)->findOneBy(['id' => $quizId, 'active' => 1]);
        }
        $questoesActive = $quiz->getQuestoes();
        $quiz->setQuestoes($questoesActive ? $questoesActive : []);
        foreach ($quiz->getQuestoes() as $questao) {
            $questao->setOpcoes($questao->getOpcoes());

            if (!$questao->getArquivosQuestao()) {
                $arquivosQuestao = new ArquivosQuestao();

                $questao->setArquivosQuestao($arquivosQuestao);
            }
        }

        if ($quiz->getQuestoes()->count() === 0) {
            $newQuestao = new Questao();
            $newQuestao
                ->setId(1)
                ->setPergunta('')
                ->setExplicacaoResposta('')
                ->setTipo('MULTIPLA_ESCOLHA')
                ->setValor(0)
                ->setTempo('')
                ->setObrigatoria(true)
                ->setMostrarExplicacao(true)
                ->setNumeroQuestao(1)
                ->setActive(true);

            $newOpcao = new Opcao();
            $newOpcao
                ->setRespostaCorreta('')
                ->setTexto('')
                ->setQuestao($newQuestao)
                ->setNumeroOpcao(1)
                ->setActive(true);

            $arquivosQuestao = new ArquivosQuestao();

            $newQuestao->setArquivosQuestao($arquivosQuestao);

            $newQuestao->addOpcao($newOpcao);
            $quiz->addQuestao($newQuestao);
        }

        $response =  new Response(
            $this->serializer->serialize(

                $quiz,
                'json'
            ),
            Response::HTTP_OK
        );

        return $response;
    }

    /**
     *
     * @Rest\Post("", name="api_save_quiz")
     * @return Response
     */
    public function postQUiz(Request $request, GenerateCodigoQuizOuNivelamento $generateCodigoQuiz)
    {
        $userId = $request->headers->get('X-Client-Id');
        $sessionId = $request->headers->get('X-Session-Id') !== 'null' && $request->headers->get('X-Session-Id') !== 'undefined' ? $request->headers->get('X-Session-Id') : null;
        /*
        $nome = $request->get('nome');
        $assuntos = $request->get('assuntos');
        $nivel = $request->get('nivel');
        $image = $request->get('image');
        $questoes = $request->get('questoes');
        */
        $data = $request->toArray();

        $nome = $data['nome'];
        $assuntos = $data['assuntos'];
        $nivel = $data['nivel'];
        $image = $data['image'];
        $questoes = $data['questoes'];

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneBy(['id' => $userId]);
        if (!$user && !$sessionId) {
            $session = new Session();
            //$session->start();
            $sessionId = $session->getId();
        }
        try {
            $quiz = new Quiz();

            $quiz
                ->setCodigo($generateCodigoQuiz->getCode())
                ->setNome($nome ? $nome : $quiz->getCodigo())
                ->setAssuntos($assuntos)
                ->setNivel($nivel)
                ->setImage($image)
                ->setUser($user)
                ->setCreatedBy($user)
                ->setSession($sessionId);

            foreach ($questoes as $key => $questao) {

                $novaQuestao = new Questao();
                $novaQuestao
                    ->setPergunta($questao['pergunta'])
                    ->setExplicacaoResposta($questao['explicacao_resposta'])
                    ->setTipo($questao['tipo'])
                    ->setCreatedBy($user)
                    ->setValor(($questao['valor'] != '') ? $questao['valor'] : 0)
                    ->setTempo(array_key_exists("tempo", $questao) ? $questao['tempo'] : '')
                    ->setObrigatoria($questao['obrigatoria'])
                    ->setMostrarExplicacao($questao['mostrar_explicacao'])
                    ->setNumeroQuestao($key + 1)
                    ->setActive($questao['active']);
                if ($questao['tipo'] === 'V_F') {
                    $novaQuestao->setTrueOrFalseCaracteres($questao['true_or_false_caracteres']);
                }

                $arquivosQuestao = new ArquivosQuestao();
                $arquivosQuestao
                    ->setArquivosResposta(
                        array_key_exists("arquivos_resposta", $questao['arquivos_questao']) ?
                            $questao['arquivos_questao']['arquivos_resposta'] : $arquivosQuestao->getArquivosResposta()
                    )
                    ->setArquivosExplicacao(
                        array_key_exists("arquivos_explicacao", $questao['arquivos_questao']) ?
                            $questao['arquivos_questao']['arquivos_explicacao'] : $arquivosQuestao->getArquivosExplicacao()
                    );

                $novaQuestao->setArquivosQuestao($arquivosQuestao);

                foreach ($questao['opcoes'] as $keyopcao => $opcao) {
                    $op = new Opcao();
                    $op
                        ->setRespostaCorreta($opcao['resposta_correta'])
                        ->setTexto($opcao['texto'])
                        ->setQuestao($novaQuestao)
                        ->setNumeroOpcao($keyopcao + 1)
                        ->setActive($opcao['active']);
                    $novaQuestao->addOpcao($op);
                }

                $quiz->addQuestao($novaQuestao);
            }

            $em->persist($quiz);
            $em->flush();
            // $refererArr = explode("/", $request->server->get('HTTP_REFERER'));
            // $referer = (isset($refererArr[2])) ? $refererArr[2] : null;


            $response =  new Response(
                $this->serializer->serialize(
                    [
                        'quiz' => $quiz,
                        'id_session' => $sessionId
                    ],
                    'json'
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $response = new Response(
                $this->serializer->serialize(
                    $e->getMessage(),
                    'json'
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $response;
    }

    /**
     * @Rest\Put("/{id}", name="api_edit_quiz")
     * @return Response
     */
    public function editQuiz(Request $request, QuestaoRepository $questaoRepository)
    {
        $userId = $request->headers->get('X-Client-Id');
        $id = $request->get('id') ? $request->get('id') : '';
        /*$nome = $request->get('nome');
        $assuntos = $request->get('assuntos');
        $nivel = $request->get('nivel');
        $image = $request->get('image');
        $questoes = $request->get('questoes');*/
        $data = $request->toArray();

        $nome = $data['nome'];
        $assuntos = $data['assuntos'];
        $nivel = $data['nivel'];
        $image = $data['image'];
        $questoes = $data['questoes'];

        $em = $this->getDoctrine()->getManager();
        $response =  new Response('{"Erro": "O quiz informado não foi encontrado."}', Response::HTTP_NOT_FOUND);

        try {
            $quiz = $em->getRepository(Quiz::class)->find($id);
            if ($quiz) {
                $user = $em->getRepository(User::class)->findOneBy(['id' => $userId]);

                if ($user && !$quiz->getUser()) {
                    $quiz->setUser($user);
                }

                $quiz
                    ->setNome($nome)
                    ->setAssuntos($assuntos)
                    ->setNivel($nivel)
                    ->setLastUpdatedBy($this->getUser())
                    ->setImage($image);

                foreach ($questoes as $key => $questao) {
                    $questaoEdit = $questaoRepository->findQuestaoQuiz($questao['id'], $quiz->getId());

                    if ($questaoEdit) {
                        $questaoEdit
                            ->setPergunta($questao['pergunta'])
                            ->setExplicacaoResposta($questao['explicacao_resposta'])
                            ->setTipo($questao['tipo'])
                            ->setValor(($questao['valor'] != '') ? $questao['valor'] : 0)
                            ->setTempo(array_key_exists("tempo", $questao) ? $questao['tempo'] : '')
                            ->setObrigatoria($questao['obrigatoria'])
                            ->setMostrarExplicacao($questao['mostrar_explicacao'])
                            ->setNumeroQuestao($key + 1)
                            ->setActive($questao['active']);

                        if ($questao['tipo'] === 'V_F') {
                            $questaoEdit->setTrueOrFalseCaracteres($questao['true_or_false_caracteres']);
                        }

                        $arquivosQuestao = $questaoEdit->getArquivosQuestao();
                        if (!$arquivosQuestao) {
                            $arquivosQuestao = new ArquivosQuestao();
                        }
                        $arquivosQuestao
                            ->setArquivosResposta(
                                array_key_exists("arquivos_resposta", $questao['arquivos_questao']) ?
                                    $questao['arquivos_questao']['arquivos_resposta'] : $arquivosQuestao->getArquivosResposta()
                            )
                            ->setArquivosExplicacao(
                                array_key_exists("arquivos_explicacao", $questao['arquivos_questao']) ?
                                    $questao['arquivos_questao']['arquivos_explicacao'] : $arquivosQuestao->getArquivosExplicacao()
                            );
                        $questaoEdit->setArquivosQuestao($arquivosQuestao);

                        foreach ($questao['opcoes'] as $keyopcao => $opcao) {
                            $op = $em->getRepository(Opcao::class)->find($opcao['id']);
                            if ($op) {
                                $op
                                    ->setRespostaCorreta($opcao['resposta_correta'])
                                    ->setTexto($opcao['texto'])
                                    ->setNumeroOpcao($keyopcao + 1)
                                    ->setActive($opcao['active']);
                                $em->persist($op);
                            }
                        }
                        $em->persist($questaoEdit);
                    }
                }

                $em->persist($quiz);
                $em->flush();

                $response =  new Response(
                    $this->serializer->serialize(
                        [
                            'id_quiz' => $quiz->getId()
                        ],
                        'json'
                    ),
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            $response = new Response(
                $this->serializer->serialize(
                    $e->getMessage(),
                    'json'
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $response;
    }

    /**
     *
     * @Rest\Post("/questoes", name="api_new_quiz_questao")
     * @return Response
     */
    public function postQuizQuestoes(Request $request, GenerateCodigoQuizOuNivelamento $generateCodigoQuiz, QuestaoRepository $questaoRepository)
    {
        $userId = $request->headers->get('X-Client-Id');
        $sessionId = $request->headers->get('X-Session-Id') !== 'null' && $request->headers->get('X-Session-Id') !== 'undefined' ? $request->headers->get('X-Session-Id') : null;
       // $quizId = $request->get('quiz_id') ? $request->get('quiz_id') : '';
        $data = $request->toArray();
        $quizId = $data['quiz_id'];
        $questao = $data['questao'];

        $em = $this->getDoctrine()->getManager();
        $response =  new Response('{"Erro": "O quiz informado não foi encontrado."}', Response::HTTP_NOT_FOUND);
        $data = [];
        try {
            $quiz = $em->getRepository(Quiz::class)->find($quizId);
            $user = $em->getRepository(User::class)->findOneBy(['id' => $userId]);

            if (!$quiz) {
                $quiz = new Quiz();
                $quiz
                    ->setCodigo($generateCodigoQuiz->getCode())
                    ->setNome($quiz->getCodigo())
                    ->setUser($user)
                    ->setCreatedBy($user);

                if (!$user && !$sessionId) {
                    $session = new Session();
                    $session->start();
                    $sessionId = $session->getId();
                    $quiz->setSession($sessionId);
                    $data['id_session'] = $sessionId;
                }

                $data['quiz_id'] = $quiz->getId();
                $data['quiz_nome'] = $quiz->getNome();

            
            }
    
            $questaoEdit = $questaoRepository->findQuestaoQuiz($questao['id'], $quiz->getId());

            if ($questaoEdit) {
                $questaoEdit = $this->editQuestao($questaoEdit, $questao);
                $em->persist($questaoEdit);
                $em->persist($quiz);
                $data['questao_id'] = $questaoEdit->getId();
                $data['questao'] = $questaoEdit;
            } else {
                $novaQuestao = $this->createQuestao($questao, $user);
                foreach ($questao['opcoes'] as $keyopcao => $opcao) {
                    $novaQuestao->addOpcao($this->createOpcao($keyopcao, $opcao, $novaQuestao));
                }
                $quiz->addQuestao($novaQuestao);
                $em->persist($quiz);
                $em->persist($novaQuestao);
                $data['questao_id'] = $novaQuestao->getId();
                $data['questao'] = $novaQuestao;
            }

            $em->flush();




            $response =  new Response(
                $this->serializer->serialize(
                    $data,
                    'json'
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $response = new Response(
                $this->serializer->serialize(
                    $e->getMessage(),
                    'json'
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $response;
    }

    /**
     *
     * @Rest\Put("/questoes/{questao_id}", name="api_edit_quiz_questoes")
     * @return Response
     */
    public function putQuizQuestoes(Request $request)
    {
        $questaoId = $request->get('questao_id');
        //$questao = $request->get('questao');
        $questao = $data = $request->toArray()['questao'];

        $em = $this->getDoctrine()->getManager();
        $response =  new Response('{"Erro": "A questão informada não foi encontrada."}', Response::HTTP_NOT_FOUND);
        try {
            $questaoEdit = $em->getRepository(Questao::class)->find($questaoId);
            if ($questaoEdit) {
                $questaoEdit = $this->editQuestao($questaoEdit, $questao);

                foreach ($questao['opcoes'] as $keyopcao => $opcao) {
                    $op = $em->getRepository(Opcao::class)->find($opcao['id'] ? $opcao['id'] : '');
                    if ($op) {
                        $em->persist($this->editOpcao($op, $keyopcao, $opcao));
                    }
                }
                $em->persist($questaoEdit);
                $em->flush();

                $response =  new Response(
                    $this->serializer->serialize(
                        [
                            'questao_id' => $questaoEdit->getId()
                        ],
                        'json'
                    ),
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            $response = new Response(
                $this->serializer->serialize(
                    $e->getMessage(),
                    'json'
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $response;
    }

    private function createQuestao($questao, $user = null)
    {
        $novaQuestao = new Questao();
        $novaQuestao
            ->setPergunta($questao['pergunta'])
            ->setExplicacaoResposta($questao['explicacao_resposta'])
            ->setTipo($questao['tipo'])
            ->setCreatedBy($user)
            ->setValor(($questao['valor'] != '') ? $questao['valor'] : 0)
            ->setTempo(array_key_exists("tempo", $questao) ? $questao['tempo'] : '')
            ->setObrigatoria($questao['obrigatoria'])
            ->setMostrarExplicacao($questao['mostrar_explicacao'])
            ->setNumeroQuestao($questao['numero_questao'])
            ->setActive($questao['active']);

        if ($questao['tipo'] === 'V_F') {
            $novaQuestao->setTrueOrFalseCaracteres($questao['true_or_false_caracteres']);
        }

        $arquivosQuestao = new ArquivosQuestao();
        $arquivosQuestao
            ->setArquivosResposta(
                array_key_exists("arquivos_resposta", $questao['arquivos_questao']) ?
                    $questao['arquivos_questao']['arquivos_resposta'] : $arquivosQuestao->getArquivosResposta()
            )
            ->setArquivosExplicacao(
                array_key_exists("arquivos_explicacao", $questao['arquivos_questao']) ?
                    $questao['arquivos_questao']['arquivos_explicacao'] : $arquivosQuestao->getArquivosExplicacao()
            );

        $novaQuestao->setArquivosQuestao($arquivosQuestao);

        return $novaQuestao;
    }

    private function editQuestao(Questao $questaoEdit, $questao)
    {
        $questaoEdit
            ->setPergunta($questao['pergunta'])
            ->setExplicacaoResposta($questao['explicacao_resposta'])
            ->setTipo($questao['tipo'])
            ->setValor(($questao['valor'] != '') ? $questao['valor'] : 0)
            ->setTempo(array_key_exists("tempo", $questao) ? $questao['tempo'] : '')
            ->setObrigatoria($questao['obrigatoria'])
            ->setMostrarExplicacao($questao['mostrar_explicacao'])
            ->setNumeroQuestao($questao['numero_questao'])
            ->setActive($questao['active']);
        if ($questao['tipo'] === 'V_F') {
            $questaoEdit->setTrueOrFalseCaracteres($questao['true_or_false_caracteres']);
        }

        $arquivosQuestao = $questaoEdit->getArquivosQuestao();
        if (!$arquivosQuestao) {
            $arquivosQuestao = new ArquivosQuestao();
        }
        $arquivosQuestao
            ->setArquivosResposta(
                array_key_exists("arquivos_resposta", $questao['arquivos_questao']) ?
                    $questao['arquivos_questao']['arquivos_resposta'] : $arquivosQuestao->getArquivosResposta()
            )
            ->setArquivosExplicacao(
                array_key_exists("arquivos_explicacao", $questao['arquivos_questao']) ?
                    $questao['arquivos_questao']['arquivos_explicacao'] : $arquivosQuestao->getArquivosExplicacao()
            );
        $questaoEdit->setArquivosQuestao($arquivosQuestao);

        return $questaoEdit;
    }

    private function createOpcao($keyopcao, $opcao, $questao)
    {
        $op = new Opcao();
        $op
            ->setRespostaCorreta($opcao['resposta_correta'])
            ->setTexto($opcao['texto'])
            ->setQuestao($questao)
            ->setNumeroOpcao($keyopcao + 1)
            ->setActive($opcao['active']);
        return $op;
    }

    private function editOpcao(Opcao $op, $keyopcao, $opcao)
    {
        $op
            ->setRespostaCorreta($opcao['resposta_correta'])
            ->setTexto($opcao['texto'])
            ->setNumeroOpcao($keyopcao + 1)
            ->setActive($opcao['active']);

        return $op;
    }

    /**
     *
     * @Rest\Post("/enviar-email/{quiz_id}", name="api_quiz_enviar_email")
     * @return Response
     */
    public function enviarEmail(Request $request)
    {
        $quizId = $request->get('quiz_id');
        $emails = $request->get('emails');

        $em = $this->getDoctrine()->getManager();
        $response =  new Response('{"Erro": "O quiz informado não foi encontrado."}', Response::HTTP_NOT_FOUND);

        try {
            $quiz = $em->getRepository(Quiz::class)->find($quizId);

            $transport = (new \Swift_SmtpTransport($_ENV['MAILER_HOST'], $_ENV['MAILER_PORT']))
                ->setUsername($_ENV['MAILER_USERNAME'])
                ->setPassword($_ENV['MAILER_PASSWORD']);

            $mailer = new \Swift_Mailer($transport);
            $message = (new \Swift_Message('Quiz Class '))
                ->setSubject("Responder quiz")
                ->setFrom(['no-reply@quizclass.com.br' => 'QuizClass'])
                ->setBcc($emails)
                ->setBody(
                    $this->renderView(
                        'emails/responderQuiz.html.twig',
                        [
                            'codigo' => $quiz->getCodigo(),
                            'nome' => $quiz->getNome(),
                            'username' => $quiz->getUser()->getNome()
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $response =  new Response(
                $this->serializer->serialize(
                    'Emails enviados!',
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
