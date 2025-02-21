<?php

namespace App\Dispenser\Infrastructure\Persistence\Doctrine\Repository;

use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dispenser>
 *
 * @method Dispenser|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dispenser|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dispenser[]    findAll()
 * @method Dispenser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispenserDoctrineRepository extends ServiceEntityRepository implements DispenserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dispenser::class);
    }

    public function save(Dispenser $dispenser): void
    {
        $this->getEntityManager()->persist($dispenser);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return Dispenser[] Returns an array of Dispenser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Dispenser
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
