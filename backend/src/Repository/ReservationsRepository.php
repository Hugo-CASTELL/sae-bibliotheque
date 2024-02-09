<?php

namespace App\Repository;

use App\Entity\Reservations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservations>
 *
 * @method Reservations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservations[]    findAll()
 * @method Reservations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservations::class);
    }

    public function cleanupReservations(): void {
        // Get reservation create 7 days ago
        $date = (new \DateTimeImmutable())->modify('-7 days');

        $reservations = $this->createQueryBuilder('r')
            ->andWhere('r.dateResa < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();

        foreach ($reservations as $reservation) {
            $this->_em->remove($reservation);
        }

        $this->_em->flush();
    }

    public function reservationsDispo(): QueryBuilder {
        $qb = $this->createQueryBuilder('r');

        $qb->join('App\Entity\Adherent', 'a')
            ->andWhere('r.adherent = a.id');

        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->in('a.id',
                    $this->_em->createQueryBuilder()
                        ->select('a1.id')
                        ->from('App\Entity\Adherent', 'a1')
                        ->join('a1.emprunts', 'e')
                        ->andWhere('e.dateRetour IS NULL')
                        ->groupBy('a1.id')
                        ->having('COUNT(e.id) < 5')
                        ->getDQL()
                ),
                $qb->expr()->notIn('a.id',
                    $this->_em->createQueryBuilder()
                        ->select('a2.id')
                        ->from('App\Entity\Adherent', 'a2')
                        ->join('a2.emprunts', 'e2')
                        ->andWhere('e2.dateRetour IS NULL')
                        ->getDQL()
                )
            )
        );

        return $qb;
    }

//    /**
//     * @return Reservations[] Returns an array of Reservations objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservations
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
