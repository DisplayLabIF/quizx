<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quiz\Opcao;
use App\Entity\Quiz\Questao;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/questoes")
 */
class QuestaoController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Post("/opcoes", name="api_new_questao_opcao")
     * @return Response
     */
    public function postQuestaoOpcao(Request $request)
    {

        //$questaoId = $request->get('questao_id');
        $questaoId = $request->toArray()['questao_id'];
        //$opcao = $request->get('opcao');
        $opcao = $request->toArray()['opcao'];
        $em = $this->getDoctrine()->getManager();
        try {
            $response =  new Response('{"Erro": "A questão informada não foi encontrada."}', Response::HTTP_NOT_FOUND);

            $questao = $em->getRepository(Questao::class)->find($questaoId);
            if ($questao) {
                $op = new Opcao();
                $op
                    ->setRespostaCorreta($opcao['resposta_correta'])
                    ->setTexto($opcao['texto'])
                    ->setQuestao($questao)
                    ->setNumeroOpcao($opcao['numero_opcao'])
                    ->setActive($opcao['active']);
                $em->persist($op);
                $em->flush();

                $response =  new Response(
                    $this->serializer->serialize(
                        [
                            'opcao_id' => $op->getId(),

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
     *  @Rest\Put("/opcoes/{opcao_id}", name="api_edit_questao_opcao")
     * @return Response
     */
    public function putQuestaoOpcao(Request $request)
    {
        $opcaoId = $request->get('opcao_id');
        //$opcao = $request->get('opcao');

        $opcao = $request->toArray()['opcao'];

        $em = $this->getDoctrine()->getManager();
        try {
            $response =  new Response('{"Erro": "A opção informada não foi encontrada."}', Response::HTTP_NOT_FOUND);

            $op = $em->getRepository(Opcao::class)->find($opcaoId);
            if ($op) {
                $op
                    ->setRespostaCorreta($opcao['resposta_correta'])
                    ->setTexto($opcao['texto'])
                    ->setNumeroOpcao($opcao['numero_opcao'])
                    ->setActive($opcao['active']);
                $em->persist($op);
                $em->flush();

                $response =  new Response(
                    $this->serializer->serialize(
                        [
                            'opcao_id' => $op->getId(),
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
}
