<?php

namespace App\Repository;

use App\Entity\Quiz\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function findQuiz($idOrCodigoQuiz)
    {

        $query = $this->createQueryBuilder('q')
            ->where('q.id = :idOrCodigoQuiz')
            ->orWhere('q.codigo = :idOrCodigoQuiz')
            ->setParameter('idOrCodigoQuiz', $idOrCodigoQuiz)
            ->getQuery();

        if (!$query->getOneOrNullResult()) {
            return null;
        }
        return $query->getOneOrNullResult();
    }

    public function getQuizes($userId, $tipoUsuario = '', $getQuizzesConfigurados = false, $getQuizzesPersonalizados = false)
    {
        $query = $this->createQueryBuilder('q');
        $query = $query
            ->select('q AS quiz')
            ->addSelect(
                '(
                    SELECT COUNT(resposta_quiz.id)
                    FROM App\Entity\Quiz\RespostaQuiz AS resposta_quiz
                    WHERE resposta_quiz.quizAtual = q.id AND resposta_quiz.finalizou = 1
                ) AS qtd_respostas'
            )
            ->where('q.active = 1')
            ->andWhere('q.pesquisa = 0');

        if ($getQuizzesConfigurados === true)
            $query->andWhere('q.configuracaoQuiz IS NOT NULL');

        if ($getQuizzesPersonalizados === true)
            $query->andWhere('q.personalizacaoQuiz IS NOT NULL');

        if ($tipoUsuario === 'ADM_QUIZ') {
            $query->orderBy('q.created', 'DESC');
        } else {
            $query
                ->andWhere('q.user = :userId')
                ->setParameter('userId', $userId)
                ->orderBy('q.nome', 'ASC');
        }

        return $query
            ->getQuery()
            ->getResult();
    }

    public function quizLeadExists($quizId, $leadId)
    {
        $query = $this->createQueryBuilder('q');
        $query = $query
            ->leftJoin('q.leadsQuiz', 'lead_quiz')
            ->where('q.id = :quizId')
            ->andWhere('lead_quiz.id = :leadId')
            ->setParameter('quizId', $quizId)
            ->setParameter('leadId', $leadId)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function qtdQuizesCriados()
    {
        $dateNow = new \DateTime('now');

        $ultimos7Dias = \DateTime::createFromFormat('d/m/Y', $dateNow->format('d/m/Y'))->modify('-7 day');
        $ultimos15Dias = \DateTime::createFromFormat('d/m/Y', $dateNow->format('d/m/Y'))->modify('-15 day');
        $ultimos30Dias = \DateTime::createFromFormat('d/m/Y', $dateNow->format('d/m/Y'))->modify('-30 day');
        $ultimos60Dias = \DateTime::createFromFormat('d/m/Y', $dateNow->format('d/m/Y'))->modify('-60 day');

        return $this->createQueryBuilder('q')
            ->select('
                (COUNT(q.id)) AS total,
                (SELECT COUNT(quiz.id) FROM App\Entity\Quiz\Quiz AS quiz WHERE quiz.active = 1 AND quiz.created BETWEEN :ultimos7Dias AND :dateNow) AS ultimos_7_dias,
                (SELECT COUNT(quiz2.id) FROM App\Entity\Quiz\Quiz AS quiz2 WHERE quiz2.active = 1 AND quiz2.created BETWEEN :ultimos15Dias AND :dateNow) AS ultimos_15_dias,
                (SELECT COUNT(quiz3.id) FROM App\Entity\Quiz\Quiz AS quiz3 WHERE quiz3.active = 1 AND quiz3.created BETWEEN :ultimos30Dias AND :dateNow) AS ultimos_30_dias,
                (SELECT COUNT(quiz4.id) FROM App\Entity\Quiz\Quiz AS quiz4 WHERE quiz4.active = 1 AND quiz4.created BETWEEN :ultimos60Dias AND :dateNow) AS ultimos_60_dias
            ')
            ->where('q.active = 1')
            ->setParameter('dateNow', $dateNow->format('Y-m-d 23:59:59'))
            ->setParameter('ultimos7Dias', $ultimos7Dias->format('Y-m-d 00:00:00'))
            ->setParameter('ultimos15Dias', $ultimos15Dias->format('Y-m-d 00:00:00'))
            ->setParameter('ultimos30Dias', $ultimos30Dias->format('Y-m-d 00:00:00'))
            ->setParameter('ultimos60Dias', $ultimos60Dias->format('Y-m-d 00:00:00'))
            ->getQuery()
            ->getResult()[0];
    }
}
