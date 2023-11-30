<?php

namespace App\DataFixtures;

use App\Entity\Quiz\ConfiguracaoQuiz;
use App\Entity\Quiz\Opcao;
use App\Entity\Quiz\Questao;
use App\Entity\Quiz\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuizFixture extends Fixture
{
    public function load(ObjectManager $manager)
    { //criar quiz com questões e opções
        $quiz = new Quiz();
        $quiz
            ->setCodigo('1234')
            ->setNome('teste')
            ->setAssuntos(['assunto 1', 'assunto 2'])
            ->setNivel('ENSINO_MEDIO')
            ->setImage('');

        $configuracaoQuiz = new ConfiguracaoQuiz();
        $configuracaoQuiz
            ->setObterDadosRespondente(true)
            ->setObterNome(true)
            ->setObterEmail(true)
            ->setObterTelefone(false)
            ->setObterEmpresa(false)
            ->setObterCnpj(false)
            ->setObterCpf(false)
            ->setObterCidade(false)
            ->setQuandoObterDados('INICIO')
            ->setMostrarNota('MOSTRAR')
            ->setDefinirTempoResposta(false)
            ->setPodePularQuestao(true)
            ->setMostrarCorrecao(true)
            ->setMostrarGabarito(true)
            ->setConfigurarLandPage(false)
            ->setRedirecionarUsuario(false)
            ->setDefinirNotaMinima(false)
            ->setAdicionarMateriais(false);

        //MULTIPLA_ESCOLHA
        $questao1 = new Questao();
        $questao1
            ->setPergunta('pergunta 1')
            ->setExplicacaoResposta('explicalçao 1')
            ->setTipo('MULTIPLA_ESCOLHA')
            ->setValor(2)
            ->setTempo('60')
            ->setObrigatoria(true)
            ->setMostrarExplicacao(true)
            ->setNumeroQuestao(1);

        $questao1Opcao1 = new Opcao();
        $questao1Opcao1
            ->setRespostaCorreta(true)
            ->setTexto('opcao1')
            ->setQuestao($questao1)
            ->setNumeroOpcao(1);

        $questao1Opcao2 = new Opcao();
        $questao1Opcao2
            ->setRespostaCorreta(false)
            ->setTexto('opcao2')
            ->setQuestao($questao1)
            ->setNumeroOpcao(2);

        $questao1->addOpcao($questao1Opcao1);
        $questao1->addOpcao($questao1Opcao2);

        //V_F
        $questao2 = new Questao();
        $questao2
            ->setPergunta('pergunta 2')
            ->setExplicacaoResposta('explicalçao 2')
            ->setTipo('V_F')
            ->setValor(2)
            ->setTempo('60')
            ->setObrigatoria(true)
            ->setMostrarExplicacao(true)
            ->setNumeroQuestao(2);

        $questao2Opcao1 = new Opcao();
        $questao2Opcao1
            ->setRespostaCorreta(true)
            ->setTexto('opcao1')
            ->setQuestao($questao2)
            ->setNumeroOpcao(1);

        $questao2Opcao2 = new Opcao();
        $questao2Opcao2
            ->setRespostaCorreta(false)
            ->setTexto('opcao2')
            ->setQuestao($questao2)
            ->setNumeroOpcao(2);

        $questao2->addOpcao($questao2Opcao1);
        $questao2->addOpcao($questao2Opcao2);

        //ABERTA
        $questao3 = new Questao();
        $questao3
            ->setPergunta('pergunta 3')
            ->setExplicacaoResposta('explicalçao 3')
            ->setTipo('ABERTA')
            ->setValor(2)
            ->setTempo('60')
            ->setObrigatoria(true)
            ->setMostrarExplicacao(true)
            ->setNumeroQuestao(3);

        $questao3Opcao1 = new Opcao();
        $questao3Opcao1
            ->setRespostaCorreta(true)
            ->setTexto('sim')
            ->setQuestao($questao3)
            ->setNumeroOpcao(1);

        $questao3Opcao2 = new Opcao();
        $questao3Opcao2
            ->setRespostaCorreta(true)
            ->setTexto('verdadeiro')
            ->setQuestao($questao3)
            ->setNumeroOpcao(2);

        $questao3->addOpcao($questao3Opcao1);
        $questao3->addOpcao($questao3Opcao2);

        //ORDENAR
        $questao4 = new Questao();
        $questao4
            ->setPergunta('pergunta 4')
            ->setExplicacaoResposta('explicalçao 4')
            ->setTipo('ORDENAR')
            ->setValor(2)
            ->setTempo('60')
            ->setObrigatoria(true)
            ->setMostrarExplicacao(true)
            ->setNumeroQuestao(4);

        $questao4Opcao1 = new Opcao();
        $questao4Opcao1
            ->setRespostaCorreta(true)
            ->setTexto('a b c d')
            ->setQuestao($questao4)
            ->setNumeroOpcao(1);

        $questao4->addOpcao($questao4Opcao1);

        $quiz->addQuestao($questao1);
        $quiz->addQuestao($questao2);
        $quiz->addQuestao($questao3);
        $quiz->addQuestao($questao4);

        $quiz->setConfiguracaoQuiz($configuracaoQuiz);

        $manager->persist($quiz);

        $manager->flush();
    }
}
