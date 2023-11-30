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
class AdmEscola extends User
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Escola", inversedBy="administradores")
     * @ORM\JoinColumn(name="escola_id", referencedColumnName="id", nullable=true)
     */
    private $escola;

    /**
     * AdmEscola constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->tipo = self::ADM_ESCOLA;
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
        return self::ADM_ESCOLA;
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
     * @return AdmEscola
     */
    public function setEscola(Escola $escola): AdmEscola
    {
        $this->escola = $escola;
        $escola->addAdministrador($this);
        return $this;
    }
}
