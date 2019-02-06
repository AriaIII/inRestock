<?php

namespace App\Repository;

use App\Entity\HistoriqueStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistoriqueStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoriqueStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoriqueStock[]    findAll()
 * @method HistoriqueStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueStockRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistoriqueStock::class);
    }

        public function customHistory(){

            return $this->createQueryBuilder('h')
            ->join('h.stock', 's')
            ->join('s.product','p')
            ->join('h.user', 'u')
            ->join('h.modification', 'm')
            ->orderBy('h.createdAt', 'DESC')
            ->getQuery()

            ;

        }

    // /**
    //  * @return HistoriqueStock[] Returns an array of HistoriqueStock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistoriqueStock
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
