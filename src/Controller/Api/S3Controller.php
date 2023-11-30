<?php

namespace App\Controller\Api;

use App\Entity\Curso\Modulo;
use App\Entity\Quiz\ArquivosQuestao;
use App\Entity\Quiz\Opcao;
use App\Entity\Quiz\Questao;
use App\Entity\Quiz\Quiz;
use App\Service\GenerateCodigoQuizOuNivelamento;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Aws\S3\S3Client;
use Symfony\Component\String\Slugger\SluggerInterface;
use Aws\S3\Exception\S3Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Service\S3Service;

/**
 * Class ClienteController
 * @package App\Controller\Api
 */

class S3Controller extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Post("/upload", name="upload")
     * @return Response
     */
    public function upload(Request $request, SluggerInterface $slugger, GenerateCodigoQuizOuNivelamento $generateCodigoQuiz)
    {
        $quizId = $request->get('quiz_id');
        $questaoId = $request->get('questao_id');
        $numeroQuestao = $request->get('numero_questao');
        $opcaoId = $request->get('opcao_id');
        $numeroOpcao = $request->get('numero_opcao');
        $tipoArquivo = $request->get('tipo_arquivo');
        $indexArquivo = $request->get('index_arquivo');
        $respostaOuExplicacao = $request->get('resposta_ou_explicacao');
        $file = $request->files->get('file');

        if ($file === null)
            return new Response('{"Erro": "Arquivo não encontrado."}', Response::HTTP_NOT_FOUND);

        $em = $this->getDoctrine()->getManager();

        $quiz = $em->getRepository(Quiz::class)->find($quizId);
        $questao = null;
        $opcao = null;

        if (!$quiz) {
            $quiz = new Quiz();
            $quiz
                ->setNome('')
                ->setCodigo($generateCodigoQuiz->getCode());
        }

        if ($questaoId) {
            $questao = $em->getRepository(Questao::class)->find($questaoId);
            if (!$questao) {
                $questao = new Questao();
                $questao
                    ->setPergunta('')
                    ->setExplicacaoResposta('')
                    ->setTipo('')
                    ->setValor(0)
                    ->setTempo('')
                    ->setObrigatoria(true)
                    ->setMostrarExplicacao(true)
                    ->setNumeroQuestao($numeroQuestao)
                    ->setActive(true);

                $arquivosQuestao = new ArquivosQuestao();


                $questao->setArquivosQuestao($arquivosQuestao);

                $quiz->addQuestao($questao);
            } else if (!$questao->getArquivosQuestao()) {
                $arquivosQuestao = new ArquivosQuestao();

                $questao->setArquivosQuestao($arquivosQuestao);
            }
            if ($opcaoId) {
                $opcao = $em->getRepository(Opcao::class)->find($opcaoId);
                if (!$opcao) {
                    $opcao = new Opcao();
                    $opcao
                        ->setRespostaCorreta('')
                        ->setTexto('')
                        ->setQuestao($questao)
                        ->setNumeroOpcao($numeroOpcao)
                        ->setActive(true);
                    $questao->addOpcao($opcao);
                }
            }
        }

        $path = '/';
        if ($opcao) {
            $path = $quiz->getId() . '/' . $questao->getId() . '/' . $opcao->getId() . '/';
        } else if ($questao) {
            $path = $quiz->getId() . '/' . $questao->getId() . '/';
        } else {
            $path = $quiz->getId() . '/';
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        $bucket = 'quizclass';
        $keyname = 'uploads/' .  $path . $newFilename;

        $s3 = new S3Client($this->getS3Config());
        $response = null;
        try {
            // Upload data.
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key'    => $keyname,
                'SourceFile'   => $file,
                'ACL' => 'public-read',
            ]);
            if ($opcao) {
                $opcao->setImagem($result['ObjectURL']);
            } else if ($questao) {
                if ($respostaOuExplicacao === "RESPOSTA") {
                    $arquivosQuestao = $questao->getArquivosQuestao();
                    $arquivosResposta = $arquivosQuestao->getArquivosResposta();
                    $arquivosResposta[$indexArquivo] = [
                        'type' => $tipoArquivo,
                        'url' => $result['ObjectURL'],
                        'provider' => 'S3'
                    ];

                    $arquivosQuestao->setArquivosResposta($arquivosResposta);
                } else {
                    $arquivosQuestao = $questao->getArquivosQuestao();
                    $arquivosExplicacao = $arquivosQuestao->getArquivosExplicacao();
                    $arquivosExplicacao[$indexArquivo] = [
                        'type' => $tipoArquivo,
                        'url' => $result['ObjectURL'],
                        'provider' => 'S3'
                    ];

                    $arquivosQuestao->setArquivosExplicacao($arquivosExplicacao);
                }

                $em->persist($questao);
            } else {
                $quiz->setImage($result['ObjectURL']);
            }
            $em->persist($quiz);
            $em->flush();

            $response =  new Response(
                $this->serializer->serialize(
                    [
                        'url' => $result['ObjectURL'],
                        'quiz_id' => $quiz->getId(),
                        'questao_id' => $questao ? $questao->getId() : null,
                        'opcao_id' => $opcao ? $opcao->getId() : null
                    ],
                    $request->getRequestFormat()
                ),
                Response::HTTP_OK
            );
        } catch (S3Exception $e) {
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
     * @Rest\Post("/upload-material/{user_id}", name="upload_material")
     * @return Response
     */
    public function uploadMateriaisAula(Request $request, SluggerInterface $slugger)
    {
        $aulaId = $request->get('aula_id');
        $userId = $request->get('user_id');
        $cursoId = $request->get('curso_id');
        $moduloId = $request->get('modulo');
        $quizId = $request->get('quiz_id');
        $file = $request->files->get('file');

        if ($file === null)
            return new Response('{"Erro": "Arquivo não encontrado."}', Response::HTTP_NOT_FOUND);

        $entityManager = $this->getDoctrine()->getManager();
        $path = $userId . '/';
        if ($quizId) {
            $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);
            $quiz = $quiz->getId();
            $path = $userId . '/' . $quiz . '/';
        } else {
            $modulo = $entityManager->getRepository(Modulo::class)->find($moduloId);
            if ($modulo) {
                $modulo = $slugger->slug($modulo->getNome());
            } else {
                $modulo = $slugger->slug($moduloId);
            }

            $path = $userId . '/' . $cursoId . '/' . $modulo . '/' . $aulaId . '/';
        }


        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        $bucket = 'quizclass';
        $keyname = 'uploads/' .  $path . $newFilename;

        $s3 = new S3Client($this->getS3Config());
        $response = null;
        try {
            // Upload data.
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key'    => $keyname,
                'SourceFile'   => $file,
                'ACL' => 'public-read',
            ]);
            $response =  new Response(
                $this->serializer->serialize(
                    ['url' => $result['ObjectURL']],
                    $request->getRequestFormat()
                ),
                Response::HTTP_OK
            );
        } catch (S3Exception $e) {
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
     * @Rest\Post("/upload/outros", name="upload_outros")
     * @return Response
     */
    public function uploadImageCurso(Request $request, SluggerInterface $slugger)
    {
        $cursoId = $request->get('curso_id');
        $userId = $request->get('user_id');
        $quizId = $request->get('quiz_id');

        $file = $request->files->get('file');

        if ($file === null)
            return new Response('{"Erro": "Arquivo não encontrado."}', Response::HTTP_NOT_FOUND);

        $path = $userId . '/';

        if ($cursoId) {
            $path = $userId . '/' . $cursoId . '/';
        } else if ($quizId) {
            $path = $userId . '/' . $quizId . '/';
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        $bucket = 'quizclass';
        $keyname = 'uploads/' .  $path . $newFilename;

        $s3 = new S3Client($this->getS3Config());
        $response = null;
        try {
            // Upload data.
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key'    => $keyname,
                'SourceFile'   => $file,
                'ACL' => 'public-read',
            ]);

            $response =  new Response(
                $this->serializer->serialize(
                    [
                        'url' => $result['ObjectURL']
                    ],
                    $request->getRequestFormat()
                ),
                Response::HTTP_OK
            );
        } catch (S3Exception $e) {
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


    private function getS3Config()
    {
        $s3service = new S3Service();
        return $s3service->getS3Config();
    }
}
