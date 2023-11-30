<?php

namespace App\Entity\Curso;

use App\Repository\MatriculaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use App\Entity\Base\ActiveTrait;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Curso\Turma;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use App\Entity\Admin\Compra;
use App\Entity\Curso\AulaPresenca;
use App\Entity\User\Aluno;

/**
 * @ORM\Entity(repositoryClass=MatriculaRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class Matricula
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Serializer\Expose()
     */
    private \DateTime $data;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $status;



    /**
     * @ORM\ManyToOne(targetEntity=Turma::class, inversedBy="matriculas")
     */
    private $turma;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="matriculas")
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, length=6)
     * @Serializer\Expose()
     */
    private $rand;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Serializer\Expose()
     */
    private bool $isAluno;


    /**
     * @ORM\OneToMany(targetEntity=AulaPresenca::class, mappedBy="matricula", cascade={"persist"})
     */
    private $aulaPresencas;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Curso\Aula", inversedBy="matriculas")
     * @ORM\JoinTable(name="matricula_aulas_assistidas",
     *      joinColumns={@ORM\JoinColumn(name="matricula_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="aula_id", referencedColumnName="id")}
     * )
     */
    private $aulasAssistidas;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4();
        $this->active = true;
        $this->aulaPresencas = new ArrayCollection();
        $this->aulasAssistidas = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getData(): \DateTime
    {
        return $this->data;
    }

    /**
     * @param \DateTime $data
     */
    public function setData(\DateTime $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Matricula
     */
    public function setStatus(string $status): Matricula
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorPago(): float
    {
        return $this->valorPago;
    }
    /**
     * @param float $valorPago
     */
    public function setValorPago(float $valorPago)
    {
        $this->valorPago = $valorPago;
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

    public function getUser(): ?Aluno
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int
     */
    public function getRand(): ?int
    {
        return $this->rand;
    }

    /**
     * @param int $rand
     * @return Matricula
     */
    public function setRand(int $rand = null): ?Matricula
    {
        $this->rand = $rand;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsAluno(): bool
    {
        return $this->isAluno;
    }

    /**
     * @param bool $isAluno
     */
    public function setIsAluno(bool $isAluno)
    {
        $this->isAluno = $isAluno;
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
            $aulaPresenca->setMatricula($this);
            $this->aulaPresencas->add($aulaPresenca);
        }
    }

    /**
     * @return Collection|Aula[]
     */
    public function getAulasAssistidas(): Collection
    {
        return $this->aulasAssistidas;
    }

    public function setAulasAssistidas($aulasAssistidas)
    {
        $this->aulasAssistidas = $aulasAssistidas;
        return $this;
    }

    public function addAulaAssistida(Aula $aula): void
    {
        if (!$this->aulasAssistidas->contains($aula)) {
            $this->aulasAssistidas->add($aula);
        }
    }
}
