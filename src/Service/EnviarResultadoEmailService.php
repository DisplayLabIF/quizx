<?php

namespace App\Service;

use App\Entity\Quiz\RespostaQuiz;
use Twig\Environment;


class EnviarResultadoEmailService
{
    private $templating;

    public function __construct(Environment $twig)
    {
        $this->templating = $twig;
    }

    public function enviarEmailResultado($email, $personalizacaoEmail, RespostaQuiz $respostaQuiz): void
    {
        $escola  = $respostaQuiz->getQuizAtual()->getUser()->getEscola();

        $emailFrom = 'contato@quizclass.com.br';

        if ($escola && $escola->getRemetente()) {
            $emailFrom = $escola->getRemetente();
        } else if ($escola && $escola->getEmail()) {
            $emailFrom = $escola->getEmail();
        } else if ($respostaQuiz->getQuizAtual()->getUser()->getEmail()) {
            $emailFrom = $respostaQuiz->getQuizAtual()->getUser()->getEmail();
        }

        if (!$respostaQuiz->getNivelamento()) {

            $qtdAcertos = $respostaQuiz->getRespostas()->filter(function ($respostaQuizQuestao) {
                if ($respostaQuizQuestao->getCorreto()) {
                    return $respostaQuizQuestao;
                }
            })->count();

            $resultado = [
                'type' => 'QUIZ',
                'nota' => $respostaQuiz->getNota(),
                'qtdQuestoes' => $respostaQuiz->getQuizAtual()->getQuestoes()->count(),
                'qtdAcertos' => $qtdAcertos
            ];
        } else {
            $resultado = [
                'type' => 'NIVELAMENTO',
                'nivelAtingido' => $respostaQuiz->getQuizAtual()->getNome()
            ];
        }
        $transport = (new \Swift_SmtpTransport($_ENV['MAILER_HOST'], $_ENV['MAILER_PORT']))
            ->setUsername($_ENV['MAILER_USERNAME'])
            ->setPassword($_ENV['MAILER_PASSWORD']);

        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Quiz Class '))
            ->setSubject("Resultado quiz/nivelamento")
            ->setFrom($emailFrom)
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/resultadoQuiz.html.twig',
                    [
                        'config' => $personalizacaoEmail,
                        'resultado' => $resultado,
                        'endereco' => $escola ? $escola->getEndereco() : null
                    ]
                ),
                'text/html'
            );

        $mailer->send($message);
    }
}
