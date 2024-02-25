<?php

namespace App\Repository;

use App\Entity\Mobile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @extends ServiceEntityRepository<Mobile>
 *
 * @method Mobile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mobile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mobile[]    findAll()
 * @method Mobile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mobile::class);
    }

    /**
     * The function `mobilePaginated` retrieves a paginated list of mobile items based on the specified
     * page and limit.
     * 
     * @param page The `` parameter represents the page number of the paginated results that you
     * want to retrieve. It is used to calculate the offset for fetching the data based on the page
     * number.
     * @param limit The `` parameter in the `mobilePaginated` function represents the maximum
     * number of results to be returned per page in a paginated list. It determines how many items will
     * be displayed on each page of the paginated list.
     * 
     * @return array An array of results from the query with pagination applied based on the provided
     * page number and limit.
     */
    public function mobilePaginated(int $page,int $limit): array
    {
        $list = $this->createQueryBuilder('b')
                ->setFirstResult(($page - 1) * $limit)
                ->setMaxResults($limit);
            
            return $list->getQuery()->getResult();
    }

//    /**
//     * @return Mobile[] Returns an array of Mobile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mobile
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
