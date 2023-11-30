<?php

namespace App\Repository;

use App\Entity\Curso\Matricula;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Matricula|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matricula|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matricula[]    findAll()
 * @method Matricula[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatriculaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matricula::class);
    }

    public function getQuantidadeAlunos($userId)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(m.id)
            FROM App\Entity\Curso\Matricula m
            WHERE m.status = :status AND m.turma IN (
                SELECT t.id
                FROM App\Entity\Curso\Turma t
                WHERE t.curso in(
                    SELECT c.id
                    FROM App\Entity\Curso\Curso c
                    WHERE c.user = :userId
                )
            )'
        )
            ->setParameter('userId', $userId)
            ->setParameter('status', "FINALIZADA");

        return $query->getSingleScalarResult();
    }

    // /**
    //  * @return Matricula[] Returns an array of Matricula objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Matricula
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
