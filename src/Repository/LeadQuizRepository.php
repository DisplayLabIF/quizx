<?php

namespace App\Repository;

use App\Entity\Quiz\LeadQuiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LeadQuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeadQuiz::class);
    }

    public function getUltimosLeadsCadastrados($userId)
    {
        $qb = $this->createQueryBuilder('lead_quiz');

        $qb
            ->select('DISTINCT lead_quiz AS lead, lead_quiz.created, quiz.nome AS nomeQuiz')
            ->leftJoin('lead_quiz.quizes', 'quiz')
            ->leftJoin('lead_quiz.respostas', 'resposta_quiz')
            ->where('quiz.user = :userId')
            ->orderBy('lead_quiz.created', 'DESC')
            ->setParameter('userId', $userId);

    

        return
            $qb
            ->getQuery()
            ->getResult();
    }

    public function getQuantidadeLeadsCadastrados($userId)
    {
        $qb = $this->createQueryBuilder('lead_quiz');

        $qb
            ->select('COUNT(DISTINCT lead_quiz) AS quantidadeLeads')
            ->leftJoin('lead_quiz.quizes', 'quiz')
            ->orWhere('quiz.user = :userId')
            ->setParameter('userId', $userId);

        return
            $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getUltimoLeadCadastrado($userId)
    {
        $qb = $this->createQueryBuilder('lead_quiz');

        $qb
            ->select('lead_quiz.nome AS ultimoLeadCadastrado')
            // ->where($qb->expr()->in(
            //     'lead_quiz.id',
            //     $whereIn
            // ))
            ->leftJoin('lead_quiz.quizes', 'quiz')
            ->orWhere('quiz.user = :userId')
            ->orderBy('lead_quiz.created', 'DESC')
            ->setParameter('userId', $userId);

        $result = $qb
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($result) {
            return $result['ultimoLeadCadastrado'];
        }

        return null;
    }

    public function getUltimoLeadRespostaQuiz($userId)
    {
        $qb = $this->createQueryBuilder('lead_quiz');

        $qb
            ->select('lead_quiz.nome')
            ->leftJoin('lead_quiz.quizes', 'quiz')
            ->leftJoin('lead_quiz.respostas', 'resposta_quiz')
            ->where('quiz.user = :userId')
            ->orderBy('resposta_quiz.created', 'DESC')
            ->setParameter('userId', $userId);

        $result = $qb
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($result) {
            return $result['nome'];
        }

        return null;
    }

    public function getQuantidadeLeadsCadastradosIntervalo($userId, \DateTime $startDate, \DateTime $endDate)
    {
        $qb = $this->createQueryBuilder('lead_quiz');

        $qb
            ->select('COUNT(DISTINCT lead_quiz) AS quantidadeLeads')
            ->leftJoin('lead_quiz.quizes', 'quiz')
            ->where('quiz.user = :userId')
            ->andWhere('lead_quiz.created BETWEEN :startDate AND :endDate')
            ->setParameter('userId', $userId)
            ->setParameter('startDate', $startDate->format('Y-m-d 00:00:00'))
            ->setParameter('endDate', $endDate->format('Y-m-d 23:59:59'));

        return
            $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getQuantidadeLeadsCadastradosPorMes($userId)
    {
        $qb = $this->createQueryBuilder('lead_quiz');

        $qb
            ->select("COUNT(DISTINCT lead_quiz) AS quantidadeLeads, DATE_FORMAT(lead_quiz.created, '%m-%Y') AS yearMonth")
            ->leftJoin('lead_quiz.quizes', 'quiz')
            ->where('quiz.user = :userId')
            ->groupBy('yearMonth')
            ->orderBy('yearMonth', 'ASC')
            ->setParameter('userId', $userId);

        return
            $qb
            ->getQuery()
            ->getResult();
    }

    public function leadExist($leadId, $userId)
    {
        $qb = $this->createQueryBuilder('lead_quiz');

        $qb
            ->select("lead_quiz.id")
            ->leftJoin('lead_quiz.quizes', 'quiz')
            ->where('lead_quiz.id = :leadId')
            ->andWhere('quiz.user = :userId')
            ->setParameter('leadId', $leadId)
            ->setParameter('userId', $userId);


        return $qb
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
