<?php

namespace App\Entity\Curso;

use App\Entity\Base\ActiveTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Entity\Curso\Aula;
use App\Entity\Curso\Matricula;


/**
 * @ORM\Entity()
 */
class AulaPresenca
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private bool $presente;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private bool $view;

    /**
     * @ORM\ManyToOne(targetEntity=Aula::class, inversedBy="aulaPresencas")
     */
    private $aula;

    /**
     * @ORM\ManyToOne(targetEntity=Matricula::class, inversedBy="aulaPresencas")
     */
    private $matricula;

    /**
     * @ORM\ManyToOne(targetEntity=HorarioData::class, inversedBy="aulaPresencas")
     */
    private $horarioData;


    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4();
        $this->active = true;
    }

    /**
     * @return bool
     */
    public function getPresente(): bool
    {
        return $this->presente;
    }

    /**
     * @param bool $presente
     */
    public function setPresente(bool $presente)
    {
        $this->presente = $presente;
        return $this;
    }

    /**
     * @return bool
     */
    public function getView(): bool
    {
        return $this->view;
    }

    /**
     * @param bool $view
     */
    public function setView(bool $view)
    {
        $this->view = $view;
        return $this;
    }

    public function getAula(): ?Aula
    {
        return $this->aula;
    }

    public function setAula(?Aula $aula): self
    {
        $this->aula = $aula;

        return $this;
    }

    public function getMatricula(): ?Matricula
    {
        return $this->matricula;
    }

    public function setMatricula(?Matricula $matricula): self
    {
        $this->matricula = $matricula;

        return $this;
    }

    public function getHorarioData(): ?HorarioData
    {
        return $this->horarioData;
    }

    public function setHorarioData(?HorarioData $horarioData): self
    {
        $this->horarioData = $horarioData;

        return $this;
    }
}
