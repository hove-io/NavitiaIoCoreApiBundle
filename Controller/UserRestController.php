<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CanalTP\NavitiaIoCoreApiBundle\Entity\Key;

class UserRestController extends Controller
{
    /**
     * @param string $username
     * @param string $_format
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function getUserAction($username, $_format)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

        if (!is_object($user)) {
            throw $this->createNotFoundException();
        }

        $keys = $this->get('canal_tp_tyr.api')->getUserKeys($user->getId());
        if (is_array($keys)) {
            $user->setKeys(Key::createFromObjects($keys));
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
    public function getUsersAction ($_format)
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        if (!is_array($users)) {
            throw $this->createNotFoundException();
        }

        $paginator = $this->container->get('knp_paginator');
        $pagination = $paginator->paginate($users);
        $pagination->setCustomParameters(
            array(
                'total_count'       => $pagination->getTotalItemCount(),
                'start_page'        => $pagination->getCurrentPageNumber(),
                'items_per_page'    => $pagination->getItemNumberPerPage()
            )
        );

        foreach ($pagination->getItems() as $user) {
            $keys = $this->get('canal_tp_tyr.api')->getUserKeys($user->getId());
            if (is_array($keys)) {
                $user->setKeys(Key::createFromObjects($keys));
            }
        }

        $data = $this->container->get('serializer')->serialize(
            $pagination,
            $_format
        );

        return new Response($data);
    }
}
