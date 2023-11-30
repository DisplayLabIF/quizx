<?php

namespace App\Entity;

use App\Entity\Base\ActiveTrait;
use App\Entity\Base\Endereco;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use App\Entity\Base\Notificacao;
use App\Entity\Curso\Curso;
use App\Entity\Curso\Matricula;
use App\Entity\Quiz\Quiz;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Entity\Escola;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="tipo", type="string")
 * @ORM\DiscriminatorMap({
 *      "ALUNO"    = "App\Entity\User\Aluno",
 *      "PROFESSOR"    = "App\Entity\User\Professor",
 *      "ADM_ESCOLA"    = "App\Entity\User\AdmEscola", 
 *      "ADM_QUIZ"    = "App\Entity\User\AdmQuiz",   
 * })
 * @Serializer\ExclusionPolicy("all")
 * @method string getUserIdentifier()
 */
abstract class User implements UserInterface
{
    use EntityTrait, ActiveTrait, IdTrait;

    const ALUNO = 'ALUNO';
    const PROFESSOR = 'PROFESSOR';
    const ADM_ESCOLA = 'ADM_ESCOLA';
    const ADM_QUIZ = 'ADM_QUIZ';


    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Serializer\Expose()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=180)
     * @Serializer\Expose()
     */
    private $nome;

    /**
     * @ORM\OneToMany(targetEntity=Curso::class, mappedBy="user")
     * @ORM\OrderBy({"nome" = "ASC"})
     */
    private $cursos;

    /**
     * @ORM\OneToMany(targetEntity=Matricula::class, mappedBy="user", cascade={"persist"})
     */
    private $matriculas;

    /**
     * @ORM\OneToMany(targetEntity=Quiz::class, mappedBy="user")
     * @ORM\OrderBy({"created" = "ASC"})
     */
    private $quizes;


    /**
     * @var string
     */
    protected $tipo;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $tipoUsuario;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $plano;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $qtdLogin;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime",  nullable=true)
     */
    private ?\DateTime $lastLogin = NULL;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Base\Endereco", cascade={"persist"}, mappedBy="user")
     */
    private $enderecos;

    /**
     * @var Notificacao
     * @ORM\OneToOne(targetEntity="App\Entity\Base\Notificacao", cascade={"persist"})
     * @ORM\JoinColumn(name="notificacao_id", referencedColumnName="id", nullable=true)
     */
    private $notificacao;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->active = true;
        $this->cursos = new ArrayCollection();
        $this->matriculas = new ArrayCollection();
        $this->quizes = new ArrayCollection();
        $this->qtdLogin = 0;
        $this->compras = new ArrayCollection();
        $this->enderecos = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password = null): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return User
     */
    public function setNome(string $nome): User
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCursos()
    {
        return $this->cursos;
    }

    /**
     * @param ArrayCollection $cursos
     * @return User
     */
    public function setCursos(ArrayCollection $cursos): User
    {
        $this->cursos = $cursos;
        return $this;
    }

    /**
     * @param Curso $curso
     * @return $this
     */
    public function addCurso(Curso $curso)
    {
        $this->cursos->add($curso);
        return $this;
    }

    /**
     * @param Curso $curso
     * @return $this
     */
    public function removeAdministrador(Curso $curso)
    {
        $this->administradores->removeElement($curso);
    }

    /**
     * @return ArrayCollection
     */
    public function getMatriculas()
    {
        return $this->matriculas;
    }

    /**
     * @param ArrayCollection $matriculas
     * @return User
     */
    public function setMatriculas(ArrayCollection $matriculas): User
    {
        $this->matriculas = $matriculas;
        return $this;
    }

    /**
     * @param Matricula $matricula
     * @return $this
     */
    public function addMatricula(Matricula $matricula)
    {
        $this->matriculas->add($matricula);
        return $this;
    }

    /**
     * @param Matricula $matricula
     * @return $this
     */
    public function removeMatricula(Curso $matricula)
    {
        $this->matriculas->removeElement($matricula);
        return $this;
    }

    abstract public function getTipo();

    /**
     * @return ArrayCollection
     */
    public function getQuizes()
    {
        return $this->quizes;
    }

    /**
     * @param ArrayCollection $quizes
     * @return User
     */
    public function setQuizes(ArrayCollection $quizes): User
    {
        $this->quizes = $quizes;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipoUsuario(): string
    {
        return $this->tipoUsuario;
    }

    /**
     * @param string $tipoUsuario
     * @return User
     */
    public function setTipoUsuario(string $tipoUsuario = null): User
    {
        $this->tipoUsuario = $tipoUsuario;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlano(): ?string
    {
        return $this->plano;
    }

    /**
     * @param string $plano
     * @return User
     */
    public function setPlano(string $plano = null): User
    {
        $this->plano = $plano;
        return $this;
    }

    public function setQtdLogin(?int $qtdLogin): self
    {
        $this->qtdLogin = $qtdLogin;
        return $this;
    }

    public function getQtdLogin(): ?int
    {
        return $this->qtdLogin;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $lastLogin
     */
    public function setLastLogin(\DateTime $lastLogin = NULL)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return Collection|Endereco[]
     */
    public function getEnderecos(): Collection
    {
        return $this->enderecos;
    }

    public function setEnderecos($enderecos)
    {
        $this->enderecos = $enderecos;
        return $this;
    }

    public function addEndereco(Endereco $endereco): void
    {
        if (!$this->enderecos->contains($endereco)) {
            $endereco->setUser($this);
            $this->enderecos->add($endereco);
        }
    }

    public function removeEndereco(Endereco $endereco): void
    {
        $this->enderecos->removeElement($endereco);
    }


    /**
     * @return Notificacao
     */
    public function getNotificacao(): ?Notificacao
    {
        return $this->notificacao;
    }

    public function setNotificacao(?Notificacao $notificacao): User
    {
        $this->notificacao = $notificacao;
        return $this;
    }
}
