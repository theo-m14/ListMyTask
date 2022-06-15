<?php

namespace App\Repository;

use App\Entity\Meeting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meeting>
 *
 * @method Meeting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meeting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meeting[]    findAll()
 * @method Meeting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meeting::class);
    }

    public function add(Meeting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Meeting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Meeting[] Returns an array of Meeting objects
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


    /**
     * @return Meeeting[]
     */
    public function searchMeetingByFilter($searchParameters) : array
    {
        $query =  $this->createQueryBuilder('m')
            ->andWhere('m.name LIKE :name')
            ->setParameter('name', '%' . $searchParameters['meetingName'] . '%')
            ->andWhere('m.place LIKE :place')
            ->setParameter('place', '%' . $searchParameters['meetingPlace'] . '%');
        
        if($searchParameters['meetingStartDate'] !== ''){
            $query->andWhere('m.startDate = :startDate')
                ->setParameter('startDate', $searchParameters['meetingStartDate']);
        }

        if($searchParameters['meetingEndDate'] !== ''){
            $query->andWhere('m.endDate = :endDate')
                ->setParameter('endDate', $searchParameters['meetingEndDate']);
        }

        if($searchParameters['meetingPriority'] !== ''){
            $query->andWhere('m.priority = :priority')
                ->setParameter('priority', $searchParameters['meetingPriority']);
        }
            return $query->orderBy('m.id', 'ASC')
                        ->getQuery()
                        ->getResult();
        
    }

//    public function findOneBySomeField($value): ?Meeting
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
