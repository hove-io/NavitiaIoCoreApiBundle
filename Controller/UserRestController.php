<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CanalTP\NavitiaIoCoreApiBundle\Entity\Token;
use CanalTP\NavitiaIoCoreApiBundle\Entity\BillingPlan;

class UserRestController extends Controller
{
    /**
     * @param string $id
     * @param string $_format
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function getUserAction($id, $_format)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(['id' => $id]);

        if (!is_object($user)) {
            throw $this->createNotFoundException();
        }

        if (!is_null($user->getTyrId())) {
            $userApiTyr = $this->get('canal_tp_tyr.api')->getUserById($user->getTyrId());

            if (!is_null($userApiTyr) && property_exists($userApiTyr, 'keys') && is_array($userApiTyr->keys)) {
                $user->setTokens(Token::createFromObjects($userApiTyr->keys));
            }

            if (!is_null($userApiTyr)
                && property_exists($userApiTyr, 'billing_plan')
                && is_object($userApiTyr->billing_plan)
            ) {
                $user->setBillingPlan(BillingPlan::createFromObject($userApiTyr->billing_plan));
            }
        }

        $data = $this->container->get('serializer')->serialize(
            array('users' => $user),
            $_format
        );

        return new Response($data);
    }

    /**
     * @param string $_format
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function getUsersAction(Request $request, $_format)
    {
        $userManager = $this->get('fos_user.user_manager');
        $sortField = $request->query->getAlpha('sort_by', 'id');
        $sortOrder = $request->query->getAlpha('sort_order', 'asc');
        $count = $request->query->getInt('count', 10);

        if ($request->query->has('start_date') && $request->query->has('end_date')) {
            $users = $userManager->findUsersBetweenDates(
                new \DateTime($request->query->get('start_date')),
                new \DateTime($request->query->get('end_date')),
                $sortField,
                $sortOrder
            );
        } else {
            $users = $userManager->findSortedUsers($sortField, $sortOrder);
        }

        if (!is_array($users)) {
            throw $this->createNotFoundException();
        }

        $paginator = $this->container->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            ($count > 0 ? $count : 1)
        );
        $pagination->setCustomParameters(
            array(
                'total_result'      => $pagination->getTotalItemCount(),
                'start_page'        => $pagination->getCurrentPageNumber(),
                'items_per_page'    => $pagination->getItemNumberPerPage()
            )
        );

        $data = $this->container->get('serializer')->serialize(
            $pagination,
            $_format
        );

        return new Response($data);
    }

    /**
     * Update some fields of an user.
     *
     * @param Request $request
     * @param int $id
     * @param string $_format
     */
    public function patchUserAction(Request $request, $id, $_format)
    {
        $userManager = $this->get('fos_user.user_manager');
        $serializer = $this->get('serializer');

        $user = $userManager->findUserBy(['id' => $id]);
        $patchFields = $serializer->deserialize($request->getContent(), 'array', $_format);

        if (isset($patchFields['billingPlanId'])) {
            $billingPlanId = $patchFields['billingPlanId'];
            unset($patchFields['billingPlanId']);

            $this->get('canal_tp_tyr.api')->updateUser($user->getTyrId(), [
                'billing_plan_id' => $billingPlanId,
            ]);
        }

        $this->patchEntity($user, $patchFields, [
            'firstName',
            'lastName',
            'website',
            'company',
        ]);

        $userManager->updateUser($user);
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
                    $processed []= $field;
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
