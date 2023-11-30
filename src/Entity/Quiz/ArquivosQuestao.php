<?php

namespace App\Entity\Quiz;

use App\Entity\Base\ActiveTrait;
use App\Entity\Base\EntityTrait;
use App\Entity\Base\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class ArquivosQuestao
{
    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @ORM\Column(type="json")
     */
    private $arquivosResposta = [];

    /**
     * @ORM\Column(type="json")
     */
    private $arquivosExplicacao = [];


    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
        // $this->arquivosResposta = [
        //     'url' => '',
        //     'type' => '',
        //     'provider' => ''
        // ];
        // $this->arquivosExplicacao = [
        //     'url' => '',
        //     'type' => '',
        //     'provider' => ''
        // ];
    }


    public function getArquivosResposta(): array
    {
        if (!$this->arquivosResposta) {
            $this->arquivosResposta = [];
        }
        return $this->arquivosResposta;
    }

    public function setArquivosResposta(array $arquivosResposta): self
    {
        $this->arquivosResposta = $arquivosResposta;

        return $this;
    }

    public function getArquivosExplicacao(): array
    {
        if (!$this->arquivosExplicacao) {
            $this->arquivosExplicacao = [];
        }

        return $this->arquivosExplicacao;
    }

    public function setArquivosExplicacao(array $arquivosExplicacao): self
    {
        $this->arquivosExplicacao = $arquivosExplicacao;

        return $this;
    }
}
