<?php

namespace App\Entity\Base;

// use Quizx\BaseBundle\Utils\ValueObjects\Sexo;
// use Symfony\Component\Validator\Constraints as Assert;
// use Quizx\BaseBundle\Validator\Constraints as AssertBase;

trait PessoaFisicaTrait
{
    use PessoaTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="cpf", type="string", length=14, nullable=true)
     */
    private $cpf;

    /**
     * @var string
     *
     * @ORM\Column(name="rg", type="string", length=14, nullable=true)
     */
    private $rg;


    /**
     * @ORM\Column(name="data_aniversario", type="date", nullable=true)
     */
    private $dataAniversario;

    /**
     * @ORM\Column(name="sexo", type="string", length=1, nullable=true)
     */
    private $sexo;

    /**
     * @var string
     * @ORM\Column(name="orgao_expediror", type="string", nullable=true, length=30)
     */
    private $orgaoExpediror;

    /**
     * @var string
     * @ORM\Column(name="estado_civil", type="string", nullable=true, length=20)
     */
    private $estadoCivil;

    /**
     * @return string
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param $cpf
     * @return PessoaFisicaTrait
     */
    public function setCpf($cpf)
    {
        $this->cpf = preg_replace('/[^0-9]/', '', $cpf);

        return $this;
    }

    /**
     * @return string
     */
    public function getRg()
    {
        return $this->rg;
    }

    /**
     * @param $rg
     * @return PessoaFisicaTrait
     */
    public function setRg($rg)
    {
        $this->rg = $rg;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataAniversario()
    {
        return $this->dataAniversario;
    }

    /**
     * @param $dataAniversario
     * @return PessoaFisicaTrait
     */
    public function setDataAniversario($dataAniversario)
    {
        $this->dataAniversario = $dataAniversario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    // /**
    //  * @return mixed
    //  */
    // public function getSexoNome()
    // {
    //     return Sexo::getSexo($this->sexo);
    // }

    /**
     * @param $sexo
     * @return PessoaFisicaTrait
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrgaoExpediror()
    {
        return $this->orgaoExpediror;
    }

    /**
     * @param $orgaoExpediror
     * @return PessoaFisicaTrait
     */
    public function setOrgaoExpediror($orgaoExpediror)
    {
        $this->orgaoExpediror = $orgaoExpediror;
        return $this;
    }

    /**
     * @return string
     */
    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    /**
     * @param $estadoCivil
     * @return PessoaFisicaTrait
     */
    public function setEstadoCivil($estadoCivil)
    {
        $this->estadoCivil = $estadoCivil;
        return $this;
    }
}
