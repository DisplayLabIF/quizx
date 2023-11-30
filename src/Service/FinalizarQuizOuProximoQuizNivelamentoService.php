<?php

namespace App\Service;

use App\Entity\Quiz\NotaQuiz;
use App\Entity\Quiz\RespostaQuiz;
use App\Entity\Quiz\RespostaQuizQuestoes;
use Doctrine\ORM\EntityManagerInterface;


class FinalizarQuizOuProximoQuizNivelamentoService
{
    private $entityManager;
    private $serviceEnviarResultadosEmail;

    public function __construct(EntityManagerInterface $entityManager, EnviarResultadoEmailService $serviceEnviarResultadosEmail)
    {
        $this->entityManager = $entityManager;
        $this->serviceEnviarResultadosEmail = $serviceEnviarResultadosEmail;
    }

    public function finalizarQuiz(RespostaQuiz $respostaQuiz)
    {
        $qtdAcertos = $this->entityManager->getRepository(RespostaQuizQuestoes::class)->findBy([
            'respostaQuiz' => $respostaQuiz->getId(), 'quiz' => $respostaQuiz->getQuizAtual()->getId(), 'correto' => 1
        ]);

        if ($qtdAcertos === null)
            $qtdAcertos = 0;

        $data = [
            'render_component' => 'FIM_QUIZ',
            'resultado' => [
                'qtd_acertos' => count($qtdAcertos),
                'nota' => $respostaQuiz->getNota(),
            ],
            'tempo_esgotado' => $respostaQuiz->getTempoEsgotado()
        ];
        if ($respostaQuiz->getQuizAtual()->getStartGame()) {
            $respostaQuiz
                ->setLeadCapturado(true)
                ->setFinalizou(true);
            $this->entityManager->persist($respostaQuiz);
        } else if (!$respostaQuiz->getQuizAtual()->getConfiguracaoQuiz()->getObterDadosRespondente() || $respostaQuiz->getQuizAtual()->getConfiguracaoQuiz()->getQuandoObterDados() !== 'FIM') {
            $respostaQuiz->setFinalizou(true);
            $this->entityManager->persist($respostaQuiz);
        }

        if (
            $respostaQuiz->getQuizAtual()->getConfiguracaoQuiz()->getObterDadosRespondente() &&
            $respostaQuiz->getQuizAtual()->getConfiguracaoQuiz()->getObterEmail() &&
            $respostaQuiz->getQuizAtual()->getConfiguracaoQuiz()->getMostrarNota() === 'ENVIAR_EMAIL' &&
            $respostaQuiz->getQuizAtual()->getConfiguracaoQuiz()->getQuandoObterDados() !== 'FIM' &&
            $respostaQuiz->getLeadQuizEntity()
        ) {
            $this->serviceEnviarResultadosEmail->enviarEmailResultado($respostaQuiz->getLeadQuizEntity()->getEmail(), $respostaQuiz->getQuizAtual()->getPersonalizacaoEmail(), $respostaQuiz);
        }

        $this->entityManager->flush();

        return $data;
    }

