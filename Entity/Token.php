<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Key
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    private $appName;

    /**
     * @var datetime
     */
    private $validUntil;

    /**
     * @var string
     */
    private $key;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return User
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getAppName()
    {
        return $this->appName;
    }

    /**
     * @param string $appName
     *
     * @return Key
     */
    public function setAppName($appName)
    {
        $this->appName = $appName;

        return $this;
    }

    /**
     * @return string
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * @param string $validUntil
     *
     * @return Key
     */
    public function setValidUntil($validUntil)
    {
        $this->validUntil = $validUntil;

        return $this;
    }
}
