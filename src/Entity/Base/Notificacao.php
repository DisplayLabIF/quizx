<?php

namespace App\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity()
 */
class Notificacao
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    private string $leadCadastrado;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->active = true;
        $this->leadCadastrado = true;
    }

    /**
     * @return bool
     */
    public function getLeadCadastrado(): bool
    {
        return $this->leadCadastrado;
    }

    /**
     * @param bool $leadCadastrado
     */
    public function setLeadCadastrado(bool $leadCadastrado)
    {
        $this->leadCadastrado = $leadCadastrado;
        return $this;
    }
}
