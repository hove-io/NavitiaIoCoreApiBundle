<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Entity;

use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;

class UserRepository extends EntityRepository
{
    public function findUsersBetweenDates(\DateTime $startDate, \DateTime $endDate, $sortField = 'id', $sortOrder = 'asc')
    {
        if (!in_array($sortOrder, array('asc', 'desc'))) {
            throw new InvalidArgumentException('Sort order argument must be \'asc\' or \'desc\'');
        }
        $query = $this->createQueryBuilder('u')
            ->where('u.createdAt >= :start_date')
            ->andWhere('u.createdAt <= :end_date')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->orderBy('u.'.$sortField, $sortOrder)
            ->getQuery();

        return $query->getResult();
    }

    public function findSortedUsers($sortField = 'id', $sortOrder = 'asc')
    {
        if (!in_array($sortOrder, array('asc', 'desc'))) {
            throw new InvalidArgumentException('Sort order argument must be \'asc\' or \'desc\'');
        }
        $query = $this->createQueryBuilder('u')
            ->orderBy('u.'.$sortField, $sortOrder)
            ->getQuery();

        return $query->getResult();
    }
}
