<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findUsersBetweenDates(\DateTime $startDate, \DateTime $endDate)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.createdAt >= :start_date')
            ->andWhere('u.createdAt <= :end_date')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->getQuery();

        return $query->getResult();
    }
}
