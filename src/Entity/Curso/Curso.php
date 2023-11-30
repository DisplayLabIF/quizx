<?php

namespace App\Entity\Curso;

use App\Entity\Base\ActiveTrait;
use App\Repository\CursoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Entity\User;
use App\Entity\Escola;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Entity(repositoryClass=CursoRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class Curso
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     */
    private string $nome;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     */
    private ?string $descricao = NULL;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     */
    private ?string $endereco = NULL;

    /**
     * @ORM\OneToMany(targetEntity=Turma::class, mappedBy="curso")
     * @ORM\OrderBy({"nome" = "ASC"})
     */
    private $turmas;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Expose()
     */
    private $imagemCurso;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cursos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Escola::class, inversedBy="cursosDisponiveisVenda")
     * @Serializer\Expose()
     */
    private $escola;

    /**
     * @ORM\OneToMany(targetEntity=Material::class, mappedBy="curso")
     */
    private $materiais;

    /**
     * @ORM\OneToMany(targetEntity=Modulo::class, mappedBy="curso", cascade={"persist"})
     * @ORM\OrderBy({"created" = "ASC"})
     */
    private $modulos;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4();
        $this->turmas = new ArrayCollection();
        $this->active = true;
        $this->materiais = new ArrayCollection();
        $this->modulos = new ArrayCollection();
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
     * @return Curso
     */
    public function setNome(string $nome): Curso
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     * @return Curso
     */
    public function setDescricao(string $descricao): Curso
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndereco(): string
    {
        return $this->endereco;
    }

    /**
     * @param string $endereco
     * @return Curso
     */
    public function setEndereco(string $endereco): Curso
    {
        $this->endereco = $endereco;
        return $this;
    }

    public function setTurmas($turmas)
    {
        $this->turmas = $turmas;
        return $this;
    }
    /**
     * @return Collection|Turma[]
     */
    public function getTurmas(): Collection
    {
        return $this->turmas;
    }

    /**
     * @return string
     */
    public function getImagemCurso(): ?string
    {
        return $this->imagemCurso;
    }

    /**
     * @param string $imagemCurso
     * @return Curso
     */
    public function setImagemCurso(string $imagemCurso = null): Curso
    {
        $this->imagemCurso = $imagemCurso;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getEscola(): ?Escola
    {
        return $this->escola;
    }

    public function setEscola(?Escola $escola): self
    {
        $this->escola = $escola;

        return $this;
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
        return $this->materiais;
    }

    public function setModulos($modulos)
    {
        $this->modulos = $modulos;
        return $this;
    }
    /**
     * @return Collection|Modulo[]
     */
    public function getModulos(): Collection
    {
        return $this->modulos;
    }
}
