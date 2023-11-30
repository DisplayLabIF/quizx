<?php

namespace App\Controller\Api;

use App\Entity\Curso\Curso;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Curso\Turma;
use App\Entity\Escola;
use App\Repository\TurmaRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * Class ClienteController
 * @package App\Controller\Api
 */

class CursoController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Get("/cursos", name="api_list_curso")
     * @return Response
     */
    public function getCursosAction(Request $request, TurmaRepository $turmaRepository)
    {
        $cursos = null;
        $response = null;

        try {

            $cursos = $turmaRepository->findTurmasDisponiveisMatricula();

            $response =  new Response(
                $this->serializer->serialize(
                    $cursos,
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

    /**
     *
     * @Rest\Get("/cursos/{slug_instituicao}", name="api_list_cursos_instituicao")
     * @return Response
     */
    public function getCursosInstituicaoAction(Request $request)
    {
        // $refererArr = explode("/", $request->server->get('HTTP_REFERER'));
        // $referer = (isset($refererArr[2])) ? $refererArr[2] : null;
        $em = $this->getDoctrine()->getManager();
        $cursos = null;

        $escola = $em->getRepository(Escola::class)->findOneBy(['url' => $_ENV['DOMINIO_PAGINA_CURSOS'] . '/' . $request->get('slug_instituicao')]);
        $response =  new Response(json_encode(['message' => 'Instituição não encontrada']), Response::HTTP_NOT_FOUND);

        if ($escola) {
            $cursos = $escola->getCursosDisponiveisVenda()->toArray();
            $turmas = $em->getRepository(Turma::class)->findBy(['curso' => $cursos, 'disponivelMatricula' => 1]);
            $response =  new Response(
                $this->serializer->serialize(
                    [
                        'turmas' => $turmas,
                        'instituicao' => $escola->getNome(),
                        'descricao_instituicao' => $escola->getDescricao(),
                        'logo_instituicao' => $escola->getImagem()

                    ],
                    $request->getRequestFormat()
                ),
                Response::HTTP_OK
            );
        }

        return $response;
    }
}
