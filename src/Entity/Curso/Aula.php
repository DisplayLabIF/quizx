<?php

namespace App\Entity\Curso;

use App\Entity\Base\ActiveTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Curso\AulaPresenca;
use App\Entity\Quiz\Quiz;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AulaRepository")
 */
class Aula
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $nome;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $tipo;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $descricao;

    /**
     * @ORM\OneToMany(targetEntity=AulaPresenca::class, mappedBy="aula")
     */
    private $aulaPresencas;

    /**
     * @ORM\OneToMany(targetEntity=Material::class, mappedBy="aula", cascade={"persist"})
     * @ORM\OrderBy({"nome" = "ASC"})
     */
    private $materiais;

    /**
     * @ORM\ManyToOne(targetEntity=Modulo::class, inversedBy="aulas")
     */
    private $modulo;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $file;


    /**
     * @var Quiz
     * @ORM\manyToOne(targetEntity="App\Entity\Quiz\Quiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id", nullable=true)
     */
    private $quiz;

    /**
     * @ORM\OneToMany(targetEntity=HorarioData::class, mappedBy="aula")
     * @ORM\OrderBy({"dataAula" = "ASC"})
     */
    private $horarioDatas;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Curso\Matricula", mappedBy="aulasAssistidas")
     */
    private $matriculas;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4();
        $this->active = true;
        $this->aulaPresencas = new ArrayCollection();
        $this->materiais = new ArrayCollection();
        $this->horarioDatas = new ArrayCollection();
        $this->matriculas = new ArrayCollection();
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
     * @return Aula
     */
    public function setNome(string $nome): Aula
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }
    /**
     * @param string $tipo
     * @return Aula
     */
    public function setTipo(string $tipo): Aula
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao(): ?string
    {
        return $this->descricao;
    }
    public function setDescricao($descricao): self
    {
        $this->descricao = $descricao;

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

    public function setMateriais($materiais)
    {
        $this->materiais = $materiais;
        return $this;
    }
    /**
     * @return Collection|Material[]
     */
    public function getMateriais(): Collection
    {
        return $this->materiais->matching(
            Criteria::create()->where(
                Criteria::expr()->eq('active', true)
            )
        );
    }
    public function addHMaterial(Material $material): void
    {
        if (!$this->materiais->contains($material)) {
            $material->setAula($this);
            $this->materiais->add($material);
        }
    }
    public function removeMaterial(Material $material): void
    {
        $this->materiais->removeElement($material);
    }

    public function getModulo(): ?Modulo
    {
        return $this->modulo;
    }

    public function setModulo(?Modulo $modulo): self
    {
        $this->modulo = $modulo;

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
     * @return string
     */
    public function getFile(): ?string
    {
        return $this->file;
    }
    /**
     * @param string $file
     * @return Aula
     */
    public function setFile(?string $file): Aula
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return Quiz
     */
    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): Aula
    {
        $this->quiz = $quiz;
        return $this;
    }

    /**
     * @return Collection|Matricula[]
     */
    public function getMatriculas(): Collection
    {
        return $this->matriculas;
    }

    public function setMatriculas($matriculas)
    {
        $this->matriculas = $matriculas;
        return $this;
    }
}
