<?php

namespace CanalTP\NavitiaIoCoreApiBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class User extends BaseUser
{
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", nullable=true)
     */
    protected $company;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", nullable=true)
     */
    protected $website;

    /**
     * @var Token[]
     */
    protected $tokens = [];

    /**
     * @var BillingPlan
     */
    protected $billingPlan;

    /**
     * @var int
     *
     * @ORM\Column(name="tyr_id", type="integer", nullable=true, unique=true)
     */
    protected $tyrId;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    protected $comment;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CanalTP\NavitiaIoCoreApiBundle\Entity\Tag")
     * @ORM\JoinTable(name="users_tags",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->tags = new ArrayCollection();
    }

    /**
     * @return Token[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @param Token[] $tokens
     *
     * @return User
     */
    public function setTokens(array $tokens)
    {
        $this->tokens = $tokens;

        return $this;
    }

    /**
     * @param Token $token
     *
     * @return User
     */
    public function addToken(Token $token)
    {
        $this->tokens[] = $token;

        return $this;
    }

    /**
     * Get BillingPlan.
     *
     * @return User
     */
    public function getBillingPlan()
    {
        return $this->billingPlan;
    }

    /**
     * @param BillingPlan $billingPlan
     *
     * @return User
     */
    public function setBillingPlan($billingPlan)
    {
        $this->billingPlan = $billingPlan;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Gets the value of company.
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Sets the value of company.
     *
     * @param string $company the company
     *
     * @return self
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Gets the value of tyrId.
     *
     * @return int
     */
    public function getTyrId()
    {
        return $this->tyrId;
    }

    /**
     * Set tyrId.
     *
     * @param int $tyrId
     *
     * @return User
     */
    public function setTyrId($tyrId)
    {
        $this->tyrId = $tyrId;

        return $this;
    }

    /**
     * Gets the value of website.
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Sets the value of website.
     *
     * @param string $website the website
     *
     * @return self
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Gets the value of comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the value of comment.
     *
     * @param string $comment
     *
     * @return self
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Gets the value of tags.
     *
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Sets the value of tags.
     *
     * @return self
     */
    public function setTags(ArrayCollection $tags)
    {
        $this->tags = $tags;

        return $this;
    }
}
