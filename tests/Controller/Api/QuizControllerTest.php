<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuizControllerTest extends WebTestCase
{

    public function testBuscaQuizConfig()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/quizes/1234/configuracao',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            '{"user_id": "usuarioTeste@gmail.com", "type": "QUIZ"}'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testBuscaQuestao()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/questoes/1234/busca-questao',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            '{"user_id": "usuarioTeste@gmail.com", "type": "QUIZ"}'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRespostaQuizQuestaoMultiplhaEscolha()
    {

        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/questoes/1234/busca-questao',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            '{"user_id": "usuarioTeste@gmail.com", "type": "QUIZ"}'
        );

        $questao = json_decode($client->getResponse()->getContent(), true)['questao'];

        $data = json_encode([
            "resposta" => [
                "MULTIPLA_ESCOLHA" => $questao['opcoes'][0]['id']
            ],
            "quiz_id" => "1234",
            "user_id" => "usuarioTeste@gmail.com",
            "type" => "QUIZ",
            "pular" => false
        ]);

        $client->request(
            'POST',
            '/api/v1/questoes/' . $questao['id'] . '/verificar-resposta',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            $data
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRespostaQuizQuestaoVF()
    {

        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/questoes/1234/busca-questao',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            '{"user_id": "usuarioTeste@gmail.com", "type": "QUIZ"}'
        );

        $questao = json_decode($client->getResponse()->getContent(), true)['questao'];

        $data = json_encode([
            "resposta" => [
                "V_F" => [
                    [
                        'resposta_correta' => true,
                        'selectedFalse' => false,
                        'selectedTrue' => true
                    ],
                    [
                        'resposta_correta' => false,
                        'selectedFalse' => true,
                        'selectedTrue' => false
                    ]
                ]
            ],
            "quiz_id" => "1234",
            "user_id" => "usuarioTeste@gmail.com",
            "type" => "QUIZ",
            "pular" => false
        ]);

        $client->request(
            'POST',
            '/api/v1/questoes/' . $questao['id'] . '/verificar-resposta',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            $data
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRespostaQuizQuestaoAberta()
    {

        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/questoes/1234/busca-questao',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            '{"user_id": "usuarioTeste@gmail.com", "type": "QUIZ"}'
        );

        $questao = json_decode($client->getResponse()->getContent(), true)['questao'];

        $data = json_encode([
            "resposta" => [
                "ABERTA" => "sim, é verdade!"
            ],
            "quiz_id" => "1234",
            "user_id" => "usuarioTeste@gmail.com",
            "type" => "QUIZ",
            "pular" => false
        ]);

        $client->request(
            'POST',
            '/api/v1/questoes/' . $questao['id'] . '/verificar-resposta',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            $data
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRespostaQuizQuestaoOrdenar()
    {

        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/questoes/1234/busca-questao',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            '{"user_id": "usuarioTeste@gmail.com", "type": "QUIZ"}'
        );

        $questao = json_decode($client->getResponse()->getContent(), true)['questao'];

        $data = json_encode([
            "resposta" => [
                "ORDENAR" => ['a b c d']
            ],
            "quiz_id" => "1234",
            "user_id" => "usuarioTeste@gmail.com",
            "pular" => false,
            "type" => "QUIZ"
        ]);

        $client->request(
            'POST',
            '/api/v1/questoes/' . $questao['id'] . '/verificar-resposta',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            $data
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
