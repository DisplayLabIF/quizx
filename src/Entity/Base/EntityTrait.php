<?php

namespace App\Entity\Base;

use App\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait EntityTrait
 * @package App\Entity\Base
 */
trait EntityTrait
{

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deleted;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     */
    private $createdBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="last_updated_by", referencedColumnName="id")
     */
    private $lastUpdatedBy;

    /**
     * EntityTrait constructor.
     * @param \DateTime $created
     */
    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
    }

    /**
     * @return array
     */
    public function toArrayEntityTrait()
    {
        return [
            'updated' => $this->updated,
            'created' => $this->created
        ];
    }


    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return \DateTime
     */
    public function setUpdated()
    {
        $agora = new \DateTime();
        return $this->updated = $agora;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     * @return EntityTrait
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return User
     */
    public function getLastUpdatedBy()
    {
        return $this->lastUpdatedBy;
    }

    /**
     * @param User $lastUpdatedBy
     * @return EntityTrait
     */
    public function setLastUpdatedBy($lastUpdatedBy)
    {
        $this->lastUpdatedBy = $lastUpdatedBy;
        return $this;
    }

    /**
     * @param \DateTime|null $deleted
     * @return EntityTrait
     */
    public function setDeleted(\DateTime $deletedAt = null)
    {
        $this->deleted = $deletedAt;
        return $this;
    }
}
