<?php

namespace App\Entity\Base;

use App\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity()
 */
class Endereco
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="cep", type="string", length=9)
     */
    private string $cep;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=200, nullable=false)
     */
    private string $logradouro;

    /**
     * @var integer
     * @ORM\Column(name="number", type="integer")
     */
    private int $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="complement", type="string", length=100, nullable=true)
     */
    private ?string $complemento = NULL;

    /**
     * @var string
     *
     * @ORM\Column(name="bairro", type="string", length=100, nullable=false)
     */
    private string $bairro;

    /**
     * @var string
     *
     * @ORM\Column(name="cidade", type="string", length=100, nullable=false)
     */
    private string $cidade;

    /**
     * @var integer
     *
     * @ORM\Column(name="cod_cidade_ibge", type="integer", length=10, nullable=true)
     */
    private ?int $codCidadeIBGE = NULL;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=2, nullable=false)
     */
    private string $estado;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="enderecos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->active = true;
    }

    /**
     * @return string
     */
    public function getCep(): string
    {
        return $this->cep;
    }

    /**
     * @param string $cep
     * @return Endereco
     */
    public function setCep(string $cep): Endereco
    {
        $this->cep = preg_replace('/[^0-9]/', '', $cep);
        return $this;
    }

    /**
     * @return string
     */
    public function getLogradouro(): string
    {
        return $this->logradouro;
    }

    /**
     * @param string $logradouro
     * @return Endereco
     */
    public function setLogradouro(string $logradouro): Endereco
    {
        $this->logradouro = $logradouro;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumero(): int
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     * @return Endereco
     */
    public function setNumero(int $numero): Endereco
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * @return string
     */
    public function getComplemento(): ?string
    {
        return $this->complemento;
    }

    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * @return string
     */
    public function getBairro(): string
    {
        return $this->bairro;
    }

    /**
     * @param string $bairro
     * @return Endereco
     */
    public function setBairro(string $bairro): Endereco
    {
        $this->bairro = $bairro;
        return $this;
    }

    /**
     * @return string
     */
    public function getCidade(): string
    {
        return $this->cidade;
    }

    /**
     * @param string $cidade
     * @return Endereco
     */
    public function setCidade(string $cidade): Endereco
    {
        $this->cidade = $cidade;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodCidadeIBGE(): int
    {
        return $this->codCidadeIBGE;
    }

    /**
     * @param int $codCidadeIBGE
     * @return Endereco
     */
    public function setCodCidadeIBGE(int $codCidadeIBGE): Endereco
    {
        $this->codCidadeIBGE = $codCidadeIBGE;
        return $this;
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     * @return Endereco
     */
    public function setEstado(string $estado): Endereco
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Endereco
     */
    public function setUser(User $user): Endereco
    {
        $this->user = $user;
        return $this;
    }
}
