<?php

namespace App\Entity\Curso;

use App\Entity\Base\ActiveTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity()
 */
class HorarioData
{
    use EntityTrait, ActiveTrait, IdTrait;


    /**
     * @ORM\ManyToOne(targetEntity=Turma::class, inversedBy="horarioDatas")
     */
    private $turma;

    /**
     * @ORM\ManyToOne(targetEntity=Aula::class, inversedBy="horarioDatas")
     */
    private $aula;

    /**
     * @ORM\ManyToOne(targetEntity=Horario::class, inversedBy="horarioDatas")
     */
    private $horario;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $dataAula = NULL;

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
     * @ORM\OneToMany(targetEntity=AulaPresenca::class, mappedBy="horarioData", cascade={"persist"})
     */
    private $aulaPresencas;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $chamada;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4();
        $this->active = true;
        $this->chamada = false;
        $this->aulaPresencas = new ArrayCollection();
    }

    public function getHorario(): ?Horario
    {
        return $this->horario;
    }

    public function setHorario(?Horario $horario): self
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDataAula()
    {
        return $this->dataAula;
    }

    /**
     * @param \DateTime|null $dataAula
     */
    public function setDataAula(\DateTime $dataAula = null)
    {
        $this->dataAula = $dataAula;
        return $this;
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

    public function getAula(): ?Aula
    {
        return $this->aula;
    }

    public function setAula(?Aula $aula): self
    {
        $this->aula = $aula;

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

    public function setAulaPresencas($aulaPresencas)
    {
        $this->aulaPresencas = $aulaPresencas;
        return $this;
    }
    /**
     * @return Collection|AulaPresenca[]
     */
    public function getAulaPresencas(): Collection
    {
        return $this->aulaPresencas;
    }

    public function addAulaPresenca(AulaPresenca $aulaPresenca): void
    {
        if (!$this->aulaPresencas->contains($aulaPresenca)) {
            $aulaPresenca->setHorarioData($this);
            $this->aulaPresencas->add($aulaPresenca);
        }
    }

    /**
     * @return bool
     */
    public function getChamada(): bool
    {
        return $this->chamada;
    }

    /**
     * @param bool $chamada
     */
    public function setChamada(bool $chamada)
    {
        $this->chamada = $chamada;
        return $this;
    }
}
