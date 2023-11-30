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
use App\Repository\ModuloRepository;



/**
 * @ORM\Entity(repositoryClass=ModuloRepository::class)
 */
class Modulo
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $nome;

    /**
     * @ORM\ManyToOne(targetEntity=Curso::class, inversedBy="modulos")
     */
    private $curso;

    /**
     * @ORM\OneToMany(targetEntity=Aula::class, mappedBy="modulo")
     * @ORM\OrderBy({"created" = "ASC"})
     */
    private $aulas;


    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4();
        $this->active = true;
        $this->aulas = new ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return Modulo
     */
    public function setNome(string $nome): Modulo
    {
        $this->nome = $nome;
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

    public function setAulas($aulas)
    {
        $this->aulas = $aulas;
        return $this;
    }
    /**
     * @return Collection|Aula[]
     */
    public function getAulas(): Collection
    {
        return $this->aulas;
    }
}
