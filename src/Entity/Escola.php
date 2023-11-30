<?php

namespace App\Entity;

use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use App\Entity\User;
use App\Entity\Curso\Curso;
use App\Entity\User\AdmEscola;
use App\Entity\User\Professor;
use App\Repository\EscolaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=EscolaRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class Escola
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
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     */
    private string $url;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $cnpj;

    /**
     * @ORM\OneToMany(targetEntity=Curso::class, mappedBy="escola", cascade={"persist"})
     * @ORM\OrderBy({"nome" = "ASC"})
     */
    private $cursosDisponiveisVenda;

    /**
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $descricao;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $banco;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $agencia;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $conta;

    /**
     * @ORM\OneToMany(targetEntity=AdmEscola::class, mappedBy="escola")
     */
    private $administradores;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $braspagMerchantId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $rdStationApiToken;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $rdStationClientId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $rdStationClientSecret;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $rdStationAccessToken;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $rdStationRefreshToken;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $rdStationCode;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $rdStationEventoConversao;


    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $endereco;

    /**
     * @ORM\OneToMany(targetEntity=Professor::class, mappedBy="escola")
     */
    private $professores;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $remetente;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $imagem;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4();
        $this->active = true;
        $this->administradores = new ArrayCollection();
        $this->cursosDisponiveisVenda = new ArrayCollection();
        $this->professores = new ArrayCollection();
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
     * @return User
     */
    public function setNome(string $nome): Escola
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
    /**
     * @param string $url
     * @return User
     */
    public function setUrl(string $url): Escola
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getCnpj(): string
    {
        return $this->cnpj;
    }
    /**
     * @param string $cnpj
     * @return User
     */
    public function setCnpj(string $cnpj): Escola
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCursosDisponiveisVenda()
    {
        return $this->cursosDisponiveisVenda;
    }

    /**
     * @param ArrayCollection $cursosDisponiveisVenda
     * @return Escola
     */
    public function setCursosDisponiveisVenda(ArrayCollection $cursosDisponiveisVenda): Escola
    {
        $this->cursosDisponiveisVenda = $cursosDisponiveisVenda;
        return $this;
    }

    /**
     * @param Curso $curso
     * @return $this
     */
    public function addCursoDisponivelVenda(Curso $curso)
    {
        if (!$this->cursosDisponiveisVenda->contains($curso)) {
            $curso->setEscola($this);
            $this->cursosDisponiveisVenda->add($curso);
        }
        return $this;
    }

    /**
     * @param Curso $curso
     * @return $this
     */
    public function removeCursoDisponivelVenda(Curso $curso)
    {
        $this->cursosDisponiveisVenda->removeElement($curso);
        return $this;
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

    public function getBanco(): ?string
    {
        return $this->banco;
    }

    public function setBanco(string $banco): self
    {
        $this->banco = $banco;

        return $this;
    }

    public function getAgencia(): ?string
    {
        return $this->agencia;
    }

    public function setAgencia(string $agencia): self
    {
        $this->agencia = $agencia;

        return $this;
    }

    public function getConta(): ?string
    {
        return $this->conta;
    }

    public function setConta(string $conta): self
    {
        $this->conta = $conta;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAdministradores()
    {
        return $this->administradores;
    }

    /**
     * @param ArrayCollection $administradores
     * @return Escola
     */
    public function setAdministradores(ArrayCollection $administradores): Escola
    {
        $this->administradores = $administradores;
        return $this;
    }

    /**
     * @param AdmEscola $administradorEscola
     * @return $this
     */
    public function addAdministrador(AdmEscola $administradorEscola)
    {
        $this->administradores->add($administradorEscola);
        return $this;
    }

    /**
     * @param AdmEscola $administradorEscola
     * @return $this
     */
    public function removeAdministrador(AdmEscola $administradorEscola)
    {
        $this->administradores->removeElement($administradorEscola);
    }

    /**
     * @return string
     */
    public function getBraspagMerchantId(): ?string
    {
        return $this->braspagMerchantId;
    }
    public function setBraspagMerchantId($braspagMerchantId): self
    {
        $this->braspagMerchantId = $braspagMerchantId;

        return $this;
    }

    /**
     * @return string
     */
    public function getRdStationApiToken(): ?string
    {
        return $this->rdStationApiToken;
    }
    public function setRdStationApiToken($rdStationApiToken): self
    {
        $this->rdStationApiToken = $rdStationApiToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getRdStationClientId(): ?string
    {
        return $this->rdStationClientId;
    }
    public function setRdStationClientId($rdStationClientId): self
    {
        $this->rdStationClientId = $rdStationClientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getRdStationClientSecret(): ?string
    {
        return $this->rdStationClientSecret;
    }
    public function setRdStationClientSecret($rdStationClientSecret): self
    {
        $this->rdStationClientSecret = $rdStationClientSecret;

        return $this;
    }

    /**
     * @return string
     */
    public function getRdStationAccessToken(): ?string
    {
        return $this->rdStationAccessToken;
    }
    public function setRdStationAccessToken($rdStationAccessToken): self
    {
        $this->rdStationAccessToken = $rdStationAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getRdStationRefreshToken(): ?string
    {
        return $this->rdStationRefreshToken;
    }
    public function setRdStationRefreshToken($rdStationRefreshToken): self
    {
        $this->rdStationRefreshToken = $rdStationRefreshToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getRdStationCode(): ?string
    {
        return $this->rdStationCode;
    }
    public function setRdStationCode($rdStationCode): self
    {
        $this->rdStationCode = $rdStationCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getRdStationEventoConversao(): ?string
    {
        return $this->rdStationEventoConversao;
    }
    public function setRdStationEventoConversao($rdStationEventoConversao): self
    {
        $this->rdStationEventoConversao = $rdStationEventoConversao;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndereco(): ?string
    {
        return $this->endereco;
    }
    public function setEndereco($endereco): self
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProfessores()
    {
        return $this->professores;
    }

    /**
     * @param ArrayCollection $professores
     * @return Escola
     */
    public function setProfessores(ArrayCollection $professores): Escola
    {
        $this->professores = $professores;
        return $this;
    }

    /**
     * @param Professor $professor
     * @return $this
     */
    public function addProfessor(Professor $professor)
    {
        $this->professores->add($professor);
        return $this;
    }

    /**
     * @param Professor $professor
     * @return $this
     */
    public function removeProfessor(Professor $professor)
    {
        $this->professores->removeElement($professor);
    }

    /**
     * @return string
     */
    public function getRemetente(): ?string
    {
        return $this->remetente;
    }
    public function setRemetente($remetente): self
    {
        $this->remetente = $remetente;

        return $this;
    }

    /**
     * @return string
     */
    public function getImagem(): ?string
    {
        return $this->imagem;
    }

    /**
     * @param string $imagem
     * @return Escola
     */
    public function setImagem(string $imagem = null): Escola
    {
        $this->imagem = $imagem;
        return $this;
    }
}
