<?php

namespace App\Repository;

use App\Entity\Quiz\Questao;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuestaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questao::class);
    }

    public function findQuestao($nivel = null, $subAreaConhecimento = null)
    {

        $qb = $this->createQueryBuilder('questao');

        if ($nivel) {
            $qb->where('questao.nivel = :nivel')
                ->setParameter('nivel', $nivel);
        }

        if ($subAreaConhecimento) {
            $qb->andWhere('questao.areaConhecimento = :areaConhecimento')
                ->setParameter('areaConhecimento', $subAreaConhecimento);
        }

        return
            $qb
            ->getQuery()
            ->getResult();
    }

    public function getQuestaoAleatoria($sala)
    {
        $qb = $this->createQueryBuilder('questao');
        $qb
            ->join('questao.quizes', 'quizes')
            ->where('quizes.codigo = :sala')
            ->setParameter('sala', $sala);

        $qb
            ->setMaxResults(1)
            ->getQuery()
            ->setMaxResults(1)
            ->getResult();
    }

    public function getQuestao($quizId, $aluno, $aleatoria = true, $buscaPulada = false)
    {

        $qb = $this->createQueryBuilder('questao');

        $qbResposta = $this->_em->createQueryBuilder();
        $qbRespondida = $this->_em->createQueryBuilder();

        $qbRespondida->select('questao3.id')
            ->from('App\Entity\Quiz\RespostaQuizQuestoes', 'resposta_quiz_questoes2')
            ->join('resposta_quiz_questoes2.quiz', 'quiz2')
            ->join('resposta_quiz_questoes2.respostaQuiz', 'resposta_quiz2')
            ->join('resposta_quiz_questoes2.questao', 'questao3')
            ->where('quiz2.id = :quizId')
            ->andWhere('resposta_quiz2.aluno = :aluno')
            ->andWhere('resposta_quiz2.finalizou = 0')
            ->andWhere('resposta_quiz_questoes2.pulou = 0');

        if ($buscaPulada) {
            $qbResposta->select('questao2.id')
                ->from('App\Entity\Quiz\RespostaQuizQuestoes', 'resposta_quiz_questoes')
                ->join('resposta_quiz_questoes.quiz', 'quiz')
                ->join('resposta_quiz_questoes.respostaQuiz', 'resposta_quiz')
                ->join('resposta_quiz_questoes.questao', 'questao2')
                ->where('quiz.id = :quizId')
                ->andWhere('resposta_quiz.aluno = :aluno')
                ->andWhere('resposta_quiz.finalizou = 0')
                ->andWhere('resposta_quiz_questoes.pulou = 1');
        } else {
            $qbResposta->select('questao2.id')
                ->from('App\Entity\Quiz\RespostaQuizQuestoes', 'resposta_quiz_questoes')
                ->join('resposta_quiz_questoes.quiz', 'quiz')
                ->join('resposta_quiz_questoes.respostaQuiz', 'resposta_quiz')
                ->join('resposta_quiz_questoes.questao', 'questao2')
                ->where('quiz.id = :quizId')
                ->andWhere('resposta_quiz.aluno = :aluno')
                ->andWhere('resposta_quiz.finalizou = 0')
                //->andWhere('resposta_quiz_questoes.pulou = 0 or resposta_quiz_questoes.pulou is null')
                //->andWhere('resposta_quiz_questoes.pulou is null or resposta_quiz_questoes.pulou = 1')
            ;
        }


        $qb
            ->join('questao.quizes', 'quizes')
            ->where('quizes.id = :quizId')
            ->andWhere('questao.active = 1')
            ->setParameter('quizId', $quizId)
            ->setParameter('aluno', $aluno);

        if ($buscaPulada) {
            $qb->andWhere($qb->expr()->in('questao.id', $qbResposta->getDQL()));
            $qb->andWhere($qb->expr()->notIn('questao.id', $qbRespondida->getDQL()));
        } else {
            $qb->andWhere($qb->expr()->notIn('questao.id', $qbResposta->getDQL()));
        }

        if ($aleatoria) {
            return
                $qb
                ->setMaxResults(1)
                ->addSelect('RAND() as HIDDEN rand')
                ->addOrderBy('rand')
                ->getQuery()
                ->setMaxResults(1)
                ->getResult();
        }

        return
            $qb
            ->setMaxResults(1)
            ->addOrderBy('questao.numeroQuestao', 'ASC')
            ->getQuery()
            ->setMaxResults(1)
            ->getResult();
    }

    public function findQuestaoQuiz($questao, $quizId)
    {
        $query = $this->createQueryBuilder('q');
        $query = $query
            ->leftJoin('q.quizes', 'quiz')
            ->where('q.id = :questao')
            ->andWhere('quiz.id = :quizId')
            ->setParameter('questao', strval($questao))
            ->setParameter('quizId', $quizId)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
