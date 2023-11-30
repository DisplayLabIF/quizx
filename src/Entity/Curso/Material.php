<?php

namespace App\Entity\Curso;

use App\Entity\Base\ActiveTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Entity\Curso\Curso;
use App\Entity\Curso\Aula;
use App\Entity\Quiz\ConfiguracaoQuiz;
use App\Entity\Quiz\Quiz;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity()
 * @Serializer\ExclusionPolicy("all")
 */
class Material
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     */
    private string $nome;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     */
    private string $tipo;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Expose()
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Curso::class, inversedBy="materiais")
     * @ORM\JoinColumn(name="curso_id", referencedColumnName="id", nullable=true)
     */
    private $curso;

    /**
     * @ORM\ManyToOne(targetEntity=Aula::class, inversedBy="materiais")
     * @ORM\JoinColumn(name="aula_id", referencedColumnName="id", nullable=true)
     */
    private $aula;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\ConfiguracaoQuiz", inversedBy="materiais")
     * @ORM\JoinColumn(name="configuracao_quiz_id", referencedColumnName="id", nullable=true)
     */
    private $configuracaoQuiz;



    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4();
        $this->active = true;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }
    /**
     * @param string $nome
     * @return Material
     */
    public function setNome(string $nome): Material
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }
    /**
     * @param string $tipo
     * @return Material
     */
    public function setTipo(string $tipo): Material
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile(): ?string
    {
        return $this->file;
    }
    /**
     * @param string $file
     * @return Material
     */
    public function setFile(?string $file): Material
    {
        $this->file = $file;
        return $this;
    }

    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): self
    {
        $this->curso = $curso;

        return $this;
    }

    public function getAula(): ?Aula
    {
        return $this->aula;
    }

    public function setAula(?Aula $aula): self
    {
        $this->aula = $aula;

        return $this;
    }

    public function getConfiguracaoQuiz(): ?ConfiguracaoQuiz
    {
        return $this->configuracaoQuiz;
    }

    public function setConfiguracaoQuiz(?ConfiguracaoQuiz $configuracaoQuiz): self
    {
        $this->configuracaoQuiz = $configuracaoQuiz;

        return $this;
    }
}
