<?php

namespace App\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

trait ActiveTrait
{

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", options={"default": 1})
     */
    protected bool $active;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
        return $this;
    }
}
