<?php

namespace App\Repository;

use App\Entity\SendNewsletter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SendNewsletter|null find($id, $lockMode = null, $lockVersion = null)
 * @method SendNewsletter|null findOneBy(array $criteria, array $orderBy = null)
 * @method SendNewsletter[]    findAll()
 * @method SendNewsletter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SendNewsletterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SendNewsletter::class);
    }

    // /**
    //  * @return SendNewsletter[] Returns an array of SendNewsletter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SendNewsletter
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
