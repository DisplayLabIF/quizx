<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Curso\Matricula;
use App\Entity\Curso\Turma;
use App\Service\MatricularAlunoService;
use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * Class ClienteController
 * @package App\Controller\Api
 */

class MatriculaController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Get("/matriculas/{matricula_id}", name="api_get_matricula")
     * @return Response
     */
    public function getMatriculaAction(Request $request)
    {
        $matricula_id = $request->get('matricula_id');
        $em = $this->getDoctrine()->getManager();

        $matricula = $em->getRepository(Matricula::class)->find($matricula_id);
        $response =  new Response('{"Erro": "Matricula não encontrada."}', Response::HTTP_NOT_FOUND);

        if ($matricula) {
            $turma = $matricula->getTurma();

            $response =  new Response(
                $this->serializer->serialize(
                    [
                        'turma' => $turma,
                        'instituicao' => [
                            'nome' => $turma->getCurso()->getEscola()->getNome(),
                            'cnpj' => $turma->getCurso()->getEscola()->getCnpj()
                        ],
                        'braspagMerchantId' => $turma->getCurso()->getEscola()->getBraspagMerchantId(),
                        'matricula' => $matricula,
                        'cliente' => $matricula->getUser()

                    ],
                    $request->getRequestFormat()
                ),
                Response::HTTP_OK
            );
        }

        return $response;
    }


    /**
     *
     * @Rest\Post("/matriculas/{turma_id}", name="api_create_matricula")
     * @return Response
     */
    public function salvaDadosUserMatricula(Request $request, MatricularAlunoService $matricularAlunoService)
    {
        $dados_user = $request->get('dados');
        $turma_id = $request->get('turma_id');
        $response =  new Response('{"Erro": "dados da mtrícula não enviados."}', Response::HTTP_BAD_REQUEST);
        $entityManager = $this->getDoctrine()->getManager();
        $turma = $entityManager->getRepository(Turma::class)->find($turma_id);

        if ($dados_user && $turma) {
            try {
                $retorno = $matricularAlunoService->efetuarMatricula($dados_user, $turma);

                if ($retorno['status'] === 200) {
                    $response =  new Response($this->serializer->serialize(
                        ['matricula' => $retorno['matriculaId']],
                        $request->getRequestFormat()
                    ), Response::HTTP_OK);
                } else if ($retorno['status'] === 422) {
                    $response = new Response('Email informado já possui matrícula no curso.', Response::HTTP_UNPROCESSABLE_ENTITY);
                } else if ($retorno['status'] === 403) {
                    $response = new Response(
                        'E-mail informado já possui um cadastro como professor. Informe um novo e-mail, ou um e-mail de aluno.',
                        Response::HTTP_FORBIDDEN
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
        }
        return $response;
    }
}
