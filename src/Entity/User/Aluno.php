<?php

namespace App\Entity\User;


use Doctrine\ORM\Mapping as ORM;
use App\Entity\Base\Contato;
use App\Entity\Base\PessoaFisicaTrait;
use App\Entity\Quiz\RespostaQuiz;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity()
 */
class Aluno extends User
{
    use PessoaFisicaTrait;

    /**
     * @var Contato
     * @ORM\ManyToOne(targetEntity="App\Entity\Base\Contato", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="contato_id", referencedColumnName="id")
     */
    private $contato;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\RespostaQuiz", mappedBy="alunoEntity")
     */
    private $respostas;

    /**
     * Aluno constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->tipo = self::ALUNO;
        $this->respostas = new ArrayCollection();
    }


    public function toArray()
    {
        // TODO: Implement toArray() method.
        return array_merge(
            $this->toArrayEntityTrait(),
            [
                'tipo' => $this->tipo
            ]
        );
    }


    /**
     * @return string
     */
    public function getTipo()
    {
        return self::ALUNO;
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
}
