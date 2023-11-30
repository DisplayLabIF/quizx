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
 * @ORM\Table(name="quiz_personalizacao")
 */
class PersonalizacaoQuiz
{

    use EntityTrait, ActiveTrait, IdTrait;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $defaultColor;


    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $primaryColor;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $secondaryColor;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $textPrimaryColor;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $textSecondaryColor;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $textPrimaryColorQuestao;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $textSecondaryColorQuestao;


    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $backgroundImageApresentacao;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $backgroundColorApresentacao;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $backgroundSizeApresentacao;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $backgroundImageQuestao;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $backgroundColorQuestao;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $backgroundSizeQuestao;

    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->id = Uuid::v4()->toRfc4122();
        $this->active = true;
    }

    /**
     * @return string
     */
    public function getDefaultColor()
    {
        return $this->defaultColor;
    }

    /**
     * @param string $defaultColor
     * @return $this
     */
    public function setDefaultColor($defaultColor)
    {
        $this->defaultColor = $defaultColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimaryColor()
    {
        return $this->primaryColor;
    }

    /**
     * @param string $primaryColor
     * @return $this
     */
    public function setPrimaryColor($primaryColor)
    {
        $this->primaryColor = $primaryColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecondaryColor()
    {
        return $this->secondaryColor;
    }

    /**
     * @param string $secondaryColor
     * @return $this
     */
    public function setSecondaryColor($secondaryColor)
    {
        $this->secondaryColor = $secondaryColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextPrimaryColor()
    {
        return $this->textPrimaryColor;
    }

    /**
     * @param string $textPrimaryColor
     * @return $this
     */
    public function setTextPrimaryColor($textPrimaryColor)
    {
        $this->textPrimaryColor = $textPrimaryColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextSecondaryColor()
    {
        return $this->textSecondaryColor;
    }

    /**
     * @param string $textSecondaryColor
     * @return $this
     */
    public function setTextSecondaryColor($textSecondaryColor)
    {
        $this->textSecondaryColor = $textSecondaryColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextPrimaryColorQuestao()
    {
        return $this->textPrimaryColorQuestao;
    }

    /**
     * @param string $textPrimaryColorQuestao
     * @return $this
     */
    public function setTextPrimaryColorQuestao($textPrimaryColorQuestao)
    {
        $this->textPrimaryColorQuestao = $textPrimaryColorQuestao;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextSecondaryColorQuestao()
    {
        return $this->textSecondaryColorQuestao;
    }

    /**
     * @param string $textSecondaryColorQuestao
     * @return $this
     */
    public function setTextSecondaryColorQuestao($textSecondaryColorQuestao)
    {
        $this->textSecondaryColorQuestao = $textSecondaryColorQuestao;
        return $this;
    }

    public function getBackgroundImageApresentacao()
    {
        return $this->backgroundImageApresentacao;
    }
    public function setBackgroundImageApresentacao($backgroundImageApresentacao)
    {
        $this->backgroundImageApresentacao = $backgroundImageApresentacao;
        return $this;
    }

    public function getBackgroundColorApresentacao()
    {
        return $this->backgroundColorApresentacao;
    }
    public function setBackgroundColorApresentacao($backgroundColorApresentacao)
    {
        $this->backgroundColorApresentacao = $backgroundColorApresentacao;
        return $this;
    }

    public function getBackgroundImageQuestao()
    {
        return $this->backgroundImageQuestao;
    }
    public function setBackgroundImageQuestao($backgroundImageQuestao)
    {
        $this->backgroundImageQuestao = $backgroundImageQuestao;
        return $this;
    }

    public function getBackgroundColorQuestao()
    {
        return $this->backgroundColorQuestao;
    }
    public function setBackgroundColorQuestao($backgroundColorQuestao)
    {
        $this->backgroundColorQuestao = $backgroundColorQuestao;
        return $this;
    }

    public function getBackgroundSizeApresentacao()
    {
        return $this->backgroundSizeApresentacao;
    }
    public function setBackgroundSizeApresentacao($backgroundSizeApresentacao)
    {
        $this->backgroundSizeApresentacao = $backgroundSizeApresentacao;
        return $this;
    }

    public function getBackgroundSizeQuestao()
    {
        return $this->backgroundSizeQuestao;
    }
    public function setBackgroundSizeQuestao($backgroundSizeQuestao)
    {
        $this->backgroundSizeQuestao = $backgroundSizeQuestao;
        return $this;
    }
}
