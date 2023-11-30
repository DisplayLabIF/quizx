<?php

namespace App\Entity\Quiz;

use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use JMS\Serializer\Annotation as Serializer;
use App\Repository\QuestaoRepository;

/**
 * Quiz
 *
 * @ORM\Table(name="questoes")
 * @ORM\Entity(repositoryClass=QuestaoRepository::class)
 * @Serializer\ExclusionPolicy("none")
 */
class Questao
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="pergunta", type="text")
     * @Assert\NotBlank(message="questao.blank_pergunta")
     */
    private $pergunta;

    /**
     * @var string
     *
     * @ORM\Column(name="explicacao_resposta", type="text", nullable=true)
     */
    private $explicacaoResposta;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=20)
     * @Assert\NotBlank(message="questao.blank_tipo")
     */
    private $tipo;

    /**
     * @var double
     *
     * @ORM\Column(name="valor", type="decimal", precision=6, scale=2, nullable=true)
     */
    private $valor;

    /**
     * @var string
     * @ORM\Column(name="nivel", type="string", nullable=true)
     */
    private $nivel;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\Opcao", mappedBy="questao", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"numeroOpcao" = "ASC"})
     */
    private $opcoes;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Quiz\Quiz", mappedBy="questoes")
     * @Serializer\Exclude()
     */
    private $quizes;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $tempo;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $obrigatoria;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mostrarExplicacao;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroQuestao; // é o indice incrementável no js para identifiar o campo no DOM

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $trueOrFalseCaracteres = [];

    /**
     * @var ArquivosQuestao
     * @ORM\OneToOne(targetEntity="App\Entity\Quiz\ArquivosQuestao", cascade={"persist"})
     * @ORM\JoinColumn(name="arquivos_questao_id", referencedColumnName="id", nullable=true)
     */
    private $arquivosQuestao;

    /**
     * @Serializer\Exclude()
     */
    private $opcoesActive;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
        $this->opcoes = new ArrayCollection();
        $this->imagens = new ArrayCollection();
        $this->quizes = new ArrayCollection();
        $this->trueOrFalseCaracteres = ['V', 'F'];
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getPergunta()
    {
        return $this->pergunta;
    }

    /**
     * @param string $pergunta
     * @return Questao
     */
    public function setPergunta($pergunta)
    {
        $this->pergunta = $pergunta;
        return $this;
    }

    /**
     * @return string
     */
    public function getExplicacaoResposta()
    {
        return $this->explicacaoResposta;
    }

    /**
     * @param string $explicacaoResposta
     * @return Questao
     */
    public function setExplicacaoResposta($explicacaoResposta)
    {
        $this->explicacaoResposta = $explicacaoResposta;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    // /**
    //  * @return string
    //  */
    // public function getTipoNome()
    // {
    //     return TiposResposta::getTipo($this->getTipo());
    // }

    /**
     * @param string $tipo
     * @return Questao
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     * @return Questao
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return string
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    // /**
    //  * @return string
    //  */
    // public function getNivelNome()
    // {
    //     return NivelEscolar::getNivel($this->nivel);
    // }


    /**
     * @param string $nivel
     * @return Questao
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getOpcoes()
    {
        $this->opcoesActive = new arrayCollection();

        $this->opcoes->filter(function ($opcao) {
            if ($opcao->isActive()) {
                return $this->opcoesActive->add($opcao);
            }
        });

        return $this->opcoesActive;
    }

    /**
     * @param ArrayCollection $opcoes
     * @return Questao
     */
    public function setOpcoes($opcoes)
    {
        $this->opcoes = $opcoes;
        return $this;
    }

    public function addOpcao(Opcao $opcao): void
    {
        if (!$this->opcoes->contains($opcao)) {
            $this->opcoes->add($opcao);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getImagens()
    {
        return $this->imagens;
    }

    /**
     * @param ArrayCollection $imagens
     * @return Questao
     */
    public function setImagens($imagens)
    {
        $this->imagens = $imagens;
        return $this;
    }

    public function getMyHash()
    {
        return md5(serialize($this)) . PHP_EOL;
    }

    public function equalsQuestao(Questao $questao)
    {
        return $this->getMyHash() == $questao->getMyHash();
    }

    /**
     * @return mixed
     */
    public function getQuizes()
    {
        return $this->quizes;
    }

    public function getTipoResposta()
    {
        if ($this->getTipo() === 'V_F') {
            foreach ($this->getOpcoes() as $opcao) {
                if (strtoupper($opcao->getRespostaCorreta()) == 'T') {
                    return 'T_F';
                }
            }
            return 'V_F';
        }
        return null;
    }

    public function getTempo()
    {
        return $this->tempo;
    }
    public function setTempo($tempo)
    {
        $this->tempo = $tempo;
        return $this;
    }

    /**
     * @return bool
     */
    public function getObrigatoria()
    {
        return $this->obrigatoria;
    }

    /**
     * @param bool $obrigatoria
     * @return Questao
     */
    public function setObrigatoria($obrigatoria)
    {
        $this->obrigatoria = $obrigatoria;
        return $this;
    }

    /**
     * @return bool
     */
    public function getMostrarExplicacao()
    {
        return $this->mostrarExplicacao;
    }

    /**
     * @param bool $mostrarExplicacao
     * @return Questao
     */
    public function setMostrarExplicacao($mostrarExplicacao)
    {
        $this->mostrarExplicacao = $mostrarExplicacao;
        return $this;
    }

    public function setNumeroQuestao(?int $numeroQuestao): self
    {
        $this->numeroQuestao = $numeroQuestao;
        return $this;
    }

    public function getNumeroQuestao(): ?int
    {
        return $this->numeroQuestao;
    }

    public function setTrueOrFalseCaracteres($trueOrFalseCaracteres = null)
    {
        $this->trueOrFalseCaracteres = $trueOrFalseCaracteres;
        return $this;
    }

    public function getTrueOrFalseCaracteres()
    {
        return $this->trueOrFalseCaracteres;
    }

    /**
     * @return ArquivosQuestao
     */
    public function getArquivosQuestao(): ?ArquivosQuestao
    {
        return $this->arquivosQuestao;
    }

    public function setArquivosQuestao(?ArquivosQuestao $arquivosQuestao): Questao
    {
        $this->arquivosQuestao = $arquivosQuestao;
        return $this;
    }
}
