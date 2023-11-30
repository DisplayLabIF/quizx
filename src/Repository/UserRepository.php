<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function qtdCadastros()
    {
        $dateNow = new \DateTime('now');

        $ultimos7Dias = \DateTime::createFromFormat('d/m/Y', $dateNow->format('d/m/Y'))->modify('-7 day');
        $ultimos15Dias = \DateTime::createFromFormat('d/m/Y', $dateNow->format('d/m/Y'))->modify('-15 day');
        $ultimos30Dias = \DateTime::createFromFormat('d/m/Y', $dateNow->format('d/m/Y'))->modify('-30 day');
        $ultimos60Dias = \DateTime::createFromFormat('d/m/Y', $dateNow->format('d/m/Y'))->modify('-60 day');

        return $this->createQueryBuilder('u')
            ->select('
                (COUNT(u.id)) AS total,
                (SELECT COUNT(user.id) FROM App\Entity\User AS user WHERE user.active = 1 AND user.created BETWEEN :ultimos7Dias AND :dateNow) AS ultimos_7_dias,
                (SELECT COUNT(user2.id) FROM App\Entity\User AS user2 WHERE user2.active = 1 AND user2.created BETWEEN :ultimos15Dias AND :dateNow) AS ultimos_15_dias,
                (SELECT COUNT(user3.id) FROM App\Entity\User AS user3 WHERE user3.active = 1 AND user3.created BETWEEN :ultimos30Dias AND :dateNow) AS ultimos_30_dias,
                (SELECT COUNT(user4.id) FROM App\Entity\User AS user4 WHERE user4.active = 1 AND user4.created BETWEEN :ultimos60Dias AND :dateNow) AS ultimos_60_dias
            ')
            ->where('u.active = 1')
            ->setParameter('dateNow', $dateNow->format('Y-m-d 23:59:59'))
            ->setParameter('ultimos7Dias', $ultimos7Dias->format('Y-m-d 00:00:00'))
            ->setParameter('ultimos15Dias', $ultimos15Dias->format('Y-m-d 00:00:00'))
            ->setParameter('ultimos30Dias', $ultimos30Dias->format('Y-m-d 00:00:00'))
            ->setParameter('ultimos60Dias', $ultimos60Dias->format('Y-m-d 00:00:00'))
            ->getQuery()
            ->getResult()[0];
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
