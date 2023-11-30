<?php

namespace App\Entity\Quiz;

use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="quiz_personalizacao_email")
 * @ORM\Entity()
 */
class PersonalizacaoEmail
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     */
    private $urlBase;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=7)
     */
    private $cor;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $texto;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $textoBotao;


    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getUrlBase(): ?string
    {
        return $this->urlBase;
    }

    public function setUrlBase(?string $urlBase): self
    {
        $this->urlBase = $urlBase;

        return $this;
    }

    /**
     * @return string
     */
    public function getCor()
    {
        return $this->cor;
    }
    /**
     * @param string $cor
     * @return PersonalizacaoEmail
     */
    public function setCor($cor)
    {
        $this->cor = $cor;
        return $this;
    }

    /**
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }
    /**
     * @param string $texto
     * @return PersonalizacaoEmail
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextoBotao()
    {
        return $this->textoBotao;
    }
    /**
     * @param string $textoBotao
     * @return PersonalizacaoEmail
     */
    public function setTextoBotao($textoBotao)
    {
        $this->textoBotao = $textoBotao;
        return $this;
    }
}
