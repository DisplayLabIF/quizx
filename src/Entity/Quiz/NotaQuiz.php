<?php

namespace App\Entity\Quiz;

use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table(name="notas_quiz")
 * @ORM\Entity()
 */
class NotaQuiz
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Quiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quiz;

    /**
     * @var RespostaQuiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\RespostaQuiz", inversedBy="notas")
     * @ORM\JoinColumn(name="resposta_quiz_id", referencedColumnName="id")
     */
    private $respostaQuiz;

    /**
     * @var double
     * @ORM\Column(name="nota", type="decimal", precision=6, scale=2)
     */
    private $nota;

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
    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    /**
     * @param Quiz $quiz
     * @return NotaQuiz
     */
    public function setQuiz(Quiz $quiz): NotaQuiz
    {
        $this->quiz = $quiz;
        return $this;
    }

    /**
     * @return RespostaQuiz
     */
    public function getRespostaQuiz(): RespostaQuiz
    {
        return $this->respostaQuiz;
    }

    /**
     * @param RespostaQuiz $respostaQuiz
     * @return NotaQuiz
     */
    public function setRespostaQuiz(RespostaQuiz $respostaQuiz): NotaQuiz
    {
        $this->respostaQuiz = $respostaQuiz;
        return $this;
    }

    /**
     * @return float
     */
    public function getNota(): float
    {
        return $this->nota;
    }

    /**
     * @param float $nota
     * @return NotaQuiz
     */
    public function setNota(float $nota): NotaQuiz
    {
        $this->nota = $nota;
        return $this;
    }
}