    public function finalizarOuAtualizarProximoQuizNivelamento(RespostaQuiz $respostaQuiz, Nivelamento $nivelamento)
    {
        $data = [];

        $qtdAcertos = $this->entityManager->getRepository(RespostaQuizQuestoes::class)->findBy([
            'respostaQuiz' => $respostaQuiz->getId(), 'quiz' => $respostaQuiz->getQuizAtual()->getId(), 'correto' => 1
        ]);


        $atualQuizNivelamento =  $this->entityManager->getRepository(QuizesNivelamento::class)->findOneBy([
            'nivelamento' => $nivelamento->getId(),
            'quiz' => $respostaQuiz->getQuizAtual()->getId()
        ]);

        $proximoQuizNivelamento =  $this->entityManager->getRepository(QuizesNivelamento::class)->findOneBy([
            'nivelamento' => $nivelamento->getId(),
            'ordem' => $atualQuizNivelamento->getOrdem() + 1
        ]);

        //SALVAR NOTA
        $notaQuiz = new NotaQuiz();
        $notaQuiz
            ->setNota($respostaQuiz->getNota())
            ->setRespostaQuiz($respostaQuiz)
            ->setQuiz($respostaQuiz->getQuizAtual());
        $this->entityManager->persist($notaQuiz);

        //VERIFICAR SE O QUIZ RESPONDIDO NÃO ATINGIU A MÉDIA E SE PODE AVANÇAR AO PRÓXIMO
        if ($respostaQuiz->getTempoEsgotado() || ($notaQuiz->getNota() < $atualQuizNivelamento->getPontuacaoMinima())) {
            // $anteriorNivelamento =  $this->entityManager->getRepository(QuizesNivelamento::class)->findOneBy([
            //     'nivelamento' => $nivelamento->getId(),
            //     'ordem' => $atualQuizNivelamento->getOrdem() - 1
            // ]);

            // $nivelAtingido = $anteriorNivelamento ? $anteriorNivelamento->getQuiz() : $atualQuizNivelamento->getQuiz();
            // $respostaQuiz->setQuizAtual($nivelAtingido);

            $data = [
                'render_component' => 'FIM_QUIZ',
                'nivel_atingido' => $atualQuizNivelamento->getQuiz()->getNome(),
                'resultado' => [
                    'qtd_acertos' => count($qtdAcertos),
                    'nota' => $notaQuiz->getNota(),
                ],
                'urlCallBack' => ($atualQuizNivelamento->getNivelamento()->getConfiguracaoNivelamento()->getUrlCallBack() != null) ? $atualQuizNivelamento->getNivelamento()->getConfiguracaoNivelamento()->getUrlCallBack() : '',
                'tempo_esgotado' => $respostaQuiz->getTempoEsgotado()
            ];
            if (!$nivelamento->getConfiguracaoNivelamento()->getObterDadosRespondente() || $nivelamento->getConfiguracaoNivelamento()->getQuandoObterDados() !== 'FIM' || $respostaQuiz->getLeadCapturado()) {
                $respostaQuiz->setFinalizou(true);
            }

            if (
                $nivelamento->getConfiguracaoNivelamento()->getObterDadosRespondente() &&
                $nivelamento->getConfiguracaoNivelamento()->getObterEmail() &&
                $nivelamento->getConfiguracaoNivelamento()->getMostrarNota() === 'ENVIAR_EMAIL' &&
                $nivelamento->getConfiguracaoNivelamento()->getQuandoObterDados() !== 'FIM' &&
                $respostaQuiz->getLeadQuizEntity()
            ) {
                $this->serviceEnviarResultadosEmail->enviarEmailResultado($respostaQuiz->getLeadQuizEntity()->getEmail(), $respostaQuiz->getQuizAtual()->getPersonalizacaoEmail(), $respostaQuiz);
            }

            $this->entityManager->persist($respostaQuiz);
        } else if (!$proximoQuizNivelamento) {
            $data = [
                'render_component' => 'FIM_QUIZ',
                'nivel_atingido' => $atualQuizNivelamento->getQuiz()->getNome(),
                'resultado' => [
                    'qtd_acertos' => count($qtdAcertos),
                    'nota' => $notaQuiz->getNota(),
                ],
                'urlCallBack' => ($atualQuizNivelamento->getNivelamento()->getConfiguracaoNivelamento()->getUrlCallBack() != null) ? $atualQuizNivelamento->getNivelamento()->getConfiguracaoNivelamento()->getUrlCallBack() : '',
                'tempo_esgotado' => $respostaQuiz->getTempoEsgotado()
            ];
            if (!$nivelamento->getConfiguracaoNivelamento()->getObterDadosRespondente() || $nivelamento->getConfiguracaoNivelamento()->getQuandoObterDados() !== 'FIM' || $respostaQuiz->getLeadCapturado()) {
                $respostaQuiz->setFinalizou(true);
                $this->entityManager->persist($respostaQuiz);
            }

            if (
                $nivelamento->getConfiguracaoNivelamento()->getObterDadosRespondente() &&
                $nivelamento->getConfiguracaoNivelamento()->getObterEmail() &&
                $nivelamento->getConfiguracaoNivelamento()->getMostrarNota() === 'ENVIAR_EMAIL' &&
                $nivelamento->getConfiguracaoNivelamento()->getQuandoObterDados() !== 'FIM' &&
                $respostaQuiz->getLeadQuizEntity()
            ) {
                $this->serviceEnviarResultadosEmail->enviarEmailResultado($respostaQuiz->getLeadQuizEntity()->getEmail(), $respostaQuiz->getQuizAtual()->getPersonalizacaoEmail(), $respostaQuiz);
            }
        } else {
            $respostaQuiz
                ->setNota(0)
                ->setQuizAtual($proximoQuizNivelamento->getQuiz())
                ->setApresentandoQuiz(true)
                ->setFinalizou(false);
            $this->entityManager->persist($respostaQuiz);
            $data = [
                'render_component' => 'FIM_QUIZ',
                'resultado' => [
                    'qtd_acertos' => count($qtdAcertos),
                    'nota' => $notaQuiz->getNota(),
                ],
                'tempo_esgotado' => $respostaQuiz->getTempoEsgotado()
            ];
            // if ($nivelamento->getConfiguracaoNivelamento()->getMostrarNota() === 'MOSTRAR') {
            //     $data = [
            //         'render_component' => 'FIM_QUIZ',
            //         'resultado' => [
            //             'qtd_acertos' => count($qtdAcertos),
            //             'nota' => $notaQuiz->getNota(),
            //         ],
            //     ];
            // } else {
            //     $data = [
            //         'render_component' => 'CARREGAR_QUIZ'
            //     ];
            // }
        }
        $this->entityManager->flush();

        if ($nivelamento->getConfiguracaoNivelamento()->getMostrarGabarito())
            $data['respostas'] = $respostaQuiz->getRespostasForQuiz($atualQuizNivelamento->getQuiz());

        return $data;
    }
}
