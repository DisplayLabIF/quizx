<?php

namespace App\Command;

use App\Entity\User\AdmQuiz;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CriarAdminCommand extends Command
{
    protected static $defaultName = 'app:criar:admin';

    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Criando usuario Administrados do quizClass')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $newAdmin = new AdmQuiz();

        $newAdmin
            ->setNome('geraldo@valuecode Admin QuizClass')
            ->setEmail('geraldo@valuecode.com.br')
            ->setPassword($this->passwordEncoder->encodePassword(
                $newAdmin,
                'aaaa102030'
            ))
            ->setRoles(['ROLE_ADMIN_QUIZ', 'ROLE_ADMIN']);

        $this->entityManager->persist($newAdmin);
        $this->entityManager->flush();

        return 0;
    }
}
