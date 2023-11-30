<?php

namespace App\Controller\Api;

use App\Entity\Quiz\Questao;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Google\Cloud\Speech\SpeechClient;
use App\Service\AudioRecognition;
use Google\Cloud\Language\LanguageClient;
//use Google\Cloud\Speech\V1\SpeechClient;

/**
 * @Route("/audio")
 */
class AudioController extends AbstractFOSRestController
{
    use TraitApiController;

    /**
     *
     * @Rest\Post("/recognition", name="audio_recognition")
     * @param AudioRecognition $audioRecognition
     * @return Response
     */
    public function postAudioRespostaAction(Request $request, AudioRecognition $audioRecognition)
    {
        $questaoId = $request->request->get('questao_id');
        $typeRecording = $request->request->get('type_recording');

        $em = $this->getDoctrine()->getManager();

        $questao = $em->getRepository(Questao::class)->find($questaoId);
        $respostasEsperadas = [];
        $opcoesCorretas = [];
        $percentualAcertoCorreto = 80;

        if ($questao->getTipo() === 'MULTIPLA_ESCOLHA_VOICE') {
            foreach ($questao->getOpcoes() as $index => $opcao) {
                if ($opcao->getRespostaCorreta()) {
                    $respostasEsperadas[] = $opcao->getTexto();
                    $opcoesCorretas[] = $index;
                }
            }
        } else {
            foreach ($questao->getOpcoes() as $opcao) {
                $respostasEsperadas[] = $opcao->getTexto();
            }
        }
        $audio = $request->get('data');
        $mostra_resposta = true;
        $result = $audioRecognition->reconhecimentoDeAudio($audio, $respostasEsperadas, $mostra_resposta, $percentualAcertoCorreto, $typeRecording);

        if ($questao->getTipo() === 'MULTIPLA_ESCOLHA_VOICE' && $result['acertou']) {
            $result['opcoes_corretas'] = $opcoesCorretas;
        }

        $view = $this->view(
            $result,
            200
        );
        return $this->handleView($view);
    }
}
