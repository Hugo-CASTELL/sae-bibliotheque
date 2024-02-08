<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function search($param): array
    {
        $queryBuilder = $this->createQueryBuilder('l');
        
        if (isset($param['titre'])) {
            $queryBuilder
                ->andWhere('l.titre LIKE :titre')
                ->setParameter('titre', '%' . $param['titre'] . '%');
        }

        if (isset($param['langue'])) {
            $queryBuilder
                ->andWhere('l.langue = :langue')
                ->setParameter('langue', $param['langue']);
        }

        if (isset($param['before_date'])) {
            $queryBuilder
                ->andWhere('l.dateSortie < :before_date')
                ->setParameter('before_date', $param['before_date']);
        }

        if (isset($param['after_date'])) {
            $queryBuilder
                ->andWhere('l.dateSortie > :after_date')
                ->setParameter('after_date', $param['after_date']);
        }

        if (isset($param['categorie_nom'])) {
            $queryBuilder
                ->join('l.categories', 'c')
                ->andWhere('c.nom = :categorie_nom')
                ->setParameter('categorie_nom', $param['categorie_nom']);
        }

        if (isset($param['auteurs_nom'])) {
            $queryBuilder
                ->join('l.auteurs', 'a')
                ->andWhere('a.nom = :auteurs_nom')
                ->setParameter('auteurs_nom', $param['auteurs_nom']);
        }

        if (isset($param['auteurs_prenom'])) {
            $queryBuilder
                ->join('l.auteurs', 'a')
                ->andWhere('a.prenom = :auteurs_prenom')
                ->setParameter('auteurs_prenom', $param['auteurs_prenom']);
        }

        if (isset($param['auteurs_nationalite'])) {
            $queryBuilder
                ->join('l.auteurs', 'a')
                ->andWhere('a.nationalite = :auteurs_nationalite')
                ->setParameter('auteurs_nationalite', $param['auteurs_nationalite']);
        }

        if (isset($param['sort'])) {
            $sort = $param['sort'];
            $queryBuilder
                ->orderBy('l.' . $sort[0], $sort[1]);
        }

        if (isset($param['offset'])) {
            $queryBuilder
                ->setFirstResult($param['offset']);
        }

        if (isset($param['limit'])) {
            $queryBuilder
                ->setMaxResults($param['limit']);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function livreDispo(): QueryBuilder {
        $qb = $this->createQueryBuilder('l');

        $qb->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->notIn(
                            'l.id',
                            $this->_em->createQueryBuilder()
                                ->select('l2.id')
                                ->from('App\Entity\Emprunt', 'e2')
                                ->join('App\Entity\Livre', 'l2')
                                ->andWhere('e2.livre = l2')
                                ->andWhere('e2.dateRetour IS NULL')
                                ->getDQL()),
                        $qb->expr()->notIn(
                            'l.id',
                            $this->_em->createQueryBuilder()
                                ->select('l3.id')
                                ->from('App\Entity\Emprunt', 'e3')
                                ->join('App\Entity\Livre', 'l3')
                                ->andWhere('e3.livre = l3')
                                ->getDQL()
                        )
                    ),
                    $qb->expr()->notIn(
                        'l.id',
                        $this->_em->createQueryBuilder()
                            ->select('l4.id')
                            ->from('App\Entity\Reservations', 'r4')
                            ->join('App\Entity\Livre', 'l4')
                            ->andWhere('r4.livre = l4')
                            ->getDQL()
                    )
                )
            );

        return $qb;
    }

//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
