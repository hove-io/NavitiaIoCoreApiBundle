<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Token
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
     * @var \DateTime
     */
    private $validUntil;

    /**
     * @var string
     */
    private $key;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Token
     */
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
     * @return Token
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
     * @return Token
     */
    public function setAppName($appName)
    {
        $this->appName = $appName;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * @param \DateTime $validUntil
     *
     * @return Token
     */
    public function setValidUntil(\DateTime $validUntil)
    {
        $this->validUntil = $validUntil;

        return $this;
    }

    /**
     * @param \stdClass $tokenData
     *
     * @return Token
     */
    public static function createFromObject(\stdClass $tokenData)
    {
        $token = new self();

        return $token
            ->setId($tokenData->id)
            ->setKey($tokenData->token)
            ->setAppName($tokenData->app_name)
        ;
    }

    /**
     * @param \stdClass $tokensData
     *
     * @return Token[]
     */
    public static function createFromObjects(array $tokensData)
    {
        $tokens = array();

        foreach ($tokensData as $tokenData) {
            $tokens []= self::createFromObject($tokenData);
        }

        return $tokens;
    }
}
