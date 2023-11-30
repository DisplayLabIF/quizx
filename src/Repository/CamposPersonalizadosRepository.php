<?php

namespace App\Repository;

use App\Entity\Quiz\CamposPersonalizados;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CamposPersonalizadosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CamposPersonalizados::class);
    }

   
}
