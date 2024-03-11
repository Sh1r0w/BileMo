<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }


    /**
     * The function `customerPaginated` retrieves a paginated list of customers using Doctrine's query
     * builder in PHP.
     * 
     * @param int page The `page` parameter represents the page number of the paginated results that
     * you want to retrieve. It is used to calculate the offset for fetching a specific page of
     * results.
     * @param int limit The `` parameter in the `customerPaginated` function specifies the
     * maximum number of results to be returned per page. It determines how many records will be
     * fetched from the database in a single page of paginated results.
     * 
     * @return array An array of customer entities is being returned, with pagination applied based on
     * the provided page and limit parameters.
     */
    public function customerPaginated(int $page,int $limit, UserInterface $user): array
    {
        $list = $this->createQueryBuilder('b')
        ->where('b.user = :userId')
        ->setParameter('userId', $user->getId())
        ->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();

    return $list;
    }
}
