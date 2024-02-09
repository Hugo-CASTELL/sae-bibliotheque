<?php

namespace App\Repository;

use App\Entity\Adherent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adherent>
 *
 * @method Adherent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adherent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adherent[]    findAll()
 * @method Adherent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherent::class);
    }

//    /**
//     * @return Adherent[] Returns an array of Adherent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function adherentDispo(): QueryBuilder {
        $qb = $this->createQueryBuilder('a');

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

    public function findHasEmprunts(): array
    {
        return $this->createQueryBuilder('a')
        ->leftJoin('a.emprunts', 'e') 
        ->where('e.id IS NOT NULL') 
        ->andWhere('e.dateRetour IS NULL')
        ->orderBy('a.nom', 'DESC')
        ->getQuery()
        ->getResult();
    }

    public function findHasEmpruntsEnRetard(): array
    {
        return $this->createQueryBuilder('a')
        ->innerJoin('a.emprunts', 'e') 
        ->where('e.id IS NOT NULL') 
        ->andWhere('e.dateRetour IS NULL')
        ->andWhere('e.dateEmprunt < :threeWeeksAgo')
        ->setParameter('threeWeeksAgo', new \DateTime('-3 weeks'))
        ->orderBy('a.nom', 'DESC')
        ->getQuery()
        ->getResult();
    }

    public function findHasEmpruntsATemps(): array
    {
        return $this->createQueryBuilder('a')
        ->innerJoin('a.emprunts', 'e') 
        ->where('e.id IS NOT NULL') 
        ->andWhere('e.dateRetour IS NULL')
        ->andWhere('e.dateEmprunt >= :threeWeeksAgo')
        ->setParameter('threeWeeksAgo', new \DateTime('-3 weeks'))
        ->orderBy('a.nom', 'DESC')
        ->getQuery()
        ->getResult();
    }
}
