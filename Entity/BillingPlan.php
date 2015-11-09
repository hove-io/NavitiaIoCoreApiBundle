<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Entity;

class BillingPlan
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $maxRequestCount;

    /**
     * @var int
     */
    private $maxObjectCount;

    /**
     * @var boolean
     */
    private $default;

    /**
     * @var int
     */
    private $endPointId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMaxRequestCount()
    {
        return $this->maxRequestCount;
    }

    /**
     * @return int
     */
    public function getMaxObjectCount()
    {
        return $this->maxObjectCount;
    }

    /**
     * @return bool
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return int
     */
    public function getEndPointId()
    {
        return $this->endPointId;
    }

    /**
     * @param int $id
     *
     * @return BillingPlan
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return BillingPlan
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param int $maxRequestCount
     *
     * @return BillingPlan
     */
    public function setMaxRequestCount($maxRequestCount)
    {
        $this->maxRequestCount = $maxRequestCount;

        return $this;
    }

    /**
     * @param int $maxObjectCount
     *
     * @return BillingPlan
     */
    public function setMaxObjectCount($maxObjectCount)
    {
        $this->maxObjectCount = $maxObjectCount;

        return $this;
    }

    /**
     * @param bool $default
     *
     * @return BillingPlan
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @param int $endPointId
     *
     * @return BillingPlan
     */
    public function setEndPointId($endPointId)
    {
        $this->endPointId = $endPointId;

        return $this;
    }

    /**
     * @param \stdClass $billingPlanData
     *
     * @return BillingPlan
     */
    public static function createFromObject(\stdClass $billingPlanData)
    {
        $billingPlan = new self();

        return $billingPlan
            ->setId($billingPlanData->id)
            ->setName($billingPlanData->name)
            ->setMaxObjectCount($billingPlanData->max_object_count)
            ->setMaxRequestCount($billingPlanData->max_request_count)
            ->setDefault($billingPlanData->default)
        ;
    }
}
