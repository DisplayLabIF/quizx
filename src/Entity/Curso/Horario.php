<?php

namespace App\Entity\Curso;

use App\Entity\Base\ActiveTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity()
 */
class Horario
{
    use EntityTrait, ActiveTrait, IdTrait;


    /**
     * @ORM\ManyToOne(targetEntity=Turma::class, inversedBy="horarios")
     */
    private $turma;

    /**
     * @var \DateTime
     * @ORM\Column(type="time")
     */
    private \DateTime $horaInicio;

    /**
     * @var \DateTime
     * @ORM\Column(type="time")
     */
    private \DateTime $horaTermino;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $dia;

    /**
     * @ORM\OneToMany(targetEntity=HorarioData::class, mappedBy="horario", cascade={"persist"})
     * @ORM\OrderBy({"dataAula" = "ASC"})
     */
    private $horarioDatas;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4();
        $this->active = true;
        $this->horarioDatas = new ArrayCollection();
    }

    public function getTurma(): ?Turma
    {
        return $this->turma;
    }

    public function setTurma(?Turma $turma): self
    {
        $this->turma = $turma;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * @param \DateTime $horaInicio
     */
    public function setHoraInicio(\DateTime $horaInicio)
    {
        $this->horaInicio = $horaInicio;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHoraTermino()
    {
        return $this->horaTermino;
    }

    /**
     * @param \DateTime $horaTermino
     */
    public function setHoraTermino(\DateTime $horaTermino)
    {
        $this->horaTermino = $horaTermino;
        return $this;
    }

    /**
     * @return string
     */
    public function getDia(): string
    {
        return $this->dia;
    }

    /**
     * @param string $dia
     * @return Horario
     */
    public function setDia(string $dia): Horario
    {
        $this->dia = $dia;
        return $this;
    }

    public function setHorarioDatas($horarioDatas)
    {
        $this->horarioDatas = $horarioDatas;
        return $this;
    }
    /**
     * @return Collection|HorarioData[]
     */
    public function getHorarioDatas(): Collection
    {
        $horarioDatasAux =  $this->horarioDatas;
        $this->horarioDatas = new arrayCollection();
        $horarioDatasAux->filter(function ($horarioData) {
            if ($horarioData->isActive())
                return $this->horarioDatas->add($horarioData);
        });
        return $this->horarioDatas;
    }

    public function addHorarioDatas(HorarioData $horarioData): void
    {
        if (!$this->horarioDatas->contains($horarioData)) {
            $horarioData->setHorario($this);
            $this->horarioDatas->add($horarioData);
        }
    }

    public function removeHorarioData(HorarioData $horarioData): void
    {
        $this->horarioDatas->removeElement($horarioData);
    }
}
