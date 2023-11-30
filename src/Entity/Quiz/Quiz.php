<?php

namespace App\Entity\Quiz;


use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use App\Entity\User;
use App\Entity\User\AdmEscola;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use JMS\Serializer\Annotation as Serializer;

/**
 * Quiz
 *
 * @ORM\Table(name="quizes", indexes={@ORM\Index(name="codigo_idx", columns={"codigo"})})
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 * @UniqueEntity(fields={"codigo"}, message="codigo.unique")
 * @Serializer\ExclusionPolicy("none")
 */
class Quiz
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string")
     * @Assert\NotBlank(message="quiz.blank_codigo")
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=100)
     * @Assert\NotBlank(message="quiz.blank_name")
     */
    private $nome;

    /**
     * @ORM\Column(type="json")
     */
    private $assuntos = [];

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Quiz\Questao", inversedBy="quizes", cascade={"persist"})
     * @ORM\JoinTable(name="quiz_questoes",
     *      joinColumns={@ORM\JoinColumn(name="quiz_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="questao_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * @ORM\OrderBy({"numeroQuestao" = "ASC"})
     **/
    private $questoes;

    /**
     * @var string
     * @ORM\Column(name="nivel", type="string", nullable=true)
     */
    private $nivel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $session;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="quizes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * @Serializer\Exclude()
     */
    private $user;

    /**
     * @var ConfiguracaoQuiz
     * @ORM\OneToOne(targetEntity="App\Entity\Quiz\ConfiguracaoQuiz", cascade={"persist"})
     * @ORM\JoinColumn(name="configuracao_quiz_id", referencedColumnName="id", nullable=true)
     */
    private $configuracaoQuiz;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Quiz\LeadQuiz", mappedBy="quizes", cascade={"persist"})
     * @Serializer\Exclude()
     */
    private $leadsQuiz;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\RespostaQuiz", mappedBy="quizAtual")
     * @ORM\OrderBy({"created" = "ASC"})
     * @Serializer\Exclude()
     */
    private $respostas;

    /**
     * @var PersonalizacaoQuiz
     * @ORM\OneToOne(targetEntity="App\Entity\Quiz\PersonalizacaoQuiz", cascade={"persist"})
     * @ORM\JoinColumn(name="personalizacao_quiz_id", referencedColumnName="id", nullable=true)
     */
    private $personalizacaoQuiz;

    /**
     * @Serializer\Exclude()
     */
    private $questoesActive;

    /**
     * @var PersonalizacaoEmail
     * @ORM\OneToOne(targetEntity="App\Entity\Quiz\PersonalizacaoEmail", cascade={"persist"})
     * @ORM\JoinColumn(name="personalizacao_email_id", referencedColumnName="id", nullable=true)
     * @Serializer\Exclude()
     */
    private $personalizacaoEmail;

    /**
     * @var ConfiguracaoMarketingQuiz
     * @ORM\OneToOne(targetEntity="App\Entity\Quiz\ConfiguracaoMarketingQuiz", cascade={"persist"})
     * @ORM\JoinColumn(name="config_marketing_id", referencedColumnName="id", nullable=true)
     */
    private $configuracaoMarketingQuiz;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $pesquisa;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $startGame;



    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
        $this->questoes = new ArrayCollection();
        $this->leadsQuiz = new ArrayCollection();
        $this->respostas = new ArrayCollection();
        $this->pesquisa = false;
        $this->startGame = false;
    }

    /**
     * @param string $codigo
     * @return Quiz
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
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
     * @return Quiz
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getAssuntos()
    {
        return $this->assuntos;
    }

    public function setAssuntos(array $assuntos): self
    {
        $this->assuntos = $assuntos;

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getQuestoes()
    {
        $this->questoesActive = new arrayCollection();
        $this->questoes->filter(function ($questao) {
            if ($questao->isActive()) {
                if ($questao->getTipo() === 'V_F' && !$questao->getTrueOrFalseCaracteres()) {
                    $questao->setTrueOrFalseCaracteres(['V', 'F']);
                }
                return $this->questoesActive->add($questao);
            }
        });
        return $this->questoesActive;
    }

    /**
     * @param mixed $questoes
     * @return Quiz
     */
    public function setQuestoes($questoes)
    {
        $this->questoes = $questoes;
        return $this;
    }

    public function addQuestao(Questao $questao): void
    {
        if (!$this->questoes->contains($questao)) {
            $this->questoes->add($questao);
        }
    }

    public function removeQuestao(Questao $questao)
    {
        $this->questoes->removeElement($questao);
    }

    /**
     * @return string
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * @return string
     */
    public function getNivelFormat()
    {
        $nivelTypes = [
            'FUNDAMENTAL_1' => 'Fundamental 1',
            'FUNDAMENTAL_2' => "Fundamental 2",
            'ENSINO_MEDIO' => "Ensino médio",
            'ENSINO_TECNICO' => "Ensino técnico",
            'GRADUACAO' => "Graduação",
            'POS_GRADUACAO' => "Pós-graduação",
            'CURIOSIDADES' => "Curiosidades",
            'FILMES_SERIES_ANIMES' => "Filmes, Séries e Animes",
            'TV' => "TV",
            'NONE' => ''
        ];
        if (key_exists($this->nivel, $nivelTypes))
            return $nivelTypes[$this->nivel];

        return $this->nivel;
    }


    /**
     * @param string $nivel
     * @return Quiz
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }


    public function getSession(): ?string
    {
        return $this->session;
    }

    public function setSession(?string $session): self
    {
        $this->session = $session;
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

    /**
     * @return ConfiguracaoQuiz
     */
    public function getConfiguracaoQuiz(): ?ConfiguracaoQuiz
    {
        return $this->configuracaoQuiz;
    }

    public function setConfiguracaoQuiz(?ConfiguracaoQuiz $configuracaoQuiz): Quiz
    {
        $this->configuracaoQuiz = $configuracaoQuiz;
        return $this;
    }

    /**
     * @return Collection|LeadQuiz[]
     */
    public function getLeadsQuiz(): Collection
    {
        return $this->leadsQuiz;
    }

    public function setLeadsQuiz($leadsQuiz)
    {
        $this->leadsQuiz = $leadsQuiz;
        return $this;
    }

    public function addLeadQuiz(LeadQuiz $leadQuiz): void
    {
        if (!$this->leadsQuiz->contains($leadQuiz)) {
            $leadQuiz->addQuiz($this);
            $this->leadsQuiz->add($leadQuiz);
        }
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
     * @return PersonalizacaoQuiz
     */
    public function getPersonalizacaoQuiz(): ?PersonalizacaoQuiz
    {
        return $this->personalizacaoQuiz;
    }

    public function setPersonalizacaoQuiz(?PersonalizacaoQuiz $personalizacaoQuiz): Quiz
    {
        $this->personalizacaoQuiz = $personalizacaoQuiz;
        return $this;
    }

    /**
     * @return PersonalizacaoEmail
     */
    public function getPersonalizacaoEmail(): ?PersonalizacaoEmail
    {
        return $this->personalizacaoEmail;
    }

    public function setPersonalizacaoEmail(?PersonalizacaoEmail $personalizacaoEmail): Quiz
    {
        $this->personalizacaoEmail = $personalizacaoEmail;
        return $this;
    }

    /**
     * @return ConfiguracaoMarketingQuiz
     */
    public function getConfiguracaoMarketingQuiz(): ?ConfiguracaoMarketingQuiz
    {
        return $this->configuracaoMarketingQuiz;
    }

    public function setConfiguracaoMarketingQuiz(?ConfiguracaoMarketingQuiz $configuracaoMarketingQuiz): Quiz
    {
        $this->configuracaoMarketingQuiz = $configuracaoMarketingQuiz;
        return $this;
    }


    public function getPesquisa()
    {
        return $this->pesquisa;
    }

    public function setPesquisa($pesquisa)
    {
        $this->pesquisa = $pesquisa;
        return $this;
    }

    public function getStartGame()
    {
        return $this->startGame;
    }

    public function setStartGame($startGame)
    {
        $this->startGame = $startGame;
        return $this;
    }
}
