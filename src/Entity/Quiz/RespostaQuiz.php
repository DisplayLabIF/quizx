<?php

namespace App\Entity\Quiz;

use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use App\Entity\User\Aluno;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Entity(repositoryClass="App\Repository\RespostaQuizRepository")
 */
class RespostaQuiz
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Quiz", inversedBy="respostas")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quizAtual;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\RespostaQuizQuestoes", mappedBy="respostaQuiz")
     */
    private $respostas;

    /**
     * E-mail ou id do aluno ou id do lead
     * @var string
     * @ORM\Column(name="aluno", type="string")
     */
    private $aluno;

    /**
     * @var double
     * @ORM\Column(name="nota", type="decimal", precision=6, scale=2)
     */
    private $nota;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\NotaQuiz", mappedBy="respostaQuiz")
     */
    private $notas;

    /**
     * @var boolean
     * @ORM\Column(name="finalizou", type="boolean", nullable=true)
     */
    private $finalizou;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $apresentandoQuiz;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $leadCapturado;

    /**
     * @var Aluno
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Aluno", inversedBy="respostas")
     * @ORM\JoinColumn(name="aluno_id", referencedColumnName="id", nullable=true)
     */
    private $alunoEntity;

    /**
     * @var LeadQuiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\LeadQuiz", inversedBy="respostas")
     * @ORM\JoinColumn(name="lead_id", referencedColumnName="id", nullable=true)
     */
    private $leadQuizEntity;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tempoEsgotado;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    protected bool $respostaCorrigida;


    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
        $this->respostas = new ArrayCollection();
        $this->notas = new ArrayCollection();
        $this->notas = new ArrayCollection();
        $this->apresentandoQuiz = true;
        $this->leadCapturado = false;
        $this->respostaCorrigida = false;
    }

    /**
     * @return Quiz
     */
    public function getQuizAtual()
    {
        return $this->quizAtual;
    }

    /**
     * @param Quiz $quizAtual
     * @return RespostaQuiz
     */
    public function setQuizAtual($quizAtual)
    {
        $this->quizAtual = $quizAtual;
        return $this;
    }


    /**
     * @return string
     */
    public function getAluno()
    {
        return $this->aluno;
    }

    /**
     * @param string $aluno
     * @return RespostaQuiz
     */
    public function setAluno($aluno)
    {
        $this->aluno = $aluno;
        return $this;
    }

    /**
     * @return float
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * @param float $nota
     * @return RespostaQuiz
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
        return $this;
    }

    /**
     * @return bool
     */
    public function getFinalizou()
    {
        return $this->finalizou;
    }

    /**
     * @param bool $finalizou
     * @return RespostaQuiz
     */
    public function setFinalizou($finalizou)
    {
        $this->finalizou = $finalizou;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRespostas()
    {
        return $this->respostas;
    }
    /**
     * @return mixed
     */
    public function getRespostasForQuiz(Quiz $quiz)
    {
        return $this->respostas->matching(
            Criteria::create()->where(
                Criteria::expr()->eq('quiz', $quiz)
            )
        );
    }
    public function setRespostas($respostas)
    {
        $this->respostas = $respostas;
        return $this;
    }

    public function addResposta(RespostaQuizQuestoes $resposta): void
    {
        if (!$this->respostas->contains($resposta)) {
            $this->respostas->add($resposta);
        }
    }

    public function removeResposta(RespostaQuizQuestoes $resposta)
    {
        $this->respostas->removeElement($resposta);
    }

    /**
     * @return mixed
     */
    public function getNotas()
    {
        return $this->notas;
    }

    /**
     * @return bool
     */
    public function getApresentandoQuiz()
    {
        return $this->apresentandoQuiz;
    }

    /**
     * @param bool $apresentandoQuiz
     * @return RespostaQuiz
     */
    public function setApresentandoQuiz($apresentandoQuiz)
    {
        $this->apresentandoQuiz = $apresentandoQuiz;
        return $this;
    }

    /**
     * @return bool
     */
    public function getLeadCapturado()
    {
        return $this->leadCapturado;
    }

    /**
     * @param bool $leadCapturado
     * @return RespostaQuiz
     */
    public function setLeadCapturado($leadCapturado)
    {
        $this->leadCapturado = $leadCapturado;
        return $this;
    }

    /**
     * @return Aluno|null
     */
    public function getAlunoEntity(): ?Aluno
    {
        return $this->alunoEntity;
    }

    /**
     * @param Aluno $alunoEntity
     * @return RespostaQuiz
     */
    public function setAlunoEntity(Aluno $alunoEntity): RespostaQuiz
    {
        $this->alunoEntity = $alunoEntity;
        return $this;
    }

    /**
     * @return LeadQuiz|null
     */
    public function getLeadQuizEntity(): ?LeadQuiz
    {
        return $this->leadQuizEntity;
    }

    /**
     * @param LeadQuiz $leadQuizEntity
     * @return RespostaQuiz
     */
    public function setLeadQuizEntity(LeadQuiz $leadQuizEntity): RespostaQuiz
    {
        $this->leadQuizEntity = $leadQuizEntity;
        return $this;
    }

    /**
     * @return bool
     */
    public function getTempoEsgotado()
    {
        return $this->tempoEsgotado;
    }

    /**
     * @param bool $tempoEsgotado
     * @return RespostaQuiz
     */
    public function setTempoEsgotado($tempoEsgotado)
    {
        $this->tempoEsgotado = $tempoEsgotado;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRespostaCorrigida(): bool
    {
        return $this->respostaCorrigida;
    }

    /**
     * @param bool $respostaCorrigida
     */
    public function setRespostaCorrigida(bool $respostaCorrigida)
    {
        $this->respostaCorrigida = $respostaCorrigida;
        return $this;
    }
}
