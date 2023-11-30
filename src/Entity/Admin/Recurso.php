<?php

namespace App\Entity\Admin;

use App\Entity\Base\EntityTrait;
use App\Entity\Base\ActiveTrait;
use App\Entity\Base\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use App\Entity\Admin\PlanoAcesso;
use Symfony\Component\Uid\Uuid;

/**
 *
 * @ORM\Table(name="recursos")
 * @ORM\Entity()
 */
class Recurso
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $role;


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
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return Recurso
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return Recurso
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }
}
