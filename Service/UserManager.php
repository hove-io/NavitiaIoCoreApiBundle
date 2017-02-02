<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use CanalTP\NavitiaIoUserBundle\Service\TyrSynchronization;
use CanalTP\NavitiaIoUserBundle\Model\RegisterFolder;
use CanalTP\NavitiaIoUserBundle\Entity\User;

class UserManager extends BaseUserManager
{
    private $tyrApi;

    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer,
        ObjectManager $om,
        $class,
        TyrSynchronization $tyrSynchronization,
        $tyrApi
    ) {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $om, $class);

        $this->tyrApi = $tyrApi;
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

    public function updateDatabase(User $user, array $patchFields) {
        $oldEmail = $user->getEmail();
        $this->patchEntity($user, $patchFields, [
            'firstName',
            'lastName',
            'email',
            'website',
            'company',
            'comment',
        ]);

        if ($oldEmail != $user->getEmail() && count($this->findUserByEmail($user->getEmail())) >= 1) {
            throw new ConflictHttpException('"' . $user->getEmail() . '": Already exist');
        }
        $this->updateUser($user);
    }

    private function generateTyrField($fieldId, array &$fields, array &$patchFields) {
        if (isset($patchFields[$fieldId])) {
            $fields += [strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $fieldId)) => $patchFields[$fieldId]];
        }
    }

    public function updateTyrApi($tyrUserId, array &$patchFields) {
        $fields = [];
        $this->generateTyrField('billingPlanId', $fields, $patchFields);
        unset($patchFields['billingPlanId']);
        $this->generateTyrField('email', $fields, $patchFields);

        $this->tyrApi->updateUser($tyrUserId, $fields);
    }

    /**
     * Patch an entity with fields.
     *
     * @param mixed $baseEntity
     * @param array $patchFields
     */
    private function patchEntity($baseEntity, $patchFields, array $whitelist)
    {
        $processed = [];

        foreach ($whitelist as $field) {
            if (array_key_exists($field, $patchFields)) {
                if (method_exists($baseEntity, 'set'.$field)) {
                    $baseEntity->{'set'.$field}($patchFields[$field]);
                    $processed [] = $field;
                } else {
                    throw new UnprocessableEntityHttpException('Setter "set'.$field.'" not existing for this entity.');
                }
            }
        }

        $unprocessed = array_diff(array_keys($patchFields), $processed);

        if (count($unprocessed) > 0) {
            throw new UnprocessableEntityHttpException(
                'Field(s) '.implode(', ', $unprocessed).' cannot be updated or no setter.'
            );
        }
    }
}
