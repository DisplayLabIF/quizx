<?php

namespace App\Service;

use App\Entity\Quiz\LeadQuiz;
use App\Entity\Quiz\Quiz;
use App\Repository\LeadQuizRepository;
use Twig\Environment;


class NotificacaoService
{
    private $templating;

    public function __construct(Environment $twig)
    {
        $this->templating = $twig;
    }

    public function notificarLeadCadastrado(LeadQuiz $lead, Quiz $quiz, LeadQuizRepository $leadQuizRepository)
    {
        $notificacao = $quiz->getUser()->getNotificacao();
        try {
            if ((!$notificacao || $notificacao->getLeadCadastrado()) && !$leadQuizRepository->leadExist($lead->getId(), $quiz->getUser()->getId())) {

                $transport = (new \Swift_SmtpTransport($_ENV['MAILER_HOST'], $_ENV['MAILER_PORT']))
                    ->setUsername($_ENV['MAILER_USERNAME'])
                    ->setPassword($_ENV['MAILER_PASSWORD']);

                $mailer = new \Swift_Mailer($transport);
                $message = (new \Swift_Message('Quiz Class '))
                    ->setSubject("Novo lead!")
                    ->setFrom(['contato@quizclass.com.br' => 'QuizClass'])
                    ->setTo($quiz->getUser()->getEmail())
                    ->setBody(
                        $this->templating->render(
                            'emails/notificacao-leadCadastrado.html.twig',
                            [
                                'lead' => $lead,
                                'quiz' => $quiz
                            ]
                        ),
                        'text/html'
                    );
                $mailer->send($message);
                return [
                    'notificacao' => [
                        'lead_cadastrado' => true,
                        'message' => 'notificação enviada'
                    ]
                ];
            }
        } catch (\Exception $e) {
            return [
                'notificacao' => [
                    'lead_cadastrado' => false,
                    'message' => $e->getMessage()
                ]
            ];
        }
        return [];
    }
}
