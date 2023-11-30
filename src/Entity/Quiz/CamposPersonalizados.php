<?php

namespace App\Entity\Quiz;


use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CamposPersonalizadosRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class CamposPersonalizados
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotIdenticalTo("Nome")
     * @Assert\NotIdenticalTo("E-mail")
     * @Assert\NotIdenticalTo("Telefone")
     * @Assert\NotIdenticalTo("Empresa")
     * @Assert\NotIdenticalTo("CNPJ")
     * @Assert\NotIdenticalTo("CPF")
     * @Assert\NotIdenticalTo("Cidade")
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $tipo;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $fonteExterna;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $endpointFonteExterna;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $dependeOutroCampo;

    // /**
    //  * @ORM\OneToOne(targetEntity="App\Entity\Quiz\CamposPersonalizados")
    //  * @ORM\JoinColumn(name="campo_id", referencedColumnName="id")
    //  */
    // private $campoDependente;

    /**
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\CamposPersonalizados", mappedBy="campoDependente")
     */
    private $parent;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\CamposPersonalizados", inversedBy="parent")
     */
    private $campoDependente;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Quiz\ConfiguracaoQuiz", mappedBy="camposPersonalizados")
     * @Serializer\Exclude()
     */
    private $configuracaoQuiz;


    /**
     * @var array|null
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $opcoes;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $apiIdentifier;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $idCampoRd;



    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
        $this->configuracaoQuiz = new ArrayCollection();
        $this->fonteExterna = false;
        $this->dependeOutroCampo = false;
        $this->parent = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getNome()
    {
        return $this->nome;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function getTipo()
    {
        return $this->tipo;
    }
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getFonteExterna()
    {
        return $this->fonteExterna;
    }
    public function setFonteExterna($fonteExterna)
    {
        $this->fonteExterna = $fonteExterna;
        return $this;
    }

    public function getEndpointFonteExterna()
    {
        return $this->endpointFonteExterna;
    }
    public function setEndpointFonteExterna($endpointFonteExterna)
    {
        $this->endpointFonteExterna = $endpointFonteExterna;
        return $this;
    }

    public function getDependeOutroCampo()
    {
        return $this->dependeOutroCampo;
    }
    public function setDependeOutroCampo($dependeOutroCampo)
    {
        $this->dependeOutroCampo = $dependeOutroCampo;
        return $this;
    }

    public function getCampoDependente()
    {
        return $this->campoDependente;
    }
    public function setCampoDependente($campoDependente)
    {
        $this->campoDependente = $campoDependente;
        return $this;
    }

    /**
     * @return Collection|ConfiguracaoQuiz[]
     */
    public function getConfiguracaoQuiz(): Collection
    {
        return $this->configuracaoQuiz;
    }

    public function setConfiguracaoQuiz($configuracaoQuiz)
    {
        $this->configuracaoQuiz = $configuracaoQuiz;
        return $this;
    }

    public function getOpcoes(): ?array
    {
        return $this->opcoes;
    }

    public function setOpcoes(?array $opcoes): self
    {
        $this->opcoes = $opcoes;

        return $this;
    }

    public function getApiIdentifier()
    {
        return $this->apiIdentifier;
    }
    public function setApiIdentifier($apiIdentifier)
    {
        $this->apiIdentifier = $apiIdentifier;
        return $this;
    }

    public function getIdCampoRd()
    {
        return $this->idCampoRd;
    }
    public function setIdCampoRd($idCampoRd)
    {
        $this->idCampoRd = $idCampoRd;
        return $this;
    }
}
