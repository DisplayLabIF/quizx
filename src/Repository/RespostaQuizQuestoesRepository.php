<?php

namespace App\Repository;

use App\Entity\Quiz\Questao;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\RespostaQuizQuestoes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RespostaQuizQuestoesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RespostaQuizQuestoes::class);
    }

    public function getRespostaQuestaoAluno(Quiz $quiz, Questao $questao, $aluno)
    {

        $qb = $this->createQueryBuilder('resposta_quiz_questoes');

        $qb
            ->join('resposta_quiz_questoes.respostaQuiz', 'respostaQuiz')
            ->where('resposta_quiz_questoes.quiz = :quiz')
            ->andWhere('resposta_quiz_questoes.questao = :questao')
            ->andWhere('respostaQuiz.aluno = :aluno')
            ->andWhere('respostaQuiz.finalizou = 0')
            ->setParameter('quiz', $quiz->getId())
            ->setParameter('questao', $questao->getId())
            ->setParameter('aluno', $aluno);

        return
            $qb
            ->getQuery()
            ->getResult();
    }

    public function getTotalPerguntasRespondias(Quiz $quiz, $aluno)
    {
        $qb = $this->createQueryBuilder('resposta_quiz_questoes');

        $qb
            ->select('COUNT(resposta_quiz_questoes.id)')
            ->join('resposta_quiz_questoes.respostaQuiz', 'respostaQuiz')
            ->where('resposta_quiz_questoes.quiz = :quiz')
            ->andWhere('respostaQuiz.aluno = :aluno')
            ->andWhere('(resposta_quiz_questoes.pulou = 0 OR resposta_quiz_questoes.pulou is null)')
            ->andWhere('respostaQuiz.finalizou = 0')
            ->setParameter('quiz', $quiz->getId())
            ->setParameter('aluno', $aluno);

        return
            $qb
            ->getQuery()
            ->getResult();
    }
}
