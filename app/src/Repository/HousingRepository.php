<?php

namespace App\Repository;

use App\Entity\Housing;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Housing>
 *
 * @method Housing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Housing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Housing[]    findAll()
 * @method Housing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HousingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Housing::class);
    }

    /**
     * Renvoie tout les logments de l'annonceur actuelle
     * 
     * @return Housing[]
     */
    public function findAllAssocietedAdvertiser(UserInterface $user): array
    {
        return $this->createQueryBuilder('h')
            ->innerJoin('h.user', 'user', Join::WITH, 'user.id = :id')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * Renvoie tout les logments publier
     * 
     * @return Housing[]
     */
    public function findAllPublished(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.is_published = 1')
            ->getQuery()
            ->getResult();
    }
    
    public function save(Housing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Housing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Housing[] Returns an array of Housing objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Housing
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
