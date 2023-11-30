<?php

namespace App\Entity\Quiz;

use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * Quiz
 *
 * @ORM\Entity(repositoryClass="App\Repository\RespostaQuizQuestoesRepository")
 * @UniqueEntity(fields="respostaQuiz, questao", message="Só pode ter uma questão por resposta")
 * @Serializer\ExclusionPolicy("none")
 */
class RespostaQuizQuestoes
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\RespostaQuiz", inversedBy="respostas")
     * @ORM\JoinColumn(name="resposta_quiz_id", referencedColumnName="id")
     * @Serializer\Exclude()
     */
    private $respostaQuiz;

    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Quiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     * @Serializer\Exclude()
     */
    private $quiz;

    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Questao")
     * @ORM\JoinColumn(name="questao_id", referencedColumnName="id")
     */
    private $questao;

    /**
     * @var boolean
     * @ORM\Column(name="correto", type="boolean", nullable=true)
     */
    private $correto;

    /**
     * @ORM\Column(type="json")
     */
    private $resposta;

    /**
     * @var boolean
     * @ORM\Column(name="pulou", type="boolean", nullable=true)
     */
    private $pulou;

    /**
     * @var integer
     * @ORM\Column(name="qtd_tentativas", type="integer", options={"default" = 1})
     */
    private $qtdTentativas;

    /**
     * @var double
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     */
    private $nota;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacao;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
    }

    /**
     * @return Quiz
     */
    public function getRespostaQuiz()
    {
        return $this->respostaQuiz;
    }

    /**
     * @param Quiz $respostaQuiz
     * @return RespostaQuizQuestoes
     */
    public function setRespostaQuiz($respostaQuiz)
    {
        $this->respostaQuiz = $respostaQuiz;
        return $this;
    }

    /**
     * @return Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * @param Quiz $quiz
     * @return RespostaQuizQuestoes
     */
    public function setQuiz($quiz)
    {
        $this->quiz = $quiz;
        return $this;
    }

    /**
     * @return Quiz
     */
    public function getQuestao()
    {
        return $this->questao;
    }

    /**
     * @param Quiz $questao
     * @return RespostaQuizQuestoes
     */
    public function setQuestao($questao)
    {
        $this->questao = $questao;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCorreto()
    {
        return $this->correto;
    }

    /**
     * @param bool $correto
     * @return RespostaQuizQuestoes
     */
    public function setCorreto($correto)
    {
        $this->correto = $correto;
        return $this;
    }


    public function getResposta()
    {
        return $this->resposta;
    }

    public function setResposta(array $resposta): self
    {
        $this->resposta = $resposta;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPulou()
    {
        return $this->pulou;
    }

    /**
     * @param bool $pulou
     * @return RespostaQuizQuestoes
     */
    public function setPulou($pulou)
    {
        $this->pulou = $pulou;
        return $this;
    }

    /**
     * @return int
     */
    public function getQtdTentativas()
    {
        return $this->qtdTentativas;
    }

    /**
     * @param int $qtdTentativas
     * @return RespostaQuizQuestoes
     */
    public function setQtdTentativas($qtdTentativas)
    {
        $this->qtdTentativas = $qtdTentativas;
        return $this;
    }

    public function getNota()
    {
        return $this->nota;
    }
    public function setNota($nota)
    {
        $this->nota = $nota;
        return $this;
    }

    public function getObservacao()
    {
        return $this->observacao;
    }
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
        return $this;
    }
}
