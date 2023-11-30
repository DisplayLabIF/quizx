<?php

namespace App\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 */
trait IdTrait
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", unique=true)
     * @Serializer\Expose()
     */
    private $id;

    /**
     * EntityTrait constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->id = Uuid::v4()->toRfc4122();
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
