<?php

namespace App\Repository;

use App\Entity\Curso\Modulo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Modulo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modulo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modulo[]    findAll()
 * @method Modulo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modulo::class);
    }
}
