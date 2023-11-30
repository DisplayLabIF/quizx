<?php

namespace App\Entity\Quiz;


use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use App\Entity\User;
use App\Entity\User\AdmEscola;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity()
 * @Serializer\ExclusionPolicy("none")
 */
class ConfiguracaoMarketingQuiz
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $titulo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descricao;

    /**
     * @var array|null
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $scriptExternos;


    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getScriptExternos(): ?array
    {
        return $this->scriptExternos;
    }

    public function setScriptExternos(?array $scriptExternos): self
    {
        $this->scriptExternos = $scriptExternos;

        return $this;
    }
}
