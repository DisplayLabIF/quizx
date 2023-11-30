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
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

/**
 * @ORM\Entity()
 * @ORM\Table(name="quiz_configuracao")
 */
class ConfiguracaoQuiz
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="Escolha uma das opções")
     */
    private $obterDadosRespondente;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $obterNome;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $obterEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $obterTelefone;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $obterCpf;


    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $obterCidade;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $quandoObterDados;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotNull(message="Escolha uma das opções")
     */
    private $mostrarNota;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="Escolha uma das opções")
     */
    private $definirTempoResposta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tempo;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="Escolha uma das opções")
     */
    private $podePularQuestao;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mostraAleatoria;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="Escolha uma das opções")
     */
    private $mostrarCorrecao;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $configurarLandPage;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="Escolha uma das opções")
     */
    private $mostrarGabarito;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $definirNotaMinima;

    /**
     * @var double
     *
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     */
    private $notaMinima;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacao;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(message="Escolha uma das opções")
     */
    private $adicionarMateriais;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Curso\Material", mappedBy="configuracaoQuiz", cascade={"persist"})
     */
    private $materiais;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity=CamposPersonalizados::class, inversedBy="configuracaoQuiz")
     * @ORM\JoinTable(name="campos_personalizados_configuracao_quiz",
     *      joinColumns={@ORM\JoinColumn(name="configuracao_quiz_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="campo_personalizado_id", referencedColumnName="id")}
     * )
     */
    private $camposPersonalizados;

    /**
     * @var array|null
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $ordemCampos;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nomeBotaoRedirecionar;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
        $this->materiais = new ArrayCollection();
        $this->camposPersonalizados = new ArrayCollection();
        $this->redirecionarAutomaticamente = true;
        $this->nomeBotaoRedirecionar = 'Continuar';
    }

    /**
     * @return bool
     */
    public function getObterDadosRespondente()
    {
        return $this->obterDadosRespondente;
    }
    /**
     * @param bool $obterDadosRespondente
     * @return ConfiguracaoQuiz
     */
    public function setObterDadosRespondente($obterDadosRespondente)
    {
        $this->obterDadosRespondente = $obterDadosRespondente;
        return $this;
    }

    /**
     * @return bool
     */
    public function getObterNome()
    {
        return $this->obterNome;
    }
    /**
     * @param bool $obterNome
     * @return ConfiguracaoQuiz
     */
    public function setObterNome($obterNome)
    {
        $this->obterNome = $obterNome;
        return $this;
    }

    /**
     * @return bool
     */
    public function getObterEmail()
    {
        return $this->obterEmail;
    }
    /**
     * @param bool $obterEmail
     * @return ConfiguracaoQuiz
     */
    public function setObterEmail($obterEmail)
    {
        $this->obterEmail = $obterEmail;
        return $this;
    }

    /**
     * @return bool
     */
    public function getObterTelefone()
    {
        return $this->obterTelefone;
    }
    /**
     * @param bool $obterTelefone
     * @return ConfiguracaoQuiz
     */
    public function setObterTelefone($obterTelefone)
    {
        $this->obterTelefone = $obterTelefone;
        return $this;
    }
    /**
     * @return bool
     */
    public function getObterCpf()
    {
        return $this->obterCpf;
    }
    /**
     * @param bool $obterCpf
     * @return ConfiguracaoQuiz
     */
    public function setObterCpf($obterCpf)
    {
        $this->obterCpf = $obterCpf;
        return $this;
    }

    /**
     * @return bool
     */
    public function getObterCnpj()
    {
        return $this->obterCnpj;
    }
    /**
     * @param bool $obterCnpj
     * @return ConfiguracaoQuiz
     */
    public function setObterCnpj($obterCnpj)
    {
        $this->obterCnpj = $obterCnpj;
        return $this;
    }

    /**
     * @return bool
     */
    public function getObterCidade()
    {
        return $this->obterCidade;
    }
    /**
     * @param bool $obterCidade
     * @return ConfiguracaoQuiz
     */
    public function setObterCidade($obterCidade)
    {
        $this->obterCidade = $obterCidade;
        return $this;
    }

    public function getQuandoObterDados()
    {
        return $this->quandoObterDados;
    }

    public function setQuandoObterDados($quandoObterDados)
    {
        $this->quandoObterDados = $quandoObterDados;
        return $this;
    }


    public function getMostrarNota()
    {
        return $this->mostrarNota;
    }

    public function setMostrarNota($mostrarNota)
    {
        $this->mostrarNota = $mostrarNota;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDefinirTempoResposta()
    {
        return $this->definirTempoResposta;
    }
    /**
     * @param bool $definirTempoResposta
     * @return ConfiguracaoQuiz
     */
    public function setDefinirTempoResposta($definirTempoResposta)
    {
        $this->definirTempoResposta = $definirTempoResposta;
        return $this;
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
    public function getPodePularQuestao()
    {
        return $this->podePularQuestao;
    }
    /**
     * @param bool $podePularQuestao
     * @return ConfiguracaoQuiz
     */
    public function setPodePularQuestao($podePularQuestao)
    {
        $this->podePularQuestao = $podePularQuestao;
        return $this;
    }

    /**
     * @return bool
     */
    public function getMostraAleatoria()
    {
        return $this->mostraAleatoria;
    }
    /**
     * @param bool $mostraAleatoria
     * @return ConfiguracaoQuiz
     */
    public function setMostraAleatoria($mostraAleatoria)
    {
        $this->mostraAleatoria = $mostraAleatoria;
        return $this;
    }

    /**
     * @return bool
     */
    public function getMostrarCorrecao()
    {
        return $this->mostrarCorrecao;
    }
    /**
     * @param bool $mostrarCorrecao
     * @return ConfiguracaoQuiz
     */
    public function setMostrarCorrecao($mostrarCorrecao)
    {
        $this->mostrarCorrecao = $mostrarCorrecao;
        return $this;
    }


    public function getConfigurarLandPage()
    {
        return $this->configurarLandPage;
    }

    public function setConfigurarLandPage($configurarLandPage)
    {
        $this->configurarLandPage = $configurarLandPage;
        return $this;
    }

    /**
     * @return bool
     */
    public function getMostrarGabarito()
    {
        return $this->mostrarGabarito;
    }
    /**
     * @param bool $mostrarGabarito
     * @return ConfiguracaoQuiz
     */
    public function setMostrarGabarito($mostrarGabarito)
    {
        $this->mostrarGabarito = $mostrarGabarito;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDefinirNotaMinima()
    {
        return $this->definirNotaMinima;
    }
    /**
     * @param bool $definirNotaMinima
     * @return ConfiguracaoQuiz
     */
    public function setDefinirNotaMinima($definirNotaMinima)
    {
        $this->definirNotaMinima = $definirNotaMinima;
        return $this;
    }

    /**
     * @return float
     */
    public function getNotaMinima()
    {
        return $this->notaMinima;
    }

    /**
     * @param float $notaMinima
     * @return Questao
     */
    public function setNotaMinima($notaMinima)
    {
        $this->notaMinima = $notaMinima;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlCallBack()
    {
        return $this->urlCallBack;
    }

    /**
     * @param string $urlCallBack
     * @return $this
     */
    public function setUrlCallBack($urlCallBack)
    {
        $this->urlCallBack = $urlCallBack;
        return $this;
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
    public function setObservacao(?string $observacao)
    {
        $this->observacao = $observacao;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAdicionarMateriais()
    {
        return $this->adicionarMateriais;
    }

    /**
     * @param bool $adicionarMateriais
     * @return ConfiguracaoQuiz
     */
    public function setAdicionarMateriais($adicionarMateriais)
    {
        $this->adicionarMateriais = $adicionarMateriais;
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
        $materialAux =  $this->materiais;
        $this->materiais = new ArrayCollection();
        $materialAux->filter(function ($material) {
            if ($material->isActive())
                return $this->materiais->add($material);
        });
        return $this->materiais;
    }

    /**
     * @return ArrayCollection
     */
    public function getCamposPersonalizados()
    {
        return $this->camposPersonalizados->matching(
            Criteria::create()->where(
                Criteria::expr()->eq('active', true)
            )
        );
    }

    /**
     * @param ArrayCollection $camposPersonalizados
     * @return ConfiguracaoQuiz
     */
    public function setCamposPersonalizados($camposPersonalizados)
    {
        $this->camposPersonalizados = $camposPersonalizados;
        return $this;
    }

    public function getOrdemCampos(): ?array
    {
        return $this->ordemCampos;
    }

    public function setOrdemCampos(?array $ordemCampos): self
    {
        $this->ordemCampos = $ordemCampos;

        return $this;
    }

    public function getRedirecionarAutomaticamente()
    {
        return $this->redirecionarAutomaticamente;
    }

    public function setRedirecionarAutomaticamente($redirecionarAutomaticamente)
    {
        $this->redirecionarAutomaticamente = $redirecionarAutomaticamente;

        return $this;
    }

    public function getNomeBotaoRedirecionar()
    {
        return $this->nomeBotaoRedirecionar;
    }
    public function setNomeBotaoRedirecionar($nomeBotaoRedirecionar)
    {
        $this->nomeBotaoRedirecionar = $nomeBotaoRedirecionar;
        return $this;
    }
}
