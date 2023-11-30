<?php

namespace App\Entity\Quiz;

use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LeadQuizRepository")
 * @ORM\Table(name="quiz_lead")
 */
class LeadQuiz
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity=Quiz::class, inversedBy="leadsQuiz")
     * @ORM\JoinTable(name="leads_quiz",
     *      joinColumns={@ORM\JoinColumn(name="lead_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="quiz_id", referencedColumnName="id")}
     * )
     */
    private $quizes;


    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $telefone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $empresa;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $cnpj;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $cpf;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $cidade;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\RespostaQuiz", mappedBy="leadQuizEntity")
     */
    private $respostas;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $communications;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $privacyPolicy;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $termsOfUse;

    /**
     * @var array|null
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $camposPersonalizados;


    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
        $this->respostas = new ArrayCollection();
        $this->quizes = new ArrayCollection();

    }

    /**
     * @return ArrayCollection
     */
    public function getQuizes()
    {
        return $this->quizes;
    }

    /**
     * @param ArrayCollection $quizes
     * @return LeadQuiz
     */
    public function setQuizes($quizes)
    {
        $this->quizes = $quizes;
        return $this;
    }

    public function addQuiz(Quiz $quiz): void
    {
        if (!$this->quizes->contains($quiz)) {
            $this->quizes->add($quiz);
        }
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return LeadQuiz
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return LeadQuiz
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param string $telefone
     * @return LeadQuiz
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param string $empresa
     * @return LeadQuiz
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
        return $this;
    }

    /**
     * @return string
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param string $cnpj
     * @return LeadQuiz
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    /**
     * @return string
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param string $cpf
     * @return LeadQuiz
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * @return string
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @param string $cidade
     * @return LeadQuiz
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
        return $this;
    }

    public function setRespostas($respostas)
    {
        $this->respostas = $respostas;
        return $this;
    }
    /**
     * @return Collection|RespostaQuiz[]
     */
    public function getRespostas(): Collection
    {
        return $this->respostas;
    }

    /**
     * @return bool
     */
    public function getCommunications()
    {
        return $this->communications;
    }
    /**
     * @param bool $communications
     * @return LeadQuiz
     */
    public function setCommunications($communications)
    {
        $this->communications = $communications;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPrivacyPolicy()
    {
        return $this->privacyPolicy;
    }
    /**
     * @param bool $privacyPolicy
     * @return LeadQuiz
     */
    public function setPrivacyPolicy($privacyPolicy)
    {
        $this->privacyPolicy = $privacyPolicy;
        return $this;
    }

    /**
     * @return bool
     */
    public function getTermsOfUse()
    {
        return $this->termsOfUse;
    }
    /**
     * @param bool $termsOfUse
     * @return LeadQuiz
     */
    public function setTermsOfUse($termsOfUse)
    {
        $this->termsOfUse = $termsOfUse;
        return $this;
    }

    public function getCamposPersonalizados(): ?array
    {
        return $this->camposPersonalizados;
    }

    public function setCamposPersonalizados(?array $camposPersonalizados): self
    {
        $this->camposPersonalizados = $camposPersonalizados;

        return $this;
    }
}
