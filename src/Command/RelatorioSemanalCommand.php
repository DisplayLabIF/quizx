<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\User\AdmEscola;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LeadQuizRepository;
use App\Repository\QuizRepository;
use App\Repository\RespostaQuizRepository;
use App\Service\BuildConfigPdf;
use Twig\Environment;

class RelatorioSemanalCommand extends Command
{
    protected static $defaultName = 'app:relatorio:semanal';

    private $entityManager;
    private $templating;
    private $respostaQuizRepository;
    private $leadQuizRepository;
    private $quizRepository;
    private $buildConfigPdf;

    public function __construct(EntityManagerInterface $entityManager, Environment $twig, RespostaQuizRepository $respostaQuizRepository, LeadQuizRepository $leadQuizRepository, QuizRepository $quizRepository, BuildConfigPdf $buildConfigPdf)
    {
        $this->entityManager = $entityManager;
        $this->templating = $twig;
        $this->respostaQuizRepository = $respostaQuizRepository;
        $this->leadQuizRepository = $leadQuizRepository;
        $this->quizRepository = $quizRepository;
        $this->buildConfigPdf = $buildConfigPdf;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Enviar relatorio semanal para os usuarios')
            ->addArgument('email', InputArgument::OPTIONAL, 'email')
            ->addArgument('emailDestinatario', InputArgument::OPTIONAL, 'E-mail do destinatário')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $emailDestinatario = $input->getArgument('emailDestinatario');

        $startDate = (new \DateTime('now'))->modify('-7 day');
        $endDate = (new \DateTime('now'));

        $startDateSemanaAnterior = (new \DateTime('now'))->modify('-14 day');
        $endDateSemanaAnterior = (new \DateTime('now'))->modify('-8 day');

        foreach ($email ? $this->entityManager->getRepository(AdmEscola::class)->findBy(['email' => $email])  :
            $this->entityManager->getRepository(AdmEscola::class)->findAll() as $user) {



            try {

                $quantidadeRespostaSemana = $this->respostaQuizRepository->getQuantidadeRespostasIntervalo($user->getId(), $startDate, $endDate);
                $quantidadeRespostaSemanaAnterior = $this->respostaQuizRepository->getQuantidadeRespostasIntervalo(
                    $user->getId(),
                    $startDateSemanaAnterior,
                    $endDateSemanaAnterior
                );

                // dd($quantidadeRespostaQuizzesSemana . ' - ' . $quantidadeRespostaQuizzesSemanaAnterior);

                $repostasPorMes = $this->respostaQuizRepository->getQuantidadeRespostasMes($user->getId());
                $leadsPorMes = $this->leadQuizRepository->getQuantidadeLeadsCadastradosPorMes($user->getId());

                $pdf = $this->buildConfigPdf->getConfig();
                $pdf->addPage($this->templating->render('pdf/relatorioSemanal.html.twig', [
                    'dataRelatorio' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
                    'quantidadeRespostaSemana' => $quantidadeRespostaSemana,
                    'quantidadeLeadsSemana' => $this->leadQuizRepository->getQuantidadeLeadsCadastradosIntervalo($user->getId(), $startDate, $endDate),
                    'quantidadeRespostaTotal' => $this->respostaQuizRepository->getQuantidadeRespostasQuizAndNIvelamento($user->getId()),
                    'quantidadeQuizzesCriados' =>  COUNT($this->quizRepository->getQuizes($user->getId())),
                    'quantidadeRespostaSemanaAnterior' => $quantidadeRespostaSemanaAnterior,
                    'respostasPorMes' => $repostasPorMes,
                    'leadsPorMes' => $leadsPorMes
                ]));


                $transport = (new \Swift_SmtpTransport($_ENV['MAILER_HOST'], $_ENV['MAILER_PORT']))
                    ->setUsername($_ENV['MAILER_USERNAME'])
                    ->setPassword($_ENV['MAILER_PASSWORD']);

                $mailer = new \Swift_Mailer($transport);

                $message = (new \Swift_Message('Quiz Class'))
                    ->setSubject("Relatorio semanal QuizClass")
                    ->setFrom(['contato@quizclass.com.br' => 'QuizClass'])
                    ->setTo((empty($emailDestinatario)) ? $user->getEmail() : $emailDestinatario)
                    ->attach(new \Swift_Attachment($pdf->toString(), 'relatorio-semanal.pdf', 'application/pdf'))
                    ->setBody(
                        $this->templating->render(
                            'emails/relatorioSemanal.html.twig',
                            [
                                'user' => $user
                            ]
                        ),
                        'text/html'
                    );

                $mailer->send($message);
                var_dump('relatorio enviado para ' . $user->getEmail() . "\n\n");
            } catch (\Exception $e) {
                var_dump('Não enviado para ' . $user->getEmail() . "\n" . $e->getMessage() . "\n\n");
            }
        }
        return 0;
    }
}
