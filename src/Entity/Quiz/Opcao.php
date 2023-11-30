<?php

namespace App\Entity\Quiz;

use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use JMS\Serializer\Annotation as Serializer;

/**
 * Quiz
 *
 * @ORM\Table(name="questao_opcoes")
 * @ORM\Entity
 * @Serializer\ExclusionPolicy("none")
 */
class Opcao
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $texto;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $respostaCorreta;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $imagem;


    /**
     * @var Questao
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Questao", inversedBy="opcoes")
     * @ORM\JoinColumn(name="questao_id", referencedColumnName="id")
     * @Serializer\Exclude()
     */
    private $questao;


    /**
     * @ORM\Column(type="integer")
     */
    private $numeroOpcao; // é o indice incrementável no js para identifiar o campo no DOM


    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getRespostaCorreta(): ?bool
    {
        return $this->respostaCorreta;
    }

    public function setRespostaCorreta(bool $respostaCorreta = null): ?Opcao
    {
        $this->respostaCorreta = $respostaCorreta;
        return $this;
    }

    public function getImagem()
    {
        return $this->imagem;
    }
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
        return $this;
    }

    /**
     * @return Questao
     */
    public function getQuestao()
    {
        return $this->questao;
    }

    /**
     * @param Questao $questao
     * @return Opcao
     */
    public function setQuestao($questao)
    {
        $this->questao = $questao;
        return $this;
    }

    public function setNumeroOpcao(?int $numeroOpcao): self
    {
        $this->numeroOpcao = $numeroOpcao;
        return $this;
    }

    public function getNumeroOpcao(): ?int
    {
        return $this->numeroOpcao;
    }
}
