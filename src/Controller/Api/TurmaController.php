<?php

namespace App\Controller\Api;


use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Curso\Turma;
use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * Class ClienteController
 * @package App\Controller\Api
 */

class TurmaController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Get("/turmas/{turma_id}", name="api_get_turma")
     * @return Response
     */
    public function getTurmaAction(Request $request)
    {
        $turma_id = $request->get('turma_id');
        $em = $this->getDoctrine()->getManager();

        $turma = $em->getRepository(Turma::class)->find($turma_id);
        $response =  new Response('{"Erro": "Turma não encontrada."}', Response::HTTP_NOT_FOUND);

        if ($turma) {
            $response =  new Response(
                $this->serializer->serialize(
                    [
                        'turma' => $turma,
                        'instituicao' => $turma->getCurso()->getEscola()->getNome()

                    ],
                    $request->getRequestFormat()
                ),
                Response::HTTP_OK
            );
        }

        return $response;
    }
}
