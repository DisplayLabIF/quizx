<?php

namespace App\Entity\Curso;

use App\Entity\Base\ActiveTrait;
use App\Repository\TurmaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Entity\Curso\Matricula;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use JMS\Serializer\Annotation as Serializer;
use phpDocumentor\Reflection\Types\Null_;

/**
 * @ORM\Entity(repositoryClass=TurmaRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class Turma
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     */
    private string $nome;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime",  nullable=true)
     * @Serializer\Expose()
     */
    private ?\DateTime $dataInicio = NULL;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime",  nullable=true)
     * @Serializer\Expose()
     */
    private ?\DateTime $dataTermino = NULL;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     * @Serializer\Expose()
     */
    private $limiteAlunos;

    /**
     * @ORM\ManyToOne(targetEntity=Curso::class, inversedBy="turmas")
     * @Serializer\Expose()
     */
    private $curso;


    /**
     * @ORM\OneToMany(targetEntity=Matricula::class, mappedBy="turma")
     */
    private $matriculas;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Expose()
     */
    private $observacao;

    /**
     * @ORM\OneToMany(targetEntity=Horario::class, mappedBy="turma", cascade={"persist"})
     */
    private $horarios;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantidadeAulas;

    /**
     * @ORM\OneToMany(targetEntity=HorarioData::class, mappedBy="turma")
     * @ORM\OrderBy({"dataAula" = "ASC"})
     */
    private $horarioDatas;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     */
    private ?string $formaPagamento = NULL;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4();
        $this->active = true;
        $this->matriculas = new ArrayCollection();
        $this->horarios = new ArrayCollection();
        $this->horarioDatas = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return Turma
     */
    public function setNome(string $nome): Turma
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * @param \DateTime|null $dataInicio
     */
    public function setDataInicio(\DateTime $dataInicio = NULL)
    {
        $this->dataInicio = $dataInicio;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDataTermino()
    {
        return $this->dataTermino;
    }

    /**
     * @param \DateTime|null $dataTermino
     */
    public function setDataTermino(\DateTime $dataTermino = NULL)
    {
        $this->dataTermino = $dataTermino;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimiteAlunos(): int
    {
        return $this->limiteAlunos;
    }
    /**
     * @param int $limiteAlunos
     */
    public function setLimiteAlunos(int $limiteAlunos)
    {
        $this->limiteAlunos = $limiteAlunos;
        return $this;
    }



    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): self
    {
        $this->curso = $curso;

        return $this;
    }


    public function setMatriculas($matriculas)
    {
        $this->matriculas = $matriculas;
        return $this;
    }
    /**
     * @return Collection|Matricula[]
     */
    public function getMatriculas(): Collection
    {
        return $this->matriculas;
    }

    /**
     * @return Collection|Matricula[]
     */
    public function getMatriculasFinalizadas(): Collection
    {
        $matricualAux =  $this->matriculas;
        $this->matriculas = new arrayCollection();
        $matricualAux->filter(function ($matricula) {
            if ($matricula->getStatus() === 'FINALIZADA')
                return $this->matriculas->add($matricula);
        });
        return $this->matriculas;
    }

    /**
     * @return string
     */
    public function getObservacao(): ?string
    {
        return $this->observacao;
    }
    /**
     * @param string $observacao
     */
    public function setObservacao(string $observacao)
    {
        $this->observacao = $observacao;
        return $this;
    }

    public function setHorarios($horarios)
    {
        $this->horarios = $horarios;
        return $this;
    }
    /**
     * @return Collection|Horario[]
     */
    public function getHorarios(): Collection
    {
        $horariosAux =  $this->horarios;
        $this->horarios = new arrayCollection();
        $horariosAux->filter(function ($horario) {
            if ($horario->isActive())
                return $this->horarios->add($horario);
        });
        return $this->horarios;
    }

    public function addHorario(Horario $horario): void
    {
        if (!$this->horarios->contains($horario)) {
            $horario->setTurma($this);
            $this->horarios->add($horario);
        }
    }

    public function removeHorario(Horario $horario): void
    {
        $this->horarios->removeElement($horario);
    }

    /**
     * @return int
     */
    public function getQuantidadeAulas(): ?int
    {
        return $this->quantidadeAulas;
    }
    /**
     * @param int $limiteAlunos
     */
    public function setQuantidadeAulas(int $quantidadeAulas)
    {
        $this->quantidadeAulas = $quantidadeAulas;
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
        return $this->horarioDatas->matching(
            Criteria::create()->where(
                Criteria::expr()->eq('active', true)
            )
        );
    }

    /**
     * @return String
     */
    public function getFormaPagamento(): string
    {
        return $this->formaPagamento;
    }
    /**
     * @param int $formaPagamento
     */
    public function setFormaPagamento(string $formaPagamento)
    {
        $this->formaPagamento = $formaPagamento;
        return $this;
    }
}
