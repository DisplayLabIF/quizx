<?php

namespace App\Entity\User;



use Doctrine\ORM\Mapping as ORM;
use App\Entity\Base\Contato;
use App\Entity\Base\PessoaFisicaTrait;
use App\Entity\Escola;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity()
 */
class Professor extends User
{
    use PessoaFisicaTrait;

    /**
     * @var Contato
     * @ORM\ManyToOne(targetEntity="App\Entity\Base\Contato", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="contato_id", referencedColumnName="id")
     */
    private $contato;

    /**
     * @var Escola
     * @ORM\ManyToOne(targetEntity="App\Entity\Escola", inversedBy="professores")
     * @ORM\JoinColumn(name="escola_id", referencedColumnName="id", nullable=true)
     */
    private $escola;

    /**
     * Professor constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->tipo = self::PROFESSOR;
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
        return self::PROFESSOR;
    }

    /**
     * @return Escola
     */
    public function getEscola(): ?Escola
    {
        return $this->escola;
    }

    /**
     * @param Escola $escola
     * @return Professor
     */
    public function setEscola(Escola $escola): Professor
    {
        $this->escola = $escola;
        $escola->addProfessor($this);
        return $this;
    }
}
