<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Service;

use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;

class UserManager extends BaseUserManager
{
    public function findUsersBetweenDates(\DateTime $startDate, \DateTime $endDate, $sortField, $sortOrder = 'asc')
    {
        $endDate->setTime(23, 59, 59);

        return $this->repository->findUsersBetweenDates($startDate, $endDate, $sortField, $sortOrder);
    }

    public function findSortedUsers($sortField, $sortOrder = 'asc')
    {
        return $this->repository->findSortedUsers($sortField, $sortOrder);
    }
}
