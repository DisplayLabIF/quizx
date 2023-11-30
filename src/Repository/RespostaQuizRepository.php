<?php

namespace App\Repository;

use App\Entity\Quiz\RespostaQuiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RespostaQuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RespostaQuiz::class);
    }


    public function findResposta($quizId, $nivelamentoId, $aluno)
    {

        $qb = $this->createQueryBuilder('resposta_quiz');

        if ($quizId) {
            $qb
                ->where('resposta_quiz.quizAtual = :quizId')
                ->setParameter('quizId', $quizId);
        }
        if ($nivelamentoId) {
            $qb
                ->where('resposta_quiz.nivelamento = :nivelamentoId')
                ->setParameter('nivelamentoId', $nivelamentoId);
        }

        $qb
            ->andWhere('resposta_quiz.aluno = :aluno')
            ->andWhere('resposta_quiz.finalizou = 0')
            ->setParameter('aluno', $aluno)
            ->orderBy('resposta_quiz.created', 'DESC');

        $result = $qb
            ->getQuery()
            ->getResult();

        if (COUNT($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }

    public function getRespostasQuiz($nivelamentoId, $quizId, $intervalo, $nomeLead, $leadsCapturados = false)
    {
        $qb = $this->createQueryBuilder('resposta_quiz');


        $qb
            ->leftJoin('resposta_quiz.leadQuizEntity', 'lead_quiz')
            ->leftJoin('resposta_quiz.alunoEntity', 'aluno');


        if ($nivelamentoId) {
            $qb
                ->where('resposta_quiz.nivelamento = :nivelamentoId')
                ->setParameter('nivelamentoId', $nivelamentoId);
        }

        if ($quizId) {
            $qb
                ->where('resposta_quiz.nivelamento is NULL')
                ->andWhere('resposta_quiz.quizAtual = :quizId')
                ->setParameter('quizId', $quizId);
        }


        if ($leadsCapturados) {
            $qb->andWhere('resposta_quiz.leadQuizEntity IS NOT NULL OR resposta_quiz.alunoEntity IS NOT NULL');
        }

        if ($intervalo) {
            $intervalo =  explode('-', $intervalo);

            $startDate = \DateTime::createFromFormat('d/m/Y', trim($intervalo[0]));
            $endDate = \DateTime::createFromFormat('d/m/Y', trim($intervalo[1]));

            $qb
                ->andWhere('resposta_quiz.created BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $startDate->format('Y-m-d 00:00:00'))
                ->setParameter('endDate', $endDate->format('Y-m-d 23:59:59'));
        }

        if ($nomeLead) {
            $qb
                ->andWhere('lead_quiz.nome LIKE :nomeLead OR aluno.nome LIKE :nomeLead')
                ->setParameter('nomeLead', '%' . $nomeLead . '%');
        }

        $qb->select('resposta_quiz AS resposta')
            ->addSelect(
                '(
                    SELECT SUM(n.nota)
                    FROM App\Entity\Quiz\NotaQuiz AS n
                    WHERE n.respostaQuiz = resposta_quiz.id
                ) AS notaTotal,
                (
                    SELECT a.nome
                    FROM App\Entity\User\Aluno AS a
                    WHERE a.id = resposta_quiz.aluno
                ) AS userAluno'
            );

        return
            $qb
            ->addOrderBy('resposta_quiz.created', 'DESC')
            ->getQuery();
    }


    public function getQuestaoMaisAcertadaOuMaisErrada($nivelamentoId, $quizId, $intervalo, $nomeLead, $questaoCertaOuErrada, $leadsCapturados = false)
    {
        $qb = $this->createQueryBuilder('resposta_quiz');

        $qb
            ->leftJoin('resposta_quiz.leadQuizEntity', 'lead_quiz')
            ->leftJoin('resposta_quiz.alunoEntity', 'aluno')
            ->leftJoin('resposta_quiz.respostas', 'resposta_quiz_questoes')
            ->where('resposta_quiz_questoes.correto = :questaoCertaOuErrada')
            ->setParameter('questaoCertaOuErrada', $questaoCertaOuErrada);


        if ($nivelamentoId) {
            $qb
                ->andWhere('resposta_quiz.nivelamento = :nivelamentoId')
                ->setParameter('nivelamentoId', $nivelamentoId);
        }

        if ($quizId) {
            $qb
                ->andWhere('resposta_quiz.nivelamento is NULL')
                ->andWhere('resposta_quiz.quizAtual = :quizId')
                ->setParameter('quizId', $quizId);
        }

        if ($leadsCapturados) {
            $qb
                ->andWhere('resposta_quiz.leadQuizEntity IS NOT NULL')
                ->orWhere('resposta_quiz.alunoEntity IS NOT NULL');
        }


        if ($intervalo) {
            $intervalo =  explode('-', $intervalo);

            $startDate = \DateTime::createFromFormat('d/m/Y', trim($intervalo[0]));
            $endDate = \DateTime::createFromFormat('d/m/Y', trim($intervalo[1]));

            $qb
                ->andWhere('resposta_quiz.created BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $startDate->format('Y-m-d 00:00:00'))
                ->setParameter('endDate', $endDate->format('Y-m-d 23:59:59'));
        }

        if ($nomeLead) {
            $qb
                ->andWhere('lead_quiz.nome LIKE :nomeLead')
                ->orWhere('aluno.nome LIKE :nomeLead')
                ->setParameter('nomeLead', '%' . $nomeLead . '%');
        }

        $qb
            ->join('resposta_quiz_questoes.questao', 'questao')
            ->join('resposta_quiz_questoes.quiz', 'quiz');

        $qb->groupBy('questao.id, quiz.id');
        $qb->select('quiz.nome as nomeQuiz ,questao.id as idQuestao, questao.numeroQuestao, count(questao.id) as qtd')->orderBy('qtd', 'DESC');
        $result = $qb
            ->getQuery()
            ->getResult();
        return array_key_exists(0, $result) ? $result[0] : null;
    }

    public function getQuantidadeRespostasQuiz($userId)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(r.id)
            FROM App\Entity\Quiz\RespostaQuiz r
            WHERE r.finalizou = 1 AND r.quizAtual IN (
                SELECT q.id
                FROM App\Entity\Quiz\Quiz q
                WHERE q.user = :userId
            )'
        )
            ->setParameter('userId', $userId);

        return $query->getSingleScalarResult();
    }



    public function getNotaMedia($nivelamentoId, $quizId, $intervalo, $nomeLead, $leadsCapturados = false)
    {
        $qb = $this->createQueryBuilder('resposta_quiz');

        $qb
            ->leftJoin('resposta_quiz.leadQuizEntity', 'lead_quiz')
            ->leftJoin('resposta_quiz.alunoEntity', 'aluno');

        if ($quizId) {
            $qb
                ->where('resposta_quiz.quizAtual = :quizId')
                ->setParameter('quizId', $quizId);
        }

        if ($leadsCapturados) {
            $qb
                ->andWhere('resposta_quiz.leadQuizEntity IS NOT NULL')
                ->orWhere('resposta_quiz.alunoEntity IS NOT NULL');
        }

        if ($intervalo) {
            $intervalo =  explode('-', $intervalo);

            $startDate = \DateTime::createFromFormat('d/m/Y', trim($intervalo[0]));
            $endDate = \DateTime::createFromFormat('d/m/Y', trim($intervalo[1]));

            $qb
                ->andWhere('resposta_quiz.created BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $startDate->format('Y-m-d 00:00:00'))
                ->setParameter('endDate', $endDate->format('Y-m-d 23:59:59'));
        }

        if ($nomeLead) {
            $qb
                ->andWhere('lead_quiz.nome LIKE :nomeLead')
                ->orWhere('aluno.nome LIKE :nomeLead')
                ->setParameter('nomeLead', '%' . $nomeLead . '%');
        }

        $qb->select('AVG(resposta_quiz.nota) AS mediaQuiz');
        

        return
            $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getQuantidadeRespostasIntervalo($userId, \DateTime $startDate, \DateTime $endDate)
    {
        $qb = $this->createQueryBuilder('resposta_quiz');

        $qb
            ->select('COUNT(resposta_quiz.id)')
            ->leftJoin('resposta_quiz.quizAtual', 'quiz')
            ->where('quiz.user = :userId')
            ->andWhere('resposta_quiz.created BETWEEN :startDate AND :endDate')
            ->setParameter('userId', $userId)
            ->setParameter('startDate', $startDate->format('Y-m-d 00:00:00'))
            ->setParameter('endDate', $endDate->format('Y-m-d 23:59:59'));

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getQuantidadeRespostasMes($userId)
    {
        $qb = $this->createQueryBuilder('resposta_quiz');

        $qb
            ->select("COUNT(resposta_quiz.id) AS qtdRespostas, DATE_FORMAT(resposta_quiz.created, '%m-%Y') AS yearMonth")
            ->leftJoin('resposta_quiz.quizAtual', 'quiz')
            ->where('quiz.user = :userId')
            ->groupBy('yearMonth')
            ->orderBy('yearMonth', 'ASC')
            ->setParameter('userId', $userId);

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function getQuantidadeRespostasQuizAndNIvelamento($userId)
    {
        $qb = $this->createQueryBuilder('resposta_quiz');

        $qb
            ->select('COUNT(resposta_quiz.id)')
            ->leftJoin('resposta_quiz.quizAtual', 'quiz')
            ->where('quiz.user = :userId')
            ->setParameter('userId', $userId);

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }
}
