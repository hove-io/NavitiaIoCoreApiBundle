<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use CanalTP\NavitiaIoUserBundle\Service\TyrSynchronization;
use CanalTP\NavitiaIoUserBundle\Model\RegisterFolder;
use CanalTP\NavitiaIoUserBundle\Entity\User;

class UserManager extends BaseUserManager
{
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer,
        ObjectManager $om,
        $class,
        TyrSynchronization $tyrSynchronization
    ) {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $om, $class);

        $this->tyrSynchronization = $tyrSynchronization;
    }

    public function findUsersBetweenDates(\DateTime $startDate, \DateTime $endDate, $sortField, $sortOrder = 'asc')
    {
        $endDate->setTime(23, 59, 59);

        return $this->repository->findUsersBetweenDates($startDate, $endDate, $sortField, $sortOrder);
    }

    public function findSortedUsers($sortField, $sortOrder = 'asc')
    {
        return $this->repository->findSortedUsers($sortField, $sortOrder);
    }

    public function save(RegisterFolder $registerFolder)
    {
        $user = new User();

        $user->setUsername($registerFolder->getEmail());
        $user->setEmail($registerFolder->getEmail());
        $user->setPlainPassword($registerFolder->getPlainPassword());
        $user->setProjectName($registerFolder->getProjectName());
        $user->setProjectType($registerFolder->getProjectType());
        $user->setDeviceProject($registerFolder->getDeviceProject());
        $user->setFirstName($registerFolder->getFirstName());
        $user->setLastName($registerFolder->getLastName());
        $user->setCompany($registerFolder->getOrganization());
        $user->setCompany($registerFolder->getOrganization());
        $user->setActivity($registerFolder->getActivity());
        $user->setCountry($registerFolder->getCountry());
        $user->setNewsletter($registerFolder->getNewsletter());

        $this->tyrSynchronization->synchronizeTyr($user);
        $this->updateUser($user);

        return $user;
    }
}
