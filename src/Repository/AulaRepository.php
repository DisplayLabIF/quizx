<?php

namespace App\Repository;

use App\Entity\Curso\Aula;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AulaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aula::class);
    }

    public function getAulasAoVivo($userId)
    {
        $query = $this->createQueryBuilder('aula')
            ->join('aula.modulo', 'modulo')
            ->join('modulo.curso', 'curso')
            ->where('aula.createdBy = :user')
            ->orWhere('curso.user = :user')
            ->andWhere('aula.active = true')
            ->andWhere('aula.tipo = :tipoAula')
            ->setParameter('user', $userId)
            ->setParameter('tipoAula', 'ao_vivo')
            ->orderBy('aula.nome', 'ASC');

        return $query;
    }
}
