<?php

namespace App\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
// use Quizx\BaseBundle\Validator\Constraints as AssertBase;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity()
 */
class Contato
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     * @ORM\Column(name="telefone", type="string", length=15, nullable=true)
     * @Assert\NotBlank(message="contato.blank_telefone")
     */
    private $telefone;

    /**
     * @var string
     * @ORM\Column(name="celular", type="string", length=16, nullable=true)
     * @Assert\NotBlank(message="contato.blank_celular")
     */
    private $celular;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->active = true;
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
        return [
            'telefone' => $this->telefone,
            'celular' => $this->celular,
        ];
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
     * @return Contato
     */
    public function setTelefone($telefone): Contato
    {
        $this->telefone = preg_replace('/[^0-9]/', '', $telefone);
        return $this;
    }

    /**
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param string $celular
     * @return Contato
     */
    public function setCelular($celular): Contato
    {
        $this->celular = preg_replace('/[^0-9]/', '', $celular);
        return $this;
    }
}
