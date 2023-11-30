<?php

namespace App\Repository;

use App\Entity\Curso\Turma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Turma|null find($id, $lockMode = null, $lockVersion = null)
 * @method Turma|null findOneBy(array $criteria, array $orderBy = null)
 * @method Turma[]    findAll()
 * @method Turma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurmaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Turma::class);
    }

    public function findTurmasDisponiveisMatricula()
    {
        // $turmas = $em->getRepository(Turma::class)->findBy(['curso' => $cursos, 'disponivelMatricula' => 1]);
        $query = $this->createQueryBuilder('t');
        $query = $query
            ->join('t.curso', 'curso')
            ->where('t.disponivelMatricula = 1')
            ->andWhere('curso.escola IS NOT NULL')
            ->orderBy('t.created', 'DESC')
            ->getQuery();

        return $query->getResult();
    }
}
