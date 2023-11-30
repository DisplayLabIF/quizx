<?php

namespace App\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


trait PessoaTrait
{

    /**
     * @var Contato
     * @ORM\ManyToOne(targetEntity="App\Entity\Base\Contato", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="contato_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    private $contato;

    /**
     * @return Contato
     */
    public function getContato()
    {
        return $this->contato;
    }

    /**
     * @param Contato $contato
     * @return PessoaTrait
     */
    public function setContato($contato)
    {
        $this->contato = $contato;
        return $this;
    }
}
